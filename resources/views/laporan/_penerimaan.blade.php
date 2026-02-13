<link rel="stylesheet" href="{{ asset('css/laporan-style/_penerimaan.css') }}">
<div class="preview-area">
    <div class="a4-page">

        {{-- HEADER --}}
        <table class="header-table">
            <tr>
                <td width="20%" align="center" valign="top">
                    <div class="header-logos">
                        <img src="{{ asset('images/logoiso.png') }}" class="logo-iso" alt="Logo ISO">
                        <img src="{{ asset('images/Logo-Genta.png') }}" class="logo-genta" alt="Logo Genta">
                    </div>
                </td>

                <td width="50%" align="center" valign="top">
                    <div class="header-title">
                        PT. GENTANUSA GEMILANG
                    </div>
                    <div class="header-subtitle">ROD REPAIR SHOP</div>
                    <div class="header-subtitle">
                        PENERIMAAN TANGKAI ANODA
                    </div>
                </td>

                <td width="30%" valign="top" rowspan="3">
                    <table class="tabel-ttd">
                        <tr>
                            <th colspan="3">Diperiksa</th>
                        </tr>
                        <tr>
                            <td>Foreman</td>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>As. Foreman</td>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>Checker</td>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>SEKSI</td>
                            <th>PT GNG</th>
                            <th>PT INALUM</th>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="3" valign="top">
                    Hari / Tanggal : {{ $tanggal ?? '-' }}
                </td>
            </tr>
            <tr>
                <td colspan="3" valign="top">
                    Shift / Tim : {{ $shift ?? '-' }} / {{ $tim ?? '-' }}
                </td>
            </tr>
        </table>

        {{-- TABLE DATA --}}
        <table class="tabel-data">

            {{-- KUNCI LAYOUT --}}
            <colgroup>
                <col style="width: 8mm">
                <col style="width: 22mm">
                <col style="width: 10mm">
                <col style="width: 8mm">

                <col style="width: 6mm" span="12">

                <col style="width: 12mm">
                <col style="width: 18mm">

                <col style="width: 16mm">
                <col style="width: 16mm">
            </colgroup>

            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Nomor ROD</th>
                    <th rowspan="3">Jenis</th>
                    <th rowspan="3" class="th-vertikal">Stasiun</th>

                    <th colspan="12">JENIS DAN JUMLAH KERUSAKAN</th>

                    <th rowspan="3">Total</th>
                    <th rowspan="3">Catatan</th>

                    <th colspan="2">Dibuat Oleh</th>
                </tr>

                <tr>
                    <th rowspan="2">E1</th>
                    <th rowspan="2">E2</th>
                    <th rowspan="2">E3</th>
                    <th rowspan="2">S</th>
                    <th rowspan="2">D</th>
                    <th rowspan="2">B</th>
                    <th rowspan="2">BA</th>
                    <th rowspan="2">R</th>
                    <th rowspan="2">M</th>
                    <th rowspan="2">CR</th>
                    <th rowspan="2">C</th>
                    <th rowspan="2">RL</th>

                    <th>Rodding</th>
                    <th>RRS</th>
                </tr>

                <tr>
                    <th>OP</th>
                    <th>OP</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $i => $row)
                    <tr>
                        <td class="cell-padding">{{ $i + 1 }}</td>
                        <td class="cell-padding">{{ $row->identitasRod->nomor_rod }}</td>
                        <td class="cell-padding">{{ $row->jenis }}</td>
                        <td class="cell-padding">{{ $row->stasiun }}</td>
                        <td class="cell-padding">{{ $row->e1 }}</td>
                        <td class="cell-padding">{{ $row->e2 }}</td>
                        <td class="cell-padding">{{ $row->e3 }}</td>
                        <td class="cell-padding">{{ $row->s }}</td>
                        <td class="cell-padding">{{ $row->d }}</td>
                        <td class="cell-padding">{{ $row->b }}</td>
                        <td class="cell-padding">{{ $row->ba }}</td>
                        <td class="cell-padding">{{ $row->r }}</td>
                        <td class="cell-padding">{{ $row->m }}</td>
                        <td class="cell-padding">{{ $row->cr }}</td>
                        <td class="cell-padding">{{ $row->c }}</td>
                        <td class="cell-padding">{{ $row->rl }}</td>
                        <td class="cell-padding">{{ $row->jumlah ?? '-' }}</td>
                        <td class="cell-padding">{{ $row->catatan }}</td>
                        <td class="cell-padding"></td>
                        <td class="cell-padding"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" class="empty-row">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- FOOTER --}}
        <div class="print-footer">
            @php
                $shifts = ['I', 'II', 'III'];
                $grouped = $data->groupBy('shift');
                $fields = ['e1', 'e2', 'e3', 's', 'd', 'b', 'ba', 'r', 'm', 'cr', 'c', 'rl'];
            @endphp

            <table class="tabel-footer">
                <tr>
                    <th colspan="2">Jenis</th>
                    <th>E1</th>
                    <th>E2</th>
                    <th>E3</th>
                    <th>S</th>
                    <th>D</th>
                    <th>B</th>
                    <th>BA</th>
                    <th>R</th>
                    <th>M</th>
                    <th>CR</th>
                    <th>C</th>
                    <th>RL</th>
                    <th>Total</th>
                </tr>

                @foreach ($shifts as $s)
                    <tr>
                        @if ($loop->first)
                            <td rowspan="3">Shift</td>
                        @endif

                        <td>{{ $s }}</td>

                        @php
                            $rowTotal = 0;
                        @endphp

                        @foreach ($fields as $f)
                            @php
                                $value = isset($grouped[$s]) ? $grouped[$s]->sum($f) : 0;
                                $rowTotal += $value;
                            @endphp
                            <td>{{ $value }}</td>
                        @endforeach

                        <td>{{ $rowTotal }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2">Jumlah</td>

                    @php $grandTotal = 0; @endphp

                    @foreach ($fields as $f)
                        @php
                            $value = $data->sum($f);
                            $grandTotal += $value;
                        @endphp
                        <td>{{ $value }}</td>
                    @endforeach

                    <td>{{ $grandTotal }}</td>
                </tr>

            </table>

        </div>

    </div>
</div>
