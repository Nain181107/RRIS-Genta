<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi Akun</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoRRIS.png') }}">
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-linear-to-br from-blue-50 to-gray-100 p-4">

    {{-- Register Container --}}
    <div
        class="w-full max-w-md lg:max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[95vh] overflow-y-auto">

        {{-- Header Section --}}
        <div class="bg-linear-to-r from-(--blue) to-blue-800 px-6 py-8 sm:px-8 sm:py-10 lg:py-12 sticky top-0 z-10">
            <div class="text-center">
                <div
                    class="mx-auto w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-white rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-(--blue)">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                    Registrasi Akun
                </h1>
                <p class="text-sm sm:text-base lg:text-lg text-blue-100">
                    Buat akun baru untuk memulai
                </p>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="px-6 py-8 sm:px-8 sm:py-10 lg:px-12 lg:py-12">

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

            {{-- Form Registrasi --}}
            <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5 lg:space-y-6" autocomplete="off">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-6">

                    {{-- ID Karyawan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            ID Karyawan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="id_karyawan" value="{{ old('id_karyawan') }}"
                            placeholder="Masukkan ID Karyawan"
                            class="w-full h-11 sm:h-12 px-4 border-2 border-gray-300 rounded-xl text-sm sm:text-base
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">
                        @error('id_karyawan')
                            <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-4 h-4">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            placeholder="Masukkan nama lengkap"
                            class="w-full h-11 sm:h-12 px-4 border-2 border-gray-300 rounded-xl text-sm sm:text-base
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">
                        @error('nama_lengkap')
                            <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-4 h-4">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jabatan"
                            class="w-full h-11 sm:h-12 px-4 border-2 border-gray-300 rounded-xl text-sm sm:text-base bg-white
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">
                            <option value="">Pilih Jabatan</option>
                            <option value="Operator" {{ old('jabatan') == 'Operator' ? 'selected' : '' }}>Operator
                            </option>
                            <option value="Admin" {{ old('jabatan') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Operator Gudang" {{ old('jabatan') == 'Operator Gudang' ? 'selected' : '' }}>
                                Operator Gudang</option>
                            <option value="Manager" {{ old('jabatan') == 'Manager' ? 'selected' : '' }}>Manager
                            </option>
                        </select>
                        @error('jabatan')
                            <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-4 h-4">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tim --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tim <span class="text-red-500">*</span>
                        </label>
                        <select name="tim"
                            class="w-full h-11 sm:h-12 px-4 border-2 border-gray-300 rounded-xl text-sm sm:text-base bg-white uppercase tracking-wide
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">
                            <option value="">Pilih Tim</option>
                            <option value="A" {{ old('tim') == 'A' ? 'selected' : '' }}>Tim A</option>
                            <option value="B" {{ old('tim') == 'B' ? 'selected' : '' }}>Tim B</option>
                            <option value="C" {{ old('tim') == 'C' ? 'selected' : '' }}>Tim C</option>
                            <option value="D" {{ old('tim') == 'D' ? 'selected' : '' }}>Tim D</option>
                            <option value="E" {{ old('tim') == 'E' ? 'selected' : '' }}>Tim E</option>
                            <option value="F" {{ old('tim') == 'F' ? 'selected' : '' }}>Tim F</option>
                        </select>
                        @error('tim')
                            <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-4 h-4">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir 
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                            class="w-full h-11 sm:h-12 px-4 border-2 border-gray-300 rounded-xl text-sm sm:text-base
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">
                        @error('tgl_lahir')
                            <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-4 h-4">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    --}}

                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        No. Handphone
                    </label>
                    <input type="tel" name="nohp" value="{{ old('nohp') }}" placeholder="08123456789"
                        class="w-full h-11 sm:h-12 px-4 border-2 border-gray-300 rounded-xl text-sm sm:text-base
                                   focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-(--blue) transition-all duration-200
                                   hover:border-gray-400">
                    @error('nohp')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Masukkan password"
                            class="w-full h-11 sm:h-12 px-4 pr-12 border-2 border-gray-300 rounded-xl text-sm sm:text-base
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

                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 sm:w-6 sm:h-6 hidden" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Upload Foto --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload Foto Profil
                    </label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-xl hover:border-(--blue) transition-colors">
                        <input type="file" name="foto" accept="image/*"
                            class="w-full text-xs sm:text-sm text-gray-600 file:mr-3 file:py-2 sm:file:py-2.5 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 file:cursor-pointer cursor-pointer">
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">Format: JPG, PNG. Max: 2MB</p>
                    @error('foto')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Register Button --}}
                <button type="submit"
                    class="w-full h-11 sm:h-12 lg:h-13 bg-linear-to-r from-(--blue) to-blue-800 text-white rounded-xl text-sm sm:text-base lg:text-lg font-semibold
                           hover:from-blue-800 hover:to-(--blue) active:scale-[0.98] transition-all duration-200 shadow-lg hover:shadow-xl mt-2">
                    Registrasi
                </button>
            </form>

            {{-- Login Link --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="/"
                        class="text-(--blue) font-semibold hover:text-blue-700 hover:underline transition-colors">
                        Login Sekarang
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
