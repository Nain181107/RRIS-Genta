<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi Akun</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg
           max-h-[90vh] overflow-y-auto p-6">

        <h1 class="text-2xl font-semibold text-center text-gray-700 mb-6">
            Regitrasi Akun
        </h1>

        {{-- Tampilkan semua error di atas form --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Registrasi --}}
        <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4"
            autocomplete="off">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Id Karyawan</label>
                <input type="text" name="id_karyawan" value="{{ old('id_karyawan') }}" placeholder="Abcdef"
                    class="w-full h-10 px-3 border border-gray-300 rounded-md text-sm
                   focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('id_karyawan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Abcdef"
                    class="w-full h-10 px-3 border border-gray-300 rounded-md text-sm
                   focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('nama_lengkap')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Jabatan</label>
                <select name="jabatan"
                    class="w-full h-10 px-3 border border-gray-300 rounded-md text-sm bg-white
               focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Operator" {{ old('jabatan') == 'Operator' ? 'selected' : '' }}>Operator</option>
                    <option value="Admin" {{ old('jabatan') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Operator Gudang" {{ old('jabatan') == 'Operator Gudang' ? 'selected' : '' }}>Operator
                        Gudang</option>
                    <option value="Manager" {{ old('jabatan') == 'Manager' ? 'selected' : '' }}>Manager</option>
                </select>
                @error('jabatan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tim --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Tim</label>
                <input type="text" name="tim" value="{{ old('tim') }}" placeholder="ABCDEF"
                    class="w-full h-10 px-3 border border-gray-300 rounded-md text-sm uppercase tracking-wide
                   focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('tim')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                    class="w-full h-10 px-3 border border-gray-300 rounded-md text-sm
                   focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('tgl_lahir')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- No HP --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">No. Handphone</label>
                <input type="number" name="nohp" value="{{ old('nohp') }}" placeholder="0821******"
                    class="w-full h-10 px-3 border border-gray-300 rounded-md text-sm
                   focus:outline-none focus:ring-1 focus:ring-blue-500">
                @error('nohp')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="relative">
                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="••••••••"
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

                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            {{-- Upload Foto --}}
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Upload Foto</label>
                <div class="border border-gray-300 rounded-md px-2 py-1">
                    <input type="file" name="foto" accept="image/*"
                        class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50
                       file:text-blue-600 hover:file:bg-blue-100">
                </div>
                @error('foto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full h-10 bg-(--blue) text-white rounded-md text-sm font-semibold
               hover:opacity-80 transition">
                Registrasi
            </button>
        </form>


        <p class="text-center text-sm text-gray-500 mt-4">
            Sudah punya ID User? <a href="/" class="text-blue-500">Login</a>
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
