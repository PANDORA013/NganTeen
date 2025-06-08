<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pembeli']);
    }
    
    public function checkout()
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $keranjang = Cart::with('menu')->where('user_id', $user->id)->get();
            
            if ($keranjang->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
            }

            // Validate stock availability
            foreach ($keranjang as $item) {
                if ($item->menu->stok < $item->jumlah) {
                    return redirect()->route('cart.index')
                        ->with('error', "Stok {$item->menu->nama_menu} tidak mencukupi");
                }
            }

            $total = 0;
            foreach ($keranjang as $item) {
                $total += $item->menu->harga * $item->jumlah;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_harga' => $total,
                'status' => 'pending'
            ]);

            foreach ($keranjang as $item) {
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'jumlah' => $item->jumlah,
                    'subtotal' => $item->menu->harga * $item->jumlah,
                ]);

                // Update stock
                $menu = $item->menu;
                $menu->stok -= $item->jumlah;
                $menu->save();
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();
            return redirect()->route('order.history')->with('success', 'Pesanan berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan');
        }
    }

    public function riwayat()
    {
        $orders = Order::with(['items.menu'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('pembeli.riwayat', compact('orders'));
    }

    public function cancel(Order $order)
    {
        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        try {
            DB::beginTransaction();

            // Return stock to menu
            foreach ($order->items as $item) {
                $menu = $item->menu;
                $menu->stok += $item->jumlah;
                $menu->save();
            }

            $order->cancel();

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan');
        }
    }
}