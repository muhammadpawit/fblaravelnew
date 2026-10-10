<x-layout title="Dashboard">
    <p class="text-slate-600">Ringkasan master data untuk mendukung aktivitas produksi Anda.</p>
    <div class="mt-6 grid gap-6 sm:grid-cols-2">
        @can('cmt.view')
        <a href="{{ route('master.cmt.index') }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:border-teal-500">
            <h2 class="text-sm font-medium text-slate-500">CMT aktif</h2>
            <p class="mt-3 text-4xl font-semibold text-teal-700">{{ number_format($activeCmtCount, 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-slate-500">Mitra jahit, sablon, dan bordir yang belum dihapus.</p>
        </a>
        @endcan
        @can('jenis-po.view')
        <a href="{{ route('master.jenis-po.index') }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:border-teal-500">
            <h2 class="text-sm font-medium text-slate-500">Jenis PO aktif</h2>
            <p class="mt-3 text-4xl font-semibold text-teal-700">{{ number_format($activeJenisPoCount, 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-slate-500">Kategori berstatus aktif dan ditampilkan.</p>
        </a>
        @endcan
    </div>
    @cannot('cmt.view')
        @cannot('jenis-po.view')
            <p class="rounded-lg bg-white p-5 text-slate-600">Akun Anda aktif. Hubungi administrator untuk mendapatkan akses modul.</p>
        @endcannot
    @endcannot
</x-layout>
