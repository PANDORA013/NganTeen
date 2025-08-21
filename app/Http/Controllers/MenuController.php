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
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * Validation rules for menu
     */
    private const VALIDATION_RULES = [
        'nama_menu' => 'required|string|max:255',
        'harga' => 'required|integer|min:1000',
        'stok' => 'required|integer|min:0',
        'area_kampus' => 'required|in:Kampus A,Kampus B,Kampus C',
        'kategori' => 'required|in:Makanan,Minuman,Snack,Paket,Lainnya',
        'deskripsi' => 'required|string|max:1000',
        'nama_warung' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ];

    /**
     * Constructor with middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:penjual')->except(['show']);
    }

    /**
     * Display menu list for seller
     */
    public function index(): View
    {
        $menus = Menu::where('user_id', Auth::id())
                    ->latest()
                    ->get();
                    
        return view('penjual.menu.index', compact('menus'));
    }

    /**
     * Display professional menu list for seller
     */
    public function indexProfessional(): View
    {
        $user = Auth::user();
        $menus = Menu::where('user_id', $user->id)
                    ->latest()
                    ->get();
        
        // Calculate menu statistics
        $totalMenus = $menus->count();
        $inStockMenus = $menus->where('stok', '>', 5)->count();
        $lowStockMenus = $menus->whereBetween('stok', [1, 5])->count();
        $outOfStockMenus = $menus->where('stok', 0)->count();
        
        return view('penjual.menu.index-professional', compact(
            'menus', 
            'totalMenus', 
            'inStockMenus', 
            'lowStockMenus', 
            'outOfStockMenus'
        ));
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        return view('penjual.menu.create');
    }

    /**
     * Show professional create form
     */
    public function createProfessional(): View
    {
        return view('penjual.menu.create-professional');
    }

    /**
     * Store new menu
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->authorize('create', Menu::class);
            
            $validated = $request->validate(self::VALIDATION_RULES);
            $data = $this->prepareMenuData($validated, $request);

            Menu::create($data);

            return redirect()
                ->route('penjual.menu.index')
                ->with('success', 'Menu berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return $this->handleError($e, 'menambahkan menu', $request);
        }
    }

    /**
     * Show edit form
     */
    public function edit(Menu $menu): View
    {
        $this->authorize('update', $menu);
        return view('penjual.menu.edit', compact('menu'));
    }

    /**
     * Show professional edit form
     */
    public function editProfessional(Menu $menu): View
    {
        // Ensure user owns this menu
        if ($menu->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('penjual.menu.create-professional', compact('menu'));
    }

    /**
     * Update menu
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        try {
            $this->authorize('update', $menu);
            
            $validated = $request->validate(self::VALIDATION_RULES);
            $data = $this->prepareMenuData($validated, $request, $menu);

            $menu->update($data);
            
            return redirect()
                ->route('penjual.menu.index')
                ->with('success', 'Menu berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return $this->handleError($e, 'memperbarui menu', $request);
        }
    }

    /**
     * Delete menu
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        try {
            $this->authorize('delete', $menu);
            
            $this->deleteMenuImage($menu);
            $menu->delete();
            
            Log::info('Menu berhasil dihapus:', ['menu_id' => $menu->id]);

            return redirect()
                ->back()
                ->with('success', 'Menu berhasil dihapus!');
                
        } catch (\Exception $e) {
            return $this->handleError($e, 'menghapus menu');
        }
    }

    /**
     * Show menu detail (for both seller and buyer)
     */
    public function show(Menu $menu): View
    {
        return view('menu.show', compact('menu'));
    }

    /**
     * Toggle favorite status (AJAX)
     */
    public function toggleFavorite(Request $request, Menu $menu): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Authentication required'
            ], 401);
        }

        $user = Auth::user();
        $isFavorited = $menu->favoritedBy()->where('user_id', $user->id)->exists();

        if ($isFavorited) {
            $menu->favoritedBy()->detach($user->id);
            $favorited = false;
            $message = 'Menu removed from favorites';
        } else {
            $menu->favoritedBy()->attach($user->id);
            $favorited = true;
            $message = 'Menu added to favorites';
        }

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'message' => $message
        ]);
    }

    /**
     * Prepare menu data with image handling
     */
    private function prepareMenuData(array $validated, Request $request, ?Menu $existingMenu = null): array
    {
        $data = $validated;
        $data['user_id'] = Auth::id();

        if ($request->hasFile('gambar')) {
            // Delete old image if updating
            if ($existingMenu) {
                $this->deleteMenuImage($existingMenu);
            }
            
            $data['gambar'] = $this->uploadMenuImage($request->file('gambar'), $validated['nama_menu']);
        }

        return $data;
    }

    /**
     * Upload menu image
     */
    private function uploadMenuImage($imageFile, string $menuName): string
    {
        $filename = time() . '_' . Str::slug($menuName) . '.' . $imageFile->getClientOriginalExtension();
        $path = $imageFile->storeAs('menu-images', $filename, 'public');
        
        if (!$path) {
            throw new \Exception('Gagal mengupload gambar');
        }
        
        Log::info('Gambar berhasil disimpan:', ['path' => $path]);
        return $path;
    }

    /**
     * Delete menu image
     */
    private function deleteMenuImage(Menu $menu): void
    {
        if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
            Storage::disk('public')->delete($menu->gambar);
        }
    }

    /**
     * Handle errors consistently
     */
    private function handleError(\Exception $e, string $action, ?Request $request = null): RedirectResponse
    {
        Log::error("Gagal {$action}:", ['error' => $e->getMessage()]);
        
        $redirect = $request 
            ? redirect()->back()->withInput()
            : redirect()->back();
            
        return $redirect->withErrors(['error' => "Gagal {$action}. " . $e->getMessage()]);
    }
}
