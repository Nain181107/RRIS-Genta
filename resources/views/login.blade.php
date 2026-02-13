<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Akun</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoRRIS.png') }}">
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-linear-to-br from-blue-50 to-gray-100 p-4">

    {{-- Login Container --}}
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header Section --}}
        <div class="bg-linear-to-r from-(--blue) to-blue-800 px-6 py-8 sm:px-8 sm:py-10">
            <div class="text-center">
                <div
                    class="mx-auto w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-8 h-8 sm:w-10 sm:h-10 text-(--blue)">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
                    Selamat Datang
                </h1>
                <p class="text-sm sm:text-base text-blue-100">
                    Silakan login untuk melanjutkan
                </p>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="px-6 py-8 sm:px-8 sm:py-10">

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 mr-2 mt-0.5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <ul class="text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 mr-2 mt-0.5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5" autocomplete="off">
                @csrf

                {{-- ID Karyawan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ID Karyawan
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                        </div>
                        <input type="text" name="id_karyawan" placeholder="Masukkan ID Karyawan" autocomplete="off"
                            class="w-full h-11 sm:h-12 pl-10 pr-4 border-2 border-gray-300 rounded-xl text-sm sm:text-base
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" placeholder="Masukkan password"
                            autocomplete="new-password"
                            class="w-full h-11 sm:h-12 pl-10 pr-12 border-2 border-gray-300 rounded-xl text-sm sm:text-base
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">

                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 hidden"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Login Button --}}
                <button type="submit"
                    class="w-full h-11 sm:h-12 bg-linear-to-r from-(--blue) to-blue-800 text-white rounded-xl text-sm sm:text-base font-semibold
                           hover:from-blue-800 hover:to-(--blue) active:scale-[0.98] transition-all duration-200 shadow-lg hover:shadow-xl">
                    Login
                </button>
            </form>

            {{-- Register Link --}}
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    Belum punya akun?
                    <a href="/registrasi"
                        class="text-blue-600 font-semibold hover:text-blue-700 hover:underline transition-colors">
                        Registrasi Sekarang
                    </a>
                </p>
            </div>

        </div>

    </div>
</body>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (password.type === "password") {
            password.type = "text";
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            password.type = "password";
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // Auto-focus on first input
    document.addEventListener('DOMContentLoaded', function() {
        const firstInput = document.querySelector('input[name="id_karyawan"]');
        if (firstInput) {
            firstInput.focus();
        }
    });
</script>

</html>
