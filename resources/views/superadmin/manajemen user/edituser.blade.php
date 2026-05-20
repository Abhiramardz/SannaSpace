<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex min-h-screen">
    @include('layout.superadmin.sidebarsuperadmin')

    <div class="flex-1 flex flex-col">
        @include('layout.superadmin.headersuperadmin', ['title' => 'Edit User'])

        <main class="p-4 md:p-8">
            <div class="max-w-2xl mx-auto">
                <a href="/superadmin/user" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-green-600 mb-6 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar User
                </a>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl font-black">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-800">Edit Pengguna</h2>
                            <p class="text-sm text-gray-400 font-medium">Memperbarui informasi untuk <span class="text-green-600 font-bold">{{ $user->name }}</span></p>
                        </div>
                    </div>

                    <form action="/superadmin/user/{{ $user->id }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-bold text-red-800">Terdapat {{ $errors->count() }} kesalahan:</h3>
                                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Role / Hak Akses</label>
                            <select name="role" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm font-bold appearance-none cursor-pointer" required>
                                <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                <option value="kasir" {{ $user->role === 'kasir' ? 'selected' : '' }}>Kasir</option>
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-gray-50">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition text-sm" placeholder="Isi hanya jika ingin mengganti password">
                        </div>

                        <button type="submit" class="w-full bg-green-600 text-white py-5 rounded-2xl font-black hover:bg-green-700 transition shadow-xl shadow-green-100 active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>