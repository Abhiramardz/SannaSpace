<?php

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\MenuController;

Route::middleware(['role:customer'])->group(function () {
    Route::get('/', [MenuController::class, 'index']);
    Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/daftar', function () {
    return view('daftar');
});

Route::middleware(['role:customer'])->group(function () {
    Route::get('/profile', function () {
        $orders = Auth::check() ? Auth::user()->orders()->latest()->get() : collect();
        return view('customer.profile', compact('orders'));
    })->name('profile');
});

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
        
        // Find user by google_id first, then by email
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(16)), // Use random password
                'role' => 'customer'
            ]);
        } else {
            // Update google_id and avatar if they exist but weren't set
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/profile')->with('success', 'Berhasil masuk dengan Google!');
        
    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
    }
});

// SuperAdmin
use App\Http\Controllers\SuperadminUserController;
use App\Http\Controllers\SuperadminDashboardController;

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // Dashboard
    Route::get('/dashboardsuperadmin', [SuperadminDashboardController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/dashboard/orders', [SuperadminDashboardController::class, 'orderDetail'])->name('superadmin.dashboard.orders');

    Route::get('/superadmin/dashboard/orders/export-pdf', [SuperadminDashboardController::class, 'exportPDF'])->name('superadmin.dashboard.orders.pdf');
    Route::get('/superadmin/dashboard/orders/{id}', [SuperadminDashboardController::class, 'showOrderItems'])->name('superadmin.dashboard.orders.show');
    
    // Manajemen User
    Route::get('/superadmin/user', [SuperadminUserController::class, 'index'])->name('superadmin.user.index');
    Route::get('/superadmin/tambahuser', [SuperadminUserController::class, 'tambahuser'])->name('superadmin.user.create');
    Route::post('/superadmin/user', [SuperadminUserController::class, 'store'])->name('superadmin.user.store');
    Route::get('/superadmin/user/{id}/edit', [SuperadminUserController::class, 'edit'])->name('superadmin.user.edit');
    Route::put('/superadmin/user/{id}', [SuperadminUserController::class, 'update'])->name('superadmin.user.update'); // Diubah ke PUT agar aman
    Route::delete('/superadmin/user/{id}', [SuperadminUserController::class, 'destroy'])->name('superadmin.user.destroy');

    // Manajemen Menu
    Route::get('/superadmin/menu', [\App\Http\Controllers\AdminMenuController::class, 'index'])->name('superadmin.menu.index');
    Route::get('/superadmin/tambahmenu', [\App\Http\Controllers\AdminMenuController::class, 'tambahmenu'])->name('superadmin.menu.create');
    Route::post('/superadmin/menu', [\App\Http\Controllers\AdminMenuController::class, 'store'])->name('superadmin.menu.store');
    Route::get('/superadmin/menu/{id}/edit', [\App\Http\Controllers\AdminMenuController::class, 'edit'])->name('superadmin.menu.edit');
    Route::put('/superadmin/menu/{id}', [\App\Http\Controllers\AdminMenuController::class, 'update'])->name('superadmin.menu.update');
    Route::delete('/superadmin/menu/{id}', [\App\Http\Controllers\AdminMenuController::class, 'destroy'])->name('superadmin.menu.destroy');
    Route::patch('/superadmin/menu/{id}/stock', [\App\Http\Controllers\AdminMenuController::class, 'updateStock'])->name('superadmin.menu.stock');

    // Manajemen Kategori
    Route::get('/superadmin/kategori', [\App\Http\Controllers\CategoryController::class, 'index'])->name('superadmin.kategori.index');
    Route::post('/superadmin/kategori', [\App\Http\Controllers\CategoryController::class, 'store'])->name('superadmin.kategori.store');
    Route::delete('/superadmin/kategori/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('superadmin.kategori.destroy');

    // Manajemen Stok
    Route::get('/superadmin/stok', [\App\Http\Controllers\BahanBakuController::class, 'index'])->name('superadmin.stok.index');
    Route::get('/superadmin/tambahstok', [\App\Http\Controllers\BahanBakuController::class, 'create'])->name('superadmin.stok.create');
    Route::post('/superadmin/stok', [\App\Http\Controllers\BahanBakuController::class, 'store'])->name('superadmin.stok.store');
    Route::get('/superadmin/stok/{bahanBaku}/edit', [\App\Http\Controllers\BahanBakuController::class, 'edit'])->name('superadmin.stok.edit');
    Route::put('/superadmin/stok/{bahanBaku}', [\App\Http\Controllers\BahanBakuController::class, 'update'])->name('superadmin.stok.update');
    Route::delete('/superadmin/stok/{bahanBaku}', [\App\Http\Controllers\BahanBakuController::class, 'destroy'])->name('superadmin.stok.destroy');
    Route::patch('/superadmin/stok/{bahanBaku}/stock', [\App\Http\Controllers\BahanBakuController::class, 'updateStock'])->name('superadmin.stok.stock');
});

// Customer
use App\Http\Controllers\CartController;

Route::post('/midtrans/callback', [CartController::class, 'callback'])->name('midtrans.callback');

Route::middleware(['role:customer'])->group(function () {
    Route::get('/pesanan', [CartController::class, 'index'])->name('pesanan.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Pembayaran
    Route::get('/checkout', [CartController::class, 'checkoutPage'])->name('checkout.page');
    Route::post('/checkout/proses', [CartController::class, 'prosesCheckout'])->name('checkout.proses');

});

use App\Http\Controllers\ProfileController;
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/pesanan/{id}', [ProfileController::class, 'showOrder'])->name('customer.order.show');
});

// Kasir
use App\Http\Controllers\KasirController;

Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/pesanan', [KasirController::class, 'index'])->name('kasir.dashboard');

    // Kasir View & Update Order
    Route::get('/kasir/pesanan/{id}', [KasirController::class, 'show'])->name('kasir.order.show');
    Route::patch('/kasir/pesanan/{id}/status', [KasirController::class, 'updateStatus'])->name('kasir.order.status');

    //Stok Kasir
    Route::get('/kasir/stok', [\App\Http\Controllers\KasirStokController::class, 'index'])->name('kasir.stok.index');
    Route::patch('/kasir/stok/{bahanBaku}/update', [\App\Http\Controllers\KasirStokController::class, 'updateStock'])->name('kasir.stok.update');
});
