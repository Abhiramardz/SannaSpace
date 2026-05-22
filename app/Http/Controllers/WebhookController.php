<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleXenditCallback(Request $request)
    {
        // 1. Ambil token dari header request Xendit dan bandingkan dengan .env
        $xenditToken = env('XENDIT_WEBHOOK_TOKEN');
        $callbackToken = $request->header('x-callback-token');

        // Jika token tidak cocok, tolak request (Keamanan)
        if ($callbackToken !== $xenditToken) {
            Log::warning('Xendit Webhook: Invalid Token dari IP ' . $request->ip());
            return response()->json(['message' => 'Token tidak valid'], 403);
        }

        // 2. Ambil data payload dari Xendit
        $externalId = $request->input('external_id'); // Ini adalah order_number kita
        $status = $request->input('status'); // Status dari Xendit (PAID, EXPIRED, dll)
        
        Log::info("Xendit Webhook Diterima: Pesanan {$externalId} berstatus {$status}");

        // 3. Cari pesanan di database berdasarkan order_number
        $order = Order::with('items')->where('order_number', $externalId)->first();

        // Jika pesanan tidak ditemukan di database kita
        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // 4. Update status berdasarkan konfirmasi Xendit
        if ($status === 'PAID' || $status === 'SETTLED') {
            // Cek agar tidak update pesanan yang sudah dibatalkan atau selesai
            if (in_array($order->status, ['unpaid', 'pending'])) {
                $order->update([
                    'status' => 'pending', // Masuk ke "Menunggu" agar diproses Kasir
                ]);
                Log::info("Pesanan {$externalId} berhasil dibayar dan berstatus pending.");
            }
        } 
        elseif ($status === 'EXPIRED') {
            // Jika pelanggan tidak membayar hingga batas waktu habis
            if ($order->status !== 'cancelled') {
                // Kembalikan stok produk ke semula
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
                
                // Ubah status jadi dibatalkan
                $order->update(['status' => 'cancelled']);
                Log::info("Pesanan {$externalId} expired dan otomatis dibatalkan. Stok dikembalikan.");
            }
        }

        // 5. Kembalikan respons 200 OK agar Xendit berhenti mengirim ulang notifikasi
        return response()->json(['status' => 'success', 'message' => 'Webhook berhasil diproses'], 200);
    }
}