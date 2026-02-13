@extends('master.master')

@section('konten')
    <div class="flex-1 h-full flex flex-col overflow-hidden">

        {{-- Header --}}
        <div class="h-13 mb-2 flex items-center p-2 justify-between font-semibold text-sm">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-semibold text-(--blue)">
                    Form Laporan
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <select id="jenisLaporan" name="jenisLaporan"
                    class="h-10 rounded-xl border border-gray-400 bg-gray-50 px-4 text-sm text-gray-800 shadow transition-all duration-200
                            hover:bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 focus:outline-none ">
                    <option value="" selected>Pilih Data Yang Ingin Di Cetak</option>
                    <option value="penerimaan">Penerimaan</option>
                    <option value="perbaikan">Perbaikan</option>
                    <option value="pengiriman">Pengiriman</option>
                    <option value="buktiperubahan">Bukti Perubahan</option>
                </select>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="flex-1 min-h-0">

            {{-- Panel Besar --}}
            <div class="h-full bg-white p-3 border-2 border-gray-300 rounded-xl flex flex-col min-h-0 gap-3">

                <form id="formCetak" class="w-full flex items-center justify-between gap-2">

                    {{-- Panel Pencarian Data --}}
                    <div
                        class="min-h-10 shrink-0 flex items-center justify-between bg-white border-2 border-gray-300 rounded-xl px-4 gap-2">

                        <!-- KIRI : FILTER INPUT -->
                        <div class="flex items-center gap-3 flex-nowrap overflow-x-auto p-3">

                            <input type="date" name="tanggal" id="tanggal"
                                class=" h-10 w-56 rounded-lg border border-gray-400 bg-white px-3 text-sm text-gray-700 shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:outline-none hover:border-gray-400">

                            <select name="shift" id="shift"
                                class="h-10 w-40 rounded-lg border border-gray-400 bg-gray-50 px-4 text-sm text-gray-800 shadow transition-all duration-200
                            hover:bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 focus:outline-none ">
                                <option value="" selected>Shift</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>

                            <select name="tim" id="tim"
                                class="h-10 w-40 rounded-lg border border-gray-400 bg-gray-50 px-4 text-sm text-gray-800 shadow 
                            transition-all duration-200 hover:bg-white focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 focus:outline-none">
                                <option value="" selected>Tim</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>

                        </div>

                        <div class="flex items-center gap-2 shrink-0">

                            <button type="button" id="btnCetak"
                                class="h-9 px-5 bg-(--blue) text-white text-sm font-medium rounded-md">
                                Previous Data
                            </button>

                            <button type="button" onclick="printLaporan()"
                                class="h-9 px-5 bg-green-600 text-white text-sm font-medium rounded-md">
                                Print / PDF
                            </button>

                            <button
                                class="h-9 px-5 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                                Reset
                            </button>

                        </div>
                    </div>
                </form>

                <div id="areaPrint" class="flex-1 bg-gray-200 overflow-auto p-4">

                    <div id="previewWrapper" class="flex justify-center">
                        <div id="hasilCetak">
                            <!-- isi laporan -->
                        </div>
                    </div>

                </div>


                {{-- Panel Informasi Total Data --}}
                <div class="flex items-center justify-center min-h-11 shrink-0">
                    <h1 class="text-base font-semibold text-(--blue)">
                        Jumlah Data: 0
                    </h1>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const btn = document.getElementById('btnCetak');

            if (!btn) {
                console.log('btnCetak tidak ditemukan');
                return;
            }

            btn.addEventListener('click', function() {

                let jenis = document.getElementById('jenisLaporan').value;

                if (!jenis) {
                    alert('Pilih jenis laporan dulu');
                    return;
                }

                let formData = new FormData(document.getElementById('formCetak'));
                formData.append('jenis', jenis);

                fetch("{{ route('laporan.preview') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('hasilCetak').innerHTML = html;
                    })
                    .catch(err => {
                        console.log(err);
                        alert('Preview gagal. Cek console.');
                    });

            });

        });

        function printLaporan() {

            const form = document.getElementById('formCetak');
            const formData = new FormData(form);

            const tanggal = formData.get('tanggal');
            const shift = formData.get('shift');
            const tim = formData.get('tim');

            let url = `/laporan/pdf?tanggal=${tanggal}&shift=${shift}&tim=${tim}`;

            window.location.href = url;
        }
    </script>
@endsection
