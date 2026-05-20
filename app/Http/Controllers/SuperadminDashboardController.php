<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SuperadminDashboardController extends Controller
{
    public function index()
    {
        // 1. Total Penjualan Kotor (Semua pesanan yang selesai)
        $grossSales = Order::where('status', 'completed')->sum('total_amount');

        // 2. Total Penjualan Bersih (Profit)
        // Kita hitung dari order_items join products
        $netProfit = OrderItem::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->selectRaw('SUM((order_items.price - products.cost_price) * order_items.quantity) as profit')
        ->value('profit') ?? 0;

        // 3. Rekap Penjualan Bulanan (Tahun ini)
        $monthlyRecap = Order::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->all();

        // Siapkan data untuk chart atau list (12 bulan)
        $months = [];
        $salesValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthName = Carbon::create()->month($m)->translatedFormat('F');
            $months[] = $monthName;
            $salesValues[] = $monthlyRecap[$m] ?? 0;
        }

        // 4. Statistik Tambahan (opsional tapi bagus)
        $totalOrders = Order::where('status', 'completed')->count();
        $averageOrderValue = $totalOrders > 0 ? $grossSales / $totalOrders : 0;

        return view('superadmin.dashboard.dashboardsuperadmin', compact(
            'grossSales', 
            'netProfit', 
            'months', 
            'salesValues', 
            'totalOrders',
            'averageOrderValue'
        ));
    }

    public function orderDetail(Request $request)
    {
        $query = Order::with('user')->where('status', 'completed');

        // Tangkap data input dari filter
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $preset = $request->get('filter_preset');

        // Logika Pilihan Preset Waktu
        if ($preset) {
            $endDate = Carbon::now()->endOfDay()->format('Y-m-d');
            if ($preset === '1_week') {
                $startDate = Carbon::now()->subWeek()->startOfDay()->format('Y-m-d');
            } elseif ($preset === '1_month') {
                $startDate = Carbon::now()->subMonth()->startOfDay()->format('Y-m-d');
            }
        }

        // Jalankan query jika tanggal start dan end tersedia
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Urutkan data terbaru dan beri pagination 10 data
        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('superadmin.dashboard.orderdetail', compact('orders', 'startDate', 'endDate', 'preset'));
    }

    // MELIHAT DETAIL ITEM PESANAN
    public function showOrderItems($id)
    {
        // Mengambil data pesanan beserta user, item transaksi, dan data menu/produk terkait
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('superadmin.dashboard.ordershow', compact('order'));
    }

    // Laporan PDF
    public function exportPDF(Request $request)
    {
        $query = Order::with('user')->where('status', 'completed');

        // Tangkap data input dari filter yang sama
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $preset = $request->get('filter_preset');

        // Logika Pilihan Preset Waktu
        if ($preset) {
            $endDate = Carbon::now()->endOfDay()->format('Y-m-d');
            if ($preset === '1_week') {
                $startDate = Carbon::now()->subWeek()->startOfDay()->format('Y-m-d');
            } elseif ($preset === '1_month') {
                $startDate = Carbon::now()->subMonth()->startOfDay()->format('Y-m-d');
            }
        }

        // Jalankan query jika tanggal tersedia
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Ambil semua data tanpa pagination untuk laporan PDF
        $orders = $query->latest()->get();
        $totalAmount = $orders->sum('total_amount');

        // Render file view khusus cetak laporan kustom
        $pdf = Pdf::loadView('superadmin.dashboard.orderpdf', compact('orders', 'startDate', 'endDate', 'totalAmount'))
                  ->setPaper('a4', 'portrait');

        // Download otomatis file PDF dengan nama dinamis sesuai tanggal
        $filename = 'Laporan_Penjualan_Sanna_Space_' . ($startDate ?? date('Y-m-d')) . '_to_' . ($endDate ?? date('Y-m-d')) . '.pdf';
        return $pdf->download($filename);
    }
}