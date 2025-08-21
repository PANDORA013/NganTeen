<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPaymentSetting;
use App\Models\GlobalOrder;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display payment settings
     */
    public function index()
    {
        $setting = AdminPaymentSetting::getActive();
        $stats = [
            'total_paid_orders' => GlobalOrder::where('payment_status', 'paid')->count(),
            'total_pending_orders' => GlobalOrder::where('payment_status', 'pending')->count(),
            'total_revenue' => GlobalOrder::where('payment_status', 'paid')->sum('gross_amount'),
            'total_platform_fee' => GlobalOrder::where('payment_status', 'paid')->sum('platform_fee'),
            'unsettled_orders' => GlobalOrder::where('payment_status', 'paid')
                ->where('is_settled', false)->count(),
        ];
        
        return view('admin.payment-settings', compact('setting', 'stats'));
    }

    /**
     * Update payment settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'merchant_name' => 'required|string|max:255',
            'platform_fee_percentage' => 'required|numeric|min:0|max:50',
            'payment_fee_fixed' => 'required|numeric|min:0',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
        ]);

        $setting = AdminPaymentSetting::getActive();
        if (!$setting) {
            $setting = new AdminPaymentSetting();
        }

        $data = $request->only([
            'merchant_name',
            'platform_fee_percentage', 
            'payment_fee_fixed',
            'bank_name',
            'bank_account_number',
            'bank_account_name'
        ]);

        // Handle QRIS image upload
        if ($request->hasFile('qris_image')) {
            // Delete old image
            if ($setting->qris_image) {
                Storage::disk('public')->delete($setting->qris_image);
            }
            
            $data['qris_image'] = $request->file('qris_image')->store('qris', 'public');
        }

        $setting->fill($data);
        $setting->is_active = true;
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan pembayaran berhasil diperbarui');
    }

    /**
     * Show settlement management
     */
    public function settlements(Request $request)
    {
        $query = GlobalOrder::with(['warung'])
                           ->where('payment_status', 'paid');
        
        // Apply filters
        if ($request->filled('warung_id')) {
            $query->where('warung_id', $request->warung_id);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_settled', false);
            } elseif ($request->status === 'settled') {
                $query->where('is_settled', true);
            }
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $query->where('id', 'like', '%' . $request->search . '%');
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Calculate statistics
        $stats = [
            'pending_orders' => GlobalOrder::where('payment_status', 'paid')
                                          ->where('is_settled', false)
                                          ->count(),
            'pending_amount' => GlobalOrder::where('payment_status', 'paid')
                                          ->where('is_settled', false)
                                          ->sum('net_amount'),
            'settled_today' => GlobalOrder::where('payment_status', 'paid')
                                         ->where('is_settled', true)
                                         ->whereDate('settled_at', today())
                                         ->sum('net_amount'),
            'total_settled' => GlobalOrder::where('payment_status', 'paid')
                                         ->where('is_settled', true)
                                         ->count(),
        ];
        
        // Get all warungs for filter
        $warungs = Warung::select('id', 'nama_warung')->get();
        
        return view('admin.settlements', compact('orders', 'stats', 'warungs'));
    }

    /**
     * Settle single order
     */
    public function settleOrder(GlobalOrder $order)
    {
        if ($order->payment_status !== 'paid' || $order->is_settled) {
            return response()->json(['success' => false, 'message' => 'Order tidak dapat di-settle']);
        }

        DB::beginTransaction();
        try {
            $order->markAsSettled();
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Order berhasil di-settle']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Bulk settle orders
     */
    public function settleBulk(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        
        if (empty($orderIds)) {
            return response()->json(['success' => false, 'message' => 'Pilih order yang akan di-settle']);
        }

        $orders = GlobalOrder::whereIn('id', $orderIds)
            ->where('payment_status', 'paid')
            ->where('is_settled', false)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada order yang valid untuk di-settle']);
        }

        DB::beginTransaction();
        try {
            $settledCount = 0;
            foreach ($orders as $order) {
                $order->markAsSettled();
                $settledCount++;
            }

            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => "Berhasil settle {$settledCount} order"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Settle all pending orders
     */
    public function settleAll()
    {
        $orders = GlobalOrder::where('payment_status', 'paid')
            ->where('is_settled', false)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada order yang perlu di-settle']);
        }

        DB::beginTransaction();
        try {
            $settledCount = 0;
            foreach ($orders as $order) {
                $order->markAsSettled();
                $settledCount++;
            }

            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => "Berhasil settle semua {$settledCount} order"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
