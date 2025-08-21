<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pembeli']);
    }

    /**
     * Add item to cart via AJAX
     */
    public function addToCart(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'menu_id' => 'required|integer|exists:menus,id',
            'jumlah' => 'required|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $menu = Menu::findOrFail($request->menu_id);
            
            // Check stock availability
            if ($menu->stok < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $menu->stok
                ], 400);
            }

            // Check if item already exists in cart
            $existingCart = Cart::where('user_id', Auth::id())
                               ->where('menu_id', $request->menu_id)
                               ->first();

            if ($existingCart) {
                // Update quantity
                $newQuantity = $existingCart->jumlah + $request->jumlah;
                
                if ($newQuantity > $menu->stok) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total jumlah melebihi stok. Stok tersedia: ' . $menu->stok
                    ], 400);
                }

                $existingCart->jumlah = $newQuantity;
                $existingCart->save();
                
                $cartItem = $existingCart;
            } else {
                // Create new cart item
                $cartItem = Cart::create([
                    'user_id' => Auth::id(),
                    'menu_id' => $request->menu_id,
                    'jumlah' => $request->jumlah,
                ]);
            }

            // Get cart count
            $cartCount = Cart::where('user_id', Auth::id())->sum('jumlah');

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan ke keranjang',
                'data' => [
                    'cart_item' => $cartItem->load('menu'),
                    'cart_count' => $cartCount,
                    'menu_name' => $menu->nama,
                    'total_price' => $cartItem->jumlah * $menu->harga
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan ke keranjang',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update cart item quantity via AJAX
     */
    public function updateCartItem(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cartItem = Cart::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();

            // Check stock availability
            if ($cartItem->menu->stok < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $cartItem->menu->stok
                ], 400);
            }

            $cartItem->jumlah = $request->jumlah;
            $cartItem->save();

            // Calculate new totals
            $itemTotal = $cartItem->jumlah * $cartItem->menu->harga;
            $cartTotal = Cart::with('menu')
                            ->where('user_id', Auth::id())
                            ->get()
                            ->sum(function ($item) {
                                return $item->jumlah * $item->menu->harga;
                            });

            return response()->json([
                'success' => true,
                'message' => 'Jumlah item berhasil diperbarui',
                'data' => [
                    'cart_item' => $cartItem->load('menu'),
                    'item_total' => $itemTotal,
                    'cart_total' => $cartTotal,
                    'formatted_item_total' => 'Rp ' . number_format($itemTotal, 0, ',', '.'),
                    'formatted_cart_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui item',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove item from cart via AJAX
     */
    public function removeFromCart($id): JsonResponse
    {
        try {
            $cartItem = Cart::where('user_id', Auth::id())
                           ->where('id', $id)
                           ->firstOrFail();

            $menuName = $cartItem->menu->nama;
            $cartItem->delete();

            // Get updated cart count and total
            $cartItems = Cart::with('menu')->where('user_id', Auth::id())->get();
            $cartCount = $cartItems->sum('jumlah');
            $cartTotal = $cartItems->sum(function ($item) {
                return $item->jumlah * $item->menu->harga;
            });

            return response()->json([
                'success' => true,
                'message' => $menuName . ' berhasil dihapus dari keranjang',
                'data' => [
                    'cart_count' => $cartCount,
                    'cart_total' => $cartTotal,
                    'formatted_cart_total' => 'Rp ' . number_format($cartTotal, 0, ',', '.'),
                    'is_empty' => $cartCount === 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus item',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get cart count for badge update
     */
    public function getCartCount(): JsonResponse
    {
        try {
            $cartCount = Cart::where('user_id', Auth::id())->sum('jumlah');
            
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keranjang'
            ], 500);
        }
    }

    /**
     * Get cart items for quick cart display
     */
    public function getCartItems(): JsonResponse
    {
        try {
            $cartItems = Cart::with('menu')
                            ->where('user_id', Auth::id())
                            ->get();

            $total = $cartItems->sum(function ($item) {
                return $item->jumlah * $item->menu->harga;
            });

            $count = $cartItems->sum('jumlah');

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cartItems,
                    'total' => $total,
                    'count' => $count,
                    'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data keranjang',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
