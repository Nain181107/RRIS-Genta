<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Penerimaan Tangkai Anoda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 8px;
        }

        /* ===== HEADER ===== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .header-table td {
            padding: 2px;
            vertical-align: top;
            font-size: 8px;
        }

        .logo-iso {
            width: 40px;
            height: auto;
        }

        .logo-genta {
            width: 40px;
            height: auto;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-bottom: 1px;
            text-align: center;
        }

        .header-subtitle {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
        }

        /* ===== TABLE TTD ===== */
        .tabel-ttd {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
        }

        .tabel-ttd th {
            border: 1px solid #000;
            padding: 2px 1px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tabel-ttd td {
            border: 1px solid #000;
            padding: 2px;
            text-align: left;
            vertical-align: middle;
            font-size: 6.5px;
        }

        /* ===== TABLE DATA ===== */
        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tabel-data th {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 6.5px;
            font-weight: bold;
            line-height: 1;
        }

        .tabel-data td {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 6px;
        }

        /* ===== FOOTER ===== */
        .print-footer {
            margin-top: 6px;
        }

        .tabel-footer {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tabel-footer th {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 6.5px;
        }

        .tabel-footer td {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 6px;
        }

        @page {
            size: A4 portrait;
            margin: 6mm 4mm;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td width="12%" align="center">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50%" align="right">
                            <img src="{{ public_path('images/logoiso.png') }}" class="logo-iso">
                        </td>
                        <td width="50%" align="left">
                            <img src="{{ public_path('images/Logo-Genta.png') }}" class="logo-genta">
                        </td>
                    </tr>
                </table>
            </td>

            <td width="58%" align="center">
                <div class="header-title">PT. GENTANUSA GEMILANG</div>
                <div class="header-subtitle">ROD REPAIR SHOP</div>
                <div class="header-subtitle">PENERIMAAN TANGKAI ANODA</div>
            </td>

            <td width="30%" rowspan="3" style="vertical-align: top;">
                <table class="tabel-ttd">
                    <tr>
                        <th colspan="3">Diperiksa</th>
                    </tr>
                    <tr>
                        <td style="width: 28%;">Foreman</td>
                        <th style="width: 36%;"></th>
                        <th style="width: 36%;"></th>
                    </tr>
                    <tr>
                        <td>As.<br>Foreman</td>
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
                        <th>PT<br>GNG</th>
                        <th>PT<br>INALUM</th>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding: 1px 2px;">
                Hari / Tanggal : {{ $tanggal ?? '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 1px 2px;">
                Shift / Tim : {{ $shift ?? '-' }} / {{ $tim ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- TABLE DATA --}}
    <table class="tabel-data">
        <colgroup>
            <col style="width: 2.2%;">
            <col style="width: 7.5%;">
            <col style="width: 3.2%;">
            <col style="width: 3.2%;">
            <col style="width: 2.5%;" span="12">
            <col style="width: 3.2%;">
            <col style="width: 6.5%;">
            <col style="width: 5.5%;">
            <col style="width: 5.5%;">
        </colgroup>

        <thead>
            <tr>
                <th rowspan="3">No</th>
                <th rowspan="3">Nomor ROD</th>
                <th rowspan="3">Jenis</th>
                <th rowspan="3">Stasiun</th>
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
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->identitasRod->nomor_rod }}</td>
                    <td>{{ $row->jenis }}</td>
                    <td>{{ $row->stasiun }}</td>
                    <td>{{ $row->e1 }}</td>
                    <td>{{ $row->e2 }}</td>
                    <td>{{ $row->e3 }}</td>
                    <td>{{ $row->s }}</td>
                    <td>{{ $row->d }}</td>
                    <td>{{ $row->b }}</td>
                    <td>{{ $row->ba }}</td>
                    <td>{{ $row->r }}</td>
                    <td>{{ $row->m }}</td>
                    <td>{{ $row->cr }}</td>
                    <td>{{ $row->c }}</td>
                    <td>{{ $row->rl }}</td>
                    <td>{{ $row->jumlah ?? '-' }}</td>
                    <td>{{ $row->catatan }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="20" style="padding: 8px; color: #999;">Data tidak ditemukan</td>
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
            <colgroup>
                <col style="width: 6.5%;">
                <col style="width: 4.8%;" span="12">
                <col style="width: 5.5%;">
            </colgroup>

            <tr>
                <th>Jenis</th>
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
                    <td style="font-weight: bold;">Shift {{ $s }}</td>

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
                <td style="font-weight: bold;">Jumlah</td>

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

</body>

</html>
