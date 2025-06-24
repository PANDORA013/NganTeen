<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            $menus = Menu::where('user_id', Auth::id())
                        ->latest()
                        ->get();
            return view('penjual.kelola_menu', compact('menus'));
        }
        
        $menus = Menu::where('stok', '>', 0)
                     ->latest()
                     ->get();
        return view('menu.index', compact('menus'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_menu' => 'required|string|max:255',
                'harga' => 'required|integer|min:100',
                'stok' => 'required|integer|min:0',
                'area_kampus' => 'required|in:Kampus A,Kampus B,Kampus C',
                'nama_warung' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'harga.min' => 'Harga minimal adalah Rp 100',
                'stok.min' => 'Stok tidak boleh negatif',
                'gambar.max' => 'Ukuran gambar maksimal 2MB',
                'gambar.mimes' => 'Format gambar harus JPEG, PNG, atau JPG'
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

            $menu = Menu::create($data);
            Log::info('Menu berhasil dibuat:', ['menu_id' => $menu->id]);

            return redirect()
                ->route('penjual.menu.index')
                ->with('success', 'Menu berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Gagal membuat menu:', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan menu. ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Menu $menu)
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
            ], [
                'harga.min' => 'Harga minimal adalah Rp 100',
                'stok.min' => 'Stok tidak boleh negatif',
                'gambar.max' => 'Ukuran gambar maksimal 2MB',
                'gambar.mimes' => 'Format gambar harus JPEG, PNG, atau JPG'
            ]);

            $data = $validated;

            if ($request->hasFile('gambar')) {
                // Delete old image if exists
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
            }

            $menu->update($data);
            Log::info('Menu berhasil diperbarui:', ['menu_id' => $menu->id]);

            return redirect()
                ->back()
                ->with('success', 'Menu berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui menu:', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui menu. ' . $e->getMessage()]);
        }
    }

    public function destroy(Menu $menu)
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

    public function show(Menu $menu)
    {
        return view('menu.show', compact('menu'));
    }
}