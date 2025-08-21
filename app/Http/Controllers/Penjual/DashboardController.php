<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get total menus for the current seller
        $menuCount = Menu::where('user_id', $user->id)->count();
        
        // Get new orders (status = 'pending' or 'processing')
        $newOrders = Order::whereHas('orderItems.menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['pending', 'processing'])
            ->count();
        
        // Get total revenue from completed orders
        $totalRevenue = Order::whereHas('orderItems.menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->sum('total_harga');
        
        // Get recent orders with their items and menu details (limit to 5 for dashboard)
        $recentOrders = Order::select('orders.*')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('menus.user_id', $user->id)
            ->with(['user', 'orderItems.menu'])
            ->distinct()
            ->orderBy('orders.created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('penjual.dashboard', compact(
            'menuCount',
            'newOrders', 
            'totalRevenue',
            'recentOrders'
        ));
    }
    
    public function getStats()
    {
        $user = Auth::user();
        
        $menuCount = Menu::where('user_id', $user->id)->count();
        
        $newOrders = Order::whereHas('orderItems.menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['pending', 'processing'])
            ->count();
        
        $totalRevenue = Order::whereHas('orderItems.menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->sum('total_harga');
        
        return response()->json([
            'success' => true,
            'menuCount' => $menuCount,
            'newOrders' => $newOrders,
            'totalRevenue' => $totalRevenue,
            'formattedRevenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.')
        ]);
    }
    
    public function orders()
    {
        $user = Auth::user();
        
        // Get all orders that contain items from this seller's menus
        $orders = Order::select('orders.*')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('menus.user_id', $user->id)
            ->with(['user', 'orderItems.menu'])
            ->distinct()
            ->orderBy('orders.created_at', 'desc')
            ->paginate(20);
        
        // Calculate order statistics
        $baseQuery = Order::whereHas('orderItems.menu', function($query) use ($user) {
            $query->where('user_id', $user->id);
        });
        
        $orderStats = [
            'pending_orders' => (clone $baseQuery)->where('status', 'pending')->count(),
            'processing_orders' => (clone $baseQuery)->whereIn('status', ['confirmed', 'preparing', 'processing'])->count(),
            'completed_today' => (clone $baseQuery)->where('status', 'completed')
                ->whereDate('created_at', today())->count(),
        ];
        
        // Calculate total revenue from completed orders
        $totalRevenue = (clone $baseQuery)->where('status', 'completed')->sum('total_harga');
        
        return view('penjual.orders.index', compact('orders', 'orderStats', 'totalRevenue'));
    }
    
    public function updateOrderStatus(Request $request, OrderItem $orderItem)
    {
        $user = Auth::user();
        
        // Verify that the order item belongs to this seller's menu
        if ($orderItem->menu->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);
        
        $orderItem->update([
            'status' => $request->status
        ]);
        
        // Check if all order items are ready/completed to update main order status
        $order = $orderItem->order;
        $allItems = $order->orderItems;
        
        if ($allItems->every(fn($item) => in_array($item->status, ['ready', 'completed']))) {
            $order->update(['status' => 'ready']);
        } elseif ($allItems->every(fn($item) => $item->status === 'completed')) {
            $order->update(['status' => 'completed']);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }
    
    public function payouts()
    {
        $user = Auth::user();
        
        // Calculate total earnings from completed orders
        $totalEarnings = Order::whereHas('orderItems.menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->sum('total_harga');
        
        // Get payout history (you'll need to create PayoutRequest model)
        $payoutRequests = collect(); // Placeholder for now
        
        // Create a paginated collection for the view (using LengthAwarePaginator for now)
        $payouts = new LengthAwarePaginator(
            collect(), // Empty collection for now
            0, // Total items
            15, // Items per page
            1, // Current page
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
        
        // Calculate payout statistics
        $totalPaid = 0; // Placeholder - will be calculated from PayoutRequest model when implemented
        $pendingAmount = 0; // Placeholder - will be calculated from pending PayoutRequest
        $currentBalance = $totalEarnings - $totalPaid - $pendingAmount; // Available balance for withdrawal
        
        return view('penjual.payouts.index', [
            'totalEarnings' => $totalEarnings,
            'currentBalance' => $currentBalance,
            'totalPaid' => $totalPaid,
            'pendingAmount' => $pendingAmount,
            'payouts' => $payouts,
            'payoutRequests' => $payoutRequests
        ]);
    }
    
    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000' // Minimum payout 50k
        ]);
        
        $user = Auth::user();
        
        // Calculate available balance
        $availableBalance = Order::whereHas('orderItems.menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->sum('total_harga');
        
        if ($request->amount > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance for payout'
            ], 400);
        }
        
        // Create payout request (you'll need PayoutRequest model)
        // PayoutRequest::create([...]);
        
        return response()->json([
            'success' => true,
            'message' => 'Payout request submitted successfully'
        ]);
    }
    
    public function getDashboardData()
    {
        $user = Auth::user();
        
        $data = [
            'menuCount' => Menu::where('user_id', $user->id)->count(),
            'newOrders' => Order::whereHas('orderItems.menu', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'totalRevenue' => Order::whereHas('orderItems.menu', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status', 'completed')
                ->sum('total_harga'),
            'avgOrderValue' => Order::whereHas('orderItems.menu', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status', 'completed')
                ->avg('total_harga') ?? 0
        ];
        
        return response()->json(['success' => true, 'data' => $data]);
    }
    
    public function getChartData()
    {
        $user = Auth::user();
        
        // Get daily sales for the last 7 days
        $salesData = Order::whereHas('orderItems.menu', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total_harga) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $salesData
        ]);
    }
    
    public function warungSetup()
    {
        $user = Auth::user();
        return view('penjual.warung.setup', compact('user'));
    }
    
    public function storeWarung(Request $request)
    {
        $request->validate([
            'nama_warung' => 'required|string|max:255',
            'alamat_warung' => 'required|string|max:500',
            'telepon_warung' => 'required|string|max:20'
        ]);
        
        $user = Auth::user();
        $user->update([
            'nama_warung' => $request->nama_warung,
            'alamat_warung' => $request->alamat_warung,
            'telepon_warung' => $request->telepon_warung
        ]);
        
        return redirect()->route('penjual.dashboard')->with('success', 'Warung setup completed successfully!');
    }
    
    public function editWarung()
    {
        $user = Auth::user();
        $warung = $user->warung;
        
        // If warung doesn't exist, create a default one
        if (!$warung) {
            $warung = $user->warung()->create([
                'nama_warung' => $user->name . ' Warung',
                'lokasi' => '',
                'no_hp' => '',
                'deskripsi' => '',
                'nama_pemilik' => $user->name,
                'rekening_bank' => '',
                'no_rekening' => '',
                'status' => 'active'
            ]);
        }
        
        return view('penjual.warung.edit', compact('user', 'warung'));
    }
    
    public function updateWarung(Request $request)
    {
        $request->validate([
            'nama_warung' => 'required|string|max:255',
            'lokasi' => 'required|string|max:500',
            'no_hp' => 'required|string|max:20',
            'deskripsi' => 'nullable|string|max:1000',
            'nama_pemilik' => 'required|string|max:255',
            'rekening_bank' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:50',
        ]);
        
        $user = Auth::user();
        $warung = $user->warung;
        
        if (!$warung) {
            // Create new warung if doesn't exist
            $warung = $user->warung()->create($request->all());
        } else {
            // Update existing warung
            $warung->update($request->all());
        }
        
        return redirect()->route('penjual.warung.edit')->with('success', 'Warung information updated successfully!');
    }
}
