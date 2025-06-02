<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        $keranjang = Cart::with('menu')->where('user_id', $user->id)->get();

        $total = 0;
        foreach ($keranjang as $item) {
            $total += $item->menu->harga * $item->jumlah;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_harga' => $total,
        ]);

        foreach ($keranjang as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item->menu_id,
                'jumlah' => $item->jumlah,
                'subtotal' => $item->menu->harga * $item->jumlah,
            ]);
        }

        Cart::where('user_id', $user->id)->delete();

        return redirect('/pembeli/riwayat')->with('success', 'Pesanan berhasil dibuat');
    }

    public function riwayat()
    {
        $orders = Order::with('items.menu')->where('user_id', Auth::id())->get();
        return view('pembeli.riwayat', compact('orders'));
    }
}