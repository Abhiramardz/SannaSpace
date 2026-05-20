<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sanna Space</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">

        <!-- Title -->
        <h2 class="text-center text-2xl font-bold text-gray-700 mb-6">
            Login
        </h2>

        <!-- Form -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Email</label>
                <input 
                    type="email" 
                    name="email"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Masukkan email"
                    required
                >
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Password</label>
                <input 
                    type="password" 
                    name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <!-- Button -->
            <button 
                type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition"
            >
                Login
            </button>

            <!-- Footer -->
            <p class="text-center text-sm text-gray-500 mt-4">
                Belum punya akun? <a href="daftar" class="text-green-600">Daftar</a>
            </p>

            <div class="mt-8 flex items-center gap-3">
                <div class="flex-1 h-[1px] bg-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">Atau masuk dengan</span>
                <div class="flex-1 h-[1px] bg-gray-200"></div>
            </div>

            <a href="/auth/google" class="mt-6 flex items-center justify-center gap-3 w-full py-3 border-2 border-gray-100 rounded-2xl hover:bg-gray-50 transition-all active:scale-95 group">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-5 h-5 group-hover:scale-110 transition" alt="Google">
                <span class="text-sm font-bold text-gray-600">Masuk dengan Google</span>
            </a>

        </form>

    </div>

</body>
</html>