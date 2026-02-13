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
                    Butt Ratio & Man Power
                </h1>
            </div>
        </div>

        <input type="hidden" name="master_datetime" id="master_datetime">
        <input type="hidden" name="master_shift" id="master_shift">

        {{-- Main Grid --}}
        <div
            class="grid flex-1 min-h-0 grid-cols-1 lg:grid-cols-[2fr_3fr_3fr] gap-2 overflow-y-auto lg:overflow-hidden pb-20 lg:pb-0">

            {{-- Panel Input --}}
            <div
                class="row-span-2 bg-white p-4 md:p-7 border-2 border-gray-300 rounded-xl flex items-center justify-center shrink-0 lg:overflow-auto">

                <div class="border border-dashed border-gray-400 rounded-md p-3 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-left">

                        <div>
                            <label class="text-sm md:text-base">Tanggal</label>
                            <input type="date"
                                class="input-mini border-2 border-gray-400 rounded-md w-full mt-1 text-left text-sm md:text-base tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                        </div>

                        <div>
                            <label class="text-sm md:text-base">Shift</label>
                            <select
                                class="input-mini border-2 border-gray-400 rounded-md w-full mt-1 text-left text-sm md:text-base tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                <option value="">Semua Shift</option>
                                <option value="1">Shift 1</option>
                                <option value="2">Shift 2</option>
                                <option value="3">Shift 3</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm md:text-base">Butt Ratio</label>
                            <input type="number"
                                class="input-mini border-2 border-gray-400 rounded-md w-full mt-1 text-left text-sm md:text-base tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                                placeholder="4xxxx">
                        </div>

                        <div>
                            <label class="text-sm md:text-base">Man Power</label>
                            <input type="number"
                                class="input-mini border-2 border-gray-400 rounded-md w-full mt-1 text-left text-sm md:text-base tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition"
                                placeholder="4xxxx">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel Besar --}}
            <div
                class="lg:col-span-2 lg:row-span-2 bg-white p-3 border-2 border-gray-300 rounded-xl flex flex-col gap-3 shadow-sm shrink-0 pb-20 lg:pb-3 relative lg:min-h-0">

                {{-- Panel Pencarian Data --}}
                <div class="min-h-auto shrink-0">
                    <div class="flex flex-col md:flex-row gap-2 md:items-end md:justify-between">
                        <!-- Row 1: Date + Shift -->
                        <div class="flex gap-2">
                            <input type="date"
                                class="h-9 md:h-10 flex-1 md:w-56 border-2 border-gray-400 rounded-md px-3 text-left text-sm md:text-base tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition" />

                            <select
                                class="h-9 md:h-10 w-24 border-2 border-gray-400 rounded-md px-3 text-left text-sm md:text-base tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                                <option value="">Shift</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>

                        <!-- Row 2: Tombol Cari -->
                        <a href="#"
                            class="h-9 md:h-10 px-4 md:px-6 flex items-center justify-center bg-green-500 text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 tracking-wide focus:outline-none focus:ring-2 focus:ring-(--blue) focus:border-transparent transition">
                            Cari
                        </a>
                    </div>
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
                                        <th class="px-4 py-3">No</th>
                                        <th class="px-4 py-3">Tanggal Perbaikan</th>
                                        <th class="px-4 py-3">Shift</th>
                                        <th class="px-4 py-3">Butt Ratio</th>
                                        <th class="px-4 py-3">Man Power</th>
                                        <th class="px-4 py-3">Diubah</th>
                                        <th class="px-4 py-3">Penginput</th>
                                        <th class="px-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-300 text-xs md:text-sm">
                                    @php
                                        $users = ['Andi', 'Budi', 'Citra', 'Dewi', 'Rizky'];
                                    @endphp
                                    @for ($i = 1; $i <= 40; $i++)
                                        @php
                                            $tanggal = now()->subDays(rand(0, 7))->format('d-m-Y H:i:s');
                                            $shift = rand(1, 3);
                                            $diubah = now()->subDays(rand(0, 3))->format('d-m-Y H:i:s');
                                            $penginput = $users[array_rand($users)];
                                        @endphp

                                        <tr class="hover:bg-gray-200 text-center text-gray-900 whitespace-nowrap">
                                            <td class="px-3 md:px-4 py-2">{{ $i }}</td>
                                            <td class="px-3 md:px-4 py-2">{{ $tanggal }}</td>
                                            <td class="px-3 md:px-4 py-2">{{ $shift }}</td>
                                            <td class="px-3 md:px-4 py-2">{{ rand(0, 5) }}</td>
                                            <td class="px-3 md:px-4 py-2">{{ rand(0, 5) }}</td>
                                            <td class="px-3 md:px-4 py-2">{{ $diubah }}</td>
                                            <td class="px-3 md:px-4 py-2">{{ $penginput }}</td>
                                            <td class="px-3 md:px-4 py-2">
                                                <div class="flex items-center justify-center gap-1 md:gap-2">
                                                    <a href="#"
                                                        class="px-3 md:px-5 py-2 md:py-3 border border-(--blue) bg-white text-(--blue) text-xs md:text-sm font-semibold rounded-lg hover:bg-(--blue) hover:text-white transition">
                                                        Pilih
                                                    </a>

                                                    <a href="#"
                                                        class="px-3 md:px-5 py-2 md:py-3 bg-red-300 text-(--blue) text-xs md:text-sm font-semibold rounded-lg hover:bg-red-600 hover:text-white transition">
                                                        Hapus
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Panel Informasi Total Data --}}
                    <div
                        class="flex flex-col md:flex-row items-start md:items-center justify-between gap-2 md:gap-0 font-semibold text-xs md:text-sm min-h-auto md:min-h-15.5 shrink-0 border-t pt-2">
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-10">
                            <span class="text-xs md:text-sm text-(--blue) font-medium">
                                Halaman 1 dari 70
                            </span>
                            <h1 class="text-sm md:text-base font-semibold text-(--blue)">
                                Stock ROD Ready: 40
                            </h1>
                        </div>

                        <div class="flex items-center gap-3 md:gap-5">
                            <a href=""
                                class="w-10 md:w-15 bg-white p-2 md:p-3 border-2 border-gray-300 rounded-xl flex items-center justify-center hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="var(--blue)" class="size-5 md:size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </a>

                            <a href=""
                                class="w-10 md:w-15 bg-white p-2 md:p-3 border-2 border-gray-300 rounded-xl flex items-center justify-center hover:bg-gray-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="var(--blue)" class="size-5 md:size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Panel Eksekusi Data --}}
                <div
                    class="fixed lg:static bottom-0 left-0 right-0 lg:min-h-15 bg-white border-t-2 lg:border-t-0 border-gray-300 lg:border-none p-4 lg:p-0 z-30 lg:z-auto shadow-lg lg:shadow-none">
                    <div class="flex justify-end gap-2">
                        <a href="#"
                            class="px-4 md:px-5 py-3 md:py-3 bg-red-500 text-white text-xs md:text-sm font-semibold rounded-lg hover:bg-red-400 transition shadow-sm">
                            Batal
                        </a>
                        <a href="#"
                            class="px-4 md:px-5 py-3 md:py-3 bg-(--blue) text-white text-xs md:text-sm font-semibold rounded-lg hover:opacity-90 transition shadow-sm">
                            Simpan Data
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
