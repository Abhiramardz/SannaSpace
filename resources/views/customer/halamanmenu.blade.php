<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .category-active { color: #16a34a; border-left: 3px solid #16a34a; font-weight: 600; }
        .category-item { border-left: 3px solid transparent; }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-5xl mx-auto min-h-screen bg-white flex flex-col shadow-xl">
    
    <!-- ===== TOP HEADER ===== -->
    <div class="sticky top-0 z-30 bg-white px-4 pt-4 pb-2 border-b border-gray-100 shadow-sm">

        <!-- Toggle Take Away / Delivery -->
        <div class="flex bg-gray-100 rounded-full p-1 mb-3">
            <button id="btn-takeaway"
                class="flex-1 flex items-center justify-center gap-2 py-1.5 rounded-full text-xs font-semibold transition-all bg-red-500 text-white shadow">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Dine In
            </button>
            <button id="btn-delivery"
                class="flex-1 flex items-center justify-center gap-2 py-1.5 rounded-full text-xs font-semibold transition-all text-gray-500 hover:text-gray-700">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Take Away
            </button>
        </div>


        <!-- Search Bar & Filter Button -->
        <div class="flex items-center gap-2 mb-1">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="search-input" type="text" placeholder="Search menu..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-100 rounded-xl text-sm outline-none focus:ring-2 focus:ring-green-400 transition">
            </div>
            <button id="filter-btn" class="p-2.5 bg-gray-100 rounded-xl text-gray-500 hover:bg-gray-200 transition relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <div id="filter-badge" class="hidden absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></div>
            </button>
        </div>

        <!-- Filter Drawer (Hidden by default) -->
        <div id="filter-drawer" class="hidden mt-3 p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-4 animate-in slide-in-from-top duration-300">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kategori</h3>
                <div class="flex flex-wrap gap-2">
                    <button data-filter-cat="all" class="cat-filter-btn px-4 py-2 rounded-full text-xs font-semibold bg-red-500 text-white shadow-sm transition">Semua</button>
                    @foreach($categories as $category)
                        <button data-filter-cat="{{ $category->name }}" class="cat-filter-btn px-4 py-2 rounded-full text-xs font-semibold bg-white text-gray-500 border border-gray-200 hover:border-red-200 transition">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>
            
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Range Harga</h3>
                <div class="flex items-center gap-3">
                    <div class="flex-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold">MIN</span>
                        <input type="number" id="min-price" placeholder="0" class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none focus:border-red-300 transition">
                    </div>
                    <div class="flex-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold">MAX</span>
                        <input type="number" id="max-price" placeholder="∞" class="w-full pl-10 pr-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none focus:border-red-300 transition">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button id="reset-filter" class="text-xs font-bold text-gray-400 hover:text-gray-600 px-4 py-2 transition">Reset</button>
            </div>
        </div>
    </div>

    <!-- ===== MAIN BODY: SIDEBAR + CONTENT ===== -->
    <div class="flex flex-1 overflow-hidden">

        <!-- KONTEN MENU -->
        <main class="flex-1 overflow-y-auto scrollbar-hide pb-32" id="menu-content">
            
            @if($productsByCategory->isEmpty())
            <div class="flex flex-col items-center justify-center h-64 text-gray-400 gap-3">
                <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="font-medium text-sm">Belum ada menu tersedia</p>
            </div>
            @else
                @foreach($productsByCategory as $categoryName => $items)
                <section id="cat-{{ Str::slug($categoryName) }}" class="px-4 pt-6 pb-2">
                    <h2 class="text-base font-bold text-gray-800 mb-4">{{ $categoryName }}</h2>

                    <div class="space-y-3">
                        @foreach($items as $product)
                        <div class="menu-card flex gap-3 bg-white border border-gray-100 rounded-2xl p-3 shadow-sm hover:shadow-md transition-all cursor-pointer @if($product->stock <= 0) opacity-75 grayscale-[0.5] @endif"
                             data-id="{{ $product->id }}"
                             data-price="{{ $product->price }}"
                             data-category="{{ $product->category }}"
                             data-stock="{{ $product->stock }}"
                             onclick="@if($product->stock > 0) window.location.href='{{ route('menu.show', $product->id) }}' @else event.preventDefault(); @endif">
                            <!-- Info -->
                            <div class="flex-1">
                                <h3 class="font-semibold text-sm text-gray-800 leading-snug">{{ $product->name }}</h3>
                                @if($product->description)
                                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $product->description }}</p>
                                @endif
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="font-bold text-sm text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @if($product->stock <= 5 && $product->stock > 0)
                                        <span class="text-[9px] font-bold text-orange-500 bg-orange-50 px-1.5 py-0.5 rounded">Sisa {{ $product->stock }}</span>
                                    @endif
                                </div>
                            </div>
                            <!-- Foto + Tombol + -->
                            <div class="relative w-24 h-24 flex-shrink-0">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-24 h-24 object-cover rounded-xl">
                                @else
                                    <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <!-- Tombol Tambah / Quantity Controls -->
                                <div id="qty-control-{{ $product->id }}" class="absolute -bottom-2 -right-2 flex items-center">
                                    @if($product->stock > 0)
                                        <div class="qty-display hidden flex items-center bg-white border border-red-500 rounded-full shadow-lg overflow-hidden">
                                            <button onclick="event.stopPropagation(); updateItemQty({{ $product->id }}, -1)"
                                                class="w-7 h-7 flex items-center justify-center text-red-500 hover:bg-red-50 transition font-bold text-lg">-</button>
                                            <span class="qty-count px-2 text-xs font-bold text-gray-800">0</span>
                                            <button onclick="event.stopPropagation(); updateItemQty({{ $product->id }}, 1)"
                                                class="w-7 h-7 flex items-center justify-center text-red-500 hover:bg-red-50 transition font-bold text-lg">+</button>
                                        </div>
                                        <button id="add-btn-{{ $product->id }}" onclick="event.stopPropagation(); updateItemQty({{ $product->id }}, 1)"
                                            class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition text-xl font-light leading-none">
                                            +
                                        </button>
                                    @else
                                        <div class="px-2 py-1 bg-gray-200 text-gray-500 rounded-lg text-[9px] font-black uppercase tracking-tighter shadow-sm">
                                            Habis
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endforeach
            @endif

        </main>
    </div>

    <!-- ===== BOTTOM BAR (Keranjang) ===== -->
    <div id="cart-bar" class="hidden fixed bottom-20 left-1/2 -translate-x-1/2 w-full max-w-5xl z-40 px-4">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_10px_25px_rgba(0,0,0,0.1)] px-5 py-4 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Harga</p>
                <p id="cart-total" class="text-lg font-bold text-gray-800">Rp 0</p>
            </div>
            <a href="/pesanan" class="flex items-center gap-2 bg-red-500 text-white font-bold text-sm px-4 py-2 rounded-xl hover:bg-red-600 transition shadow-md">
                Lihat Pesanan
                <span id="cart-count" class="bg-white text-red-500 text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </a>
        </div>
    </div>

    <!-- ===== BOTTOM NAVIGATION ===== -->
    @include('layout.bottomnav')

