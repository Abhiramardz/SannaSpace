<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Total Pesanan - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Detail Total Pesanan'])

        <main class="p-4 md:p-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                {{-- Detail Pesanan Container --}}
                <div class="xl:col-span-2">
                    
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('superadmin.dashboard') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-green-600 mb-6 transition gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"/>
                        </svg>
                        Kembali ke Dashboard
                    </a>

                    {{-- FILTER SECTION BARU --}}
                    <div class="bg-white rounded-3xl p-6 mb-8 shadow-sm border border-gray-100">
                        <form action="{{ route('superadmin.dashboard.orders') }}" method="GET" class="flex flex-col gap-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Preset Waktu</label>
                                    <select name="filter_preset" id="filter_preset" class="w-full px-4 py-3 bg-gray-50 border border-transparent focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 rounded-2xl text-xs font-bold text-gray-700 outline-none transition cursor-pointer">
                                        <option value="">-- Pilih Preset Waktu --</option>
                                        <option value="1_week" {{ ($preset ?? '') === '1_week' ? 'selected' : '' }}>1 Minggu Terakhir</option>
                                        <option value="1_month" {{ ($preset ?? '') === '1_month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Dari Tanggal</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}" class="w-full px-4 py-2.5 bg-gray-50 border border-transparent focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 rounded-2xl text-xs font-bold text-gray-700 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Sampai Tanggal</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" class="w-full px-4 py-2.5 bg-gray-50 border border-transparent focus:bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 rounded-2xl text-xs font-bold text-gray-700 outline-none transition">
                                </div>
                            </div>

                            {{-- GRID ACTION TOMBOL DI BAWAH INI --}}
                            <div class="flex justify-end gap-2 pt-2 border-t border-gray-50">
                                @if(request('start_date') || request('end_date') || request('filter_preset'))
                                    <a href="{{ route('superadmin.dashboard.orders') }}" class="px-6 py-3 bg-gray-100 text-gray-500 font-bold text-xs rounded-xl hover:bg-gray-200 transition flex items-center justify-center">
                                        Reset Filter
                                    </a>
                                @endif
                                
                                {{-- Ekspor PDF Baru --}}
                                <a href="{{ route('superadmin.dashboard.orders.pdf', request()->all()) }}" class="px-6 py-3 bg-red-500 text-white font-bold text-xs rounded-xl hover:bg-red-600 transition flex items-center justify-center gap-2 shadow-md shadow-red-100 active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l4 4v13a2 2 0 01-2 2z"/>
                                    </svg>
                                    Ekspor PDF
                                </a>

                                <button type="submit" class="px-8 py-3 bg-green-600 text-white font-bold text-xs rounded-xl hover:bg-green-700 transition shadow-md shadow-green-100 active:scale-95">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Konten Tabel Utama --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800">Daftar Pesanan Berhasil</h3>
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold uppercase rounded-full tracking-wider">
                                {{ $orders->total() }} Total
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap min-w-[600px]">
                                <thead>
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                        <th class="px-8 py-5">ID Pesanan</th>
                                        <th class="px-8 py-5">Info Pelanggan</th>
                                        <th class="px-8 py-5">Tanggal Pesan</th>
                                        <th class="px-8 py-5">Total Pembayaran</th>
                                        <th class="px-8 py-5 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($orders as $order)
                                    <tr class="hover:bg-gray-50/50 transition cursor-pointer" onclick="window.location.href='/superadmin/dashboard/orders/{{ $order->id }}'">
                                        <td class="px-8 py-5 text-sm font-bold text-gray-800">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-bold text-sm">
                                                    {{ substr($order->user ? $order->user->name : 'G', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-800">
                                                        {{ $order->user ? $order->user->name : 'Guest/Deleted User' }}
                                                    </p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ $order->user ? $order->user->email : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-sm text-gray-400 font-medium">
                                            {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                                        </td>
                                        <td class="px-8 py-5 text-sm font-black text-gray-800">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <span class="px-3 py-1 bg-green-50 text-green-600 border border-green-100 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-12 text-center text-sm font-bold text-gray-400 italic">
                                            Belum ada data pesanan yang diselesaikan pada rentang tanggal ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Fitur navigasi halaman (Pagination) jika data banyak --}}
                        @if($orders->hasPages())
                        <div class="px-8 py-5 border-t border-gray-50 bg-gray-50/50">
                            {{ $orders->links() }}
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Otomatis mengosongkan input tanggal manual jika user memilih preset drop-down agar tidak bertabrakan
        const presetSelect = document.getElementById('filter_preset');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if(presetSelect) {
            presetSelect.addEventListener('change', function() {
                if(this.value !== "") {
                    startDateInput.value = "";
                    endDateInput.value = "";
                }
            });
        }

        const clearManualDates = () => {
            if(presetSelect) presetSelect.value = "";
        };

        if(startDateInput) startDateInput.addEventListener('input', clearManualDates);
        if(endDateInput) endDateInput.addEventListener('input', clearManualDates);

        // Sidebar Logic Mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');

        function toggleSidebar() {
            if(sidebar) sidebar.classList.toggle('-translate-x-full');
            if(sidebarOverlay) sidebarOverlay.classList.toggle('hidden');
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    });
</script>

</body>
</html>