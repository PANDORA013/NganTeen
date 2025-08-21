<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodNews;
use App\Models\Menu;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FoodNewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display food news management
     */
    public function index(Request $request)
    {
        $query = FoodNews::with(['menu', 'warung', 'creator']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $stats = [
            'total_news' => FoodNews::count(),
            'active_news' => FoodNews::where('is_active', true)->count(),
            'new_menu_news' => FoodNews::where('type', 'new_menu')->count(),
            'promo_news' => FoodNews::where('type', 'promo')->count(),
        ];

        return view('admin.food-news.index', compact('news', 'stats'));
    }

    /**
     * Show form to create news
     */
    public function create()
    {
        $menus = Menu::where('stok', '>', 0)->get();
        $warungs = Warung::where('status', 'aktif')->get();
        
        return view('admin.food-news.create', compact('menus', 'warungs'));
    }

    /**
     * Store new food news
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:new_menu,promo,announcement',
            'menu_id' => 'nullable|exists:menus,id',
            'warung_id' => 'nullable|exists:warungs,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only(['title', 'content', 'type', 'menu_id', 'warung_id', 'published_at']);
        $data['created_by'] = Auth::id();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('food-news', 'public');
        }

        FoodNews::create($data);

        return redirect()->route('admin.food-news.index')
                        ->with('success', 'Food news created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(FoodNews $news)
    {
        $menus = Menu::where('stok', '>', 0)->get();
        $warungs = Warung::where('status', 'aktif')->get();
        
        return view('admin.food-news.edit', compact('news', 'menus', 'warungs'));
    }

    /**
     * Update food news
     */
    public function update(Request $request, FoodNews $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:new_menu,promo,announcement',
            'menu_id' => 'nullable|exists:menus,id',
            'warung_id' => 'nullable|exists:warungs,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only(['title', 'content', 'type', 'menu_id', 'warung_id', 'published_at']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('food-news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.food-news.index')
                        ->with('success', 'Food news updated successfully!');
    }

    /**
     * Delete food news
     */
    public function destroy(FoodNews $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'Food news deleted successfully!'
        ]);
    }

    /**
     * Toggle news status
     */
    public function toggleStatus(FoodNews $news)
    {
        $news->is_active = !$news->is_active;
        $news->save();

        $status = $news->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "News has been {$status} successfully.",
            'is_active' => $news->is_active
        ]);
    }
}
