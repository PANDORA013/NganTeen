<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Constructor, menambahkan middleware auth
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Proses checkout oleh pembeli
     * 
     * @param Request $request Data request
     * @return RedirectResponse
     */
    public function checkout(Request $request): RedirectResponse
    {
        $request->validate([
            'menu_ids' => 'required|array',
            'menu_ids.*' => 'exists:menus,id'
        ]);

        foreach ($request->menu_ids as $menu_id) {
            Order::create([
                'user_id' => Auth::id(),
                'menu_id' => $menu_id,
                'status' => 'pending',
            ]);
        }

        return redirect()->back()->with('message', 'Silakan lakukan pembayaran ke kantin setelah status pesanan Anda berubah menjadi "Siap Diambil".');
    }

    /**
     * Penjual menandai pesanan sudah dibayar
     * 
     * @param int $id ID pesanan
     * @return RedirectResponse
     */
    public function markAsPaid(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->status = 'paid';
        $order->save();

        // Kirim notifikasi ke pembeli
        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusUpdated($order, 'paid'));
        }

        return redirect()->back()->with('success', 'Pesanan telah ditandai sebagai dibayar.');
    }

    /**
     * Menampilkan daftar pesanan untuk penjual dengan filter dan pagination
     * 
     * @return View
     */
    public function daftarPesanan(): View
    {
        $status = request('status');
        $fromDate = request('from_date');
        $toDate = request('to_date');
        $search = request('search');

        $orders = Order::query()
            ->select('orders.*')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->where('menus.user_id', Auth::id())
            ->with(['orderItems.menu', 'user'])
            ->when($status && in_array($status, ['pending', 'processing', 'siap_diambil', 'selesai', 'batal']), function($query) use ($status) {
                $query->where('orders.status', $status);
            })
            ->when($fromDate, function($query) use ($fromDate) {
                $query->whereDate('orders.created_at', '>=', $fromDate);
            })
            ->when($toDate, function($query) use ($toDate) {
                $query->whereDate('orders.created_at', '<=', $toDate);
            })
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->with(['orderItems' => function($query) {
                $query->whereHas('menu', function($q) {
                    $q->where('user_id', Auth::id());
                });
            }])
            ->distinct()
            ->latest('orders.created_at')
            ->paginate(10)
            ->withQueryString();

        // Calculate total revenue for the filtered results
        $totalRevenue = 0;
        foreach ($orders as $order) {
            $totalRevenue += $order->orderItems->sum(function($item) {
                return $item->menu->harga * $item->quantity;
            });
        }

        return view('penjual.orders.index', compact('orders', 'totalRevenue'));
    }

    /**
     * Menampilkan daftar pesanan untuk pembeli
     * 
     * @return View
     */
    public function userOrders(): View
    {
        $status = request('status');
        $fromDate = request('from_date');
        $toDate = request('to_date');

        $orders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['selesai', 'batal']) // Hanya tampilkan yang selesai atau batal
            ->when($status && in_array($status, ['selesai', 'batal']), function($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($fromDate, function($query) use ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function($query) use ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            })
            ->with(['orderItems.menu']) // Eager load order items and their related menus
            ->latest()
            ->paginate(10)
            ->appends(request()->query());

        return view('pembeli.orders.index', compact('orders'));
    }

    /**
     * Method index untuk routing
     * 
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user && $user->isPenjual()) {
            return $this->daftarPesanan();
        }
        
        return $this->userOrders();
    }
}