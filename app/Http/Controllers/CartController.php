<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:access-cart');
    }

    public function index() {
        $keranjang = Cart::with('menu')->where('user_id', Auth::id())->get();
        return view('pembeli.keranjang', compact('keranjang'));
    }

    public function store(Request $request) {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        if ($menu->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'menu_id' => $request->menu_id,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->back()->with('success', 'Item berhasil ditambahkan ke keranjang');
    }

    public function destroy($id) {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();
        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang');
    }
}