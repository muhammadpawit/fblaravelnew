<x-layout title="Master Jenis PO">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-600">{{ $jenisPos->total() }} kategori PO. Tarif ditampilkan dalam rupiah.</p>
        @can('jenis-po.create')
        <a href="{{ route('master.jenis-po.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Tambah Jenis PO</a>
        @endcan
    </div>
    <form method="GET" action="{{ route('master.jenis-po.index') }}" class="mt-5 flex flex-wrap items-end gap-3">
        <div class="min-w-0 flex-1"><x-form-input name="q" label="Cari Jenis PO" :value="$search" placeholder="Nama kategori PO" maxlength="100" /></div>
        <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium hover:bg-slate-50">Cari</button>
        @if ($search !== '')<a href="{{ route('master.jenis-po.index') }}" class="px-3 py-2.5 text-sm text-teal-700">Reset</a>@endif
    </form>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">Daftar Jenis PO dan tarif finishing</caption>
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600"><tr>
                @foreach (['Jenis PO', 'Perkalian', 'Tarif finishing (Rp)', 'Status', 'Visibilitas', 'Aksi'] as $heading)
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">{{ $heading }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($jenisPos as $jenisPo)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-semibold">{{ $jenisPo->nama_jenis_po }}</td>
                        <td class="px-5 py-4 tabular-nums">{{ $jenisPo->perkalian }}</td>
                        <td class="min-w-64 px-5 py-4">
                            <details><summary class="cursor-pointer font-medium text-teal-700">Packing: {{ number_format((float) $jenisPo->harga_packing, 2, ',', '.') }}</summary>
                                <dl class="mt-3 grid grid-cols-[1fr_auto] gap-x-4 gap-y-2 text-xs">
                                    @foreach (['cucian_finishing' => 'Cucian finishing', 'cucian_finishing_luar' => 'Cucian luar', 'buangbenang' => 'Buang benang', 'lobangkancing' => 'Lubang kancing', 'pasangkancing' => 'Pasang kancing', 'tress' => 'Tress'] as $field => $label)
                                        <dt class="text-slate-500">{{ $label }}</dt><dd class="text-right tabular-nums">{{ number_format((float) $jenisPo->{$field}, 2, ',', '.') }}</dd>
                                    @endforeach
                                </dl>
                            </details>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4"><span @class(['rounded-full px-2.5 py-1 text-xs font-medium', 'bg-teal-50 text-teal-800' => $jenisPo->status === 1, 'bg-slate-100 text-slate-600' => $jenisPo->status !== 1])>{{ $jenisPo->status === 1 ? 'Aktif' : 'Tidak aktif' }}</span></td>
                        <td class="px-5 py-4 text-slate-600">{{ $jenisPo->tampil ? 'Ditampilkan' : 'Disembunyikan' }}</td>
                        <td class="px-5 py-4"><x-master-actions edit-permission="jenis-po.update" delete-permission="jenis-po.delete" :edit="route('master.jenis-po.edit', $jenisPo)" :destroy="route('master.jenis-po.destroy', $jenisPo)" :label="$jenisPo->nama_jenis_po" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">{{ $search !== '' ? 'Tidak ada Jenis PO yang cocok dengan pencarian.' : 'Belum ada Jenis PO. Tambahkan kategori pertama Anda.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $jenisPos->links() }}</div>
</x-layout>
