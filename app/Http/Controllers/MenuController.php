<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\MenuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('role:penjual')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index() 
    {
        if (request()->is('penjual/menu*')) {
            // Penjual view - show only their menus
            $menus = Menu::where('user_id', Auth::id())->latest()->get();
            return view('penjual.kelola_menu', compact('menus'));
        }
        
        // Public menu listing - show all available menus
        $menus = Menu::where('stok', '>', 0)->latest()->get();
        return view('menu.index', compact('menus'));
    }

    public function create()
    {
        return view('penjual.menu.create');
    }    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nama_menu' => 'required|string|max:255',
                'harga' => 'required|integer|min:0',
                'stok' => 'required|integer|min:0',
                'area_kampus' => 'required|in:Kampus A,Kampus B,Kampus C',
                'nama_warung' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $data = $request->all();
            $data['user_id'] = Auth::id();

            Log::info('Attempting to create menu with data:', ['data' => $data]);

            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('menu-images', 'public');
                if (!$gambarPath) {
                    throw new \Exception('Failed to store image');
                }
                $data['gambar'] = $gambarPath;
                Log::info('Image stored at:', ['path' => $data['gambar']]);
            }

            $menu = Menu::create($data);
            Log::info('Menu created successfully:', ['menu_id' => $menu->id]);

            session()->flash('success', 'Menu berhasil ditambahkan!');
            return redirect()->route('penjual.menu.index');
        } catch (\Exception $e) {
            Log::error('Failed to create menu:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan menu. ' . $e->getMessage()]);
        }
    }

    public function show(Menu $menu)
    {
        return view('menu.show', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);
        
        $this->validate($request, [
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'area_kampus' => 'required|in:Kampus A,Kampus B,Kampus C',
            'nama_warung' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('menu-images', 'public');
        } elseif ($request->input('hapus_gambar') == '1') {
            // Delete image if requested
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = null;
        } else {
            unset($data['gambar']); // Don't update the image field if no new image
        }

        $menu->update($data);
        return redirect()->back()->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);
        
        // Delete the image if it exists
        if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
            Storage::disk('public')->delete($menu->gambar);
        }
        
        $menu->delete();
        return redirect()->back()->with('success', 'Menu berhasil dihapus');
    }
}