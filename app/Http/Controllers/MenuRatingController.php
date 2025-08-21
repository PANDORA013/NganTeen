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

        // Calculate new average and total ratings
        $newAverage = $menu->averageRating();
        $totalRatings = $menu->ratings()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rating berhasil disimpan!',
                'newAverage' => $newAverage,
                'totalRatings' => $totalRatings,
                'userRating' => $rating->rating
            ]);
        }

        return back()->with('success', 'Ulasan berhasil disimpan!');
    }

    public function destroy(Menu $menu)
    {
        $rating = $menu->ratings()->where('user_id', Auth::id())->first();
        
        if ($rating) {
            $rating->delete();
            
            // Calculate new average and total ratings
            $newAverage = $menu->averageRating();
            $totalRatings = $menu->ratings()->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Rating berhasil dihapus!',
                'newAverage' => $newAverage,
                'totalRatings' => $totalRatings
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Rating tidak ditemukan'
        ], 404);
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
