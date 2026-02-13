<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Akun</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6">

        <h1 class="text-2xl font-semibold text-center text-gray-700 mb-6">
            Login Akun
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4" autocomplete="off">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Id Karyawan
                </label>
                <input type="text" name="id_karyawan" placeholder="Abcdef" autocomplete="off"
                    class="w-full h-10 px-3 border border-gray-300 rounded-md text-sm
                           focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Password
                </label>

                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="••••••••"
                        autocomplete="new-password"
                        class="w-full h-10 px-3 pr-10 border border-gray-300 rounded-md text-sm
                   focus:outline-none focus:ring-1 focus:ring-blue-500">

                    <button type="button" onclick="togglePassword()"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 hover:cursor-pointer">

                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 hidden" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.828-4.828M9.88 9.88A3 3 0 0115 12c0 .366-.065.718-.184 1.043M6.72 6.72C4.577 8.186 3.034 10.513 2.458 12c1.274 4.057 5.064 7 9.542 7 1.188 0 2.33-.207 3.386-.587M9.88 9.88L6.72 6.72" />
                        </svg>
                    </button>
                </div>
            </div>


            <button type="submit"
                class="w-full h-10 bg-(--blue) text-white rounded-md text-sm font-semibold
                       hover:opacity-80 transition">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            Belum punya ID User? <a href="/registrasi" class="text-blue-500">Registrasi</a>
        </p>

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
</script>

</html>
