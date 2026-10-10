<x-layout title="Login Carousel">
    <div class="flex flex-wrap items-center justify-end gap-4">
        @can('login-carousel.create')
        <a href="{{ route('master.login-carousels.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Tambah Carousel</a>
        @endcan
    </div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm p-4">
        <table id="dataTable" class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                <tr>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Gambar</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Judul</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Status / Urutan</th>
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
                ajax: '{{ route('master.login-carousels.index') }}',
                columns: [
                    { data: 'image', name: 'image', render: function(data) {
                        return '<img src="' + data + '" alt="Carousel" class="h-16 w-16 object-cover rounded shadow-sm">';
                    }},
                    { data: 'title', name: 'title', render: function(data, type, row) {
                        let subtitle = row.subtitle ? (row.subtitle.length > 50 ? row.subtitle.substring(0, 50) + '...' : row.subtitle) : '';
                        return '<p class="font-semibold">' + (data ? data : '(Tanpa Judul)') + '</p><p class="mt-1 text-xs text-slate-500">' + subtitle + '</p>';
                    }},
                    { data: 'is_active', name: 'is_active', render: function(data, type, row) {
                        let status = parseInt(data) === 1 ? '<span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-medium text-teal-800">Aktif</span>' : '<span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-800">Tidak Aktif</span>';
                        return status + '<p class="mt-2 text-xs text-slate-500">Urutan: ' + row.order + '</p>';
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
</x-layout>
