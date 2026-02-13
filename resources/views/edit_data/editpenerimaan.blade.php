@extends('master.master')

@section('konten')
    <div class="flex-1 h-full flex flex-col overflow-hidden">

        {{-- Header --}}
        <div
            class="h-auto md:h-13 mb-1 flex flex-col md:flex-row items-start md:items-center p-2 md:p-3 justify-between shadow-[0_1px_3px_rgba(0,0,0,0.1)]">
            <div class="flex items-center gap-2">
                <span class="text-xs md:text-sm text-gray-400 font-medium">
                    Edit Data /
                </span>
                <h1 class="text-lg md:text-xl font-semibold text-(--blue)">
                    Penerimaan
                </h1>
            </div>
        </div>

        <input type="hidden" name="master_datetime" id="master_datetime">
        <input type="hidden" name="master_shift" id="master_shift">

        <form id="ep_form" method="POST" action="{{ route('editpenerimaan.updated') }}">
            @csrf
            <input type="hidden" name="ep_penerimaan_id" id="ep_id" value="{{ old('ep_penerimaan_id') }}">
            <input type="hidden" name="ep_nomor_rod_history" id="ep_nomor_rod_history"
                value="{{ old('ep_nomor_rod_history') }}">
            <input type="hidden" name="ep_tanggal_penerimaan" id="ep_tanggal_penerimaan"
                value="{{ old('ep_tanggal_penerimaan') }}">
            <input type="hidden" name="ep_shift" id="ep_shift" value="{{ old('ep_shift') }}">
        </form>

        @if (session('warning') || session('success') || session('warning_confirm'))
            <div id="notifModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-1050 p-4">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-4 md:p-5">

                    <h2
                        class="text-base md:text-lg font-semibold mb-3
                            {{ session('success') ? 'text-green-600' : 'text-red-600' }}">
                        {{ session('success') ? 'Informasi' : 'Peringatan' }}
                    </h2>

                    <p class="text-sm md:text-base text-gray-700 mb-4 md:mb-5">
                        {{ session('warning') ?? (session('success') ?? session('warning_confirm')) }}
                    </p>

                    <div class="flex justify-end gap-2">
                        @if (session('warning_confirm'))
                            <button onclick="EditPenerimaan.ep_closeNotif()"
                                class="px-3 md:px-4 py-2 text-sm rounded bg-gray-300 hover:bg-gray-400">
                                Batal
                            </button>

                            <form method="POST" action="{{ route('editpenerimaan.updated') }}">
                                @csrf

                                @foreach (old() as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <input type="hidden" name="force_update" value="1">

                                <button type="submit"
                                    class="px-3 md:px-4 py-2 text-sm rounded bg-(--blue) text-white hover:opacity-90">
                                    Tetap Lanjut
                                </button>
                            </form>
                        @else
                            <button onclick="EditPenerimaan.ep_closeNotif()"
                                class="px-3 md:px-4 py-2 text-sm rounded bg-(--blue) text-white hover:opacity-90">
                                OK
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Grid --}}
        <div
            class="grid flex-1 min-h-0 grid-cols-1 lg:grid-cols-[2fr_3fr_3fr] gap-2 overflow-y-auto lg:overflow-hidden pb-20 lg:pb-0">

            <input type="hidden" id="ep_formUnlocked" value="{{ old('ep_penerimaan_id') ? '1' : '0' }}">
            {{-- Panel Input --}}
            <div
                class="lg:row-span-3 bg-white p-3 md:p-4 border-2 border-gray-300 rounded-xl flex flex-col gap-3 md:gap-4 shrink-0 lg:overflow-auto">

                <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 space-y-2 md:space-y-3 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Nomor ROD</label>
                        <input type="text" name="nomor_rod" form="ep_form" id="ep_nomor_rod"
                            value="{{ old('nomor_rod') }}" readonly
                            class="h-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Jenis</label>
                        <input type="text" name="jenis" form="ep_form" id="ep_jenis" value="{{ old('jenis') }}"
                            readonly
                            class="hh-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="Axxxx">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Stasiun</label>
                        <input type="text" name="stasiun" form="ep_form" id="ep_stasiun" value="{{ old('stasiun') }}"
                            readonly
                            class="hh-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="Axxxx">
                    </div>
                </div>

                <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 space-y-3 md:space-y-4 shadow-sm">
                    <h3 class="text-sm md:text-base font-semibold text-gray-700">
                        Jenis Dan Jumlah Kerusakan
                    </h3>
                    <div class="grid grid-cols-3 md:grid-cols-3 gap-2 md:gap-3 text-center">

                        <div>
                            <label>E1</label>
                            <input type="number" name="e1" form="ep_form" value="{{ old('e1') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>D</label>
                            <input type="number" name="d" form="ep_form" value="{{ old('d') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>M</label>
                            <input type="number" name="m" form="ep_form" value="{{ old('m') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>E2</label>
                            <input type="number" name="e2" form="ep_form" value="{{ old('e2') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>B</label>
                            <input type="number" name="b" form="ep_form" value="{{ old('b') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>CR</label>
                            <input type="number" name="cr" form="ep_form" value="{{ old('cr') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>E3</label>
                            <input type="number" name="e3" form="ep_form" value="{{ old('e3') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>BA</label>
                            <input type="number" name="ba" form="ep_form" value="{{ old('ba') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>C</label>
                            <input type="number" name="c" form="ep_form" value="{{ old('c') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>S</label>
                            <input type="number" name="s" form="ep_form" value="{{ old('s') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>R</label>
                            <input type="number" name="r" form="ep_form" value="{{ old('r') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label>RL</label>
                            <input type="number" name="rl" form="ep_form" value="{{ old('rl') }}" readonly
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                {{-- Data Total --}}
                <div
                    class="mt-auto border-2 min-h-24 md:min-h-28 lg:min-h-30.5 border-gray-300 rounded-xl p-3 md:p-4 bg-linear-to-br from-blue-50 to-white shadow-sm flex flex-row gap-5 items-end">

                    <!-- Total Update -->
                    <div class="flex flex-col space-y-2 text-left">
                        <h3 class="text-base md:text-lg text-(--blue) mb-1 font-medium">Total Update</h3>
                        <h1 id="ep_totalUpdate" class="font-(--font-sans) text-4xl md:text-5xl text-(--blue)">0</h1>
                    </div>

                    <!-- Total Sebelumnya -->
                    <div class="flex flex-col space-y-1 text-left">
                        <h3 class="text-sm md:text-base text-(--blue) mb-1 font-medium">Total
                            Sebelumnya</h3>
                        <h1 id="ep_totalSebelumnya" class="font-(--font-sans) text-3xl md:text-4xl text-(--blue)">
                            {{ old('ep_totalSebelumnya', 0) }}
                        </h1>
                        <input type="hidden" name="ep_totalSebelumnya" id="ep_totalSebelumnyaHidden" form="ep_form"
                            value="{{ old('ep_totalSebelumnya') }}">
                    </div>

                </div>


            </div>

            {{-- Panel Catatan --}}
            <div
                class="lg:col-span-2 bg-white p-3 md:p-4 border-2 border-gray-300 rounded-xl shadow-sm flex flex-col shrink-0">
                <label class="text-sm md:text-base text-(--blue) font-medium mb-2">Catatan</label>
                <textarea name="catatan" form="ep_form" rows="2" readonly
                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 text-sm md:text-base uppercase focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition resize-none"
                    placeholder="Masukkan catatan...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Panel Besar --}}
            <div
                class="lg:col-span-2 lg:row-span-2 bg-white p-3 border-2 border-gray-300 rounded-xl flex flex-col gap-2 shadow-sm shrink-0 pb-20 lg:pb-6 relative lg:min-h-0">

                {{-- Panel Pencarian Data --}}
                <div class="min-h-auto shrink-0 flex flex-col lg:flex-row items-stretch lg:items-end gap-2">

                    <!-- Row 1 Mobile: Input ROD Full -->
                    <div class="relative flex-1 order-1 lg:order-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor"
                            class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                        </svg>

                        <input type="text" id="search_nomor_rod"
                            class="h-9 md:h-10 w-full border-2 border-gray-400 rounded-md pl-9 md:pl-10 pr-3 text-sm md:text-base uppercase text-left focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="Cari Berdasarkan Nomor ROD">
                    </div>

                    <!-- Row 2 Mobile: Reset (kiri) & Tanggal (kanan) -->
                    <div class="flex gap-2 order-2 lg:order-1">
                        <button type="button" onclick="EditPenerimaan.ep_resetSearch()"
                            class="h-9 md:h-10 px-3 md:px-4 bg-gray-300 text-sm rounded-lg hover:bg-gray-400 transition">
                            Reset
                        </button>

                        <input type="date" id="search_tanggal"
                            class="h-9 md:h-10 flex-1 lg:w-56 border-2 border-gray-400 rounded-md px-3 text-sm md:text-base" />
                    </div>

                </div>

                {{-- Panel Table dan Informasi --}}
                <div class="flex-1 flex flex-col border-2 border-gray-300 rounded-lg p-2 gap-2 min-h-0">

                    {{-- Panel Table --}}
                    <div class="flex-1 overflow-auto min-h-0 max-h-120 lg:max-h-full">
                        <div class="rounded-lg overflow-auto h-full">
                            <table class="w-full table-auto border-collapse">
                                <thead
                                    class="bg-(--blue) sticky top-0 z-10 text-(--whitesmoke) text-center text-xs md:text-sm font-semibold border-b whitespace-nowrap">
                                    <tr>
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Aksi</th>
                                        <th class="px-4 py-3">Nomor ROD</th>
                                        <th class="px-4 py-3">Tanggal Penerimaan</th>
                                        <th class="px-4 py-3">Shift</th>
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
                                        <th class="px-4 py-3">Diubah</th>
                                        <th class="px-4 py-3">Penginput</th>
                                        <th class="px-4 py-3">Tim</th>
                                    </tr>
                                </thead>
                                <tbody id="editpenerimaan-body" class="divide-y divide-gray-300">
                                    @include('edit_data.partials.penerimaan.editpenerimaan-table-body')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Panel Informasi Total Data --}}
                    <div id="editpenerimaan-info" class="shrink-0 border-t pt-2">
                        @include('edit_data.partials.penerimaan.editpenerimaan-info', [
                            'penerimaan' => $penerimaan,
                        ])
                    </div>
                </div>

                {{-- Panel Eksekusi Data --}}
                <div
                    class="fixed lg:static bottom-0 left-0 right-0 lg:min-h-15 bg-white border-t-2 lg:border-t-0 border-gray-300 lg:border-none p-4 lg:p-0 z-30 lg:z-auto shadow-lg lg:shadow-none">
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="EditPenerimaan.ep_reset(event)"
                            class="px-4 md:px-5 py-3 md:py-3 bg-red-300 text-gray-800 text-xs md:text-sm font-semibold rounded-lg hover:bg-red-400 transition shadow-sm">
                            Batal
                        </button>

                        <button type="submit" id="ep_btnSimpan" form="ep_form" disabled
                            class="px-4 md:px-5 py-3 md:py-3 bg-(--blue) text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            Edit Data
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div id="loadingOverlay" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-1050">
        <div class="bg-white px-4 md:px-6 py-3 md:py-4 rounded-lg flex items-center gap-3 shadow-xl">
            <svg class="animate-spin h-5 w-5 md:h-6 md:w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
            </svg>
            <span class="font-semibold text-sm md:text-base text-gray-700">Menyimpan data...</span>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/editpenerimaan.js'])
    <script>
        window.authKaryawanId = {{ session('karyawan_id') }};
    </script>
@endpush
