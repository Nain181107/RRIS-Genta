@extends('master.master')

@section('konten')
    {{-- Konten --}}
    <div class="flex-1 h-full flex flex-col overflow-hidden">

        {{-- Header --}}
        <div
            class="h-auto md:h-13 mb-1 flex flex-col md:flex-row items-start md:items-center p-2 md:p-3 justify-between shadow-[0_1px_3px_rgba(0,0,0,0.1)]">
            <div class="flex items-center gap-2">
                <span class="text-xs md:text-sm text-gray-400 font-medium">
                    Entry Data /
                </span>
                <h1 class="text-lg md:text-xl font-semibold text-(--blue)">
                    Penerimaan
                </h1>
            </div>

            <div class="flex items-center self-end md:self-auto">
                <a href="javascript:void(0)" onclick="openPerputaranModal()"
                    class="p-1.5 md:p-2 rounded hover:bg-gray-200 transition flex items-center justify-center gap-2 md:gap-3">
                    <h3 class="text-xs md:text-sm text-(--blue) italic whitespace-nowrap">Perputaran ROD
                        {{ $hariPerputaran }} Hari</h3>
                    <svg class="w-5 h-5 md:w-7 md:h-7 text-(--blue)" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h5M20 20v-5h-5M5 9a7 7 0 0 1 12-3m2 9a7 7 0 0 1-12 3" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- form input data --}}
        <form id="formPenerimaan" method="POST" action="{{ route('penerimaan.store') }}">
            @csrf
            <input type="hidden" name="master_datetime" id="master_datetime">
            <input type="hidden" name="master_shift" id="master_shift">
        </form>

        {{-- notifikasi input --}}
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
                            <div class="flex gap-2 md:gap-3">
                                <button onclick="closeNotifModal()"
                                    class="px-3 md:px-4 py-2 text-sm rounded bg-gray-300 hover:bg-gray-400">
                                    Batal
                                </button>

                                <form method="POST" action="{{ route('penerimaan.store') }}">
                                    @csrf

                                    @foreach (old() as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach

                                    <input type="hidden" name="force_insert" value="1">

                                    <button type="submit"
                                        class="px-3 md:px-4 py-2 text-sm rounded bg-(--blue) text-white hover:opacity-90">
                                        Tetap Lanjut
                                    </button>
                                </form>
                            </div>
                        @else
                            <button onclick="closeNotifModal()"
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

            <div
                class="lg:row-span-3 bg-white p-3 md:p-4 border-2 border-gray-300 rounded-xl flex flex-col gap-3 md:gap-4 shrink-0 lg:overflow-auto">

                <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 space-y-2 md:space-y-3 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Nomor ROD</label>
                        <input type="text" name="nomor_rod" form="formPenerimaan" value="{{ old('nomor_rod') }}"
                            tabindex="1"
                            class="h-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Jenis</label>
                        <input type="text" name="jenis" form="formPenerimaan" value="{{ old('jenis') }}"
                            tabindex="2"
                            class="h-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="Axxxx">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Stasiun</label>
                        <input type="text" name="stasiun" form="formPenerimaan" value="{{ old('stasiun') }}"
                            tabindex="3"
                            class="h-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="Axxxx">
                    </div>
                </div>

                <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 space-y-3 md:space-y-4 shadow-sm">
                    <h3 class="text-sm md:text-base font-semibold text-gray-700">
                        Jenis Dan Jumlah Kerusakan
                    </h3>
                    <div class="grid grid-cols-3 md:grid-cols-3 gap-2 md:gap-3 text-center">

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">E1</label>
                            <input type="number" name="e1" form="formPenerimaan" value="{{ old('e1') }}"
                                tabindex="4"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">D</label>
                            <input type="number" name="d" form="formPenerimaan" value="{{ old('d') }}"
                                tabindex="8"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">M</label>
                            <input type="number" name="m" form="formPenerimaan" value="{{ old('m') }}"
                                tabindex="12"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">E2</label>
                            <input type="number" name="e2" form="formPenerimaan" value="{{ old('e2') }}"
                                tabindex="5"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">B</label>
                            <input type="number" name="b" form="formPenerimaan" value="{{ old('b') }}"
                                tabindex="9"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">CR</label>
                            <input type="number" name="cr" form="formPenerimaan" value="{{ old('cr') }}"
                                tabindex="13"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">E3</label>
                            <input type="number" name="e3" form="formPenerimaan" value="{{ old('e3') }}"
                                tabindex="6"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">BA</label>
                            <input type="number" name="ba" form="formPenerimaan" value="{{ old('ba') }}"
                                tabindex="10"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">C</label>
                            <input type="number" name="c" form="formPenerimaan" value="{{ old('c') }}"
                                tabindex="14"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">S</label>
                            <input type="number" name="s" form="formPenerimaan" value="{{ old('s') }}"
                                tabindex="7"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">R</label>
                            <input type="number" name="r" form="formPenerimaan" value="{{ old('r') }}"
                                tabindex="11"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-xs md:text-sm font-medium text-gray-600">RL</label>
                            <input type="number" name="rl" form="formPenerimaan" value="{{ old('rl') }}"
                                tabindex="15"
                                class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <div
                    class="mt-auto border-2 min-h-24 md:min-h-28 lg:min-h-30.5 border-gray-300 rounded-xl p-3 md:p-4 bg-linear-to-br from-blue-50 to-white shadow-sm">
                    <h3 class="text-sm md:text-base text-(--blue) mb-1 font-medium">Total Kerusakan</h3>
                    <h1 id="totalKerusakan" class="font-(--font-sans) text-3xl md:text-5xl text-(--blue)">-</h1>
                </div>

            </div>

            {{-- Panel Catatan --}}
            <div
                class="lg:col-span-2 bg-white p-3 md:p-4 border-2 border-gray-300 rounded-xl shadow-sm flex flex-col shrink-0">
                <label class="text-sm md:text-base text-(--blue) font-medium mb-2">Catatan</label>
                <textarea name="catatan" form="formPenerimaan" tabindex="16" rows="2"
                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 text-sm md:text-base uppercase focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition resize-none"
                    placeholder="Masukkan catatan...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Panel Besar - With scrollable content and floating buttons on mobile --}}
            <div
                class="lg:col-span-2 lg:row-span-2 bg-white p-3 border-2 border-gray-300 rounded-xl flex flex-col gap-3 shadow-sm shrink-0 pb-20 lg:pb-3 relative lg:min-h-0">

                <div class="relative min-h-9 md:min-h-10 shrink-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                    </svg>

                    <input type="text" id="caripenerimaan" value="{{ request('caripenerimaan') }}" tabindex="17"
                        class="h-9 md:h-10 w-full border-2 border-gray-400 uppercase rounded-md pl-9 md:pl-10 pr-3 py-1 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                        placeholder="Cari Berdasarkan Nomor ROD">

                </div>

                <div class="flex-1 flex flex-col border-2 border-gray-300 rounded-lg p-2 gap-2 min-h-0">

                    {{-- Panel Table --}}
                    <div class="flex-1 overflow-auto min-h-0 max-h-80 lg:max-h-full">
                        <div class="rounded-lg overflow-auto h-full">
                            <table class="w-full table-auto border-collapse">
                                <thead
                                    class="bg-(--blue) sticky top-0 z-10 text-(--whitesmoke) text-center text-xs md:text-sm font-semibold border-b whitespace-nowrap">
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
                                        <th class="px-4 py-3">Diubah</th>
                                        <th class="px-4 py-3">Penginput</th>
                                        <th class="px-4 py-3">Tim</th>
                                    </tr>
                                </thead>
                                <tbody id="penerimaan-body" class="divide-y divide-gray-300 text-xs md:text-sm">
                                    @include('entry_data.partials.penerimaan.penerimaan-table-body')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Panel Informasi Total Data --}}
                    <div id="penerimaan-info" class="shrink-0 border-t pt-2">
                        @include('entry_data.partials.penerimaan.penerimaan-info', [
                            'penerimaan' => $penerimaan,
                        ])
                    </div>

                </div>

                {{-- Floating Buttons on Mobile, Fixed Position on Desktop --}}
                <div
                    class="fixed lg:static bottom-0 left-0 right-0 lg:min-h-15 bg-white border-t-2 lg:border-t-0 border-gray-300 lg:border-none p-4 lg:p-0 z-30 lg:z-auto shadow-lg lg:shadow-none">
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="resetPenerimaan(event)" tabindex="19"
                            class="px-4 md:px-5 py-3 md:py-3 bg-red-300 text-gray-800 text-xs md:text-sm font-semibold rounded-lg hover:bg-red-400 transition shadow-sm">
                            Batal
                        </button>

                        <button type="submit" form="formPenerimaan" tabindex="18"
                            class="px-4 md:px-5 py-3 md:py-3 bg-(--blue) text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 transition shadow-sm">
                            Simpan Data
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Loading Overlay --}}
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

    <!-- BACKDROP -->
    <div id="perputaranModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">

        <!-- BLUR BACKGROUND -->
        <div onclick="closePerputaranModal()" class="absolute inset-0 bg-black/30 backdrop-blur-sm transition-opacity">
        </div>

        <!-- MODAL BOX -->
        <div class="relative bg-white/95 rounded-xl shadow-xl w-full max-w-md p-4 md:p-6 z-10 animate-scaleIn">

            <h3 class="text-base md:text-lg font-semibold text-(--blue) mb-3 md:mb-4">
                Pengaturan Perputaran ROD
            </h3>

            <form method="POST" action="{{ route('perputaran-rod.update') }}">
                @csrf
                <div class="mb-3 md:mb-4">
                    <label class="block text-xs md:text-sm mb-1 font-medium text-gray-700">Jumlah Hari</label>
                    <input type="number" name="hari" value="{{ old('hari', $hariPerputaran) }}"
                        data-initial="{{ $hariPerputaran }}"
                        class="w-full border-2 rounded-md px-3 py-2 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition
                            @error('hari') border-red-500 ring-red-300 @enderror"
                        min="1">
                    @error('hari')
                        <p class="text-red-600 text-xs md:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closePerputaranModal()"
                        class="px-3 md:px-4 py-2 text-sm rounded bg-gray-200 hover:bg-gray-300 transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-3 md:px-4 py-2 text-sm rounded bg-(--blue) text-white hover:opacity-80 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/penerimaan.js'])

    <script>
        window.authKaryawanId = {{ session('karyawan_id') }};
        window.penerimaanKeyword = @json(request('caripenerimaan'));
        window.hasHariError = @json($errors->has('hari'));
    </script>
@endpush
