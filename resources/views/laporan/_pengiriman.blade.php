<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pengiriman</title>
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
            width: 200mm;
            padding: 0;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
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
            text-align: center;
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
            width: 36%;
            border: 1.5px solid #000;
        }

        /* ===== TTD ===== */
        .tabel-ttd {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .tabel-ttd td {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: middle;
        }

        .tabel-ttd td:first-child {
            border-left: none;
        }

        .tabel-ttd tr:first-child td {
            border-top: none;
        }

        .tabel-ttd tr:last-child td {
            border-bottom: none;
        }

        .tabel-ttd td:last-child {
            border-right: none;
        }

        .ttd-lbl {
            font-weight: bold;
            width: 35%;
            white-space: nowrap;
        }

        .ttd-hdr {
            text-align: center;
            font-weight: bold;
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
            padding: 2px 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            font-weight: bold;
            line-height: 1.2;
        }

        .tabel-data tbody td {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
            height: 18px;
        }

        .td-no {
            width: 6mm;
        }

        .td-rod {
            width: 25mm;
            text-align: left;
            padding-left: 4px;
        }

        /* ===== PAGE BREAK ===== */
        .page-break {
            page-break-before: always;
        }

        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }

            .a4-page {
                width: 100%;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="a4-page">

        @php
            $perKolom = 30; // baris per kolom
            $perHalaman = 120; // total rod per halaman (4 kolom x 30)
            $chunks = $data->chunk($perHalaman);
        @endphp

        @foreach ($chunks as $pageIndex => $chunk)
            @if ($pageIndex > 0)
                <div class="page-break">
            @endif

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
                        <div class="title-sub">PENGIRIMAN TANGKAI ANODA</div>
                    </td>
                    <td class="cell-ttd">
                        <table class="tabel-ttd">
                            <tr>
                                <td colspan="3" class="ttd-hdr">DIPERIKSA</td>
                            </tr>
                            <tr>
                                <td class="ttd-lbl">Foreman</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="ttd-lbl">As.Foreman</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="ttd-lbl">Checker</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="ttd-lbl">SEKSI</td>
                                <td style="text-align:center;font-weight:bold;font-size:8px;">PT. GNG</td>
                                <td style="text-align:center;font-weight:bold;font-size:8px;">PT. INALUM</td>
                            </tr>
                        </table>
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
            @php
                $startNo = $pageIndex * $perHalaman;
                $col1 = $chunk->slice(0, $perKolom)->values();
                $col2 = $chunk->slice($perKolom, $perKolom)->values();
                $col3 = $chunk->slice($perKolom * 2, $perKolom)->values();
                $col4 = $chunk->slice($perKolom * 3, $perKolom)->values();
            @endphp

            <table class="tabel-data">
                <thead>
                    <tr>
                        <th rowspan="2" class="td-no">NO</th>
                        <th rowspan="2" style="width:15mm;">NOMOR ROD</th>
                        <th rowspan="2" class="td-no">NO</th>
                        <th rowspan="2" style="width:15mm;">NOMOR ROD</th>
                        <th rowspan="2" class="td-no">NO</th>
                        <th rowspan="2" style="width:15mm;">NOMOR ROD</th>
                        <th rowspan="2" class="td-no">NO</th>
                        <th rowspan="2" style="width:15mm;">NOMOR ROD</th>
                        <th colspan="2">Dibuat Oleh</th>
                    </tr>
                    <tr>
                        <th style="width:16mm;">Rodding</th>
                        <th style="width:16mm;">RRS</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < $perKolom; $i++)
                        @php
                            $r1 = $col1->get($i);
                            $r2 = $col2->get($i);
                            $r3 = $col3->get($i);
                            $r4 = $col4->get($i);
                            $n1 = $startNo + $i + 1;
                            $n2 = $startNo + $perKolom + $i + 1;
                            $n3 = $startNo + $perKolom * 2 + $i + 1;
                            $n4 = $startNo + $perKolom * 3 + $i + 1;
                        @endphp
                        @php
                            $rodAlign =
                                floor($i / 5) % 2 === 0
                                    ? 'text-align:left;padding-left:6px;'
                                    : 'text-align:right;padding-right:6px;';
                        @endphp
                        <tr>
                            <td>{{ $n1 }}</td>
                            <td style="{{ $rodAlign }}">{{ $r1 ? $r1->identitasRod->nomor_rod ?? '-' : '' }}</td>
                            <td>{{ $n2 }}</td>
                            <td style="{{ $rodAlign }}">{{ $r2 ? $r2->identitasRod->nomor_rod ?? '-' : '' }}</td>
                            <td>{{ $n3 }}</td>
                            <td style="{{ $rodAlign }}">{{ $r3 ? $r3->identitasRod->nomor_rod ?? '-' : '' }}</td>
                            <td>{{ $n4 }}</td>
                            <td style="{{ $rodAlign }}">{{ $r4 ? $r4->identitasRod->nomor_rod ?? '-' : '' }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            @if ($pageIndex > 0)
    </div>
    @endif
    @endforeach

    </div>
</body>

</html>
