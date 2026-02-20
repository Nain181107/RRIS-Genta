@extends('master.master')

@section('konten')
    <div class="flex-1 h-full flex flex-col overflow-hidden">

        {{-- Header --}}
        <div
            class="h-auto md:h-13 mb-1 flex flex-col md:flex-row items-start md:items-center p-2 md:p-3 justify-between shadow-[0_1px_3px_rgba(0,0,0,0.1)]">
            <div class="flex items-center gap-2">
                <span class="text-xs md:text-sm text-gray-400 font-medium">
                    Entry Data /
                </span>
                <h1 class="text-lg md:text-xl font-semibold text-(--blue)">
                    Perbaikan
                </h1>
            </div>
        </div>

        {{-- form input data --}}
        <form id="formPerbaikan" method="POST" action="{{ route('perbaikan.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="master_datetime" id="master_datetime">
            <input type="hidden" name="master_shift" id="master_shift">
            <input type="hidden" name="penerimaan_id" id="penerimaan_id" value="{{ old('penerimaan_id') }}">
            <input type="hidden" name="tanggal_penerimaan" id="tanggal_penerimaan" value="{{ old('tanggal_penerimaan') }}">
            <input type="file" id="inputFoto" name="fotobuktiperubahan" accept="image/*" hidden>
        </form>

        {{-- notifikasi input --}}
        @if (session('warning') || session('success'))
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

                    <div class="flex justify-end">
                        <button onclick="closeNotifModal()"
                            class="px-3 md:px-4 py-2 text-sm rounded bg-(--blue) text-white hover:opacity-90">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Grid --}}
        <div
            class="grid flex-1 min-h-0 grid-cols-1 lg:grid-cols-[2fr_3fr_3fr] gap-2 overflow-y-auto lg:overflow-hidden pb-35 lg:pb-0">

            {{-- Panel Input --}}
            <input type="hidden" id="formUnlocked" value="{{ old('penerimaan_id') ? '1' : '0' }}">
            <div
                class="lg:row-span-3 bg-white p-3 md:p-4 border-2 border-gray-300 rounded-xl flex flex-col gap-3 md:gap-4 shrink-0 lg:overflow-auto">

                {{-- Data Wajib --}}
                <div class="border-2 border-gray-300 rounded-xl p-3 md:p-4 space-y-2 md:space-y-3 shadow-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Nomor ROD</label>
                        <input type="text" name="nomor_rod" form="formPerbaikan" id="f_nomor_rod" tabindex="1"
                            value="{{ old('nomor_rod') }}" readonly
                            class="h-9 md:h-10 sm:col-span-2 border-2 border-gray-400 rounded-md px-3 py-1 text-sm md:text-base uppercase tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 items-center gap-2 md:gap-3">
                        <label class="text-sm md:text-base text-(--blue) font-medium">Jenis</label>
                        <input type="text" name="jenis" form="formPerbaikan" id="f_jenis" value="{{ old('jenis') }}"
                            tabindex="2" readonly
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
                                    <input type="number" name="bac" form="formPerbaikan" value="{{ old('bac') }}"
                                        tabindex="3" readonly
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Nba</span>
                                    <input type="number" name="nba" value="{{ old('nba') }}" form="formPerbaikan"
                                        tabindex="4" readonly
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center justify-center font-medium">
                                    <span class="text-xs md:text-sm">Jumlah</span>
                                    <span class="text-sm md:text-base">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- E1 -->
                        <div class="border border-dashed border-gray-400 rounded-md p-2 md:p-3">
                            <div class="text-xs md:text-sm font-semibold mb-2">E1</div>
                            <div class="grid grid-cols-3 gap-2">

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Ers</span>
                                    <input type="number" name="e1_ers" value="{{ old('e1_ers') }}" form="formPerbaikan"
                                        tabindex="5" readonly
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Est</span>
                                    <input type="number" name="e1_est" value="{{ old('e1_est') }}"
                                        form="formPerbaikan" tabindex="6" readonly
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center justify-center font-medium">
                                    <span class="text-xs md:text-sm">Jumlah</span>
                                    <span class="text-sm md:text-base">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- E2 -->
                        <div class="border border-dashed border-gray-400 rounded-md p-2 md:p-3">
                            <div class="text-xs md:text-sm font-semibold mb-2">E2</div>
                            <div class="grid grid-cols-4 gap-2">

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Ers</span>
                                    <input type="number" name="e2_ers" value="{{ old('e2_ers') }}" tabindex="7"
                                        form="formPerbaikan" readonly
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Cst</span>
                                    <input type="number" name="e2_cst" value="{{ old('e2_cst') }}" tabindex="8"
                                        form="formPerbaikan" readonly
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs md:text-sm">Cstub</span>
                                    <input type="number" name="e2_cstub" value="{{ old('e2_cstub') }}" tabindex="9"
                                        form="formPerbaikan" readonly
                                        class="input-mini w-full h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                </div>

                                <div class="flex flex-col items-center justify-center font-medium">
                                    <span class="text-xs md:text-sm">Jumlah</span>
                                    <span class="text-sm md:text-base">-</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- === BAGIAN BAWAH === -->
                    <div class="border border-dashed border-gray-400 rounded-md p-2 md:p-3">
                        <div class="grid grid-cols-3 gap-2 md:gap-1 text-center">

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">E3</label>
                                <input type="number" name="e3" form="formPerbaikan" value="{{ old('e3') }}"
                                    tabindex="10" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">B</label>
                                <input type="number" name="b" form="formPerbaikan" value="{{ old('b') }}"
                                    tabindex="14" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">CR</label>
                                <input type="number" name="cr" form="formPerbaikan" value="{{ old('cr') }}"
                                    tabindex="18" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">E4</label>
                                <input type="number" name="e4" form="formPerbaikan" value="{{ old('e4') }}"
                                    tabindex="11" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">BA-1</label>
                                <input type="number" name="ba1" form="formPerbaikan" value="{{ old('ba1') }}"
                                    tabindex="15" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">C</label>
                                <input type="number" name="c" form="formPerbaikan" value="{{ old('c') }}"
                                    tabindex="19" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">S</label>
                                <input type="number" name="s" form="formPerbaikan" value="{{ old('s') }}"
                                    tabindex="12" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">R</label>
                                <input type="number" name="r" form="formPerbaikan" value="{{ old('r') }}"
                                    tabindex="16" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">RL</label>
                                <input type="number" name="rl" form="formPerbaikan" value="{{ old('rl') }}"
                                    tabindex="20" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">D</label>
                                <input type="number" name="d" form="formPerbaikan" value="{{ old('d') }}"
                                    tabindex="13" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="text-xs md:text-sm font-medium text-gray-600">M</label>
                                <input type="number" name="m" form="formPerbaikan" value="{{ old('m') }}"
                                    tabindex="17" readonly
                                    class="input-mini w-full mt-1 h-8 md:h-9 text-sm border-2 border-gray-300 rounded px-2 focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Data Total --}}
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
                <textarea name="catatan" form="formPerbaikan" rows="2" readonly
                    class="w-full border-2 border-gray-400 rounded-md px-3 py-2 text-sm md:text-base uppercase focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition resize-none"
                    placeholder="Masukkan catatan...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Panel Besar - With scrollable content and floating buttons on mobile --}}
            <div
                class="lg:col-span-2 lg:row-span-2 bg-white p-3 border-2 border-gray-300 rounded-xl flex flex-col gap-3 shadow-sm shrink-0 pb-20 lg:pb-3 relative lg:min-h-0">

                {{-- Panel Pencarian Data --}}
                <div class="relative min-h-9 md:min-h-10 shrink-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                    </svg>
                    <input type="text" id="caripenerimaan" value="{{ request('caripenerimaan') }}"
                        class="h-9 md:h-10 w-full border-2 border-gray-400 uppercase rounded-md pl-9 md:pl-10 pr-3 py-1 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                        tabindex="22" placeholder="Cari Berdasarkan Nomor ROD" />
                </div>

                {{-- Panel Table dan Informasi --}}
                <div class="flex-1 flex flex-col border-2 border-gray-300 rounded-lg p-2 gap-2 min-h-0">

                    {{-- Panel Table --}}
                    <div class="flex-1 overflow-auto min-h-0 max-h-80 lg:max-h-full">
                        <div class="rounded-lg overflow-auto h-full">
                            <table class="w-full table-auto border-collapse">
                                <thead
                                    class="bg-(--blue) sticky top-0 z-10 text-(--whitesmoke) text-center text-xs md:text-sm font-semibold border-b whitespace-nowrap">
                                    <tr>
                                        <th class="px-3 md:px-4 py-3 md:py-3">No</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Aksi</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Nomor ROD</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Tanggal Penerimaan</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Shift</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Jenis</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Stasiun</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E1</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E2</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E3</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">S</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">D</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">B</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">BA</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">R</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">M</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">CR</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">C</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">RL</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Jumlah</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Catatan</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Diubah</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Penginput</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Tim</th>
                                    </tr>
                                </thead>
                                <tbody id="perbaikan-body" class="divide-y divide-gray-300 text-xs md:text-sm">
                                    @include('entry_data.partials.perbaikan.penerimaan-table-body')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Panel Informasi Total Data --}}
                    <div id="perbaikan-info" class="shrink-0 border-t pt-2">
                        @include('entry_data.partials.perbaikan.penerimaan-info', [
                            'penerimaan' => $penerimaan,
                        ])
                    </div>

                </div>

                {{-- Floating Buttons on Mobile, Fixed Position on Desktop --}}
                <div
                    class="fixed lg:static bottom-0 left-0 right-0 lg:min-h-15 bg-white border-t-2 lg:border-t-0 border-gray-300 lg:border-none p-4 lg:p-0 z-30 lg:z-auto shadow-lg lg:shadow-none">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 lg:gap-0">

                        {{-- Left side: Camera and File buttons --}}
                        <div class="flex gap-2 justify-start">

                            <div class="flex justify-between gap-2">
                                <button id="openKamera" type="button" disabled
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

                                <button id="openFile" type="button" disabled
                                    class="flex-1 lg:flex-none lg:w-12 lg:h-12 md:lg:w-15 md:lg:h-15 bg-white p-3 border-2 border-gray-300 rounded-xl flex items-center justify-center gap-2 lg:gap-0 hover:bg-gray-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="var(--blue)" class="w-5 h-5 md:w-6 md:h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                                    </svg>
                                    <span class="lg:hidden text-xs font-medium">File</span>
                                </button>
                            </div>

                            <div id="fileInfo"
                                class="hidden items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white flex">
                                <a id="fileLink" href="#" target="_blank"
                                    class="text-xs md:text-sm text-(--blue) underline truncate block max-w-25 sm:max-w-40 md:max-w-45 lg:max-w-50 ">
                                </a>
                                <button type="button" id="removeFile"
                                    class="text-red-500 hover:text-red-700 font-bold text-sm">
                                    ✕
                                </button>
                            </div>
                        </div>

                        {{-- Right side: Action buttons --}}
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="batalForm()" tabindex="24"
                                class="px-4 md:px-5 py-3 md:py-3 bg-red-300 text-gray-800 text-xs md:text-sm font-semibold rounded-lg hover:bg-red-400 transition shadow-sm">
                                Batal
                            </button>

                            <button type="submit" form="formPerbaikan" id="btnSimpan" tabindex="23" disabled
                                class="px-4 md:px-5 py-3 md:py-3 bg-(--blue) text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 transition shadow-sm">
                                Simpan Data
                            </button>
                        </div>

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

    {{-- Kamera --}}
    <div id="kameraWrapper"
        class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-1050 px-4 sm:px-6 lg:px-10">

        <div class="bg-white p-4 rounded-lg w-full max-w-md md:max-w-lg lg:w-200">
            <video id="videoKamera" autoplay playsinline class="w-full rounded"></video>

            <canvas id="canvasFoto" class="hidden"></canvas>

            <div class="flex gap-2 mt-3">
                <button type="button" id="btnAmbilFoto"
                    class="flex-1 bg-(--blue) text-white py-2 text-sm md:text-base rounded-lg hover:opacity-90 transition">
                    Ambil Foto
                </button>

                <button type="button" id="btnTutupKamera"
                    class="flex-1 bg-gray-300 py-2 text-sm md:text-base rounded-lg hover:bg-gray-400 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/perbaikan.js'])
    <script>
        window.authKaryawanId = {{ session('karyawan_id') }};
        window.penerimaanKeyword = "{{ request('caripenerimaan') }}";
    </script>
@endpush
