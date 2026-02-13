@foreach ($penerimaan as $row)
    <tr class="hover:bg-gray-200 text-center even:bg-gray-200 text-base text-gray-900 whitespace-nowrap">

        <td class="px-4 py-3">
            {{ $loop->iteration }}
        </td>

        <td class="px-4 py-3">
            {{ $row->tanggal_penerimaan->format('d-m-Y H:i:s') }}
        </td>

        <td class="px-4 py-3">
            {{ $row->shift }}
        </td>

        <td class="px-4 py-3">
            {{ $row->identitasRod->nomor_rod ?? '-' }}
        </td>

        <td class="px-4 py-3">{{ $row->jenis }}</td>
        <td class="px-4 py-3">{{ $row->stasiun }}</td>
        <td class="px-4 py-3">{{ $row->e1 }}</td>
        <td class="px-4 py-3">{{ $row->e2 }}</td>
        <td class="px-4 py-3">{{ $row->e3 }}</td>
        <td class="px-4 py-3">{{ $row->s }}</td>
        <td class="px-4 py-3">{{ $row->d }}</td>
        <td class="px-4 py-3">{{ $row->b }}</td>
        <td class="px-4 py-3">{{ $row->ba }}</td>
        <td class="px-4 py-3">{{ $row->r }}</td>
        <td class="px-4 py-3">{{ $row->m }}</td>
        <td class="px-4 py-3">{{ $row->cr }}</td>
        <td class="px-4 py-3">{{ $row->c }}</td>
        <td class="px-4 py-3">{{ $row->rl }}</td>

        <td class="px-4 py-3 font-semibold">
            {{ $row->jumlah }}
        </td>

        <td class="px-4 py-3">
            {{ $row->catatan }}
        </td>

        <td class="px-4 py-3">
            {{ $row->updated_at->format('d-m-Y H:i:s') }}
        </td>

        <td class="px-4 py-3">
            {{ $row->karyawan->nama_lengkap ?? '-' }}
        </td>

        <td class="px-4 py-3">
            {{ $row->tim }}
        </td>
    </tr>
@endforeach
