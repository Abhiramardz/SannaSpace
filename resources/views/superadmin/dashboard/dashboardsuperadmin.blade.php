<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Dashboard Utama'])

        <main class="p-4 md:p-8">
            {{-- Statistik Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Gross Sales -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penjualan Kotor</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">Rp {{ number_format($grossSales, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <!-- Net Profit -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penjualan Bersih</p>
                        <h3 class="text-2xl font-black text-blue-600 mt-1">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <!-- Total Orders -->
                <a href="{{ route('superadmin.dashboard.orders') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group block hover:border-orange-300 transition duration-300 cursor-pointer">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Pesanan</p>
                            <span class="text-[9px] bg-orange-50 text-orange-600 font-bold px-2 py-0.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">Lihat Detail →</span>
                        </div>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">{{ number_format($totalOrders, 0, ',', '.') }}</h3>
                    </div>
                </a>

                <!-- Average Order Value -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                    <div class="relative">
                        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Rata-rata Pesanan</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            {{-- Grafik Penjualan --}}
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 mb-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-black text-gray-800 tracking-tight">Rekap Penjualan Bulanan</h3>
                        <p class="text-sm text-gray-400 mt-1">Data penjualan tahun {{ date('Y') }}</p>
                    </div>
                    <div class="px-4 py-2 bg-gray-50 rounded-xl text-xs font-bold text-gray-500">
                        Total Penjualan: Rp {{ number_format($grossSales, 0, ',', '.') }}
                    </div>
                </div>
                
                <div class="h-[400px] w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-green-600 rounded-[40px] p-10 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-2xl font-black mb-4">Butuh Rekap Lengkap?</h4>
                        <p class="text-green-100 mb-8 opacity-80">Anda dapat melihat rincian modal dan harga jual di Manajemen Menu untuk memantau margin keuntungan setiap item secara spesifik.</p>
                        <a href="/superadmin/menu" class="inline-flex items-center px-8 py-4 bg-white text-green-600 rounded-2xl font-bold hover:bg-green-50 transition shadow-lg shadow-green-900/20">
                            Ke Manajemen Menu
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                    <svg class="absolute -right-20 -bottom-20 w-80 h-80 text-green-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="bg-white rounded-[40px] p-10 border border-gray-100 flex flex-col justify-center">
                    <h4 class="text-xl font-black text-gray-800 mb-2">Ringkasan Margin</h4>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">Keuntungan bersih dihitung berdasarkan selisih harga jual dan harga modal yang Anda masukkan pada saat menambahkan menu baru.</p>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <span class="text-sm font-bold text-gray-500">Total Profitability</span>
                            <span class="text-sm font-black text-green-600">{{ $grossSales > 0 ? number_format(($netProfit / $grossSales) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <span class="text-sm font-bold text-gray-500">Margin Per Pesanan</span>
                            <span class="text-sm font-black text-blue-600">Rp {{ $totalOrders > 0 ? number_format($netProfit / $totalOrders, 0, ',', '.') : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(22, 163, 74, 0.2)');
        gradient.addColorStop(1, 'rgba(22, 163, 74, 0)');

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Penjualan Kotor (Rp)',
                    data: @json($salesValues),
                    borderColor: '#16a34a',
                    borderWidth: 4,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#16a34a',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: { size: 11, weight: '600' },
                            color: '#9ca3af',
                            callback: function(value) {
                                return 'Rp ' + (value / 1000) + 'k';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 11, weight: '600' },
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });

        // Mobile Sidebar Logic
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
