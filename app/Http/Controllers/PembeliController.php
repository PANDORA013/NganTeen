<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food; // Gunakan model Food (bukan Makanan)

class PembeliController extends Controller
{
    public function index()
    {
        // Ambil semua makanan dari database
        $makanan = Food::latest()->get(); // bisa juga pakai all()

        // Kirim ke view pembeli/home.blade.php
        return view('pembeli.home', compact('makanan'));
    }
}
