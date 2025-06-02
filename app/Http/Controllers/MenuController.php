<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-menu')->except(['index', 'show']);
    }

    public function index() 
    {
        if (request()->is('penjual*')) {
            $this->authorize('viewAny', Menu::class);
            $menus = Menu::where('user_id', Auth::id())->get();
            return view('penjual.kelola_menu', compact('menus'));
        }
        
        // Public menu listing
        $menus = Menu::where('stok', '>', 0)->get();
        return view('menu.index', compact('menus'));
    }

    public function show(Menu $menu)
    {
        return view('menu.show', compact('menu'));
    }

    public function store(Request $request) {
        $this->authorize('create', Menu::class);
        
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'area_kampus' => 'required',
            'nama_warung' => 'required',
        ]);

        Menu::create([
            'user_id' => Auth::id(),
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'area_kampus' => $request->area_kampus,
            'nama_warung' => $request->nama_warung,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan');
    }

    public function update(Request $request, Menu $menu) {
        $this->authorize('update', $menu);
        
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'area_kampus' => 'required',
            'nama_warung' => 'required',
        ]);

        $menu->update($request->all());
        return redirect()->back()->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Menu $menu) {
        $this->authorize('delete', $menu);
        
        $menu->delete();
        return redirect()->back()->with('success', 'Menu berhasil dihapus');
    }
}