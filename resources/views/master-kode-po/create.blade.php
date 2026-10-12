<x-layout title="Tambah Kode PO Baru">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master.kode-po.store') }}" x-data="{ submitting: false }" @submit="submitting = true">
            @include('master-kode-po.form')
        </form>
    </div>
</x-layout>
