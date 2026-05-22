<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
// IMPORT CLASS SDK MIDTRANS
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    public function __construct()
    {
        // INISIALISASI KONFIGURASI KUNCI MIDTRANS SANDBOX
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong.');
        }

        // Validate stock for all items first
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

            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => 'Cash',
            ]);

            // Create Order Items and Update Stock
            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                // Decrement Stock
                $product = Product::find($productId);
                $product->decrement('stock', $details['quantity']);
            }

            // Clear Cart
            session()->forget('cart');

            return redirect()->route('profile')->with('success', 'Pesanan berhasil dibuat dan stok telah diperbarui!');
        });
    }

    // 1. Menampilkan Halaman Pilihan Pembayaran (Tetap Aman)
    public function checkoutPage()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/pesanan')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung total belanjaan
        $total = array_reduce($cart, function($carry, $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 0;
            return $carry + ($price * $quantity);
        }, 0);

        return view('customer.pembayaran.detailpembayaran', compact('cart', 'total'));
    }

    // 2. Memproses Transaksi Final (Fokus Perubahan: Data Masuk DB Kasir Saat QRIS Sukses)
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

        // Validasi ketersediaan stok produk
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

        // -------------------------------------------------------------------------
        // ALUR PILIHAN KESATU: Jika memilih Cash / Bayar di Kasir (Tetap Langsung Masuk DB)
        // -------------------------------------------------------------------------
        if ($request->payment_method === 'Cash') {
            $order = \DB::transaction(function() use ($cart, $total) {
                // Membuat data Order di Database
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

        // -------------------------------------------------------------------------
        // ALUR PILIHAN KEDUA: Jika memilih QRIS (Masuk DB dengan status unpaid)
        // -------------------------------------------------------------------------
        $orderNumber = 'ORD-' . strtoupper(Str::random(10));

        $order = \DB::transaction(function() use ($cart, $total, $orderNumber) {
            $newOrder = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'total_amount' => $total,
                'status' => 'unpaid', // Status khusus untuk pesanan Midtrans yang belum dibayar
                'payment_method' => 'QRIS', // <--- MEMASTIKAN STATUS METODE BAYAR TERCATAT
            ]);

            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                // Potong stok agar tidak bisa dibeli pelanggan lain selama menunggu pembayaran
                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $product->decrement('stock', $details['quantity']);
                }
            }
            return $newOrder;
        });
        
        $itemDetails = [];
        foreach ($cart as $productId => $details) {
            $itemDetails[] = [
                'id' => (string) $productId,
                'price' => (int) ($details['price'] ?? 0),
                'quantity' => (int) ($details['quantity'] ?? 0),
                'name' => (string) substr(($details['name'] ?? 'Menu'), 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderNumber,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => ['qris', 'gopay', 'shopeepay'] // <--- KUNCI PEMBATASAN QRIS
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Bersihkan isi keranjang belanja
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghubungi server Midtrans: ' . $e->getMessage()], 500);
        }
    }

    // 3. Webhook Callback Otomatis (Mengubah status transaksi menjadi valid)
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed === $request->signature_key) {
            $transactionStatus = $request->transaction_status;
            
            $existingOrder = Order::with('items')->where('order_number', $request->order_id)->first();
            
            if ($existingOrder) {
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    // Update status ke pending agar pesanan ini muncul di dashboard Kasir
                    $existingOrder->update(['status' => 'pending']);
                } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                    // Batalkan pesanan dan kembalikan stok
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
        }
        return response()->json(['status' => 'success']);
    }
}