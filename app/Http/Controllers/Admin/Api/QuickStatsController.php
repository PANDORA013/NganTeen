<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\GlobalOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuickStatsController extends Controller
{
    public function quickStats()
    {
        try {
            $today = Carbon::today();
            
            // Get today's orders count
            $todayOrders = GlobalOrder::whereDate('created_at', $today)->count();
            
            // Get today's revenue
            $todayRevenue = GlobalOrder::whereDate('created_at', $today)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            
            return response()->json([
                'orders' => $todayOrders,
                'revenue' => number_format($todayRevenue, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'orders' => 0,
                'revenue' => '0'
            ]);
        }
    }

    public function notifications()
    {
        try {
            // Get unread notifications count
            // This is a placeholder - implement based on your notification system
            $unreadCount = 3; // You can implement actual notification counting here
            
            return response()->json([
                'count' => $unreadCount,
                'notifications' => [
                    [
                        'id' => 1,
                        'type' => 'order',
                        'title' => 'Pesanan Baru',
                        'message' => 'Order #1234 - Rp 45.000',
                        'icon' => 'fas fa-shopping-cart',
                        'color' => 'primary',
                        'time' => '5 menit yang lalu'
                    ],
                    [
                        'id' => 2,
                        'type' => 'user',
                        'title' => 'User Baru',
                        'message' => 'Ahmad Rizki bergabung',
                        'icon' => 'fas fa-user',
                        'color' => 'success',
                        'time' => '10 menit yang lalu'
                    ],
                    [
                        'id' => 3,
                        'type' => 'message',
                        'title' => 'Pesan Baru',
                        'message' => 'Kontak dari website',
                        'icon' => 'fas fa-envelope',
                        'color' => 'info',
                        'time' => '15 menit yang lalu'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'count' => 0,
                'notifications' => []
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'results' => [],
                    'message' => 'Query terlalu pendek'
                ]);
            }

            // Search functionality placeholder
            $results = [];

            // Example search results - implement based on your actual models
            $results[] = [
                'type' => 'example',
                'id' => 1,
                'title' => 'Hasil pencarian untuk: ' . $query,
                'subtitle' => 'Implementasikan pencarian sesuai model yang ada',
                'url' => '#',
                'icon' => 'fas fa-search'
            ];

            // Search in users
            $users = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get(['id', 'name', 'email']);
            
            foreach ($users as $user) {
                $results[] = [
                    'type' => 'user',
                    'id' => $user->id,
                    'title' => $user->name,
                    'subtitle' => 'User - ' . $user->email,
                    'url' => '#',
                    'icon' => 'fas fa-user'
                ];
            }

            // Search in orders
            $orders = GlobalOrder::where('id', 'LIKE', "%{$query}%")
                ->limit(3)
                ->get(['id', 'total_amount', 'payment_status']);
            
            foreach ($orders as $order) {
                $results[] = [
                    'type' => 'order',
                    'id' => $order->id,
                    'title' => 'Order #' . $order->id,
                    'subtitle' => 'Pesanan - Rp ' . number_format($order->total_amount) . ' (' . $order->payment_status . ')',
                    'url' => '#',
                    'icon' => 'fas fa-shopping-cart'
                ];
            }

            return response()->json([
                'results' => $results,
                'total' => count($results)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'results' => [],
                'error' => 'Terjadi kesalahan saat pencarian'
            ], 500);
        }
    }
}
