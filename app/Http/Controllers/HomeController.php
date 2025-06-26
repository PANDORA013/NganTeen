<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class HomeController
 * 
 * Menangani tampilan halaman utama
 * 
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Menangani tampilan halaman utama
     * 
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(
                Auth::user()->role === 'penjual' 
                    ? route('penjual.dashboard') 
                    : route('pembeli.dashboard')
            );
        }
        
        // Ambil menu featured untuk halaman utama
        $menus = Menu::where('stok', '>', 0)
                     ->take(6)
                     ->latest()
                     ->get();
        return view('home', compact('menus'));
    }
}
