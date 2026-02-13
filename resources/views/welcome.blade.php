<tbody class="divide-y divide-gray-200">
    @foreach ($penerimaan as $index => $row)
        <tr class="hover:bg-gray-200 text-center text-sm text-gray-900 whitespace-nowrap">
            <td class="px-4 py-2">
                {{ $penerimaan->firstItem() + $index }}
            </td>

            <td class="px-4 py-2">
                {{ $row->tanggal_penerimaan->format('d-m-Y H:i:s') }}
            </td>

            <td class="px-4 py-2">{{ $row->shift }}</td>

            <td class="px-4 py-2">
                {{ $row->rod->nomor_rod ?? '-' }}
            </td>

            <td class="px-4 py-2">{{ $row->jenis }}</td>
            <td class="px-4 py-2">{{ $row->stasiun }}</td>

            <td class="px-4 py-2">{{ $row->e1 }}</td>
            <td class="px-4 py-2">{{ $row->e2 }}</td>
            <td class="px-4 py-2">{{ $row->e3 }}</td>
            <td class="px-4 py-2">{{ $row->s }}</td>
            <td class="px-4 py-2">{{ $row->d }}</td>
            <td class="px-4 py-2">{{ $row->b }}</td>
            <td class="px-4 py-2">{{ $row->ba }}</td>
            <td class="px-4 py-2">{{ $row->r }}</td>
            <td class="px-4 py-2">{{ $row->m }}</td>
            <td class="px-4 py-2">{{ $row->cr }}</td>
            <td class="px-4 py-2">{{ $row->c }}</td>
            <td class="px-4 py-2">{{ $row->rl }}</td>

            <td class="px-4 py-2 font-semibold">{{ $row->jumlah }}</td>
            <td class="px-4 py-2">{{ $row->catatan }}</td>

            <td class="px-4 py-2">
                {{ $row->updated_at->format('d-m-Y H:i') }}
            </td>

            <td class="px-4 py-2">
                {{ $row->karyawan->nama_lengkap ?? '-' }}
            </td>
        </tr>
    @endforeach
</tbody>
