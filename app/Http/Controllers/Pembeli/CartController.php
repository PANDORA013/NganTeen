<?php

namespace App\Http\Controllers\Pembeli;

use App\Models\Cart;
use App\Models\Menu;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

/**
 * Controller keranjang belanja khusus pembeli.
 *
 * Memisahkan logic pembeli ke dalam namespace Pembeli agar struktur proyek lebih rapi.
 */
class CartController extends Controller
{
    /**
     * Constructor - menambahkan middleware
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:pembeli']);
    }

    /**
     * Menampilkan isi keranjang belanja
     *
     * @return View
     */
    public function index(): View
    {
        $keranjang = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->get();

        $total = $keranjang->sum(function ($item) {
            return $item->menu->harga * $item->jumlah;
        });

        // Total quantity semua item
        $totalQuantity = $keranjang->sum('jumlah');

        return view('pembeli.keranjang', compact('keranjang', 'total', 'totalQuantity'));
    }

    /**
     * Menambahkan item ke keranjang
     *
     * @param CartRequest $request
     * @return RedirectResponse
     */
    public function store(CartRequest $request): RedirectResponse
    {
        $menu = Menu::findOrFail($request->menu_id);

        // Cek apakah menu sudah ada di keranjang
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

    /**
     * Mengupdate jumlah item di keranjang
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $cart->menu->stok,
        ]);

        $cart->jumlah = $request->jumlah;
        $cart->save();

        return redirect()->route('pembeli.cart.index')
            ->with('success', 'Jumlah item berhasil diperbarui');
    }

    /**
     * Menghapus item dari keranjang
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($id);

        $cart->delete();

        return redirect()->route('pembeli.cart.index')
            ->with('success', 'Item berhasil dihapus dari keranjang');
    }

    /**
     * Mengembalikan jumlah total item di keranjang (AJAX)
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        $keranjang = Cart::where('user_id', Auth::id())->get();
        $totalQuantity = $keranjang->sum('jumlah');
        $menuCount = $keranjang->count();
        
        return response()->json([
            'count' => $totalQuantity,
            'menu_count' => $menuCount,
            'total_items' => $totalQuantity
        ]);
    }
}
