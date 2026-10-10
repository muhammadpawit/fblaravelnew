<x-layout title="Master Jenis PO">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-600">Tarif ditampilkan dalam rupiah.</p>
        @can('jenis-po.create')
        <a href="{{ route('master.jenis-po.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Tambah Jenis PO</a>
        @endcan
    </div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm p-4">
        <table id="dataTable" class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600"><tr>
                <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Jenis PO</th>
                <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Perkalian</th>
                <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Tarif finishing (Rp)</th>
                <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Status</th>
                <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Visibilitas</th>
                <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const formatRp = (amount) => parseFloat(amount).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('master.jenis-po.index') }}',
                columns: [
                    { data: 'nama_jenis_po', name: 'nama_jenis_po', className: 'font-semibold' },
                    { data: 'perkalian', name: 'perkalian', className: 'tabular-nums' },
                    { data: 'harga_packing', name: 'harga_packing', render: function(data, type, row) {
                        return '<details><summary class="cursor-pointer font-medium text-teal-700">Packing: ' + formatRp(data) + '</summary>' +
                            '<dl class="mt-3 grid grid-cols-[1fr_auto] gap-x-4 gap-y-2 text-xs">' +
                                '<dt class="text-slate-500">Cucian finishing</dt><dd class="text-right tabular-nums">' + formatRp(row.cucian_finishing) + '</dd>' +
                                '<dt class="text-slate-500">Cucian luar</dt><dd class="text-right tabular-nums">' + formatRp(row.cucian_finishing_luar) + '</dd>' +
                                '<dt class="text-slate-500">Buang benang</dt><dd class="text-right tabular-nums">' + formatRp(row.buangbenang) + '</dd>' +
                                '<dt class="text-slate-500">Lubang kancing</dt><dd class="text-right tabular-nums">' + formatRp(row.lobangkancing) + '</dd>' +
                                '<dt class="text-slate-500">Pasang kancing</dt><dd class="text-right tabular-nums">' + formatRp(row.pasangkancing) + '</dd>' +
                                '<dt class="text-slate-500">Tress</dt><dd class="text-right tabular-nums">' + formatRp(row.tress) + '</dd>' +
                            '</dl>' +
                        '</details>';
                    }},
                    { data: 'status', name: 'status', render: function(data) {
                        return parseInt(data) === 1 ? '<span class="rounded-full px-2.5 py-1 text-xs font-medium bg-teal-50 text-teal-800">Aktif</span>' : '<span class="rounded-full px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-600">Tidak aktif</span>';
                    }},
                    { data: 'tampil', name: 'tampil', render: function(data) {
                        return parseInt(data) === 1 ? 'Ditampilkan' : 'Disembunyikan';
                    }},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
</x-layout>
