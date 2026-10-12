<x-layout title="Edit Kode PO: {{ $kodePo->kode_po }}">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master.kode-po.update', $kodePo) }}" x-data="{ submitting: false }" @submit="submitting = true">
            @method('PUT')
            @include('master-kode-po.form')
        </form>
    </div>
</x-layout>
