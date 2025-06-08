<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pembeli']);
    }

    public function index() {
        $keranjang = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->get();
            
        $total = $keranjang->sum(function($item) {
            return $item->menu->harga * $item->jumlah;
        });
        
        return view('pembeli.keranjang', compact('keranjang', 'total'));
    }

    public function store(CartRequest $request) {
        $menu = Menu::findOrFail($request->menu_id);
        
        // Check if menu is already in cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('menu_id', $request->menu_id)
            ->first();
            
        if ($existingCart) {
            $totalQuantity = $existingCart->jumlah + $request->jumlah;
            if ($menu->stok < $totalQuantity) {
                return redirect()->back()
                    ->withErrors(['jumlah' => 'Stok tidak mencukupi untuk total pesanan']);
            }
            
            $existingCart->jumlah = $totalQuantity;
            $existingCart->save();
            
            return redirect()->back()
                ->with('success', 'Jumlah item berhasil diperbarui');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'menu_id' => $request->menu_id,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->back()
            ->with('success', 'Item berhasil ditambahkan ke keranjang');
    }

    public function update(CartRequest $request, $id) {
        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($id);

        $cart->jumlah = $request->jumlah;
        $cart->save();

        return redirect()->back()
            ->with('success', 'Jumlah item berhasil diperbarui');
    }

    public function destroy($id) {
        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $cart->delete();
        
        return redirect()->back()
            ->with('success', 'Item berhasil dihapus dari keranjang');
    }
}