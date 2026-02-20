<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Perbaikan</title>
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
            height: 18px;
        }

        .tabel-data tbody tr.total-row td {
            border: 1.5px solid #000;
            font-weight: bold;
        }

        .td-left {
            text-align: left;
            padding-left: 3px;
        }

        /* STASIUN vertical header */
        .th-v {
            writing-mode: vertical-rl;
            transform: rotate(270deg);
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            font-weight: bold;
            line-height: 1.2;
        }

        /* ===== FOOTER ===== */
        .footer-area {
            width: 100%;
            margin-top: 4px;
            display: flex;
            gap: 6px;
            align-items: flex-start;
        }

        .tabel-footer {
            border-collapse: collapse;
            font-size: 10px;
        }

        .tabel-footer th {
            border: 1.5px solid #000;
            padding: 2px 3px;
            text-align: center;
            font-weight: bold;
            white-space: nowrap;
            min-width: 6mm;
        }

        .tabel-footer td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: center;
            height: 16px;
            min-width: 6mm;
        }

        .ft-lbl {
            font-weight: bold;
        }

        .ft-shift {
            text-align: center;
            font-weight: bold;
            padding: 2px 1px;
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
            $fields = [
                'e1_ers',
                'e1_est',
                'e1_jumlah',
                'e2_ers',
                'e2_cst',
                'e2_cstub',
                'e2_jumlah',
                'e3',
                'e4',
                's',
                'd',
                'b',
                'ba',
                'ba1',
                'r',
                'm',
                'cr',
                'c',
                'rl',
            ];
            $perPage = 30;
            $chunks = $data->chunk($perPage);
            $shifts = [1, 2, 3];
            $shiftLabels = [1 => '1', 2 => '2', 3 => '3'];
            $grouped = $data->groupBy('shift');
            $groupedAll = $dataAllShift->groupBy('shift');
        @endphp

        @foreach ($chunks as $pageIndex => $chunk)

            {{-- Wrapper page-break untuk halaman ke-2 dst --}}
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
                        <div class="title-sub">PERBAIKAN TANGKAI ANODA</div>
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
                $emptyRows = max(0, $perPage - $chunk->count());
                $startNo = $pageIndex * $perPage + 1;
            @endphp

            <table class="tabel-data">
                <thead>
                    <tr>
                        <th rowspan="3" style="width:3mm;">NO</th>
                        <th rowspan="3" style="width:17mm;">NOMOR ROD</th>
                        <th rowspan="3" style="width:8mm;max-width:8mm;overflow:hidden;">
                            <p class="th-v">JENIS PERBAIKAN</p>
                        </th>
                        <th colspan="19">JENIS DAN TOTAL KERUSAKAN</th>
                        <th rowspan="3" style="width:8mm;">TOTAL</th>
                        <th rowspan="3" style="width:15mm;">TANGGAL MASUK</th>
                        <th rowspan="3">Catatan</th>
                        <th rowspan="3" style="width:10mm;">Dibuat RRS OP</th>
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
                        <th>Ers</th>
                        <th>Est</th>
                        <th>Total</th>
                        <th>Ers</th>
                        <th>Cst</th>
                        <th>Cstub</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chunk as $i => $row)
                        <tr>
                            <td>{{ $startNo++ }}</td>
                            @php
                                $nomorAlign =
                                    floor($i / 5) % 2 === 0
                                        ? 'text-align:left;padding-left:6px;'
                                        : 'text-align:right;padding-right:6px;';
                            @endphp
                            <td style="{{ $nomorAlign }}">{{ $row->penerimaan->identitasRod->nomor_rod ?? '-' }}</td>
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
                            <td style="font-weight:bold;font-size:10px;">{{ $row->jumlah ?: '' }}</td>
                            <td>{{ $row->penerimaan->tanggal_penerimaan ? \Carbon\Carbon::parse($row->penerimaan->tanggal_penerimaan)->format('d/m/Y') : '' }}
                            </td>
                            <td class="td-left">{{ $row->catatan ?: '' }}</td>
                            <td></td>
                        </tr>
                    @endforeach

                    @for ($e = 0; $e < $emptyRows; $e++)
                        <tr>
                            @for ($c = 0; $c < 26; $c++)
                                <td></td>
                            @endfor
                        </tr>
                    @endfor

                    {{-- Baris TOTAL per halaman --}}
                    @php
                        $countFields = ['s', 'cr', 'c'];
                        $fields = [
                            'e1_ers',
                            'e1_est',
                            'e1_jumlah',
                            'e2_ers',
                            'e2_cst',
                            'e2_cstub',
                            'e2_jumlah',
                            'e3',
                            'e4',
                            's',
                            'd',
                            'b',
                            'ba',
                            'ba1',
                            'r',
                            'm',
                            'cr',
                            'c',
                            'rl',
                        ];
                    @endphp
                    <tr class="total-row">
                        <td colspan="3" style="text-align:center;">TOTAL</td>
                        @foreach ($fields as $f)
                            @php
                                if ($f === 'ba') {
                                    // Hanya jumlahkan nba, abaikan baris yang bac > 0
                                    $totalVal = $chunk->sum(
                                        fn($r) => ($r->bac ?? 0) > 0 ? 0 : $r->nba ?? ($r->ba ?? 0),
                                    );
                                } elseif (in_array($f, $countFields)) {
                                    $totalVal = $chunk->filter(fn($r) => $r->$f > 0)->count();
                                } else {
                                    $totalVal = $chunk->sum($f);
                                }
                            @endphp
                            <td>{{ $totalVal ?: '' }}</td>
                        @endforeach
                        <td>{{ $chunk->sum('jumlah') ?: '' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            {{-- FOOTER hanya di halaman terakhir --}}
            @if ($loop->last)
                <div class="footer-area">
                    <table class="tabel-footer">
                        <tr>
                            <th colspan="2" rowspan="2">JENIS</th>
                            <th colspan="3">E1</th>
                            <th colspan="4">E2</th>
                            <th rowspan="2">E3</th>
                            <th rowspan="2">E4</th>
                            <th rowspan="2">S</th>
                            <th rowspan="2">D</th>
                            <th rowspan="2">B</th>
                            <th rowspan="2">BA</th>
                            <th rowspan="2">BA-1</th>
                            <th rowspan="2">R</th>
                            <th rowspan="2">M</th>
                            <th rowspan="2">CR</th>
                            <th rowspan="2">C</th>
                            <th rowspan="2">RL</th>
                            <th style="min-width: 8mm;" rowspan="2">TOTAL</th>
                        </tr>
                        <tr>
                            <th>Ers</th>
                            <th>Est</th>
                            <th>Total</th>
                            <th>Ers</th>
                            <th>Cst</th>
                            <th>Cstub</th>
                            <th>Total</th>
                        </tr>
                        @php
                            $fieldsPerbaikan = [
                                'e1_ers',
                                'e1_est',
                                'e1_jumlah',
                                'e2_ers',
                                'e2_cst',
                                'e2_cstub',
                                'e2_jumlah',
                                'e3',
                                'e4',
                                's',
                                'd',
                                'b',
                                'nba',
                                'ba1',
                                'r',
                                'm',
                                'cr',
                                'c',
                                'rl',
                            ];
                        @endphp
                        @foreach ($shifts as $s)
                            <tr>
                                @if ($loop->first)
                                    <td rowspan="3" class="ft-shift">SHIFT</td>
                                @endif
                                <td class="ft-lbl">{{ $shiftLabels[$s] }}</td>
                                @php $rowTotal = 0; @endphp
                                @foreach ($fieldsPerbaikan as $f)
                                    @php
                                        $val = isset($groupedAll[$s]) ? $groupedAll[$s]->sum($f) : 0;
                                        $rowTotal += $val;
                                    @endphp
                                    <td>{{ $val ?: '' }}</td>
                                @endforeach
                                <td style="font-weight:bold;">{{ $rowTotal ?: '' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="ft-lbl">JUMLAH</td>
                            @php $gt = 0; @endphp
                            @foreach ($fieldsPerbaikan as $f)
                                @php
                                    $val = $dataAllShift->sum($f);
                                    $gt += $val;
                                @endphp
                                <td style="font-weight:bold;">{{ $val ?: '' }}</td>
                            @endforeach
                            <td style="font-weight:bold;">{{ $gt ?: '' }}</td>
                        </tr>
                    </table>

                    {{-- Tabel Footer Kedua + NB menggunakan table layout (kompatibel wkhtmltopdf) --}}
                    <table style="border-collapse:collapse; margin-top:5px;">
                        <tr>
                            <td style="vertical-align:top; padding:0;">
                                <table class="tabel-footer">
                                    <tr>
                                        <th colspan="2">JENIS</th>
                                        <th>E</th>
                                        <th>S</th>
                                        <th>D</th>
                                        <th>B</th>
                                        <th>BA</th>
                                        <th>R</th>
                                        <th>M</th>
                                        <th>CR</th>
                                        <th>C</th>
                                        <th>RL</th>
                                        <th>TOTAL</th>
                                    </tr>
                                    @php $jenisList2 = ['E', 'S', 'D', 'B', 'BA', 'R', 'M', 'CR', 'C', 'RL']; @endphp
                                    @foreach ($shifts as $s)
                                        <tr>
                                            @if ($loop->first)
                                                <td rowspan="3" class="ft-shift">SHIFT</td>
                                            @endif
                                            <td class="ft-lbl">{{ $shiftLabels[$s] }}</td>
                                            @php $row = $dataJenis->get($s); @endphp
                                            @foreach ($jenisList2 as $j)
                                                <td>{{ $row ? ($row[$j] ?: '') : '' }}</td>
                                            @endforeach
                                            <td style="font-weight:bold;">{{ $row ? ($row['TOTAL'] ?: '') : '' }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="ft-lbl">JUMLAH</td>
                                        @foreach ($jenisList2 as $j)
                                            <td style="font-weight:bold;">
                                                {{ $dataJenis->sum(fn($r) => $r[$j] ?? 0) ?: '' }}</td>
                                        @endforeach
                                        <td style="font-weight:bold;">
                                            {{ $dataJenis->sum(fn($r) => $r['TOTAL'] ?? 0) ?: '' }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td
                                style="vertical-align:top; padding-left:8px; padding-top:2px; white-space:nowrap; font-size:8px; line-height:2;">
                                <strong>NB :</strong><br>
                                1. Ers &nbsp;&nbsp;&nbsp;: Erosi Normal<br>
                                2. Est &nbsp;&nbsp;&nbsp;: Erosi Karena Sticking Keras<br>
                                3. Cst &nbsp;&nbsp;&nbsp;: Erosi Karena Sticking &amp; Stub Kecil<br>
                                4. Cstub : Erosi Karena Stub Sudah Kecil
                            </td>
                        </tr>
                    </table>
                </div>
            @endif

            @if ($pageIndex > 0)
    </div>
    @endif

    @endforeach

    </div>
</body>

</html>
