@foreach ($pengiriman as $index => $row)
    <tr class="hover:bg-gray-200 text-center text-base even:bg-gray-200 text-gray-900 whitespace-nowrap">
        <td class="px-4 py-3">{{ $pengiriman->firstItem() + $index }}</td>
        <td class="px-4 py-3">{{ $row->tanggal_pengiriman->format('d-m-Y H:i:s') }}</td>
        <td class="px-4 py-3">{{ $row->shift }}</td>
        <td class="px-4 py-3">
            {{ $row->perbaikan->penerimaan->identitasRod->nomor_rod ?? '-' }}</td>
        <td class="px-4 py-3">
            {{ $row->perbaikan->penerimaan->tanggal_penerimaan->format('d-m-Y H:i:s') }}
        </td>
        <td class="px-4 py-3">
            {{ $row->perbaikan->tanggal_perbaikan->format('d-m-Y H:i:s') }}</td>
        <td class="px-4 py-3">{{ $row->updated_at->format('d-m-Y H:i:s') }}</td>
        <td class="px-4 py-3">{{ $row->karyawan->nama_lengkap ?? '-' }}</td>
        <td class="px-4 py-3">{{ $row->tim }}</td>
    </tr>
@endforeach
