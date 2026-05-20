<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Manajemen User'])

        <main class="p-4 md:p-8">
            @if(session('success'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                {{-- Daftar User --}}
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800">Daftar Pengguna</h3>
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold uppercase rounded-full tracking-wider">{{ $users->count() }} Total
                            </span>
                            <a href="/superadmin/tambahuser" class="inline-flex items-center justify-center bg-green-600 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-green-700 transition shadow-md shadow-green-100 active:scale-95 gap-2 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah User
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap min-w-[600px]">
                                <thead>
                                    <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                        <th class="px-8 py-5">Info User</th>
                                        <th class="px-8 py-5">Role</th>
                                        <th class="px-8 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center font-bold text-sm">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5">
                                            @php
                                                $roleClasses = [
                                                    'superadmin' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                    'kasir' => 'bg-green-50 text-green-600 border-green-100',
                                                    'customer' => 'bg-orange-50 text-orange-600 border-orange-100'
                                                ];
                                                $class = $roleClasses[$user->role] ?? 'bg-gray-50 text-gray-600';
                                            @endphp
                                            <span class="px-3 py-1 {{ $class }} rounded-full text-[10px] font-bold uppercase tracking-wider border">{{ $user->role }}</span>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex justify-end gap-2">
                                                @if($user->role !== 'customer')
                                                    <a href="/superadmin/user/{{ $user->id }}/edit" class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="/superadmin/user/{{ $user->id }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-[10px] text-gray-300 font-bold uppercase italic px-2">View Only</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');

        function toggleSidebar() {
            if(sidebar) sidebar.classList.toggle('-translate-x-full');
            if(sidebarOverlay) sidebarOverlay.classList.toggle('hidden');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
        }
        
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', toggleSidebar);
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
    });
</script>

</body>
</html>
