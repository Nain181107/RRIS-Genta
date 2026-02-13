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
                    Pengiriman
                </h1>
            </div>
        </div>

        <form id="formPengiriman" method="POST" action="{{ route('pengiriman.store') }}">
            @csrf
            <input type="hidden" name="master_datetime" id="master_datetime">
            <input type="hidden" name="master_shift" id="master_shift">
        </form>

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
            class="grid flex-1 min-h-0 grid-cols-1 lg:grid-cols-[2fr_3fr_3fr] gap-2 overflow-y-auto lg:overflow-hidden pb-20 lg:pb-0">

            {{-- Panel Input --}}
            <div
                class="row-span-2 bg-white p-7 border-2 border-gray-300 rounded-xl flex flex-col gap-4 shrink-0 lg:overflow-auto">

                <h3 class="text-base md:text-lg flex justify-center text-center font-semibold text-gray-700">
                    Masukkan Atau Pilih Nomor ROD
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-1 gap-3 text-left">
                    <div>
                        <input type="text" name="rod1" form="formPengiriman" tabindex="1"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod2" form="formPengiriman" tabindex="2"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod3" form="formPengiriman" tabindex="3"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod4" form="formPengiriman" tabindex="4"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod5" form="formPengiriman" tabindex="5"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod6" form="formPengiriman" tabindex="6"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod7" form="formPengiriman" tabindex="7"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod8" form="formPengiriman" tabindex="8"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod9" form="formPengiriman" tabindex="9"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>

                    <div>
                        <input type="text" name="rod10" form="formPengiriman" tabindex="10"
                            class="rod-input px-5 py-5 input-mini w-full mt-1 border-gray-400 rounded focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                            placeholder="A4xxxx" onblur="validasiRod(this)">
                    </div>
                </div>
            </div>

            {{-- Panel Besar --}}
            <div
                class="lg:col-span-2 lg:row-span-2 bg-white p-3 border-2 border-gray-300 rounded-xl flex flex-col gap-3 shadow-sm shrink-0 pb-20 lg:pb-3 relative lg:min-h-0">

                {{-- Panel Pencarian Data --}}
                <div class="relative min-h-9 md:min-h-10 shrink-0">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                    </svg>
                    <input type="text" id="cariperbaikan" value="{{ request('cariperbaikan') }}" tabindex="11"
                        class="h-9 md:h-10 w-full border-2 border-gray-400 uppercase rounded-md pl-9 md:pl-10 pr-3 py-1 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                        placeholder="Cari Berdasarkan Nomor ROD" />
                </div>

                {{-- Panel Table dan Informasi --}}
                <div class="flex-1 min-h-0 flex flex-col border-2 border-gray-300 rounded-lg p-2">

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
                                        <th class="px-3 md:px-4 py-3 md:py-3">Tanggal Perbaikan</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Shift</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Jenis</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E1 Ers</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E1 Est</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E1 Jumlah</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E2 Ers</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E2 Cst</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E2 Cstub</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E2 Jumlah</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E3</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">E4</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">S</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">D</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">B</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">BAC</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">NBA</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">BA</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">BA-1</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">R</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">M</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">CR</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">C</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">RL</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Jumlah</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Catatan</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Tanggal Penerimaan</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Diubah</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Penginput</th>
                                        <th class="px-3 md:px-4 py-3 md:py-3">Tim</th>
                                    </tr>
                                </thead>
                                <tbody id="perbaikan-body" class="divide-y divide-gray-300 text-xs md:text-sm">
                                    @include('entry_data.partials.pengiriman.perbaikan-table-body')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Panel Informasi Total Data --}}
                    <div id="perbaikan-info" class="shrink-0 border-t pt-2">
                        @include('entry_data.partials.pengiriman.perbaikan-info', [
                            'perbaikan' => $perbaikan,
                        ])
                    </div>
                </div>

                {{-- Panel Eksekusi Data --}}
                <div
                    class="fixed lg:static bottom-0 left-0 right-0 lg:min-h-15 bg-white border-t-2 lg:border-t-0 border-gray-300 lg:border-none p-4 lg:p-0 z-30 lg:z-auto shadow-lg lg:shadow-none">
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="resetPerbaikan(event)" tabindex="13"
                            class="px-4 md:px-5 py-3 md:py-3 bg-red-300 text-gray-800 text-xs md:text-sm font-semibold rounded-lg hover:bg-red-400 transition shadow-sm">
                            Batal
                        </button>
                        <button type="submit" form="formPengiriman" id="btnSimpan" tabindex="12"
                            class="px-4 md:px-5 py-3 md:py-3 bg-(--blue) text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 transition shadow-sm">
                            Simpan Data
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
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
            </svg>
            <span class="font-semibold text-gray-700">Menyimpan data...</span>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pengiriman.js'])
    <script>
        window.authKaryawanId = {{ session('karyawan_id') }};
    </script>
@endpush
