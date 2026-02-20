<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Bukti Perubahan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #000;
            background: white;
            font-size: 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .a4-page {
            width: 287mm;
            padding: 0;
            margin: 0 auto;
        }

        /* ===== SETIAP HALAMAN ===== */
        .halaman {
            width: 100%;
        }

        .halaman+.halaman {
            page-break-before: always;
        }

        /* ===== HEADER ===== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .header-table td {
            padding: 0;
            vertical-align: middle;
        }

        .cell-logo {
            width: 16%;
            text-align: left;
            padding: 4px 3px;
            vertical-align: middle;
            white-space: nowrap;
        }

        .cell-logo img {
            display: inline-block;
            vertical-align: middle;
            height: 40px;
            width: auto;
        }

        .cell-title {
            width: 48%;
            text-align: center;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .title-main {
            font-size: 23px;
            font-weight: bold;
            color: #00029a;
        }

        .title-sub {
            font-size: 15px;
            font-weight: bold;
            margin-top: 2px;
        }

        .cell-ttd {
            width: 15%;
        }

        /* ===== INFO ROWS ===== */
        .info-table {
            width: 100%;
            font-size: 12px;
        }

        .info-table td {
            padding: 2px 5px;
        }

        .info-table .doc-num {
            text-align: right;
            font-size: 7px;
            font-style: italic;
            color: #444;
            vertical-align: bottom;
        }

        /* ===== DATA TABLE ===== */
        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
            margin-top: 4px;
        }

        .tabel-data thead th {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            font-weight: bold;
            line-height: 2;
        }

        .tabel-data tbody td {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
            height: 35px;
        }

        .tabel-data tbody tr.total-row td {
            border: 1.5px solid #000;
            font-weight: bold;
        }

        .td-left {
            text-align: left;
            padding-left: 3px;
        }

        /* Vertical header */
        .th-v {
            writing-mode: vertical-rl;
            transform: rotate(270deg);
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            font-weight: bold;
            line-height: 1.2;
        }

        /* Kolom foto */
        .td-foto {
            width: 40mm;
            height: 80px;
            vertical-align: middle;
            text-align: center;
        }

        .td-foto img {
            max-width: 38mm;
            max-height: 70px;
            object-fit: contain;
        }

        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        @media print {

            html,
            body {
                width: 297mm;
                height: 210mm;
            }

            .a4-page {
                width: 100%;
                margin: 0;
            }

            .halaman+.halaman {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    <div class="a4-page">

        @php
            $perPage = 8;
            $chunks = $data->chunk($perPage);
        @endphp

        @foreach ($chunks as $pageIndex => $chunk)
            @php
                $startNo = $pageIndex * $perPage + 1;
            @endphp

            <div class="halaman">

                {{-- HEADER --}}
                <table class="header-table">
                    <tr>
                        <td class="cell-logo">
                            <img src="{{ public_path('images/logoiso.png') }}" alt="ISO">
                            <img src="{{ public_path('images/Logo-Genta.png') }}" alt="Genta" style="margin-left:3px;">
                        </td>
                        <td class="cell-title">
                            <div class="title-main">PT. GENTANUSA GEMILANG</div>
                            <div class="title-sub">ROD REPAIR SHOP</div>
                            <div class="title-sub">BUKTI PERUBAHAN PERBAIKAN TANGKAI ANODA</div>
                        </td>
                        <td class="cell-ttd">
                        </td>
                    </tr>
                </table>

                {{-- INFO --}}
                <table class="info-table">
                    <tr>
                        <td style="width:65%;">Hari/Tanggal &nbsp;: &nbsp;{{ $tanggal ?? '' }}</td>
                        <td class="doc-num" rowspan="2">RRS/05072025/FORM 1/REV-0</td>
                    </tr>
                    <tr>
                        <td>Shift/Team &nbsp;: &nbsp;{{ $shift ?? '' }} / {{ $tim ?? '' }}</td>
                    </tr>
                </table>

                {{-- DATA TABLE --}}
                <table class="tabel-data">
                    <thead>
                        <tr>
                            <th rowspan="3" style="width:3mm;">NO</th>
                            <th rowspan="3" style="width:17mm;">NOMOR ROD</th>
                            <th rowspan="3" style="width:6mm;max-width:6mm;overflow:hidden;">
                                <p class="th-v">JENIS PERBAIKAN</p>
                            </th>
                            <th colspan="19">JENIS DAN TOTAL KERUSAKAN</th>
                            <th rowspan="3" style="width:8mm;">TOTAL</th>
                            <th rowspan="3" style="width:15mm;">TANGGAL PERBAIKAN</th>
                            <th rowspan="3" style="width:20mm;">Catatan</th>
                            <th rowspan="3" style="width:40mm;">Penginput</th>
                            <th rowspan="3">Foto Bukti</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="width:8mm;">E1</th>
                            <th colspan="4" style="width:8mm;">E2</th>
                            <th rowspan="2" style="width:5mm;">E3</th>
                            <th rowspan="2" style="width:5mm;">E4</th>
                            <th rowspan="2" style="width:5mm;">S</th>
                            <th rowspan="2" style="width:5mm;">D</th>
                            <th rowspan="2" style="width:5mm;">B</th>
                            <th rowspan="2" style="width:5mm;">BA</th>
                            <th rowspan="2" style="width:5mm;">BA-1</th>
                            <th rowspan="2" style="width:5mm;">R</th>
                            <th rowspan="2" style="width:5mm;">M</th>
                            <th rowspan="2" style="width:5mm;">CR</th>
                            <th rowspan="2" style="width:5mm;">C</th>
                            <th rowspan="2" style="width:5mm;">RL</th>
                        </tr>
                        <tr>
                            <th style="width:5mm;">Ers</th>
                            <th style="width:5mm;">Est</th>
                            <th style="width:5mm;">Total</th>
                            <th style="width:5mm;">Ers</th>
                            <th style="width:5mm;">Cst</th>
                            <th style="width:5mm;">Cstub</th>
                            <th style="width:5mm;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chunk as $i => $row)
                            @php
                                $nomorAlign = 'text-align:center;';
                            @endphp
                            <tr>
                                <td>{{ $startNo++ }}</td>
                                <td style="{{ $nomorAlign }}">{{ $row->identitasRod->nomor_rod ?? '-' }}</td>
                                <td>{{ $row->jenis }}</td>
                                <td>{{ $row->e1_ers ?: '' }}</td>
                                <td>{{ $row->e1_est ?: '' }}</td>
                                <td>{{ $row->e1_jumlah ?: '' }}</td>
                                <td>{{ $row->e2_ers ?: '' }}</td>
                                <td>{{ $row->e2_cst ?: '' }}</td>
                                <td>{{ $row->e2_cstub ?: '' }}</td>
                                <td>{{ $row->e2_jumlah ?: '' }}</td>
                                <td>{{ $row->e3 ?: '' }}</td>
                                <td>{{ $row->e4 ?: '' }}</td>
                                <td>{{ $row->s ?: '' }}</td>
                                <td>{{ $row->d ?: '' }}</td>
                                <td>{{ $row->b ?: '' }}</td>
                                <td>
                                    @if (($row->bac ?? 0) > 0)
                                        cek
                                    @elseif(($row->nba ?? 0) > 0)
                                        {{ $row->nba }}
                                    @else
                                        {{ $row->ba ?: '' }}
                                    @endif
                                </td>
                                <td>{{ $row->ba1 ?: '' }}</td>
                                <td>{{ $row->r ?: '' }}</td>
                                <td>{{ $row->m ?: '' }}</td>
                                <td>{{ $row->cr ?: '' }}</td>
                                <td>{{ $row->c ?: '' }}</td>
                                <td>{{ $row->rl ?: '' }}</td>
                                <td style="font-weight:bold;">{{ $row->jumlah ?: '' }}</td>
                                <td>{{ $row->tanggal_perbaikan ? \Carbon\Carbon::parse($row->tanggal_perbaikan)->format('d/m/Y') : '' }}
                                </td>
                                <td>{{ $row->catatan }}</td>
                                <td>{{ $row->karyawan->nama_lengkap ?? '' }}</td>
                                <td class="td-foto">
                                    @if ($row->fotobuktiperubahan)
                                        <img src="{{ public_path('storage/' . $row->fotobuktiperubahan) }}"
                                            alt="Foto">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        @endforeach

    </div>
</body>

</html>
