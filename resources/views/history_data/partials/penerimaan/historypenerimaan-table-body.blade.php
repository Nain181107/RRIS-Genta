@foreach ($penerimaan as $index => $row)
    <tr class="hover:bg-gray-200 text-center text-base even:bg-gray-200 text-gray-900 whitespace-nowrap">
        <td class="px-4 py-3">
            {{ $penerimaan->firstItem() + $index }}
        </td>

        <td class="px-4 py-3">
            {{ $row->tanggal_penerimaan->format('d-m-Y H:i:s') }}
        </td>

        <td class="px-4 py-3">{{ $row->shift }}</td>

        <td class="px-4 py-4">
            <div class="flex items-center justify-center gap-2">
                <button onclick="pilihPenerimaan({{ $row->id }})"
                    class="px-4 py-3 border border-(--blue) bg-white text-(--blue) text-sm font-semibold rounded-lg hover:bg-(--blue) hover:text-white transition">
                    Pilih
                </button>
            </div>
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

        <td class="px-4 py-3 font-semibold">{{ $row->jumlah }}</td>
        <td class="px-4 py-3">{{ $row->catatan }}</td>

        <td class="px-4 py-3">
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
