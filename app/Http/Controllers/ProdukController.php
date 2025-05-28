<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProdukController extends Controller
{
    public function index(): View
    {
        $data = Produk::latest()->paginate(10);
        return view('produk.index', compact('data'));
    }

    public function create(): View
    {
        return view('produk.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi_produk' => 'nullable|string',
            'harga_produk' => 'required|numeric|min:0',
            'jumlah_produk' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $filename = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '-' . Str::slug($request->nama_produk) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('produk_images', $filename, 'public');
            }

            Produk::create([
                'nama_produk' => $validated['nama_produk'],
                'deskripsi_produk' => $validated['deskripsi_produk'],
                'harga_produk' => $validated['harga_produk'],
                'jumlah_produk' => $validated['jumlah_produk'],
                'foto' => $filename,
            ]);

            return redirect()
                ->route('listProduk')
                ->with('success', 'Produk berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            if ($filename && Storage::disk('public')->exists("produk_images/$filename")) {
                Storage::disk('public')->delete("produk_images/$filename");
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan produk. Silakan coba lagi.');
        }
    }
}