</div>

<script>
    // ===== CART LOGIC =====
    let totalPrice = 0;
    let totalCount = 0;

    function updateItemQty(id, change) {
        // Optimistic UI update
        const qtyControl = document.getElementById(`qty-control-${id}`);
        const addBtn = document.getElementById(`add-btn-${id}`);
        const qtyDisplay = qtyControl.querySelector('.qty-display');
        const qtyCount = qtyControl.querySelector('.qty-count');
        
        let currentQty = parseInt(qtyCount.textContent);
        let maxStock = parseInt(document.querySelector(`.menu-card[data-id="${id}"]`)?.dataset.stock || 0);
        let newQty = currentQty + change;

        if (newQty < 0) newQty = 0;
        if (newQty > maxStock) {
            alert('Maaf, stok tidak mencukupi!');
            return;
        }

        // AJAX request to sync with server
        let url = newQty === 0 ? '{{ route("cart.remove") }}' : (change > 0 && currentQty === 0 ? '{{ route("cart.add") }}' : '{{ route("cart.update") }}');
        let body = { id: id };
        if (url.includes('add')) body = { product_id: id };
        if (url.includes('update')) body = { id: id, quantity: newQty };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(body)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            // Update the item-specific UI
            if (newQty > 0) {
                if(addBtn) addBtn.classList.add('hidden');
                if(qtyDisplay) qtyDisplay.classList.remove('hidden');
                qtyCount.textContent = newQty;
            } else {
                if(addBtn) addBtn.classList.remove('hidden');
                if(qtyDisplay) qtyDisplay.classList.add('hidden');
                qtyCount.textContent = 0;
            }

            // Update Global Cart UI
            if (data.total_price !== undefined) {
                updateCartUI(data.total_price, data.total_count);
            }
        })
        .catch(err => {
            alert(err.message || 'Terjadi kesalahan');
            // Revert UI if needed (might need more logic here to sync perfectly, but simple revert for now)
            location.reload();
        });
    }

    function updateCartUI(total, count) {
        totalPrice = total;
        totalCount = count;

        if (totalCount > 0) {
            document.getElementById('cart-total').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
            document.getElementById('cart-count').textContent = totalCount;
            document.getElementById('cart-bar').classList.remove('hidden');
        } else {
            document.getElementById('cart-bar').classList.add('hidden');
        }
    }

    // Initialize cart from session
    document.addEventListener('DOMContentLoaded', function() {
        @php
            $cart = session()->get('cart', []);
            $total = 0;
            $count = 0;
            foreach($cart as $item) {
                if (isset($item['price']) && isset($item['quantity'])) {
                    $total += $item['price'] * $item['quantity'];
                    $count += $item['quantity'];
                }
            }
        @endphp
        
        const initialCart = @json($cart);
        Object.keys(initialCart).forEach(id => {
            const qtyControl = document.getElementById(`qty-control-${id}`);
            if (qtyControl) {
                const addBtn = document.getElementById(`add-btn-${id}`);
                const qtyDisplay = qtyControl.querySelector('.qty-display');
                const qtyCount = qtyControl.querySelector('.qty-count');
                const item = initialCart[id];

                addBtn.classList.add('hidden');
                qtyDisplay.classList.remove('hidden');
                qtyCount.textContent = item.quantity;
            }
        });

        updateCartUI({{ $total }}, {{ $count }});
    });

    function showToast(msg) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-gray-800 text-white text-xs font-medium px-4 py-2 rounded-full shadow-lg transition-opacity';
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 1500);
    }

    // ===== TOGGLE TAKE AWAY / DELIVERY =====
    document.getElementById('btn-takeaway').addEventListener('click', function () {
        this.classList.add('bg-red-500', 'text-white', 'shadow');
        this.classList.remove('text-gray-500');
        const delivery = document.getElementById('btn-delivery');
        delivery.classList.remove('bg-red-500', 'text-white', 'shadow');
        delivery.classList.add('text-gray-500');
    });

    document.getElementById('btn-delivery').addEventListener('click', function () {
        this.classList.add('bg-red-500', 'text-white', 'shadow');
        this.classList.remove('text-gray-500');
        const takeaway = document.getElementById('btn-takeaway');
        takeaway.classList.remove('bg-red-500', 'text-white', 'shadow');
        takeaway.classList.add('text-gray-500');
    });

    // ===== SCROLL TO CATEGORY =====
    function scrollToCategory(id) {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        // Update active state sidebar
        document.querySelectorAll('[data-cat]').forEach(btn => {
            btn.classList.remove('category-active', 'bg-white', 'text-green-700');
            btn.classList.add('text-gray-500');
        });
        const activeBtn = document.querySelector(`[data-cat="${id}"]`);
        if (activeBtn) {
            activeBtn.classList.add('category-active', 'bg-white', 'text-green-700');
            activeBtn.classList.remove('text-gray-500');
        }
    }

    // ===== ACTIVE CATEGORY ON SCROLL =====
    const menuContent = document.getElementById('menu-content');
    if (menuContent) {
        menuContent.addEventListener('scroll', function () {
            const sections = document.querySelectorAll('section[id^="cat-"]');
            let currentId = '';
            sections.forEach(section => {
                if (section.offsetTop - menuContent.scrollTop <= 60) {
                    currentId = section.id;
                }
            });
            if (currentId) {
                document.querySelectorAll('[data-cat]').forEach(btn => {
                    btn.classList.remove('category-active', 'bg-white', 'text-green-700');
                    btn.classList.add('text-gray-500');
                });
                const activeBtn = document.querySelector(`[data-cat="${currentId}"]`);
                if (activeBtn) {
                    activeBtn.classList.add('category-active', 'bg-white', 'text-green-700');
                    activeBtn.classList.remove('text-gray-500');
                }
            }
        });
    }

    // ===== ADVANCED FILTER LOGIC =====
    const searchInput = document.getElementById('search-input');
    const filterBtn = document.getElementById('filter-btn');
    const filterDrawer = document.getElementById('filter-drawer');
    const minPriceInput = document.getElementById('min-price');
    const maxPriceInput = document.getElementById('max-price');
    const catBtns = document.querySelectorAll('.cat-filter-btn');
    const resetBtn = document.getElementById('reset-filter');
    const filterBadge = document.getElementById('filter-badge');

    let activeCategory = 'all';

    function filterMenu() {
        const query = searchInput.value.toLowerCase();
        const minPrice = parseFloat(minPriceInput.value) || 0;
        const maxPrice = parseFloat(maxPriceInput.value) || Infinity;
        
        let hasActiveFilters = query !== '' || activeCategory !== 'all' || minPrice > 0 || maxPrice !== Infinity;
        filterBadge.classList.toggle('hidden', !hasActiveFilters);

        document.querySelectorAll('section[id^="cat-"]').forEach(section => {
            let hasVisibleItems = false;
            const cards = section.querySelectorAll('.menu-card');
            
            cards.forEach(card => {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const price = parseFloat(card.dataset.price);
                const category = card.dataset.category;

                const matchSearch = name.includes(query);
                const matchCategory = activeCategory === 'all' || category === activeCategory;
                const matchPrice = price >= minPrice && price <= maxPrice;

                if (matchSearch && matchCategory && matchPrice) {
                    card.style.display = '';
                    hasVisibleItems = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Hide/show the entire category section if no items are visible
            section.style.display = hasVisibleItems ? '' : 'none';
        });
    }

    // Toggle Filter Drawer
    filterBtn.addEventListener('click', () => {
        filterDrawer.classList.toggle('hidden');
        filterBtn.classList.toggle('bg-gray-200');
    });

    // Category Filter
    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            catBtns.forEach(b => {
                b.classList.remove('bg-red-500', 'text-white', 'shadow-sm');
                b.classList.add('bg-white', 'text-gray-500', 'border', 'border-gray-200');
            });
            btn.classList.add('bg-red-500', 'text-white', 'shadow-sm');
            btn.classList.remove('bg-white', 'text-gray-500', 'border', 'border-gray-200');
            
            activeCategory = btn.dataset.filterCat;
            filterMenu();
        });
    });

    // Price Filter
    minPriceInput.addEventListener('input', filterMenu);
    maxPriceInput.addEventListener('input', filterMenu);
    searchInput.addEventListener('input', filterMenu);

    // Reset Filter
    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        minPriceInput.value = '';
        maxPriceInput.value = '';
        activeCategory = 'all';
        catBtns.forEach(btn => {
            btn.classList.remove('bg-red-500', 'text-white', 'shadow-sm');
            btn.classList.add('bg-white', 'text-gray-500', 'border', 'border-gray-200');
            if (btn.dataset.filterCat === 'all') {
                btn.classList.add('bg-red-500', 'text-white', 'shadow-sm');
                btn.classList.remove('bg-white', 'text-gray-500', 'border', 'border-gray-200');
            }
        });
        filterMenu();
    });

    // Set active first category
    const firstCat = document.querySelector('[data-cat]');
    if (firstCat) {
        firstCat.classList.add('category-active', 'bg-white', 'text-green-700');
        firstCat.classList.remove('text-gray-500');
    }
</script>

</body>
</html>