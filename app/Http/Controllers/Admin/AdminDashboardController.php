<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalOrder;
use App\Models\Warung;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
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

        return view('admin.dashboard_minimal', compact(
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

    /**
     * Orders Management Page
     */
    public function orders()
    {
        $orders = GlobalOrder::with(['buyer', 'items.warung', 'items.menu'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_orders' => GlobalOrder::count(),
            'pending_orders' => GlobalOrder::where('payment_status', 'pending')->count(),
            'paid_orders' => GlobalOrder::where('payment_status', 'paid')->count(),
            'failed_orders' => GlobalOrder::where('payment_status', 'failed')->count(),
            'total_revenue' => GlobalOrder::where('payment_status', 'paid')->sum('total_amount'),
            'today_orders' => GlobalOrder::whereDate('created_at', today())->count(),
            'today_revenue' => GlobalOrder::whereDate('created_at', today())
                ->where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => GlobalOrder::where('payment_status', 'paid')->avg('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Warungs Management Page
     */
    public function warungs()
    {
        $warungs = Warung::with(['owner', 'menus'])
            ->withCount(['menus', 'globalOrderItems as orders_count'])
            ->withSum(['globalOrderItems as revenue'], 'subtotal')
            ->latest()
            ->paginate(20);

        $stats = [
            'total_warungs' => Warung::count(),
            'active_warungs' => Warung::where('status', 'aktif')->count(),
            'inactive_warungs' => Warung::where('status', 'nonaktif')->count(),
            'total_menus' => \App\Models\Menu::count(),
            'total_revenue' => GlobalOrder::where('payment_status', 'paid')->sum('total_amount'),
            'today_warungs' => Warung::whereDate('created_at', today())->count(),
            'avg_menus_per_warung' => round(\App\Models\Menu::count() / max(Warung::count(), 1), 1),
            'top_warung_revenue' => Warung::withSum(['globalOrderItems as revenue'], 'subtotal')
                ->orderBy('revenue', 'desc')
                ->first()
                ->revenue ?? 0,
        ];

        return view('admin.warungs.index', compact('warungs', 'stats'));
    }

    /**
     * Transactions Management Page
     */
    public function transactions()
    {
        $transactions = \App\Models\Transaction::with(['globalOrder.buyer', 'warung'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_transactions' => \App\Models\Transaction::count(),
            'pending_transactions' => \App\Models\Transaction::where('status', 'pending')->count(),
            'completed_transactions' => \App\Models\Transaction::where('status', 'completed')->count(),
            'failed_transactions' => \App\Models\Transaction::where('status', 'failed')->count(),
            'total_transaction_amount' => \App\Models\Transaction::where('status', 'completed')->sum('amount'),
            'today_transactions' => \App\Models\Transaction::whereDate('created_at', today())->count(),
            'today_transaction_amount' => \App\Models\Transaction::whereDate('created_at', today())
                ->where('status', 'completed')->sum('amount'),
            'average_transaction_amount' => \App\Models\Transaction::where('status', 'completed')->avg('amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Transaction Detail Page
     */
    public function transactionDetail(\App\Models\Transaction $transaction)
    {
        $transaction->load(['globalOrder.buyer', 'globalOrder.items.warung', 'globalOrder.items.menu', 'warung']);
        
        return view('admin.transactions.detail', compact('transaction'));
    }

    /**
     * Payouts Management Page
     */
    public function payouts()
    {
        $payouts = \App\Models\Payout::with(['warung.owner'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_payouts' => \App\Models\Payout::count(),
            'pending_payouts' => \App\Models\Payout::where('status', 'pending')->count(),
            'processing_payouts' => \App\Models\Payout::where('status', 'processing')->count(),
            'completed_payouts' => \App\Models\Payout::where('status', 'completed')->count(),
            'failed_payouts' => \App\Models\Payout::where('status', 'failed')->count(),
            'total_payout_amount' => \App\Models\Payout::where('status', 'completed')->sum('amount'),
            'pending_payout_amount' => \App\Models\Payout::where('status', 'pending')->sum('amount'),
            'today_payouts' => \App\Models\Payout::whereDate('created_at', today())->count(),
            'today_payout_amount' => \App\Models\Payout::whereDate('created_at', today())
                ->where('status', 'completed')->sum('amount'),
        ];

        return view('admin.payouts.index', compact('payouts', 'stats'));
    }

    /**
     * Create Payout
     */
    public function createPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'warung_id' => 'required|exists:warungs,id'
        ]);

        $warung = Warung::findOrFail($request->warung_id);
        
        // Check if warung has enough balance
        $balance = $warung->wallet->balance ?? 0;
        if ($balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance for payout. Available: Rp ' . number_format($balance)
            ]);
        }

        $payout = \App\Models\Payout::create([
            'warung_id' => $warung->id,
            'amount' => $request->amount,
            'status' => 'pending',
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout created successfully',
            'payout' => $payout
        ]);
    }

    /**
     * Process Payout
     */
    public function processPayout(\App\Models\Payout $payout)
    {
        if ($payout->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Payout is not in pending status'
            ]);
        }

        $payout->update([
            'status' => 'processing',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout marked as processing'
        ]);
    }

    /**
     * Complete Payout
     */
    public function completePayout(\App\Models\Payout $payout)
    {
        if ($payout->status !== 'processing') {
            return response()->json([
                'success' => false,
                'message' => 'Payout is not in processing status'
            ]);
        }

        // Deduct from warung balance
        $warung = $payout->warung;
        $warung->wallet->decrement('balance', $payout->amount);

        $payout->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout completed successfully'
        ]);
    }

    /**
     * Fail Payout
     */
    public function failPayout(\App\Models\Payout $payout)
    {
        if (!in_array($payout->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payout cannot be failed from current status'
            ]);
        }

        $payout->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failed_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout marked as failed'
        ]);
    }

    /**
     * Process All Pending Payouts
     */
    public function processAllPayouts()
    {
        $pendingPayouts = \App\Models\Payout::where('status', 'pending')->get();
        
        foreach ($pendingPayouts as $payout) {
            $payout->update([
                'status' => 'processing',
                'processed_at' => now(),
                'processed_by' => Auth::id(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => count($pendingPayouts) . ' payouts marked as processing'
        ]);
    }

    /**
     * Payout Details for Modal
     */
    public function payoutDetails(\App\Models\Payout $payout)
    {
        $payout->load(['warung.owner']);
        
        return view('admin.payouts.details', compact('payout'));
    }

    /**
     * Export Payouts
     */
    public function exportPayouts()
    {
        $payouts = \App\Models\Payout::with(['warung.owner'])
            ->latest()
            ->get();

        $csvData = [];
        $csvData[] = ['Payout Number', 'Warung', 'Owner', 'Amount', 'Status', 'Method', 'Bank', 'Account Number', 'Account Name', 'Created At', 'Processed At', 'Completed At'];

        foreach ($payouts as $payout) {
            $csvData[] = [
                $payout->payout_number,
                $payout->warung->nama_warung,
                $payout->warung->owner->name,
                $payout->amount,
                $payout->status,
                $payout->method ?? 'Bank Transfer',
                $payout->bank_name ?? '',
                $payout->account_number ?? '',
                $payout->account_name ?? '',
                $payout->created_at->format('Y-m-d H:i:s'),
                $payout->processed_at ? $payout->processed_at->format('Y-m-d H:i:s') : '',
                $payout->completed_at ? $payout->completed_at->format('Y-m-d H:i:s') : '',
            ];
        }

        $filename = 'payouts_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Analytics Page
     */
    public function analytics()
    {
        try {
            // Enhanced statistics for analytics
            $stats = [
                'total_orders' => GlobalOrder::count(),
                'pending_orders' => GlobalOrder::where('payment_status', 'pending')->count(),
                'completed_orders' => GlobalOrder::where('payment_status', 'paid')->count(),
                'failed_orders' => GlobalOrder::where('payment_status', 'failed')->count(),
                'total_users' => User::count(),
                'sellers_count' => User::where('usertype', 'penjual')->count(),
                'total_warungs' => Warung::count(),
                'total_revenue' => GlobalOrder::where('payment_status', 'paid')->sum('total_amount'),
            ];

            // Chart data for revenue (last 7 days)
            $chart_data = [
                'revenue_labels' => $this->getRevenueChartLabels(),
                'revenue_data' => $this->getRevenueChartData(),
            ];

            // Recent activities for analytics
            $recent_activities = $this->getRecentActivities();

            // Recent high-value orders
            $recent_orders = $this->getHighValueOrders();

            // Top performing warungs
            $top_warungs = $this->getTopWarungs();

            return view('admin.analytics', compact(
                'stats', 
                'chart_data',
                'recent_activities',
                'recent_orders', 
                'top_warungs'
            ));
        } catch (\Exception $e) {
            Log::error('Admin Analytics Error: ' . $e->getMessage());
            
            // Fallback data
            $stats = [
                'total_orders' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0,
                'failed_orders' => 0,
                'total_users' => 0,
                'sellers_count' => 0,
                'total_warungs' => 0,
                'total_revenue' => 0,
            ];
            $chart_data = ['revenue_labels' => [], 'revenue_data' => []];
            $recent_activities = [];
            $recent_orders = [];
            $top_warungs = [];
            
            return view('admin.analytics', compact(
                'stats', 
                'chart_data',
                'recent_activities',
                'recent_orders', 
                'top_warungs'
            ));
        }
    }

    private function getHighValueOrders()
    {
        return GlobalOrder::with('user')
            ->where('payment_status', 'paid')
            ->where('total_amount', '>', 50000) // Orders above 50k
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->user ? $order->user->name : 'Guest Customer',
                    'total_amount' => $order->total_amount,
                    'created_at_formatted' => $order->created_at->diffForHumans(),
                ];
            })->toArray();
    }
}
