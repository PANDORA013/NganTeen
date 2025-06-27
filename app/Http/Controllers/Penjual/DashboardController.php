<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
        // Get recent orders with their items and menu details
        $recentOrders = Order::select('orders.*')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('menus.user_id', $user->id)
            ->with(['user', 'orderItems.menu'])
            ->distinct()
            ->orderBy('orders.created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('penjual.dashboard', [
            'menuCount' => $menuCount,
            'newOrders' => $newOrders,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders
        ]);
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
}
