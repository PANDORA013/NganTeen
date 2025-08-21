<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use App\Models\GlobalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        $analytics = [
            'overview' => $this->getOverviewStats(),
            'user_analytics' => $this->getUserAnalytics(),
            'order_analytics' => $this->getOrderAnalytics(),
            'revenue_analytics' => $this->getRevenueAnalytics(),
            'popular_menus' => $this->getPopularMenus(),
            'recent_activities' => $this->getRecentActivities(),
        ];

        return view('admin.analytics.index', compact('analytics'));
    }

    /**
     * User registration analytics
     */
    public function userRegistrations()
    {
        $data = [
            'daily_registrations' => $this->getDailyRegistrations(),
            'monthly_registrations' => $this->getMonthlyRegistrations(),
            'role_distribution' => $this->getRoleDistribution(),
            'active_users' => $this->getActiveUsers(),
        ];

        return view('admin.analytics.user-registrations', compact('data'));
    }

    /**
     * Order analytics
     */
    public function orderAnalytics()
    {
        $data = [
            'daily_orders' => $this->getDailyOrders(),
            'order_status_distribution' => $this->getOrderStatusDistribution(),
            'peak_hours' => $this->getPeakOrderHours(),
            'top_selling_items' => $this->getTopSellingItems(),
        ];

        return view('admin.analytics.order-analytics', compact('data'));
    }

    /**
     * Revenue analytics
     */
    public function revenueAnalytics()
    {
        $data = [
            'daily_revenue' => $this->getDailyRevenue(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'revenue_by_warung' => $this->getRevenueByWarung(),
            'commission_earned' => $this->getCommissionEarned(),
        ];

        return view('admin.analytics.revenue-analytics', compact('data'));
    }

    private function getOverviewStats()
    {
        return [
            'total_users' => User::count(),
            'total_pembeli' => User::where('role', 'pembeli')->count(),
            'total_penjual' => User::where('role', 'penjual')->count(),
            'total_menus' => Menu::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_harga'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_warungs' => Menu::distinct('nama_warung')->count(),
        ];
    }

    private function getUserAnalytics()
    {
        $today = Carbon::today();
        $lastWeek = Carbon::today()->subDays(7);
        $lastMonth = Carbon::today()->subDays(30);

        return [
            'new_users_today' => User::whereDate('created_at', $today)->count(),
            'new_users_this_week' => User::where('created_at', '>=', $lastWeek)->count(),
            'new_users_this_month' => User::where('created_at', '>=', $lastMonth)->count(),
            'active_users_today' => User::whereDate('last_login_at', $today)->count(),
        ];
    }

    private function getOrderAnalytics()
    {
        $today = Carbon::today();
        $lastWeek = Carbon::today()->subDays(7);

        return [
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            'orders_this_week' => Order::where('created_at', '>=', $lastWeek)->count(),
            'completed_orders_today' => Order::whereDate('created_at', $today)->where('status', 'completed')->count(),
            'average_order_value' => Order::where('status', 'completed')->avg('total_harga'),
        ];
    }

    private function getRevenueAnalytics()
    {
        $today = Carbon::today();
        $lastMonth = Carbon::today()->subDays(30);

        return [
            'revenue_today' => Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_harga'),
            'revenue_this_month' => Order::where('created_at', '>=', $lastMonth)->where('status', 'completed')->sum('total_harga'),
            'commission_rate' => 5, // 5% commission
            'commission_today' => Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_harga') * 0.05,
        ];
    }

    private function getPopularMenus()
    {
        return DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('menus.nama_menu', 'menus.nama_warung', DB::raw('SUM(order_items.jumlah) as total_sold'))
            ->groupBy('menus.id', 'menus.nama_menu', 'menus.nama_warung')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'message' => "Pesanan baru dari {$order->user->name}",
                    'amount' => $order->total_harga,
                    'created_at' => $order->created_at,
                ];
            });

        // Recent registrations
        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'registration',
                    'message' => "Pengguna baru mendaftar: {$user->name} ({$user->role})",
                    'created_at' => $user->created_at,
                ];
            });

        return collect($activities)
            ->merge($recentOrders)
            ->merge($recentUsers)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }

    private function getDailyRegistrations()
    {
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = User::whereDate('created_at', $date)->count();
            $last30Days->push([
                'date' => $date->format('Y-m-d'),
                'count' => $count
            ]);
        }
        return $last30Days;
    }

    private function getMonthlyRegistrations()
    {
        return User::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    private function getRoleDistribution()
    {
        return User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();
    }

    private function getActiveUsers()
    {
        return [
            'daily_active' => User::whereDate('last_login_at', Carbon::today())->count(),
            'weekly_active' => User::where('last_login_at', '>=', Carbon::today()->subDays(7))->count(),
            'monthly_active' => User::where('last_login_at', '>=', Carbon::today()->subDays(30))->count(),
        ];
    }

    private function getDailyOrders()
    {
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Order::whereDate('created_at', $date)->count();
            $revenue = Order::whereDate('created_at', $date)->where('status', 'completed')->sum('total_harga');
            $last30Days->push([
                'date' => $date->format('Y-m-d'),
                'orders' => $count,
                'revenue' => $revenue
            ]);
        }
        return $last30Days;
    }

    private function getOrderStatusDistribution()
    {
        return Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
    }

    private function getPeakOrderHours()
    {
        return Order::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    private function getTopSellingItems()
    {
        return DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select(
                'menus.nama_menu',
                'menus.nama_warung',
                DB::raw('SUM(order_items.jumlah) as total_quantity'),
                DB::raw('SUM(order_items.jumlah * order_items.harga) as total_revenue')
            )
            ->groupBy('menus.id', 'menus.nama_menu', 'menus.nama_warung')
            ->orderByDesc('total_quantity')
            ->take(20)
            ->get();
    }

    private function getDailyRevenue()
    {
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)->where('status', 'completed')->sum('total_harga');
            $commission = $revenue * 0.05; // 5% commission
            $last30Days->push([
                'date' => $date->format('Y-m-d'),
                'revenue' => $revenue,
                'commission' => $commission
            ]);
        }
        return $last30Days;
    }

    private function getMonthlyRevenue()
    {
        return Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_harga) as revenue')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    private function getRevenueByWarung()
    {
        return DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select(
                'menus.nama_warung',
                DB::raw('SUM(order_items.jumlah * order_items.harga) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->groupBy('menus.nama_warung')
            ->orderByDesc('total_revenue')
            ->get();
    }

    private function getCommissionEarned()
    {
        $totalRevenue = Order::where('status', 'completed')->sum('total_harga');
        return [
            'total_commission' => $totalRevenue * 0.05,
            'commission_this_month' => Order::where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->sum('total_harga') * 0.05,
        ];
    }
}
