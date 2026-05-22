<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // IMPORT HTTP CLIENT UNTUK XENDIT

class CartController extends Controller
{
    // Konstruktor Midtrans dihapus karena Xendit menggunakan HTTP Header secara langsung

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        
        return view('customer.pesanan', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $cart = session()->get('cart', []);
        $currentQtyInCart = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;

        if ($product->stock < ($currentQtyInCart + 1)) {
            return response()->json(['message' => 'Maaf, stok tidak mencukupi.'], 400);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Product added to cart',
            'cart' => $cart,
            'total_count' => array_sum(array_column($cart, 'quantity')),
            'total_price' => array_reduce($cart, function($carry, $item) {
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 0;
                return $carry + ($price * $quantity);
            }, 0)
        ]);
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $product = Product::find($request->id);
            if (!$product || $product->stock < $request->quantity) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi'], 400);
            }

            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'total_count' => array_sum(array_column($cart, 'quantity')),
                'total_price' => array_reduce($cart, function($carry, $item) {
                    $price = $item['price'] ?? 0;
                    $quantity = $item['quantity'] ?? 0;
                    return $carry + ($price * $quantity);
                }, 0)
            ]);
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            
            return response()->json([
                'success' => true,
                'total_count' => array_sum(array_column($cart, 'quantity')),
                'total_price' => array_reduce($cart, function($carry, $item) {
                    $price = $item['price'] ?? 0;
                    $quantity = $item['quantity'] ?? 0;
                    return $carry + ($price * $quantity);
                }, 0)
            ]);
        }
    }

    public function checkout(Request $request)
    {
        // ... (Kode checkout standar tidak ada yang berubah)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong.');
        }

        foreach ($cart as $productId => $details) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $details['quantity']) {
                return redirect()->back()->with('error', 'Maaf, stok ' . ($product->name ?? 'produk') . ' tidak mencukupi atau sudah habis.');
            }
        }

        return \DB::transaction(function() use ($cart) {
            $total = array_reduce($cart, function($carry, $item) {
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 0;
                return $carry + ($price * $quantity);
            }, 0);

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => 'Cash',
            ]);

            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                $product = Product::find($productId);
                $product->decrement('stock', $details['quantity']);
            }

            session()->forget('cart');

            return redirect()->route('profile')->with('success', 'Pesanan berhasil dibuat dan stok telah diperbarui!');
        });
    }

    public function checkoutPage()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/pesanan')->with('error', 'Keranjang Anda kosong.');
        }

        $total = array_reduce($cart, function($carry, $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 0;
            return $carry + ($price * $quantity);
        }, 0);

        return view('customer.pembayaran.detailpembayaran', compact('cart', 'total'));
    }

    public function prosesCheckout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }

        $request->validate([
            'payment_method' => 'required|in:Cash,QRIS'
        ], [
            'payment_method.required' => 'Silakan pilih metode pembayaran.'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Keranjang Anda kosong.'], 400);
        }

        foreach ($cart as $productId => $details) {
            $product = \App\Models\Product::find($productId);
            if (!$product || $product->stock < $details['quantity']) {
                return response()->json(['error' => 'Maaf, stok ' . ($product->name ?? 'produk') . ' tidak mencukupi.'], 400);
            }
        }

        $total = array_reduce($cart, function($carry, $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 0;
            return $carry + ($price * $quantity);
        }, 0);

        // ALUR CASH
        if ($request->payment_method === 'Cash') {
            $order = \DB::transaction(function() use ($cart, $total) {
                $newOrder = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                    'total_amount' => $total,
                    'status' => 'pending',
                    'payment_method' => 'Cash',
                ]);

                foreach ($cart as $productId => $details) {
                    OrderItem::create([
                        'order_id' => $newOrder->id,
                        'product_id' => $productId,
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                    ]);

                    $product = \App\Models\Product::find($productId);
                    $product->decrement('stock', $details['quantity']);
                }
                return $newOrder;
            });

            session()->forget('cart');
            return response()->json([
                'success' => true,
                'redirect_url' => route('profile'),
                'message' => 'Pesanan Cash berhasil dibuat!'
            ]);
        }

        // ALUR QRIS (VIA XENDIT)
        $orderNumber = 'ORD-' . strtoupper(Str::random(10));

        $order = \DB::transaction(function() use ($cart, $total, $orderNumber) {
            $newOrder = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'total_amount' => $total,
                'status' => 'unpaid',
                'payment_method' => 'QRIS',
            ]);

            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $product->decrement('stock', $details['quantity']);
                }
            }
            return $newOrder;
        });

        // Request Invoice ke Xendit
        $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
            ->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $orderNumber,
                'amount' => $total,
                'payer_email' => Auth::user()->email,
                'description' => 'Pesanan Sanna Space ' . $orderNumber,
                'success_redirect_url' => route('profile'), // Redirect ke profile jika sukses bayar
                'payment_methods' => ['QRIS'], // Kunci agar pelanggan hanya bisa bayar pakai QRIS
                'customer' => [
                    'given_names' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ]);

        if ($response->successful()) {
            $invoice = $response->json();
            session()->forget('cart');

            // Kembalikan URL Invoice Xendit ke frontend
            return response()->json([
                'success' => true,
                'redirect_url' => $invoice['invoice_url'] 
            ]);
        } else {
            return response()->json(['error' => 'Gagal menghubungi server pembayaran Xendit.'], 500);
        }
    }

    // 3. Webhook Callback Otomatis (Xendit)
    public function callback(Request $request)
    {
        $xenditToken = env('XENDIT_WEBHOOK_TOKEN');
        $callbackToken = $request->header('x-callback-token');

        // Validasi Token Keamanan dari Xendit
        if ($callbackToken !== $xenditToken) {
            return response()->json(['message' => 'Token tidak valid'], 403);
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status');
        
        $existingOrder = Order::with('items')->where('order_number', $externalId)->first();
        
        if ($existingOrder) {
            if ($status === 'PAID' || $status === 'SETTLED') {
                // Update status ke pending agar pesanan ini muncul di dashboard Kasir
                $existingOrder->update(['status' => 'pending']);
            } 
            elseif ($status === 'EXPIRED') {
                // Batalkan pesanan dan kembalikan stok jika tidak dibayar
                if ($existingOrder->status != 'cancelled') {
                    foreach ($existingOrder->items as $item) {
                        $product = \App\Models\Product::find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                    $existingOrder->update(['status' => 'cancelled']);
                }
            }
        }
        
        return response()->json(['status' => 'success']);
    }
}