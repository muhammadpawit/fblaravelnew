<x-layout title="Role & Permission">
    <div class="flex items-center justify-between gap-4"><a href="{{ route('access.roles.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">Tambah Role & Permission</a></div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white p-4">
        <table id="dataTable" class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-600"><tr><th scope="col" class="px-5 py-4">Role</th><th scope="col" class="px-5 py-4">Pengguna aktif</th><th scope="col" class="px-5 py-4">Permission</th><th scope="col" class="px-5 py-4">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        </tbody></table>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('access.roles.index') }}',
                columns: [
                    { data: 'name', name: 'name', render: function(data, type, row) {
                        return '<span class="font-semibold">' + data + '</span> ' + (row.is_system ? '<span class="text-xs text-slate-500">(sistem)</span>' : '');
                    }},
                    { data: 'users_count', name: 'users_count', searchable: false },
                    { data: 'permissions', name: 'permissions', render: function(data, type, row) {
                        return row.slug === 'administrator' ? 'Seluruh permission' : data.length + ' permission';
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, row) {
                        return row.slug === 'administrator' ? '<span class="text-xs text-slate-500">Dilindungi</span>' : data;
                    }}
                ]
            });
        });
    </script>
</x-layout>
