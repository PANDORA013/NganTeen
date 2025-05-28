<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Food;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PenjualController extends Controller
{
    /**
     * Display the seller's dashboard.
     */
    public function index(): View
    {
        // Get currently authenticated user
        $user = Auth::user();

        // Get seller's food items with eager loading
        $foods = Food::where('seller_id', $user->id)
            ->with(['orders', 'reviews'])
            ->latest()
            ->get();

        return view('penjual.home', [
            'user' => $user,
            'foods' => $foods
        ]);
    }

    public function storeMakanan(Request $request)
    {
        $validated = $request->validate([
            'nama_makanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'lokasi_kampus' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $filename = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '-' . Str::slug($request->nama_makanan) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('makanan', $filename, 'public');
        }

        Food::create([
            'nama' => $validated['nama_makanan'],
            'harga' => $validated['harga'],
            'lokasi_kampus' => $validated['lokasi_kampus'],
            'foto' => $filename,
            'penjual_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }
}
