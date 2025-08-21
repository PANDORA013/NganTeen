<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\GlobalOrder;
use App\Models\GlobalOrderItem;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\AdminPaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GlobalCheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pembeli']);
    }

    /**
     * Display checkout page with cart summary grouped by warung
     */
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = Cart::with(['menu.user.warung'])->where('user_id', $user->id)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('pembeli.cart.index')
                ->with('error', 'Keranjang belanja kosong');
        }

        // Group cart items by warung
        $warungGroups = $cartItems->groupBy(function($item) {
            return $item->menu->user->warung->id ?? 'no_warung';
        });

        // Remove items without warung
        $warungGroups = $warungGroups->except(['no_warung']);

        if ($warungGroups->isEmpty()) {
            return redirect()->route('pembeli.cart.index')
                ->with('error', 'Menu yang dipilih tidak memiliki warung yang valid');
        }

        // Calculate totals with centralized payment
        $subtotalPerWarung = [];
        $grandTotal = 0;
        $paymentSetting = AdminPaymentSetting::getActive();

        foreach ($warungGroups as $warungId => $items) {
            $warungTotal = $items->sum(function($item) {
                return $item->menu->harga * $item->jumlah;
            });
            $subtotalPerWarung[$warungId] = $warungTotal;
            $grandTotal += $warungTotal;
        }

        // Calculate fees
        $platformFee = $paymentSetting->calculatePlatformFee($grandTotal);
        $paymentFee = $paymentSetting->payment_fee_fixed;
        $grossAmount = $paymentSetting->calculateGrossAmount($grandTotal);

        return view('pembeli.global-checkout', compact(
            'warungGroups', 
            'subtotalPerWarung', 
            'grandTotal',
            'platformFee',
            'paymentFee', 
            'grossAmount',
            'paymentSetting'
        ));
    }

    /**
     * Process the checkout and create global order
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with(['menu.user.warung'])->where('user_id', $user->id)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            // Group by warung and validate stock
            $warungGroups = $cartItems->groupBy(function($item) {
                return $item->menu->user->warung->id ?? 'no_warung';
            });
            $warungGroups = $warungGroups->except(['no_warung']);

            $grandTotal = 0;
            $orderData = [];

            // Validate stock and calculate totals
            $paymentSetting = AdminPaymentSetting::getActive();
            
            foreach ($warungGroups as $warungId => $items) {
                foreach ($items as $item) {
                    if ($item->menu->stok < $item->jumlah) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 
                            "Stok {$item->menu->nama_menu} tidak mencukupi"
                        );
                    }
                    
                    $subtotal = $item->menu->harga * $item->jumlah;
                    $grandTotal += $subtotal;
                    
                    $orderData[] = [
                        'warung_id' => $warungId,
                        'menu_id' => $item->menu_id,
                        'quantity' => $item->jumlah,
                        'unit_price' => $item->menu->harga,
                        'subtotal' => $subtotal,
                    ];
                }
            }

            // Create global order with centralized payment
            $globalOrder = GlobalOrder::create([
                'buyer_id' => $user->id,
                'total_amount' => $grandTotal,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'qris', // Default to QRIS
            ]);

            // Calculate and set amounts
            $globalOrder->calculateAmounts();
            $globalOrder->save();

            // Create order items (NO WALLET UPDATE YET - wait for settlement)
            foreach ($orderData as $data) {
                // Create order item
                GlobalOrderItem::create([
                    'global_order_id' => $globalOrder->id,
                    'warung_id' => $data['warung_id'],
                    'menu_id' => $data['menu_id'],
                    'quantity' => $data['quantity'],
                    'unit_price' => $data['unit_price'],
                    'subtotal' => $data['subtotal'],
                    'status' => 'pending',
                ]);

                // Update stock
                $menu = Menu::find($data['menu_id']);
                $menu->decrement('stok', $data['quantity']);
            }

            // Create transaction record
            $transaction = Transaction::create([
                'global_order_id' => $globalOrder->id,
                'buyer_id' => $user->id,
                'amount' => $globalOrder->gross_amount, // Use gross amount (total to be paid)
                'status' => 'pending',
                'type' => 'payment',
            ]);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Redirect to payment page with admin QRIS
            return redirect()->route('global.payment', $globalOrder->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    /**
     * Simulate payment success (for demo purposes)
     * In real app, this would be called by payment gateway webhook
     */
    private function simulatePaymentSuccess($globalOrder, $transaction)
    {
        DB::beginTransaction();
        try {
            // Mark order and transaction as paid
            $globalOrder->markAsPaid($transaction->transaction_id);
            $transaction->markAsSuccess();

            // Move pending balance to available balance for each warung
            foreach ($globalOrder->items as $item) {
                $wallet = Wallet::where('warung_id', $item->warung_id)->first();
                if ($wallet) {
                    $wallet->moveToBalance($item->subtotal);
                }
            }

            DB::commit();

            return redirect()->route('pembeli.orders.index')
                ->with('success', 
                    'Pembayaran berhasil! Total: Rp ' . number_format($globalOrder->total_amount) . 
                    '. Pesanan Anda sedang diproses.'
                );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Payment webhook handler (for real payment gateway integration)
     */
    public function paymentWebhook(Request $request)
    {
        // This would be called by payment gateway
        // Validate webhook signature and process payment result
        
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status'); // success/failed
        
        $transaction = Transaction::where('transaction_id', $transactionId)->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();
        try {
            if ($status === 'success') {
                $transaction->markAsSuccess();
                $transaction->globalOrder->markAsPaid($transactionId);
                
                // Move pending balance to available balance
                foreach ($transaction->globalOrder->items as $item) {
                    $wallet = Wallet::where('warung_id', $item->warung_id)->first();
                    if ($wallet) {
                        $wallet->moveToBalance($item->subtotal);
                    }
                }
            } else {
                $transaction->markAsFailed();
                // Restore stock and remove pending balance
                // Implementation details...
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show payment page with admin QRIS
     */
    public function payment($orderNumber)
    {
        $order = GlobalOrder::where('order_number', $orderNumber)
            ->where('buyer_id', Auth::id())
            ->where('payment_status', 'pending')
            ->with(['items.warung', 'items.menu'])
            ->firstOrFail();

        $paymentSetting = AdminPaymentSetting::getActive();
        
        // Group items by warung for display
        $warungGroups = $order->items->groupBy('warung_id');
        
        return view('pembeli.payment', compact('order', 'paymentSetting', 'warungGroups'));
    }

    /**
     * Handle payment confirmation (manual for now)
     */
    public function confirmPayment(Request $request, $orderNumber)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $order = GlobalOrder::where('order_number', $orderNumber)
            ->where('buyer_id', Auth::id())
            ->where('payment_status', 'pending')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Store payment proof
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            
            // Update order status to paid (in real app, this would be done after verification)
            $order->update([
                'payment_status' => 'paid',
                'payment_reference' => $paymentProofPath,
                'paid_at' => now()
            ]);

            // Update transaction
            $order->transaction->update([
                'status' => 'completed',
                'reference_number' => 'PAY-' . time(),
                'completed_at' => now()
            ]);

            DB::commit();

            return redirect()->route('pembeli.orders.index')
                ->with('success', 'Pembayaran berhasil dikonfirmasi! Order Anda sedang diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal konfirmasi pembayaran: ' . $e->getMessage());
        }
    }
}
