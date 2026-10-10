<x-layout title="Master CMT">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-600">{{ $cmts->total() }} mitra dalam daftar aktif.</p>
        @can('cmt.create')
        <a href="{{ route('master.cmt.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Tambah CMT</a>
        @endcan
    </div>
    <form method="GET" action="{{ route('master.cmt.index') }}" class="mt-5 flex flex-wrap items-end gap-3">
        <div class="min-w-0 flex-1">
            <x-form-input name="q" label="Cari CMT" :value="$search" placeholder="Kode, nama, atau kategori CMT" maxlength="100" />
        </div>
        <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium hover:bg-slate-50">Cari</button>
        @if ($search !== '')<a href="{{ route('master.cmt.index') }}" class="px-3 py-2.5 text-sm text-teal-700">Reset</a>@endif
    </form>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">Daftar mitra CMT yang belum dihapus</caption>
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                <tr>
                    @foreach (['Kode / Nama CMT', 'Kategori', 'Alamat / Kontak', 'Transport per lusin', 'Aksi'] as $heading)
                        <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($cmts as $cmt)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4"><p class="font-semibold">{{ $cmt->cmt_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $cmt->kode_cmt }}</p></td>
                        <td class="px-5 py-4"><span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-medium text-teal-800">{{ $cmt->cmt_job_desk }}</span></td>
                        <td class="min-w-48 px-5 py-4"><p>{{ $cmt->alamat ?: '—' }}</p><p class="mt-1 text-xs text-slate-500">{{ $cmt->telephone ?: '—' }}</p></td>
                        <td class="whitespace-nowrap px-5 py-4 tabular-nums">Rp {{ number_format((float) $cmt->ongkos_transport_perdz, 2, ',', '.') }}</td>
                        <td class="px-5 py-4"><x-master-actions edit-permission="cmt.update" delete-permission="cmt.delete" :edit="route('master.cmt.edit', $cmt)" :destroy="route('master.cmt.destroy', $cmt)" :label="$cmt->cmt_name" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">{{ $search !== '' ? 'Tidak ada CMT yang cocok dengan pencarian.' : 'Belum ada CMT. Tambahkan mitra pertama Anda.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $cmts->links() }}</div>
</x-layout>
