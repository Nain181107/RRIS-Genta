<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profil Karyawan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoRRIS.png') }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="h-14 md:h-16 flex items-center gap-3 px-3 md:px-6">
            <a href="{{ route('dashboard') }}" class="p-2 rounded-full hover:bg-gray-100 active:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>

            <h3 class="text-lg md:text-xl font-semibold text-gray-800">
                Profil Karyawan
            </h3>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto pb-6">

        {{-- Cover & Profile Picture Section --}}
        <div class="relative">

            <div
                class="relative bg-linear-to-r from-(--blue) to-blue-900 w-full h-32 sm:h-40 md:h-48 lg:h-56 shadow-md">
            </div>

            {{-- Profile Picture --}}
            <div class="absolute left-1/2 transform -translate-x-1/2 -bottom-12 sm:-bottom-16 md:-bottom-20">
                @if (session('foto'))
                    <div
                        class="w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white">
                        <img src="{{ asset('storage/' . session('foto')) }}" class="w-full h-full object-cover"
                            alt="Foto Profil">
                    </div>
                @else
                    <div
                        class="w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 rounded-full bg-gray-200 border-4 border-white shadow-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- Profile Info --}}
        <div class="flex flex-col items-center mt-14 sm:mt-20 md:mt-24 px-4">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 text-center">
                {{ session('nama_karyawan') }}
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-gray-500 mt-1">
                {{ ucfirst(session('jabatan')) }}
            </p>
        </div>

        {{-- Profile Details Card --}}
        <div class="flex justify-center mt-6 md:mt-8 px-4">
            <div class="w-full max-w-2xl space-y-4">

                {{-- Details Card --}}
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                    <div class="px-4 py-3 sm:px-6 sm:py-4 border-b border-gray-100">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Informasi Personal</h3>
                    </div>

                    <div class="divide-y divide-gray-100">

                        {{-- ID Karyawan --}}
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 py-3 sm:px-6 sm:py-4 gap-1 sm:gap-0">
                            <span class="text-sm sm:text-base text-gray-600 font-medium">ID Karyawan</span>
                            <span class="text-sm sm:text-base font-semibold text-gray-800">
                                {{ session('id_karyawan') }}
                            </span>
                        </div>

                        {{-- Tim --}}
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 py-3 sm:px-6 sm:py-4 gap-1 sm:gap-0">
                            <span class="text-sm sm:text-base text-gray-600 font-medium">Tim</span>
                            <span class="text-sm sm:text-base font-semibold text-gray-800">
                                Tim {{ session('tim') }}
                            </span>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 py-3 sm:px-6 sm:py-4 gap-1 sm:gap-0">
                            <span class="text-sm sm:text-base text-gray-600 font-medium">Tanggal Lahir</span>
                            <span class="text-sm sm:text-base font-semibold text-gray-800">
                                {{ session('tgl_lahir') ? \Carbon\Carbon::parse(session('tgl_lahir'))->format('d M Y') : '-' }}
                            </span>
                        </div>

                        {{-- No HP --}}
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 py-3 sm:px-6 sm:py-4 gap-1 sm:gap-0">
                            <span class="text-sm sm:text-base text-gray-600 font-medium">No HP</span>
                            <span class="text-sm sm:text-base font-semibold text-gray-800">
                                {{ session('nohp') ? rtrim(chunk_split(session('nohp'), 4, '-'), '-') : '-' }}
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Edit Button --}}
                <button onclick="openModal()"
                    class="w-full h-11 sm:h-12 flex items-center justify-center gap-2 bg-(--blue) text-white text-sm sm:text-base font-semibold rounded-xl shadow-md hover:opacity-90 active:opacity-80 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Edit Profil
                </button>

            </div>
        </div>

    </main>

    {{-- Modal Edit Profil --}}
    <div id="modalOverlay"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 transition-opacity duration-200 opacity-0 p-4">

        <div
            class="bg-white w-full max-w-lg rounded-2xl shadow-2xl relative transform transition-transform duration-300 scale-95 max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div
                class="sticky top-0 bg-white border-b border-gray-200 px-4 sm:px-6 py-4 rounded-t-2xl flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Edit Profil</h2>
                <button onclick="closeModal()" class="p-2 rounded-full hover:bg-gray-100 active:bg-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5 text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data"
                class="p-4 sm:p-6 space-y-4">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap"
                        value="{{ old('nama_lengkap', session('nama_karyawan')) }}"
                        class="w-full h-10 sm:h-11 px-3 sm:px-4 border border-gray-300 rounded-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Masukkan nama lengkap">
                    @error('nama_lengkap')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tim --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tim <span class="text-red-500">*</span>
                    </label>
                    <select name="tim"
                        class="w-full h-10 sm:h-11 px-3 sm:px-4 border border-gray-300 rounded-lg text-sm sm:text-base bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Pilih Tim</option>
                        @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $timOption)
                            <option value="{{ $timOption }}"
                                {{ old('tim', session('tim')) == $timOption ? 'selected' : '' }}>
                                Tim {{ $timOption }}
                            </option>
                        @endforeach
                    </select>
                    @error('tim')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal Lahir
                    </label>
                    <input type="date" name="tgl_lahir"
                        value="{{ old('tgl_lahir', session('tgl_lahir') ? \Carbon\Carbon::parse(session('tgl_lahir'))->format('Y-m-d') : '') }}"
                        class="w-full h-10 sm:h-11 px-3 sm:px-4 border border-gray-300 rounded-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('tgl_lahir')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        No HP
                    </label>
                    <input type="tel" name="nohp" value="{{ old('nohp', session('nohp')) }}"
                        class="w-full h-10 sm:h-11 px-3 sm:px-4 border border-gray-300 rounded-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="08123456789">
                    @error('nohp')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto Profil --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Foto Profil
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 transition">
                        <input type="file" name="foto" accept="image/*" id="fotoInput"
                            class="w-full text-xs sm:text-sm text-gray-600 file:mr-3 file:py-2 sm:file:py-2.5 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 file:cursor-pointer cursor-pointer">
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">Format: JPG, PNG. Max: 2MB</p>
                    @error('foto')
                        <p class="text-red-600 text-xs sm:text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()"
                        class="flex-1 h-10 sm:h-11 px-4 bg-gray-100 text-gray-700 text-sm sm:text-base font-semibold rounded-lg hover:bg-gray-200 active:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 h-10 sm:h-11 px-4 bg-(--blue) text-white text-sm sm:text-base font-semibold rounded-lg hover:opacity-90 active:opacity-80 transition shadow-md">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        const modalOverlay = document.getElementById('modalOverlay');

        function openModal() {
            modalOverlay.classList.remove('hidden');
            modalOverlay.classList.add('flex');

            setTimeout(() => {
                modalOverlay.classList.remove('opacity-0');
                modalOverlay.querySelector('div').classList.remove('scale-95');
                modalOverlay.querySelector('div').classList.add('scale-100');
            }, 10);

            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modalOverlay.classList.add('opacity-0');
            modalOverlay.querySelector('div').classList.remove('scale-100');
            modalOverlay.querySelector('div').classList.add('scale-95');

            setTimeout(() => {
                modalOverlay.classList.remove('flex');
                modalOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }, 200);
        }

        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
                closeModal();
            }
        });

        document.getElementById('fotoInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('File selected:', file.name);
            }
        });
    </script>

</body>

</html>
