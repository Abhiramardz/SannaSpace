<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
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

    // 1. Menampilkan Halaman Pilihan Pembayaran
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

        // Mengarah ke folder aktual sesuai image_3f83f5.png
        return view('customer.pembayaran.detailpembayaran', compact('cart', 'total'));
    }

    // 2. Memproses Transaksi Final (Membuat Order & Potong Stok)
    public function prosesCheckout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'payment_method' => 'required|in:Cash,QRIS'
        ], [
            'payment_method.required' => 'Silakan pilih metode pembayaran.'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/pesanan')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi ketersediaan stok produk
        foreach ($cart as $productId => $details) {
            $product = \App\Models\Product::find($productId);
            if (!$product || $product->stock < $details['quantity']) {
                return redirect('/pesanan')->with('error', 'Maaf, stok ' . ($product->name ?? 'produk') . ' tidak mencukupi.');
            }
        }

        return \DB::transaction(function() use ($cart, $request) {
            $total = array_reduce($cart, function($carry, $item) {
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 0;
                return $carry + ($price * $quantity);
            }, 0);

            // Membuat data Order di Database
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            // Membuat Order Items dan Mengurangi Stok Produk
            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                $product = \App\Models\Product::find($productId);
                $product->decrement('stock', $details['quantity']);
            }

            // Kosongkan keranjang session
            session()->forget('cart');

            return redirect()->route('profile')->with('success', 'Pesanan berhasil dibuat menggunakan metode ' . $request->payment_method . '!');
        });
    }
}
