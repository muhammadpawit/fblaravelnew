<x-layout title="Master Data Kode PO">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-600">Daftar master data kode produksi PO.</p>
        @can('kode-po.create')
        <a href="{{ route('master.kode-po.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">
            Tambah Kode PO
        </a>
        @endcan
    </div>

    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <table id="dataTable" class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                <tr>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Kode PO</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Nama PO</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Jenis PO</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Model PO</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Tgl Produksi</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Kategori PO</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Kode Artikel</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Jenis Ukuran</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">Type</th>
                    <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master.kode-po.index') }}",
                columns: [
                    { data: 'kode_po', name: 'kode_po', className: 'font-semibold text-slate-900' },
                    { data: 'nama_po', name: 'nama_po' },
                    { data: 'jenis_po', name: 'jenis_po' },
                    { data: 'model_po', name: 'model_po', render: function(data) {
                        return data ? data : '<span class="text-slate-400">—</span>';
                    }},
                    { data: 'tanggal_produksi', name: 'tanggal_produksi', className: 'tabular-nums whitespace-nowrap' },
                    { data: 'kategori_po', name: 'kategori_po', render: function(data) {
                        return '<span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">' + (data || 'DALAM') + '</span>';
                    }},
                    { data: 'kode_artikel', name: 'kode_artikel', render: function(data) {
                        return data ? '<span class="font-mono text-xs">' + data + '</span>' : '<span class="text-slate-400">—</span>';
                    }},
                    { data: 'jenis_ukuran', name: 'jenis_ukuran' },
                    { data: 'type', name: 'type' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
                ]
            });
        });
    </script>
</x-layout>
