<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class DashboardUserController extends Controller
{
    public function index()
    {
        $produk = Produk::all();

        return view('user.dashboard', compact('produk'));
    }
}
