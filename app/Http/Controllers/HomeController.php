<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->intended(
                Auth::user()->role === 'penjual' 
                    ? route('penjual.dashboard') 
                    : route('pembeli.dashboard')
            );
        }
        
        // Get featured menus for homepage
        $menus = Menu::where('stok', '>', 0)
                     ->take(6)
                     ->latest()
                     ->get();
        return view('home', compact('menus'));
    }
}
