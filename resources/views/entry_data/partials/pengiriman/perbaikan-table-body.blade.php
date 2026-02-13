@foreach ($perbaikan as $index => $row)
    <tr class="hover:bg-gray-200 text-center even:bg-gray-200 text-base text-gray-900 whitespace-nowrap">
        <td class="px-4 py-2">
            {{ $perbaikan->firstItem() + $index }}
        </td>

        <td class="px-5 py-5">
            <a href="javascript:void(0)" onclick='pilihData(@json($row))'
                class="px-5 py-3 border border-(--blue) bg-white text-(--blue) text-sm font-semibold rounded-lg hover:bg-(--blue) hover:text-white transition">
                Pilih
            </a>
        </td>

        <td>
            {{ $row->penerimaan->identitasRod->nomor_rod ?? '-' }}
        </td>

        <td>
            {{ $row->tanggal_perbaikan?->format('d-m-Y H:i:s') ?? '-' }}
        </td>

        <td class="px-4 py-2">{{ $row->shift }}</td>

        <td class="px-4 py-2">{{ $row->jenis }}</td>

        <td class="px-4 py-2">{{ $row->e1_ers }}</td>
        <td class="px-4 py-2">{{ $row->e1_est }}</td>
        <td class="px-4 py-2">{{ $row->e1_jumlah }}</td>
        <td class="px-4 py-2">{{ $row->e2_ers }}</td>
        <td class="px-4 py-2">{{ $row->e2_cst }}</td>
        <td class="px-4 py-2">{{ $row->e2_cstub }}</td>
        <td class="px-4 py-2">{{ $row->e2_jumlah }}</td>
        <td class="px-4 py-2">{{ $row->e3 }}</td>
        <td class="px-4 py-2">{{ $row->e4 }}</td>
        <td class="px-4 py-2">{{ $row->s }}</td>
        <td class="px-4 py-2">{{ $row->d }}</td>
        <td class="px-4 py-2">{{ $row->b }}</td>
        <td class="px-4 py-2">{{ $row->bac }}</td>
        <td class="px-4 py-2">{{ $row->nba }}</td>
        <td class="px-4 py-2">{{ $row->ba }}</td>
        <td class="px-4 py-2">{{ $row->ba1 }}</td>
        <td class="px-4 py-2">{{ $row->r }}</td>
        <td class="px-4 py-2">{{ $row->m }}</td>
        <td class="px-4 py-2">{{ $row->cr }}</td>
        <td class="px-4 py-2">{{ $row->c }}</td>
        <td class="px-4 py-2">{{ $row->rl }}</td>

        <td class="px-4 py-2 font-semibold">{{ $row->jumlah }}</td>
        <td class="px-4 py-2">{{ $row->catatan }}</td>

        <td class="px-4 py-2">
            {{ $row->tanggal_penerimaan->format('d-m-Y H:i:s') }}
        </td>

        <td class="px-4 py-2">
            {{ $row->updated_at->format('d-m-Y H:i:s') }}
        </td>

        <td class="px-4 py-2">
            {{ $row->karyawan->nama_lengkap ?? '-' }}
        </td>
        <td class="px-4 py-2">
            {{ $row->tim }}
        </td>
    </tr>
@endforeach
