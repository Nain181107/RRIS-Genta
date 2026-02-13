@extends('master.master')

@section('konten')
    <div class="flex-1 h-full flex flex-col overflow-hidden">

        {{-- Header --}}
        <div
            class="h-auto md:h-13 mb-1 flex flex-col md:flex-row items-start md:items-center p-2 md:p-3 justify-between shadow-[0_1px_3px_rgba(0,0,0,0.1)]">
            <div class="flex items-center gap-2">
                <span class="text-xs md:text-sm text-gray-400 font-medium">
                    Riwayat Data /
                </span>
                <h1 class="text-lg md:text-xl font-semibold text-(--blue)">
                    Penerimaan
                </h1>
            </div>
        </div>

        <input type="hidden" name="master_datetime" id="master_datetime">
        <input type="hidden" name="master_shift" id="master_shift">

        {{-- Main Grid --}}
        <div class="flex-1 min-h-0">

            {{-- Panel Besar --}}
            <div class="h-full bg-white p-2 md:p-3 border-2 border-gray-300 rounded-xl flex flex-col min-h-0 gap-2">

                {{-- Panel Pencarian Data --}}
                <form id="historyFilterForm" class="w-full space-y-2">

                    {{-- Mobile: Toggle Button --}}
                    <div class="md:hidden">
                        <button type="button" id="toggleFilterBtn"
                            class="w-full h-10 px-4 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 flex items-center justify-between hover:bg-gray-200 transition">
                            <span>Filter Pencarian</span>
                            <svg id="filterIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 transition-transform duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>

                    {{-- Filter Inputs Container --}}
                    <div id="filterContainer" class="hidden md:block">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 md:gap-3">

                            {{-- Tanggal Mulai --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Tanggal Mulai</label>
                                <input type="date" name="tanggalMulai"
                                    class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none hover:border-gray-500">
                            </div>

                            {{-- Tanggal Akhir --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Tanggal Akhir</label>
                                <input type="date" name="tanggalAkhir"
                                    class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none hover:border-gray-500">
                            </div>

                            {{-- Shift --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Shift</label>
                                <select name="shift"
                                    class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-sm text-gray-800 shadow-sm transition-all duration-200
                                    hover:border-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none">
                                    <option value="" selected>Semua Shift</option>
                                    <option value="1">Shift 1</option>
                                    <option value="2">Shift 2</option>
                                    <option value="3">Shift 3</option>
                                </select>
                            </div>

                            {{-- Nomor ROD --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Nomor ROD</label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                                    </svg>
                                    <input type="text" placeholder="Cari nomor ROD..." name="nomor_rod"
                                        class="h-10 w-full border border-gray-400 rounded-lg pl-9 pr-3 text-sm focus:outline-none focus:ring-2 uppercase focus:ring-blue-500/30 focus:border-blue-500 hover:border-gray-500">
                                </div>
                            </div>

                            {{-- Jenis --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Jenis</label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                                    </svg>
                                    <input type="text" placeholder="Cari jenis..." name="jenis"
                                        class="h-10 w-full border border-gray-400 rounded-lg pl-9 pr-3 text-sm focus:outline-none focus:ring-2 uppercase focus:ring-blue-500/30 focus:border-blue-500 hover:border-gray-500">
                                </div>
                            </div>

                            {{-- Stasiun --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Stasiun</label>
                                <select name="stasiun"
                                    class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-sm text-gray-800 shadow-sm transition-all duration-200
                                    hover:border-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none">
                                    <option value="" selected>Semua Stasiun</option>
                                    <option value="A">Stasiun A</option>
                                    <option value="B">Stasiun B</option>
                                    <option value="C">Stasiun C</option>
                                    <option value="D">Stasiun D</option>
                                    <option value="E">Stasiun E</option>
                                    <option value="F">Stasiun F</option>
                                </select>
                            </div>

                            {{-- Checker Tim Penginput --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Checker/Tim Penginput</label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                                    </svg>
                                    <input type="text" placeholder="Cari checker/tim..." name="penginput"
                                        class="h-10 w-full border border-gray-400 rounded-lg pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 hover:border-gray-500">
                                </div>
                            </div>

                            {{-- Tim --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-sm font-medium text-gray-600">Tim</label>
                                <select name="tim"
                                    class="h-10 w-full rounded-lg border border-gray-400 bg-white px-3 text-sm text-gray-800 shadow-sm transition-all duration-200
                                    hover:border-gray-500 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none">
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
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-2 pt-2 justify-end">
                        <button type="button" id="btnCari"
                            class="h-10 px-5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 active:bg-blue-800 transition shadow-sm">
                            <span class="hidden sm:inline">Cari Data</span>
                            <span class="sm:hidden">Cari</span>
                        </button>

                        <button type="button" id="btnReset"
                            class="h-10 px-5 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 active:bg-gray-400 transition shadow-sm">
                            Reset
                        </button>

                        <button type="button" id="btnExport" data-export-url="{{ route('history.penerimaan.export') }}"
                            class="h-10 px-5 bg-(--blue) text-white text-sm font-medium rounded-lg hover:opacity-90 active:opacity-80 transition shadow-sm">
                            <span class="sm:inline">Ekspor Excel</span>
                        </button>
                    </div>

                </form>

                {{-- Panel Table dan Informasi --}}
                <div class="flex-1 min-h-0 flex flex-col border-2 border-gray-300 rounded-lg p-2 overflow-hidden">
                    <div class="h-full rounded-lg overflow-auto">
                        <table class="w-full table-auto border-collapse">
                            <thead
                                class="bg-(--blue) sticky top-0 z-10 text-(--whitesmoke) text-center text-sm font-semibold border-b whitespace-nowrap">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Tanggal Penerimaan</th>
                                    <th class="px-4 py-3">Shift</th>
                                    <th class="px-4 py-3">Aksi</th>
                                    <th class="px-4 py-3">Nomor ROD</th>
                                    <th class="px-4 py-3">Jenis</th>
                                    <th class="px-4 py-3">Stasiun</th>
                                    <th class="px-4 py-3">E1</th>
                                    <th class="px-4 py-3">E2</th>
                                    <th class="px-4 py-3">E3</th>
                                    <th class="px-4 py-3">S</th>
                                    <th class="px-4 py-3">D</th>
                                    <th class="px-4 py-3">B</th>
                                    <th class="px-4 py-3">BA</th>
                                    <th class="px-4 py-3">R</th>
                                    <th class="px-4 py-3">M</th>
                                    <th class="px-4 py-3">CR</th>
                                    <th class="px-4 py-3">C</th>
                                    <th class="px-4 py-3">RL</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Catatan</th>
                                    <th class="px-4 py-3">Diinput</th>
                                    <th class="px-4 py-3">Operator</th>
                                    <th class="px-4 py-3">Tim</th>
                                </tr>
                            </thead>
                            <tbody id="historypenerimaan-body" class="divide-y divide-gray-300 text-xs md:text-sm">
                                @include('history_data.partials.penerimaan.historypenerimaan-table-body')
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Panel Informasi Total Data --}}
                <div id="historypenerimaan-info">
                    @include('history_data.partials.penerimaan.historypenerimaan-info', [
                        'penerimaan' => $penerimaan,
                    ])
                </div>
            </div>
        </div>

    </div>

    {{-- Modal Form --}}
    <div id="modalForm"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-50 transition-opacity duration-300 px-4 sm:px-6 lg:px-10">

        <div id="modalBox"
            class="bg-white max-w-full md:max-w-5xl lg:max-w-6xl w-full max-h-[90vh] rounded-xl shadow-lg p-4 md:p-5 transform scale-95 opacity-0 transition-all duration-300 flex flex-col gap-3 md:gap-4 overflow-hidden">

            <h1 class="text-xl md:text-3xl font-semibold text-(--blue)">
                Penerimaan
            </h1>

            <div class="flex sm:flex-row items-start sm:items-center justify-between gap-2">

                <h2 class="text-base md:text-lg font-semibold text-(--blue)">
                    Nomor ROD : <span id="nomor-rod-title" class="font-bold">-</span>
                </h2>

                <button class="h-9 px-5 bg-(--blue) text-white text-sm font-medium rounded-md hover:opacity-80 transition">
                    Print
                </button>

            </div>

            <div class="flex-1 overflow-y-auto space-y-4">

                {{-- Identitas Awal --}}
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-700 mb-2">Identitas Awal</h3>
                    <div class="border-2 border-gray-300 rounded-md overflow-auto">
                        <table class="w-full table-auto border-collapse">
                            <thead
                                class="bg-(--blue) sticky top-0 z-10 text-(--whitesmoke) text-center text-sm font-semibold border-b whitespace-nowrap">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Tanggal Penerimaan</th>
                                    <th class="px-4 py-3">Shift</th>
                                    <th class="px-4 py-3">Nomor ROD</th>
                                    <th class="px-4 py-3">Jenis</th>
                                    <th class="px-4 py-3">Stasiun</th>
                                    <th class="px-4 py-3">E1</th>
                                    <th class="px-4 py-3">E2</th>
                                    <th class="px-4 py-3">E3</th>
                                    <th class="px-4 py-3">S</th>
                                    <th class="px-4 py-3">D</th>
                                    <th class="px-4 py-3">B</th>
                                    <th class="px-4 py-3">BA</th>
                                    <th class="px-4 py-3">R</th>
                                    <th class="px-4 py-3">M</th>
                                    <th class="px-4 py-3">CR</th>
                                    <th class="px-4 py-3">C</th>
                                    <th class="px-4 py-3">RL</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Catatan</th>
                                    <th class="px-4 py-3">Diinput</th>
                                    <th class="px-4 py-3">Operator</th>
                                    <th class="px-4 py-3">Tim</th>
                                </tr>
                            </thead>
                            <tbody id="identitas-awal-body" class="divide-y divide-gray-200 text-xs md:text-sm">
                                <tr>
                                    <td colspan="23" class="text-center py-4 text-gray-400">
                                        Pilih data untuk melihat Identitas Awal
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Riwayat Perubahan --}}
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-700 mb-2">Riwayat Perubahan</h3>
                    <div class="border-2 border-gray-300 rounded-md max-h-[30vh] overflow-auto">
                        <table class="w-full table-auto border-collapse">
                            <thead
                                class="bg-(--blue) sticky top-0 z-10 text-(--whitesmoke) text-center text-sm font-semibold border-b whitespace-nowrap">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Tanggal Penerimaan</th>
                                    <th class="px-4 py-3">Shift</th>
                                    <th class="px-4 py-3">Nomor ROD</th>
                                    <th class="px-4 py-3">Jenis</th>
                                    <th class="px-4 py-3">Stasiun</th>
                                    <th class="px-4 py-3">E1</th>
                                    <th class="px-4 py-3">E2</th>
                                    <th class="px-4 py-3">E3</th>
                                    <th class="px-4 py-3">S</th>
                                    <th class="px-4 py-3">D</th>
                                    <th class="px-4 py-3">B</th>
                                    <th class="px-4 py-3">BA</th>
                                    <th class="px-4 py-3">R</th>
                                    <th class="px-4 py-3">M</th>
                                    <th class="px-4 py-3">CR</th>
                                    <th class="px-4 py-3">C</th>
                                    <th class="px-4 py-3">RL</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Catatan</th>
                                    <th class="px-4 py-3">Diinput</th>
                                    <th class="px-4 py-3">Operator</th>
                                    <th class="px-4 py-3">Tim</th>
                                </tr>
                            </thead>
                            <tbody id="riwayat-perubahan-body" class="divide-y divide-gray-400 text-xs md:text-sm">
                                <tr>
                                    <td colspan="23" class="text-center py-4 text-gray-400">
                                        Pilih data untuk melihat Riwayat Perubahan
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Data Sekarang --}}
                <div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-700 mb-2">Data Sekarang</h3>
                    <div class="border-2 border-gray-300 rounded-md overflow-auto">
                        <table class="w-full table-auto border-collapse">
                            <thead
                                class="bg-(--blue) sticky top-0 z-10 text-(--whitesmoke) text-center text-sm font-semibold border-b whitespace-nowrap">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Tanggal Penerimaan</th>
                                    <th class="px-4 py-3">Shift</th>
                                    <th class="px-4 py-3">Nomor ROD</th>
                                    <th class="px-4 py-3">Jenis</th>
                                    <th class="px-4 py-3">Stasiun</th>
                                    <th class="px-4 py-3">E1</th>
                                    <th class="px-4 py-3">E2</th>
                                    <th class="px-4 py-3">E3</th>
                                    <th class="px-4 py-3">S</th>
                                    <th class="px-4 py-3">D</th>
                                    <th class="px-4 py-3">B</th>
                                    <th class="px-4 py-3">BA</th>
                                    <th class="px-4 py-3">R</th>
                                    <th class="px-4 py-3">M</th>
                                    <th class="px-4 py-3">CR</th>
                                    <th class="px-4 py-3">C</th>
                                    <th class="px-4 py-3">RL</th>
                                    <th class="px-4 py-3">Jumlah</th>
                                    <th class="px-4 py-3">Catatan</th>
                                    <th class="px-4 py-3">Diinput</th>
                                    <th class="px-4 py-3">Operator</th>
                                    <th class="px-4 py-3">Tim</th>
                                </tr>
                            </thead>
                            <tbody id="data-sekarang-body" class="divide-y divide-gray-400 text-xs md:text-sm">
                                <tr>
                                    <td colspan="23" class="text-center py-4 text-gray-400">
                                        Pilih data untuk melihat Data Sekarang
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="pt-2 flex justify-end border-t">
                <button onclick="closeModal()"
                    class="h-10 px-5 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/historypenerimaan.js'])
    <script>
        window.authKaryawanId = {{ session('karyawan_id') }};
    </script>
@endpush
