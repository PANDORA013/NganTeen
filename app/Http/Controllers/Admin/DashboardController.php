<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalOrder;
use App\Models\Transaction;
use App\Models\Warung;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Enhanced Statistics
        $stats = [
            // Basic Stats
            'total_users' => User::count(),
            'total_orders' => GlobalOrder::count(),
            'total_warungs' => Warung::count(),
            'total_revenue' => GlobalOrder::where('payment_status', 'paid')->sum('total_amount'),
            
            // Today's Stats
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_orders_today' => GlobalOrder::whereDate('created_at', today())->count(),
            'new_warungs_today' => Warung::whereDate('created_at', today())->count(),
            'revenue_today' => GlobalOrder::whereDate('created_at', today())
                ->where('payment_status', 'paid')->sum('total_amount'),
            
            // User Distribution
            'buyers_count' => User::where('role', 'pembeli')->count(),
            'sellers_count' => User::where('role', 'penjual')->count(),
            'admins_count' => User::where('role', 'admin')->count(),
            
            // Order Status
            'pending_orders' => GlobalOrder::where('payment_status', 'pending')->count(),
            'completed_orders' => GlobalOrder::where('payment_status', 'paid')->count(),
            
            // Messages & Issues
            'unread_messages' => $this->getUnreadContactMessages(),
            'issues_count' => $this->getIssuesCount(),
            
            // Additional Stats
            'total_orders_today' => GlobalOrder::whereDate('created_at', today())->count(),
            'total_revenue_today' => GlobalOrder::whereDate('created_at', today())
                ->where('payment_status', 'paid')->sum('total_amount'),
            'total_orders_month' => GlobalOrder::whereMonth('created_at', now()->month)->count(),
            'total_revenue_month' => GlobalOrder::whereMonth('created_at', now()->month)
                ->where('payment_status', 'paid')->sum('total_amount'),
            'active_warungs' => Warung::where('status', 'active')->count(),
            'pending_payouts' => Payout::where('status', 'pending')->sum('amount'),
            
            'active_warungs_today' => Warung::whereHas('globalOrderItems', function($q) {
                $q->whereDate('created_at', today());
            })->count(),
            'average_order_value' => GlobalOrder::where('payment_status', 'paid')->avg('total_amount') ?? 0,
            'failed_orders' => GlobalOrder::where('payment_status', 'failed')->count(),
            'growth_percentage' => $this->calculateGrowthPercentage(),
            'top_warung_today' => $this->getTopWarungToday(),
            
            // Centralized payment stats
            'pending_settlements' => GlobalOrder::where('payment_status', 'paid')
                ->where('is_settled', false)->count(),
        ];

        // Chart Data
        $chart_data = [
            'revenue_labels' => $this->getRevenueChartLabels(),
            'revenue_data' => $this->getRevenueChartData(),
        ];

        // Recent Activities
        $recent_activities = $this->getRecentActivities();

        // Recent Orders
        $recent_orders = $this->getRecentOrders();

        // Top Warungs
        $top_warungs = $this->getTopWarungs();

        return view('admin.dashboard_new', compact(
            'stats', 
            'chart_data',
            'recent_activities',
            'recent_orders', 
            'top_warungs'
        ));
    }

    private function getUnreadContactMessages()
    {
        $messages = \Illuminate\Support\Facades\Storage::exists('admin/contact-messages.json') 
            ? json_decode(\Illuminate\Support\Facades\Storage::get('admin/contact-messages.json'), true) 
            : [];
        
        return count(array_filter($messages, fn($msg) => $msg['status'] === 'unread'));
    }

    private function getIssuesCount()
    {
        // Count issues that need attention
        $issues = 0;
        
        // Failed orders
        $issues += GlobalOrder::where('payment_status', 'failed')->count();
        
        // Unsettled payments older than 7 days
        $issues += GlobalOrder::where('payment_status', 'paid')
            ->where('is_settled', false)
            ->where('created_at', '<', now()->subDays(7))
            ->count();
        
        return $issues;
    }

    private function getRevenueChartLabels()
    {
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('M j');
        }
        return $labels;
    }

    private function getRevenueChartData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = GlobalOrder::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            $data[] = (int) $revenue;
        }
        return $data;
    }

    private function getRecentActivities()
    {
        $activities = [];
        
        // Recent orders
        $recentOrders = GlobalOrder::with(['buyer'])
            ->latest()
            ->take(3)
            ->get();
        
        foreach ($recentOrders as $order) {
            $activities[] = [
                'title' => 'Pesanan Baru',
                'description' => "Pesanan #{$order->id} dari {$order->buyer->name}",
                'time' => $order->created_at->diffForHumans(),
            ];
        }
        
        // Recent user registrations
        $recentUsers = User::latest()->take(2)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'title' => 'User Baru',
                'description' => "{$user->name} mendaftar sebagai {$user->role}",
                'time' => $user->created_at->diffForHumans(),
            ];
        }
        
        return $activities;
    }

    private function getRecentOrders()
    {
        return GlobalOrder::with(['buyer', 'items.warung'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->buyer->name ?? 'Unknown',
                    'warung' => $order->items->first()->warung->nama_warung ?? 'Unknown',
                    'total' => $order->total_amount,
                    'status' => ucfirst($order->payment_status),
                    'status_color' => $this->getStatusColor($order->payment_status),
                    'time' => $order->created_at->format('H:i'),
                ];
            })->toArray();
    }

    private function getTopWarungs()
    {
        return Warung::withCount(['globalOrderItems as orders_count'])
            ->withSum(['globalOrderItems as revenue'], 'subtotal')
            ->having('orders_count', '>', 0)
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get()
            ->map(function ($warung, $index) {
                return [
                    'name' => $warung->nama_warung,
                    'orders_count' => $warung->orders_count,
                    'revenue' => $warung->revenue ?? 0,
                    'rating' => '4.5', // Default rating, implement actual rating system
                ];
            })->toArray();
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'paid' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            default => 'secondary'
        };
    }

    private function calculateGrowthPercentage()
    {
        $thisMonth = GlobalOrder::whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')->sum('total_amount');
        
        $lastMonth = GlobalOrder::whereMonth('created_at', now()->subMonth()->month)
            ->where('payment_status', 'paid')->sum('total_amount');
        
        if ($lastMonth == 0) return 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    private function getTopWarungToday()
    {
        return Warung::withSum(['globalOrderItems as revenue_today' => function($q) {
                $q->whereDate('created_at', today());
            }], 'subtotal')
            ->having('revenue_today', '>', 0)
            ->orderBy('revenue_today', 'desc')
            ->first();
    }

    private function generateRevenueChartData()
    {
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = GlobalOrder::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            $orders = GlobalOrder::whereDate('created_at', $date)->count();
            
            $chartData[] = [
                'date' => $date->format('M d'),
                'full_date' => $date->format('Y-m-d'),
                'revenue' => $revenue,
                'orders' => $orders
            ];
        }
        return collect($chartData);
    }

    private function getOrderStatusDistribution()
    {
        return [
            'paid' => GlobalOrder::where('payment_status', 'paid')->count(),
            'pending' => GlobalOrder::where('payment_status', 'pending')->count(),
            'failed' => GlobalOrder::where('payment_status', 'failed')->count(),
        ];
    }

    private function getTopPerformingWarungs()
    {
        return Warung::withSum(['globalOrderItems as total_revenue' => function($q) {
                $q->whereDate('created_at', '>=', now()->subDays(30));
            }], 'subtotal')
            ->withCount(['globalOrderItems as total_orders' => function($q) {
                $q->whereDate('created_at', '>=', now()->subDays(30));
            }])
            ->having('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(5)
            ->get();
    }

    private function getRecentActivities()
    {
        $activities = collect();
        
        // Recent orders
        $recentOrders = GlobalOrder::with('buyer')->latest()->take(5)->get();
        foreach ($recentOrders as $order) {
            $activities->push([
                'type' => 'order',
                'message' => "Order baru #{$order->order_number} dari {$order->buyer->name}",
                'time' => $order->created_at,
                'icon' => 'shopping-cart',
                'color' => 'primary'
            ]);
        }
        
        // Recent payouts
        $recentPayouts = Payout::with('warung')->latest()->take(3)->get();
        foreach ($recentPayouts as $payout) {
            $activities->push([
                'type' => 'payout',
                'message' => "Payout Rp " . number_format($payout->amount) . " untuk {$payout->warung->nama_warung}",
                'time' => $payout->created_at,
                'icon' => 'money-check-alt',
                'color' => 'success'
            ]);
        }
        
        return $activities->sortByDesc('time')->take(10);
    }

    public function orders(Request $request)
    {
        $query = GlobalOrder::with(['buyer', 'items.warung', 'items.menu', 'transaction']);
        
        // Enhanced filtering
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('buyer', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $orders = $query->latest()->paginate(20);
        
        return view('admin.orders', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $order = GlobalOrder::with(['buyer', 'items.warung', 'items.menu'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
        
        $itemsByWarung = $order->items->groupBy('warung.nama_warung');
        
        return response()->json([
            'order_number' => $order->order_number,
            'buyer' => $order->buyer,
            'total_amount' => $order->total_amount,
            'payment_status' => $order->payment_status,
            'created_at' => $order->created_at,
            'items_by_warung' => $itemsByWarung->map(function($items, $warungName) {
                return $items->map(function($item) {
                    return [
                        'menu_name' => $item->menu_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ];
                });
            })
        ]);
    }

    public function markOrderAsPaid($orderNumber)
    {
        $order = GlobalOrder::where('order_number', $orderNumber)->firstOrFail();
        
        if ($order->payment_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Order sudah diproses sebelumnya']);
        }
        
        DB::beginTransaction();
        try {
            $order->update(['payment_status' => 'paid']);
            
            // Process order items and update warung balances
            foreach ($order->items as $item) {
                if ($item->warung && $item->warung->wallet) {
                    $item->warung->wallet->addBalance($item->price * $item->quantity);
                }
            }
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function warungs(Request $request)
    {
        $query = Warung::with(['user', 'wallet'])
            ->withCount('globalOrderItems')
            ->withSum('globalOrderItems', 'subtotal');
        
        if ($request->search) {
            $query->where('nama_warung', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_pemilik', 'like', '%' . $request->search . '%');
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $warungs = $query->paginate(20);
        
        return view('admin.warungs', compact('warungs'));
    }

    public function warungDetail(Warung $warung)
    {
        $warung->load(['user', 'wallet', 'globalOrderItems.menu']);
        
        // Performance metrics
        $metrics = [
            'total_orders' => $warung->globalOrderItems()->count(),
            'total_revenue' => $warung->globalOrderItems()->sum('subtotal'),
            'orders_this_month' => $warung->globalOrderItems()->whereMonth('created_at', now()->month)->count(),
            'revenue_this_month' => $warung->globalOrderItems()->whereMonth('created_at', now()->month)->sum('subtotal'),
            'average_order_value' => $warung->globalOrderItems()->avg('subtotal') ?? 0,
        ];
        
        return view('admin.warung-detail', compact('warung', 'metrics'));
    }

    public function payouts(Request $request)
    {
        $query = Payout::with(['warung.user', 'processedBy']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->warung_id) {
            $query->where('warung_id', $request->warung_id);
        }
        
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        $payouts = $query->latest()->paginate(20);
        
        $warungs = Warung::where('status', 'active')->get(['id', 'nama_warung']);
        
        // Summary statistics
        $stats = [
            'pending_amount' => Payout::where('status', 'pending')->sum('amount'),
            'pending_count' => Payout::where('status', 'pending')->count(),
            'completed_amount' => Payout::where('status', 'completed')->sum('amount'),
            'completed_count' => Payout::where('status', 'completed')->count(),
            'total_warungs' => Warung::whereHas('wallet', function($q) {
                $q->where('balance', '>', 0);
            })->count(),
        ];
        
        return view('admin.payouts', compact('payouts', 'warungs', 'stats'));
    }

    public function createPayout(Request $request, Warung $warung = null)
    {
        $warungId = $warung ? $warung->id : $request->warung_id;
        $warung = $warung ?: Warung::findOrFail($warungId);
        
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:' . $warung->wallet->available_balance,
        ]);

        DB::beginTransaction();
        try {
            // Create payout record
            $payout = Payout::create([
                'warung_id' => $warung->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'method' => 'manual',
                'bank_name' => $warung->rekening_bank,
                'account_number' => $warung->no_rekening,
                'account_name' => $warung->nama_pemilik,
                'processed_by' => Auth::id(),
            ]);

            // Deduct from wallet balance
            $warung->wallet->pending_balance += $request->amount;
            $warung->wallet->available_balance -= $request->amount;
            $warung->wallet->save();

            DB::commit();

            return redirect()->back()->with('success', 
                "Payout sebesar Rp " . number_format($request->amount) . 
                " berhasil dibuat untuk {$warung->nama_warung}"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat payout: ' . $e->getMessage());
        }
    }

    public function processPayout(Payout $payout)
    {
        if ($payout->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Payout sudah diproses sebelumnya']);
        }
        
        $payout->update([
            'status' => 'processed',
            'processed_at' => now(),
            'processed_by' => Auth::id()
        ]);
        
        return response()->json(['success' => true]);
    }

    public function completePayout(Payout $payout)
    {
        if ($payout->status !== 'processed') {
            return response()->json(['success' => false, 'message' => 'Payout harus diproses terlebih dahulu']);
        }
        
        DB::beginTransaction();
        try {
            $payout->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by' => Auth::id()
            ]);
            
            // Update wallet
            $wallet = $payout->warung->wallet;
            $wallet->pending_balance -= $payout->amount;
            $wallet->save();
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function failPayout(Payout $payout)
    {
        if ($payout->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Payout sudah selesai, tidak bisa digagalkan']);
        }
        
        DB::beginTransaction();
        try {
            $payout->update([
                'status' => 'failed',
                'failed_at' => now(),
                'failed_by' => Auth::id()
            ]);
            
            // Return balance to available
            $wallet = $payout->warung->wallet;
            $wallet->pending_balance -= $payout->amount;
            $wallet->available_balance += $payout->amount;
            $wallet->save();
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function processAllPayouts()
    {
        $pendingPayouts = Payout::where('status', 'pending')->get();
        
        if ($pendingPayouts->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada payout pending']);
        }
        
        DB::beginTransaction();
        try {
            foreach ($pendingPayouts as $payout) {
                $payout->update([
                    'status' => 'processed',
                    'processed_at' => now(),
                    'processed_by' => Auth::id()
                ]);
            }
            
            DB::commit();
            return response()->json([
                'success' => true, 
                'message' => 'Berhasil memproses ' . $pendingPayouts->count() . ' payout'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function transactions(Request $request)
    {
        $query = Transaction::with(['order.buyer']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transactions = $query->latest()->paginate(20);
        
        return view('admin.transactions', compact('transactions'));
    }

    public function transactionDetail(Transaction $transaction)
    {
        $transaction->load(['order.buyer', 'order.items.warung']);
        
        return view('admin.transaction-detail', compact('transaction'));
    }
}
