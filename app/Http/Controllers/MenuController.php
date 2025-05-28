<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('penjual_id', auth()->id())->get();
        return view('produk.index', compact('menus'));
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect()->route('produk')->with('message', 'Menu berhasil dihapus.');
    }
}
