<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMenuAdded;
use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestBroadcastController extends Controller
{
    /**
     * Test menu broadcast
     */
    public function testMenuBroadcast(Request $request): JsonResponse
    {
        try {
            // Get a random user or use authenticated user
            $user = User::where('role', 'penjual')->inRandomOrder()->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No seller user found'
                ], 400);
            }

            // Create a test menu
            $menu = new Menu([
                'user_id' => $user->id,
                'nama_menu' => 'Test Menu ' . now()->format('H:i:s'),
                'harga' => rand(10000, 50000),
                'stok' => rand(5, 20),
                'area_kampus' => collect(['Kampus A', 'Kampus B', 'Kampus C'])->random(),
                'nama_warung' => 'Test Warung'
            ]);

            // Set user relationship
            $menu->setRelation('user', $user);

            // Fire the event manually (without saving to database)
            event(new NewMenuAdded($menu));

            return response()->json([
                'success' => true,
                'message' => 'Menu broadcast event fired successfully',
                'data' => [
                    'menu_name' => $menu->nama_menu,
                    'price' => $menu->harga,
                    'area' => $menu->area_kampus,
                    'seller' => $user->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error firing menu broadcast: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test order broadcast
     */
    public function testOrderBroadcast(Request $request): JsonResponse
    {
        try {
            // Get a random buyer user or use authenticated user
            $user = Auth::user() ?? User::where('role', 'pembeli')->inRandomOrder()->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No buyer user found'
                ], 400);
            }

            // Create a test order
            $order = new Order([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_harga' => rand(20000, 100000)
            ]);
            $order->id = rand(1000, 9999); // Fake ID for testing

            // Set user relationship
            $order->setRelation('user', $user);

            $oldStatus = 'pending';
            $newStatus = collect(['ready', 'paid'])->random();

            // Fire the event manually
            event(new OrderStatusUpdated($order, $oldStatus, $newStatus));

            return response()->json([
                'success' => true,
                'message' => 'Order broadcast event fired successfully',
                'data' => [
                    'order_id' => $order->id,
                    'user' => $user->name,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'total' => $order->total_harga
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error firing order broadcast: ' . $e->getMessage()
            ], 500);
        }
    }
}
