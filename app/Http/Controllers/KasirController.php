<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        // Ambil pesanan yang belum diselesaikan atau dibatalkan
        $orders = Order::with('user', 'items.product')
                       ->whereNotIn('status', ['completed', 'cancelled'])
                       ->orderBy('created_at', 'asc')
                       ->get();

        return view('kasir.pesananaktif.dashboardkasir', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('user', 'items.product')->findOrFail($id);
        
        // Cek jika order sudah selesai/dibatalkan, mungkin ingin dicegah kasir melihatnya,
        // tapi untuk detail bisa saja tetap dilihat. Sementara kita biarkan bisa diakses.
        
        return view('kasir.pesananaktif.detail', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,siap_saji,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
