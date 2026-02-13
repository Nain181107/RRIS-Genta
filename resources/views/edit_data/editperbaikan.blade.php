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
                    Perbaikan
                </h1>
            </div>
        </div>

        <input type="hidden" name="master_datetime" id="master_datetime">
        <input type="hidden" name="master_shift" id="master_shift">

        <form id="eper_form" method="POST" action="{{ route('editperbaikan.updated') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="eper_perbaikan_id" id="eper_id" value="{{ old('eper_perbaikan_id') }}">
            <input type="hidden" name="eper_nomor_rod_history" id="eper_nomor_rod_history"
                value="{{ old('eper_nomor_rod_history') }}">
            <input type="hidden" name="eper_tanggal_perbaikan" id="eper_tanggal_perbaikan"
                value="{{ old('eper_tanggal_perbaikan') }}">
            <input type="hidden" name="eper_shift" id="eper_shift" value="{{ old('eper_perbaikan_id') }}"
                value="{{ old('eper_shift') }}">
            <input type="hidden" name="eper_tanggal_penerimaan" id="eper_tanggal_penerimaan"
                value="{{ old('eper_tanggal_penerimaan') }}">
            <input type="file" id="eper_inputFoto" name="eper_fotobuktiperubahan" accept="image/*" hidden>
            <input type="hidden" name="hapus_foto" id="eper_hapus_foto" value="0">
            <input type="hidden" id="eper_existing_photo" name="eper_existing_photo"
                value="{{ old('eper_existing_photo') }}">
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
                            <button onclick="EditPerbaikan.eper_closeNotif()"
                                class="px-3 md:px-4 py-2 text-sm rounded bg-gray-300 hover:bg-gray-400">
                                Batal
                            </button>

                            <form method="POST" action="{{ route('editperbaikan.updated') }}">
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
                            <button onclick="EditPerbaikan.eper_closeNotif()"
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
            class="grid flex-1 min-h-0 grid-cols-1 lg:grid-cols-[2fr_3fr_3fr] gap-2 overflow-y-auto lg:overflow-hidden pb-35 lg:pb-0">

            <input type="hidden" id="eper_formUnlocked" value="{{ old('eper_perbaikan_id') ? '1' : '0' }}">

            {{-- Panel Input --}}
            <div
                class="lg:row-span-3 bg-white p-3 md:p-4 border-2 border-gray-300 rounded-xl flex flex-col gap-3 md:gap-4 shrink-0 lg:overflow-auto">

                {{-- Data Wajib --}}
                <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 space-y-2 md:space-y-3 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Nomor ROD</label>
                        <input type="text" name="nomor_rod" form="eper_form" id="eper_nomor_rod"
                            value="{{ old('nomor_rod') }}" readonly tabindex="1"
                            class="h-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-base text-(--blue)">Jenis</label>
                        <input type="text" name="jenis" form="eper_form" id="eper_jenis" value="{{ old('jenis') }}"
                            readonly tabindex="2"
                            class="h-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="Axxxx">
                    </div>
                </div>

                {{-- Data Jenis Kerusakan --}}
                <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 space-y-3 md:space-y-4 shadow-sm">
                    <h3 class="text-sm md:text-base font-semibold text-gray-700">
                        Jenis Dan Jumlah Kerusakan
                    </h3>

                    <!-- === BAGIAN ATAS === -->
                    <div class="space-y-2 md:space-y-3">

                        <!-- BA -->
                        <div class="border border-dashed border-gray-400 rounded-md p-2 md:p-3">
                            <div class="text-xs md:text-sm font-semibold mb-2">BA</div>
                            <div class="grid grid-cols-3 gap-2">

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Bac</span>
                                    <input type="number" name="bac" form="eper_form" value="{{ old('bac') }}"
                                        readonly tabindex="3"
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Nba</span>
                                    <input type="number" name="nba" form="eper_form" value="{{ old('nba') }}"
                                        readonly tabindex="4"
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center justify-center font-medium">
                                    <span class="text-xs md:text-sm">Jumlah</span>
                                    <span class="text-sm md:text-base" id="ba_jumlah">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- E1 -->
                        <div class="border border-dashed border-gray-400 rounded-md p-2 md:p-3">
                            <div class="text-xs md:text-sm font-semibold mb-2">E1</div>
                            <div class="grid grid-cols-3 gap-2">

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Ers</span>
                                    <input type="number" name="e1_ers" form="eper_form" value="{{ old('e1_ers') }}"
                                        readonly tabindex="5"
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Est</span>
                                    <input type="number" name="e1_est" form="eper_form" value="{{ old('e1_est') }}"
                                        readonly tabindex="6"
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center justify-center font-medium">
                                    <span class="text-sm">Jumlah</span>
                                    <span class="text-base" id="e1_jumlah">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- E2 -->
                        <div class="border border-dashed border-gray-400 rounded-md p-2 md:p-3">
                            <div class="text-xs md:text-sm font-semibold mb-2">E2</div>
                            <div class="grid grid-cols-4 gap-2">

                                <!-- Ers -->
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Ers</span>
                                    <input type="number" name="e2_ers" form="eper_form" value="{{ old('e2_ers') }}"
                                        readonly tabindex="7"
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <!-- Cst -->
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Cst</span>
                                    <input type="number" name="e2_cst" form="eper_form" value="{{ old('e2_cst') }}"
                                        readonly tabindex="8"
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <!-- Cstub -->
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Cstub</span>
                                    <input type="number" name="e2_cstub" form="eper_form"
                                        value="{{ old('e2_cstub') }}" readonly tabindex="9"
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <!-- Jumlah -->
                                <div class="flex flex-col items-center justify-center font-medium">
                                    <span class="text-xs md:text-sm">Jumlah</span>
                                    <span class="text-sm md:text-base" id="e2_jumlah">-</span> <!-- ✅ TAMBAHKAN ID -->
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- === BAGIAN BAWAH === -->
                    <div class="border border-dashed border-gray-400 rounded-md p-2 md:p-3">
                        <div class="grid grid-cols-3 gap-2 md:gap-1 text-center">

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">E3</label>
                                <input type="number" name="e3" form="eper_form" value="{{ old('e3') }}"
                                    readonly tabindex="10"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">B</label>
                                <input type="number" name="b" form="eper_form" value="{{ old('b') }}"
                                    readonly tabindex="14"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">CR</label>
                                <input type="number" name="cr" form="eper_form" value="{{ old('cr') }}"
                                    readonly tabindex="18"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">E4</label>
                                <input type="number" name="e4" form="eper_form" value="{{ old('e4') }}"
                                    readonly tabindex="11"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">BA-1</label>
                                <input type="number" name="ba1" form="eper_form" value="{{ old('ba1') }}"
                                    readonly tabindex="15"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">C</label>
                                <input type="number" name="c" form="eper_form" value="{{ old('c') }}"
                                    readonly tabindex="19"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">S</label>
                                <input type="number" name="s" form="eper_form" value="{{ old('s') }}"
                                    readonly tabindex="12"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">R</label>
                                <input type="number" name="r" form="eper_form" value="{{ old('r') }}"
                                    readonly tabindex="16"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">RL</label>
                                <input type="number" name="rl" form="eper_form" value="{{ old('rl') }}"
                                    readonly tabindex="20"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">D</label>
                                <input type="number" name="d" form="eper_form" value="{{ old('d') }}"
                                    readonly tabindex="13"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">M</label>
                                <input type="number" name="m" form="eper_form" value="{{ old('m') }}"
                                    readonly tabindex="17"
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Data Total --}}
                <div
                    class="mt-auto border-2 min-h-24 md:min-h-28 lg:min-h-30.5 border-gray-300 rounded-xl p-3 md:p-4 bg-linear-to-br from-blue-50 to-white shadow-sm flex flex-row gap-5 items-end">

                    <div class="flex flex-col space-y-2 text-left">
                        <h3 class="text-base md:text-lg text-(--blue) mb-1 font-medium">Total Update</h3>
                        <h1 id="eper_totalUpdate" class="font-(--font-sans) text-4xl md:text-5xl text-(--blue)">0</h1>

                    </div>

                    <div class="flex flex-col space-y-1 text-left">
                        <h3 class="text-sm md:text-base text-(--blue) mb-1 font-medium">Total Sebelumnya</h3>
                        <h1 id="eper_totalSebelumnya" class="font-(--font-sans) text-3xl md:text-4xl text-(--blue)">
                            {{ old('eper_totalSebelumnya', 0) }}
                        </h1>
                        <input type="hidden" name="eper_totalSebelumnya" id="eper_totalSebelumnyaHidden"
                            form="eper_form" value="{{ old('eper_totalSebelumnya') }}">
                    </div>

                </div>

            </div>

            {{-- Panel Catatan --}}
            <div
                class="lg:col-span-2 bg-white p-3 md:p-4 border-2 border-gray-300 rounded-xl shadow-sm flex flex-col shrink-0">
                <label class="text-sm md:text-base text-(--blue) font-medium mb-2">Catatan</label>
                <textarea name="catatan" form="eper_form" rows="2" readonly
                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 text-sm md:text-base uppercase focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition resize-none"
                    placeholder="Masukkan catatan...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Panel Besar --}}
            <div
                class="lg:col-span-2 lg:row-span-2 bg-white p-3 border-2 border-gray-300 rounded-xl flex flex-col gap-2 shadow-sm shrink-0 pb-20 lg:pb-6 relative lg:min-h-0">

                {{-- Panel Pencarian Data --}}
                <div class="min-h-auto shrink-0 flex flex-col lg:flex-row items-stretch lg:items-end gap-2">

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

                    <div class="flex gap-2 order-2 lg:order-1">
                        <button type="button" onclick="EditPerbaikan.eper_resetSearch()"
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
                                        <th class="px-4 py-3">Tanggal Perbaikan</th>
                                        <th class="px-4 py-3">Shift</th>
                                        <th class="px-4 py-3">Jenis</th>
                                        <th class="px-4 py-3">E1 Ers</th>
                                        <th class="px-4 py-3">E1 Est</th>
                                        <th class="px-4 py-3">E1 Jumlah</th>
                                        <th class="px-4 py-3">E2 Ers</th>
                                        <th class="px-4 py-3">E2 Cst</th>
                                        <th class="px-4 py-3">E2 Cstub</th>
                                        <th class="px-4 py-3">E2 Jumlah</th>
                                        <th class="px-4 py-3">E3</th>
                                        <th class="px-4 py-3">E4</th>
                                        <th class="px-4 py-3">S</th>
                                        <th class="px-4 py-3">D</th>
                                        <th class="px-4 py-3">B</th>
                                        <th class="px-4 py-3">BAC</th>
                                        <th class="px-4 py-3">NBA</th>
                                        <th class="px-4 py-3">BA</th>
                                        <th class="px-4 py-3">BA-1</th>
                                        <th class="px-4 py-3">R</th>
                                        <th class="px-4 py-3">M</th>
                                        <th class="px-4 py-3">CR</th>
                                        <th class="px-4 py-3">C</th>
                                        <th class="px-4 py-3">RL</th>
                                        <th class="px-4 py-3">Jumlah</th>
                                        <th class="px-4 py-3">Catatan</th>
                                        <th class="px-4 py-3">Tanggal Penerimaan</th>
                                        <th class="px-4 py-3">Diubah</th>
                                        <th class="px-4 py-3">Penginput</th>
                                        <th class="px-4 py-3">Tim</th>
                                        <th class="px-4 py-3">Foto</th>
                                    </tr>
                                </thead>
                                <tbody id="editperbaikan-body" class="divide-y divide-gray-300">
                                    @include('edit_data.partials.perbaikan.editperbaikan-table-body')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Panel Informasi Total Data --}}
                    <div id="editperbaikan-info" class="shrink-0 border-t pt-2">
                        @include('edit_data.partials.perbaikan.editperbaikan-info', [
                            'perbaikan' => $perbaikan,
                        ])
                    </div>
                </div>

                {{-- Panel Eksekusi Data --}}
                <div
                    class="fixed lg:static bottom-0 left-0 right-0 lg:min-h-15 bg-white border-t-2 lg:border-t-0 border-gray-300 lg:border-none p-4 lg:p-0 z-30 lg:z-auto shadow-lg lg:shadow-none">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 lg:gap-0">

                        <div class="flex items-center gap-2">
                            <button id="eper_openKamera" type="button" disabled
                                class="flex-1 lg:flex-none lg:w-12 lg:h-12 md:lg:w-15 md:lg:h-15 bg-white p-3 border-2 border-gray-300 rounded-xl flex items-center justify-center gap-2 lg:gap-0 hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="var(--blue)" class="w-5 h-5 md:w-6 md:h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                                <span class="lg:hidden text-xs font-medium">Kamera</span>
                            </button>

                            <button id="eper_openFile" type="button" disabled
                                class="flex-1 lg:flex-none lg:w-12 lg:h-12 md:lg:w-15 md:lg:h-15 bg-white p-3 border-2 border-gray-300 rounded-xl flex items-center justify-center gap-2 lg:gap-0 hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="var(--blue)" class="w-5 h-5 md:w-6 md:h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                                </svg>
                                <span class="lg:hidden text-xs font-medium">File</span>
                            </button>

                            <div id="fileInfo"
                                class="hidden items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white">
                                <a id="fileLink" href="#" target="_blank"
                                    class="text-xs md:text-sm text-(--blue) underline max-w-32 md:max-w-37.5 truncate">
                                </a>
                                <button type="button" id="removeFile"
                                    class="text-red-500 hover:text-red-700 font-bold text-sm">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="EditPerbaikan.eper_reset(event)"
                                class="px-4 md:px-5 py-3 md:py-3 bg-red-300 text-gray-800 text-xs md:text-sm font-semibold rounded-lg hover:bg-red-400 transition shadow-sm">
                                Batal
                            </button>

                            <button type="submit" id="eper_btnSimpan" form="eper_form" disabled
                                class="px-4 md:px-5 py-3 md:py-3 bg-(--blue) text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Edit Data
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div id="loadingOverlay" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-1050">
            <div class="bg-white px-6 py-4 rounded-lg flex items-center gap-3 shadow-xl">
                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
                </svg>
                <span class="font-semibold text-gray-700">Menyimpan data...</span>
            </div>
        </div>

        {{-- Kamera --}}
        <div id="kameraWrapper"
            class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-1050
            px-4 sm:px-6 lg:px-10">

            <div class="bg-white p-4 rounded-lg w-200">
                <video id="videoKamera" autoplay playsinline class="w-full rounded"></video>

                <canvas id="canvasFoto" class="hidden"></canvas>

                <div class="flex gap-2 mt-3">
                    <button type="button" id="btnAmbilFoto" class="flex-1 bg-(--blue) text-white py-2 rounded-lg">
                        Ambil Foto
                    </button>

                    <button type="button" id="btnTutupKamera" class="flex-1 bg-gray-300 py-2 rounded-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        @vite(['resources/js/editperbaikan.js'])
        <script>
            window.authKaryawanId = {{ session('karyawan_id') }};
        </script>
    @endpush
