<x-layout title="Tambah Jenis PO">
    <form method="POST" action="{{ route('master.jenis-po.store') }}" class="flex flex-col gap-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @include('jenis-po.form')
    </form>
</x-layout>
