<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuRatingController extends Controller
{
    public function store(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:500'
        ]);

        $rating = $menu->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['rating' => $validated['rating'], 'review' => $validated['review']]
        );

        return back()->with('success', 'Ulasan berhasil disimpan!');
    }

    public function toggleFavorite(Menu $menu)
    {
        $user = Auth::user();
        
        if($menu->isFavoritedBy($user)) {
            $menu->favoritedBy()->detach($user->id);
            $isFavorited = false;
        } else {
            $menu->favoritedBy()->attach($user->id);
            $isFavorited = true;
        }

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited
        ]);
    }
}
