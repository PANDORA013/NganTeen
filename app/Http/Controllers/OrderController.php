<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:penjual'])->only(['index', 'updateStatus']);
    }

    // Untuk penjual: melihat pesanan yang berisi menu milik penjual tersebut
    public function index()
    {
        // Get orders that contain menu items from this seller
        $orders = Order::whereHas('items.menu', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['items.menu', 'user'])
        ->latest()
        ->paginate(10);

        return view('penjual.orders.index', compact('orders'));
    }

    // Penjual update status order
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,completed,cancelled'
        ]);

        // Verify seller owns at least one menu item in this order
        $hasMenuItems = $order->items()->whereHas('menu', function($query) {
            $query->where('user_id', Auth::id());
        })->exists();

        if (!$hasMenuItems) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        if ($request->status === 'cancelled') {
            // Return stock for all items
            foreach ($order->items as $item) {
                $menu = $item->menu;
                if ($menu->user_id === Auth::id()) {
                    $menu->stok += $item->jumlah;
                    $menu->save();
                }
            }
        }

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Display orders for the authenticated pembeli
     */    public function userOrders()
    {
        $orders = Order::where('pembeli_id', Auth::id())
            ->with(['items.menu'])
            ->latest()
            ->paginate(10);

        return view('pembeli.orders.index', compact('orders'));
    }

    /**
     * Store a new order for pembeli
     */
    public function store(Request $request)
    {
        $cart = Auth::user()->cart;

        if (!$cart) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong');
        }

        $cartItems = $cart->items()->with('menu')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong');
        }        // Create new order
        $order = Order::create([
            'pembeli_id' => Auth::id(),
            'status' => 'pending',
            'total_harga' => $cartItems->sum(function($item) {
                return $item->menu->harga * $item->jumlah;
            }),
        ]);

        // Create order items and update stock
        foreach ($cartItems as $cartItem) {
            $menu = $cartItem->menu;

            // Check if stock is still available
            if ($menu->stok < $cartItem->jumlah) {
                return redirect()->back()->with('error', "Stok {$menu->nama_menu} tidak mencukupi");
            }

            // Create order item
            $order->items()->create([
                'menu_id' => $menu->id,
                'jumlah' => $cartItem->jumlah,
                'harga' => $menu->harga,
            ]);

            // Update stock
            $menu->stok -= $cartItem->jumlah;
            $menu->save();
        }

        // Hapus semua item di cart pembeli
        $cart->items()->delete();

        return redirect()->route('pembeli.orders.index')->with('success', 'Pesanan berhasil dibuat');
    }
}