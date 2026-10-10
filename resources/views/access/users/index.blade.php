<x-layout title="Pengguna">
    <div class="flex items-center justify-between gap-4"><a href="{{ route('access.users.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">Tambah Pengguna</a></div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white p-4">
        <table id="dataTable" class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-600"><tr><th scope="col" class="px-5 py-4">Pengguna</th><th scope="col" class="px-5 py-4">Email</th><th scope="col" class="px-5 py-4">Role</th><th scope="col" class="px-5 py-4">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        </tbody></table>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('access.users.index') }}',
                columns: [
                    { data: 'name', name: 'name', render: function(data, type, row) {
                        return '<p class="font-semibold">' + data + '</p><p class="text-xs text-slate-500">' + row.username + '</p>';
                    }},
                    { data: 'email', name: 'email' },
                    { data: 'role.name', name: 'role.name', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
</x-layout>
