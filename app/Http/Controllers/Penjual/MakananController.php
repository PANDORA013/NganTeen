<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MakananController extends Controller
{
    public function index()
    {
        $foods = Food::where('seller_id', Auth::id())
            ->latest()
            ->paginate(10); // Added pagination for better performance
        
        return view('penjual.makanan.index', compact('foods'));
    }

    public function create()
    {
        $locations = ['Kampus A', 'Kampus B'];
        return view('penjual.makanan.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'location' => 'required|in:Kampus A,Kampus B',
            'estimated_time' => 'required|numeric|min:1',
            'status' => 'nullable|in:Ready Stok,Stok Empty'
        ]);

        $photoPath = $request->file('photo')->store('makanan', 'public');

        Food::create([
            'seller_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'photo_url' => 'storage/' . $photoPath,
            'location' => $validated['location'],
            'estimated_time' => $validated['estimated_time'],
            'status' => $validated['status'] ?? 'Ready Stok'
        ]);

        return redirect()->route('penjual.makanan.index')
            ->with('success', 'Makanan berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $food = Food::where('seller_id', Auth::id())->findOrFail($id);
        $locations = ['Kampus A', 'Kampus B'];
        
        return view('penjual.makanan.edit', compact('food', 'locations'));
    }

    public function update(Request $request, string $id)
    {
        $food = Food::where('seller_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'location' => 'required|in:Kampus A,Kampus B',
            'estimated_time' => 'required|numeric|min:1',
            'status' => 'nullable|in:Ready Stok,Stok Empty'
        ]);

        if ($request->hasFile('photo')) {
            if ($food->photo_url) {
                Storage::disk('public')->delete(str_replace('storage/', '', $food->photo_url));
            }
            $photoPath = $request->file('photo')->store('makanan', 'public');
            $food->photo_url = 'storage/' . $photoPath;
        }

        $food->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'location' => $validated['location'],
            'estimated_time' => $validated['estimated_time'],
            'status' => $validated['status'] ?? $food->status
        ]);

        return redirect()->route('penjual.makanan.index')
            ->with('success', 'Makanan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $food = Food::where('seller_id', Auth::id())->findOrFail($id);

        if ($food->photo_url) {
            Storage::disk('public')->delete(str_replace('storage/', '', $food->photo_url));
        }

        $food->delete();

        return redirect()->route('penjual.makanan.index')
            ->with('success', 'Makanan berhasil dihapus!');
    }
}
