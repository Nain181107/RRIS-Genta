@extends('master.master')

@section('konten')
    <div class="flex-1 h-full flex flex-col overflow-hidden">

        {{-- Header --}}
        <div
            class="h-auto md:h-13 mb-1 flex flex-col md:flex-row items-start md:items-center p-2 md:p-3 justify-between shadow-[0_1px_3px_rgba(0,0,0,0.1)]">
            <h1 class="text-lg md:text-xl font-semibold text-(--blue)">
                Form Laporan
            </h1>
        </div>

        {{-- Main Grid --}}
        <div class="flex-1 min-h-0 overflow-hidden">

            {{-- Panel Besar --}}
            <div class="h-full bg-white p-2 md:p-3 border-2 border-gray-300 rounded-xl flex flex-col gap-2">

                {{-- Form Filter --}}
                <form id="formCetak" class="w-full space-y-2 shrink-0">

                    {{-- Mobile: Toggle Button --}}
                    <div class="md:hidden">
                        <button type="button" id="toggleFilterBtn"
                            class="w-full h-10 px-4 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 flex items-center justify-between hover:bg-gray-200 transition">
                            <span>Filter Laporan</span>
                            <svg id="filterIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 transition-transform duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>

                    {{-- Filter Container --}}
                    <div id="filterContainer" class="hidden md:block">
                        <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 bg-gray-50">

                            {{-- Filter Inputs Grid - ONLY THIS SCROLLS --}}
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3 max-h-48 overflow-y-auto md:max-h-none md:overflow-visible">

                                {{-- Jenis Laporan --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-gray-600">Jenis Laporan</label>
                                    <select id="jenisLaporan" name="jenisLaporan"
                                        class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-xs lg:text-sm md:text-sm text-gray-800 shadow-sm transition-all duration-200
                                        hover:border-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none">
                                        <option value="penerimaan">Penerimaan</option>
                                        <option value="perbaikan">Perbaikan</option>
                                        <option value="pengiriman">Pengiriman</option>
                                        <option value="buktiperubahan">Bukti Perubahan</option>
                                    </select>
                                </div>

                                {{-- Tanggal --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-gray-600">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                        class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-xs lg:text-sm md:text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none hover:border-gray-500">
                                </div>

                                {{-- Shift --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-gray-600">Shift</label>
                                    <select name="shift" id="shift"
                                        class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-xs lg:text-sm md:text-sm text-gray-800 shadow-sm transition-all duration-200
                                        hover:border-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none">
                                        <option value="" selected>Semua Shift</option>
                                        <option value="1">Shift 1</option>
                                        <option value="2">Shift 2</option>
                                        <option value="3">Shift 3</option>
                                    </select>
                                </div>

                                {{-- Tim --}}
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-gray-600">Tim</label>
                                    <select name="tim" id="tim"
                                        class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-xs lg:text-sm md:text-sm text-gray-800 shadow-sm 
                                        transition-all duration-200 hover:border-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none">
                                        <option value="" selected>Semua Tim</option>
                                        <option value="A">Tim A</option>
                                        <option value="B">Tim B</option>
                                        <option value="C">Tim C</option>
                                        <option value="D">Tim D</option>
                                        <option value="E">Tim E</option>
                                        <option value="F">Tim F</option>
                                    </select>
                                </div>

                            </div>

                            {{-- Action Buttons - NO SCROLL --}}
                            <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-300 justify-end">
                                <button type="button" id="btnCetak"
                                    class="h-10 px-4 md:px-5 bg-(--blue) text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 active:opacity-80 transition shadow-sm">
                                    <span class="sm:inline">Preview Data</span>
                                </button>

                                <button type="reset"
                                    class="h-10 px-4 md:px-5 bg-gray-200 text-gray-700 text-xs md:text-sm font-semibold rounded-lg hover:bg-gray-300 active:bg-gray-400 transition shadow-sm">
                                    Reset
                                </button>
                            </div>

                        </div>
                    </div>

                </form>

                {{-- Area Preview - ALWAYS VISIBLE --}}
                <div id="previewWrapper" class="h-full">
                    <div id="hasilCetak"
                        class="w-full h-full border-2 border-gray-300 bg-white shadow-lg rounded-lg p-2 md:p-1 min-h-50">
                        {{-- Preview Content --}}
                        <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-16 h-16 mb-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <p class="text-sm md:text-base font-medium text-center">Pilih filter dan klik "Preview
                                Data" untuk melihat laporan</p>
                        </div>
                    </div>
                </div>

                {{-- Panel Informasi Total Data - ALWAYS VISIBLE --}}
                <div
                    class="flex items-center justify-center min-h-11 shrink-0 bg-gray-50 rounded-lg border border-gray-300">
                    <h1 class="text-sm md:text-base font-semibold text-(--blue)">
                        Jumlah Data: <span id="jumlahData">0</span>
                    </h1>
                </div>

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #hasilCetak,
            #hasilCetak * {
                visibility: visible;
            }

            #hasilCetak {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
    <script>
        // Ambil CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Event listener untuk tombol Preview Data
        document.getElementById('btnCetak').addEventListener('click', function() {
            // Validasi: Jenis Laporan harus dipilih
            const jenisLaporan = document.getElementById('jenisLaporan').value;

            if (!jenisLaporan) {
                alert('Silakan pilih Jenis Laporan terlebih dahulu!');
                return;
            }

            // Ambil data dari form
            const formData = new FormData(document.getElementById('formCetak'));

            // Tampilkan loading indicator
            document.getElementById('hasilCetak').innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="animate-spin h-12 w-12 text-(--blue) mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600">Sedang memuat preview...</p>
                </div>
            `;

            // Kirim request ke server
            fetch('/laporan/preview-pdf', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal memuat preview');
                    }

                    const jumlah = response.headers.get('X-Jumlah-Data');
                    if (jumlah !== null) {
                        document.getElementById('jumlahData').textContent = jumlah;
                    }

                    return response.blob();
                })
                .then(blob => {
                    // Buat URL temporary untuk PDF
                    const pdfUrl = URL.createObjectURL(blob);

                    // Tampilkan PDF di iframe
                    document.getElementById('hasilCetak').innerHTML = `
                    <iframe 
                        src="${pdfUrl}" 
                        class="w-full h-full min-h-[600px] border-0 rounded"
                        id="pdfViewer">
                    </iframe>
                `;

                    // Update jumlah data (opsional, bisa diambil dari response)
                    // document.getElementById('jumlahData').textContent = '...';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('hasilCetak').innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mb-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <p class="font-medium">Gagal memuat preview</p>
                        <p class="text-sm text-gray-500">Silakan coba lagi</p>
                    </div>
                `;
                });
        });

        // Event listener untuk tombol Reset
        document.querySelector('button[type="reset"]').addEventListener('click', function() {
            // Reset preview area ke default
            document.getElementById('hasilCetak').innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <p class="text-sm md:text-base font-medium text-center">Pilih filter dan klik "Preview Data" untuk melihat laporan</p>
                </div>
            `;

            // Reset jumlah data
            document.getElementById('jumlahData').textContent = '0';
        });

        // Toggle filter untuk mobile
        document.getElementById('toggleFilterBtn')?.addEventListener('click', function() {
            const filterContainer = document.getElementById('filterContainer');
            const filterIcon = document.getElementById('filterIcon');

            filterContainer.classList.toggle('hidden');
            filterIcon.classList.toggle('rotate-180');
        });
    </script>
@endpush
