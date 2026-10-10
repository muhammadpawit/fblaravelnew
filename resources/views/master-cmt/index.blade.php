<x-layout title="Master CMT">
    <div class="flex flex-wrap items-center justify-end gap-4">
        @can('cmt.create')
        <a href="{{ route('master.cmt.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Tambah CMT</a>
        @endcan
    </div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm p-4">
        <table id="dataTable" class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                <tr>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Kode / Nama CMT</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Kategori</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Alamat / Kontak</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Transport per lusin</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Aksi</th>
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
                ajax: '{{ route('master.cmt.index') }}',
                columns: [
                    { data: 'cmt_name', name: 'cmt_name', render: function(data, type, row) {
                        return '<p class="font-semibold">' + data + '</p><p class="mt-1 text-xs text-slate-500">' + row.kode_cmt + '</p>';
                    }},
                    { data: 'cmt_job_desk', name: 'cmt_job_desk', render: function(data) {
                        return '<span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-medium text-teal-800">' + data + '</span>';
                    }},
                    { data: 'alamat', name: 'alamat', render: function(data, type, row) {
                        return '<p>' + (data ? data : '—') + '</p><p class="mt-1 text-xs text-slate-500">' + (row.telephone ? row.telephone : '—') + '</p>';
                    }},
                    { data: 'ongkos_transport_perdz', name: 'ongkos_transport_perdz', render: function(data) {
                        let amount = parseFloat(data).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        return '<span class="whitespace-nowrap tabular-nums">Rp ' + amount + '</span>';
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
</x-layout>
