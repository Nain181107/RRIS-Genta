@foreach ($perbaikan as $index => $row)
    <tr class="hover:bg-gray-200 text-center even:bg-gray-200 text-base text-gray-900 whitespace-nowrap">
        <td class="px-4 py-3">
            {{ $perbaikan->firstItem() + $index }}
        </td>

        <td class="px-5 py-5">
            <a href="javascript:void(0)" onclick='EditPerbaikan.eper_pilihData(@json($row))'
                class="px-5 py-3 border border-(--blue) bg-white text-(--blue) text-sm font-semibold rounded-lg hover:bg-(--blue) hover:text-white transition">
                Pilih
            </a>
        </td>

        <td class="px-4 py-3">
            {{ $row->penerimaan->identitasRod->nomor_rod ?? '-' }}</td>

        <td class="px-4 py-3">
            {{ $row->tanggal_perbaikan->format('d-m-Y H:i:s') }}
        </td>

        <td class="px-4 py-3">{{ $row->shift }}</td>

        <td class="px-4 py-3">{{ $row->jenis }}</td>

        <td class="px-4 py-3">{{ $row->e1_ers }}</td>
        <td class="px-4 py-3">{{ $row->e1_est }}</td>
        <td class="px-4 py-3">{{ $row->e1_jumlah }}</td>
        <td class="px-4 py-3">{{ $row->e2_ers }}</td>
        <td class="px-4 py-3">{{ $row->e2_cst }}</td>
        <td class="px-4 py-3">{{ $row->e2_cstub }}</td>
        <td class="px-4 py-3">{{ $row->e2_jumlah }}</td>
        <td class="px-4 py-3">{{ $row->e3 }}</td>
        <td class="px-4 py-3">{{ $row->e4 }}</td>
        <td class="px-4 py-3">{{ $row->s }}</td>
        <td class="px-4 py-3">{{ $row->d }}</td>
        <td class="px-4 py-3">{{ $row->b }}</td>
        <td class="px-4 py-3">{{ $row->bac }}</td>
        <td class="px-4 py-3">{{ $row->nba }}</td>
        <td class="px-4 py-3">{{ $row->ba }}</td>
        <td class="px-4 py-3">{{ $row->ba1 }}</td>
        <td class="px-4 py-3">{{ $row->r }}</td>
        <td class="px-4 py-3">{{ $row->m }}</td>
        <td class="px-4 py-3">{{ $row->cr }}</td>
        <td class="px-4 py-3">{{ $row->c }}</td>
        <td class="px-4 py-3">{{ $row->rl }}</td>

        <td class="px-4 py-3 font-semibold">{{ $row->jumlah }}</td>
        <td class="px-4 py-3">{{ $row->catatan }}</td>

        <td class="px-4 py-3">
            {{ $row->tanggal_penerimaan->format('d-m-Y H:i:s') }}
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
        <td class="px-4 py-3">
            <div class="flex justify-center">
                <div class="w-24 h-16 flex items-center justify-center rounded-md bg-gray-100 border-2 overflow-hidden">

                    @if ($row->fotobuktiperubahan)
                        <a href="{{ asset('storage/' . $row->fotobuktiperubahan) }}" target="_blank">
                            <img src="{{ asset('storage/' . $row->fotobuktiperubahan) }}"
                                class="w-full h-full object-contain p-1 hover:scale-105 transition"
                                alt="Foto Perbaikan">
                        </a>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif

                </div>
            </div>
        </td>
    </tr>
@endforeach
