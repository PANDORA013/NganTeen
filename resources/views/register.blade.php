<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Nganteen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen text-gray-800">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Nganteen Logo" class="mx-auto w-48 mb-4">
            <h2 class="text-2xl font-bold text-green-600">Daftar Akun</h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('actionregister') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                       required 
                       autocomplete="name">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Email</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                       required 
                       autocomplete="email">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Password</label>
                <input type="password" 
                       name="password" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror" 
                       required 
                       autocomplete="new-password">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Konfirmasi Password</label>
                <input type="password" 
                       name="password_confirmation" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                       required 
                       autocomplete="new-password">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Daftar Sebagai</label>
                <select name="role" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('role') border-red-500 @enderror" 
                        required>
                    <option value="">Pilih Role</option>
                    <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>Penjual</option>
                    <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                </select>
            </div>

            <button type="submit" 
                    class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Daftar
            </button>
        </form>

        <p class="text-center mt-6 text-sm">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-medium">Login di sini</a>
        </p>
    </div>
</body>
</html>
