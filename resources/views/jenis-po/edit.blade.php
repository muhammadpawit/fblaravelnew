<x-layout title="Ubah Jenis PO">
    <form method="POST" action="{{ route('master.jenis-po.update', $jenisPo) }}" class="flex flex-col gap-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @method('PUT')
        @include('jenis-po.form')
    </form>
</x-layout>
