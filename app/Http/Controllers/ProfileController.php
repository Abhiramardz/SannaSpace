<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('customer.editprofile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function showOrder($id)
    {
        $user = Auth::user();
        // Pastikan order milik user yang sedang login
        $order = \App\Models\Order::with('items.product')
                    ->where('id', $id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();

        // Real-time sync dengan Xendit jika status masih 'unpaid' dan menggunakan QRIS
        if ($order->status === 'unpaid' && $order->payment_method === 'QRIS') {
            try {
                $response = \Illuminate\Support\Facades\Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
                    ->get('https://api.xendit.co/v2/invoices', [
                        'external_id' => $order->order_number
                    ]);

                if ($response->successful()) {
                    $invoices = $response->json();
                    if (!empty($invoices)) {
                        $invoice = $invoices[0];
                        $status = $invoice['status'] ?? '';

                        if ($status === 'PAID' || $status === 'SETTLED') {
                            $order->update([
                                'status' => 'pending'
                            ]);
                            $order->refresh();
                            \Illuminate\Support\Facades\Log::info("Realtime Sync: Pesanan {$order->order_number} telah dibayar (PAID/SETTLED). Status diperbarui ke pending.");
                        } elseif ($status === 'EXPIRED') {
                            \Illuminate\Support\Facades\DB::transaction(function() use ($order) {
                                foreach ($order->items as $item) {
                                    $product = \App\Models\Product::find($item->product_id);
                                    if ($product) {
                                        $product->increment('stock', $item->quantity);
                                    }
                                }
                                $order->update([
                                    'status' => 'cancelled'
                                ]);
                            });
                            $order->refresh();
                            \Illuminate\Support\Facades\Log::info("Realtime Sync: Pesanan {$order->order_number} telah EXPIRED di Xendit. Status diperbarui ke cancelled dan stok dikembalikan.");
                        }
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Realtime Sync Xendit Error: ' . $e->getMessage());
            }
        }

        return view('customer.order_detail', compact('order'));
    }
}
