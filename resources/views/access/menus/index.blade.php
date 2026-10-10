<x-layout title="Menu">
    <div class="flex items-center justify-between gap-4"><a href="{{ route('access.menus.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">Tambah Menu</a></div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white p-4">
        <table id="dataTable" class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-600"><tr><th scope="col" class="px-5 py-4">Label / Grup</th><th scope="col" class="px-5 py-4">Tujuan</th><th scope="col" class="px-5 py-4">Urutan / Status</th><th scope="col" class="px-5 py-4">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        </tbody></table>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('access.menus.index') }}',
                columns: [
                    { data: 'label', name: 'label', render: function(data, type, row) {
                        return '<p class="font-semibold">' + data + '</p><p class="text-xs text-slate-500">' + row.group + '</p>';
                    }},
                    { data: 'route_name', name: 'route_name', render: function(data, type, row) {
                        let perm = row.permission ? row.permission.label : 'Semua pengguna login';
                        return '<p>' + data + '</p><p class="text-xs text-slate-500">' + perm + '</p>';
                    }},
                    { data: 'sort_order', name: 'sort_order', render: function(data, type, row) {
                        return data + ' · ' + (parseInt(row.is_active) === 1 ? 'Aktif' : 'Disembunyikan');
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
</x-layout>
