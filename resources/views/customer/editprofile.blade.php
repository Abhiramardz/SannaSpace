<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="max-w-5xl mx-auto min-h-screen bg-white flex flex-col shadow-xl">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100 px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="/profile" class="p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-black text-gray-800">Edit Profil</h1>
        </div>
    </header>

    <main class="flex-1 px-4 py-8 pb-32">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-6 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Profile Picture (View Only) -->
            <div class="flex flex-col items-center mb-8">
                <div class="relative">
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center text-red-500 mb-2 border-4 border-white shadow-sm overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-xs text-gray-400 font-medium">Dikelola melalui Google</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" 
                        placeholder="Nama Anda" required>
                    @error('name') <p class="text-red-500 text-[10px] mt-1 px-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" 
                        placeholder="email@example.com" required>
                    @error('email') <p class="text-red-500 text-[10px] mt-1 px-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-1">Keamanan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" 
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" 
                                placeholder="Min. 8 karakter">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition" 
                                placeholder="Ulangi password">
                        </div>
                    </div>
                    @error('password') <p class="text-red-500 text-[10px] mt-1 px-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="w-full bg-red-500 text-white font-black py-5 rounded-2xl shadow-xl shadow-red-200 hover:bg-red-600 transition active:scale-[0.98] mt-10">
                Simpan Perubahan
            </button>
        </form>
    </main>

    <!-- Bottom Navigation -->
    @include('layout.bottomnav')
</div>

</body>
</html>
