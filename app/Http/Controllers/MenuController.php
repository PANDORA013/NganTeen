<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Constructor, menambahkan middleware
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('role:penjual')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Menampilkan daftar menu untuk penjual
     * 
     * @return View
     */
    public function index(): View
    {
        $menus = Menu::where('user_id', Auth::id())
                    ->latest()
                    ->get();
        return view('penjual.menu.index', compact('menus'));
    }
    
    /**
     * Menampilkan daftar menu untuk pembeli
     * 
     * @return View
     */
    public function publicIndex(): View
    {
        $menus = Menu::where('stok', '>', 0)
                     ->latest()
                     ->paginate(12);
        return view('menu.index', compact('menus'));
    }
    
    /**
     * Menampilkan detail menu untuk pembeli
     * 
     * @param Menu $menu
     * @return View
     */
    public function publicShow(Menu $menu): View
    {
        return view('menu.show', compact('menu'));
    }

    /**
     * Form tambah menu baru
     * 
     * @return View
     */
    public function create(): View
    {
        return view('penjual.menu.create');
    }

    /**
     * Form edit menu
     * 
     * @param Menu $menu
     * @return View
     */
    public function edit(Menu $menu): View
    {
        $this->authorize('update', $menu);
        return view('penjual.menu.edit', compact('menu'));
    }

    /**
     * Proses simpan menu baru
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->authorize('create', Menu::class);
            
            $validated = $request->validate([
                'nama_menu' => 'required|string|max:255',
                'harga' => 'required|integer|min:100',
                'stok' => 'required|integer|min:0',
                'area_kampus' => 'required|in:Kampus A,Kampus B,Kampus C',
                'nama_warung' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $data = $validated;
            $data['user_id'] = Auth::id();

            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $filename = time() . '_' . Str::slug($request->nama_menu) . '.' . $image->getClientOriginalExtension();
                
                $path = $image->storeAs('menu-images', $filename, 'public');
                if (!$path) {
                    throw new \Exception('Gagal mengupload gambar');
                }
                
                $data['gambar'] = $path;
                Log::info('Gambar berhasil disimpan:', ['path' => $path]);
            }

            Menu::create($data);

            return redirect()
                ->route('penjual.menu.index')
                ->with('success', 'Menu berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan menu:', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan menu. ' . $e->getMessage()]);
        }
    }

    /**
     * Proses update menu
     * 
     * @param Request $request
     * @param Menu $menu
     * @return RedirectResponse
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        try {
            $this->authorize('update', $menu);
            
            $validated = $request->validate([
                'nama_menu' => 'required|string|max:255',
                'harga' => 'required|integer|min:100',
                'stok' => 'required|integer|min:0',
                'area_kampus' => 'required|in:Kampus A,Kampus B,Kampus C',
                'nama_warung' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $data = $validated;
            
            if ($request->hasFile('gambar')) {
                if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                    Storage::disk('public')->delete($menu->gambar);
                }
                
                $image = $request->file('gambar');
                $filename = time() . '_' . Str::slug($request->nama_menu) . '.' . $image->getClientOriginalExtension();
                
                $path = $image->storeAs('menu-images', $filename, 'public');
                if (!$path) {
                    throw new \Exception('Gagal mengupload gambar');
                }
                
                $data['gambar'] = $path;
                Log::info('Gambar berhasil diupdate:', ['path' => $path]);
            }

            $menu->update($data);
            
            return redirect()
                ->route('penjual.menu.index')
                ->with('success', 'Menu berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui menu:', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui menu. ' . $e->getMessage()]);
        }
    }

    /**
     * Proses hapus menu
     * 
     * @param Menu $menu
     * @return RedirectResponse
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        try {
            $this->authorize('delete', $menu);
            
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            
            $menu->delete();
            Log::info('Menu berhasil dihapus:', ['menu_id' => $menu->id]);

            return redirect()
                ->back()
                ->with('success', 'Menu berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus menu:', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withErrors(['error' => 'Gagal menghapus menu. ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan detail menu
     * 
     * @param Menu $menu
     * @return View
     */
    public function show(Menu $menu): View
    {
        return view('menu.show', compact('menu'));
    }
}