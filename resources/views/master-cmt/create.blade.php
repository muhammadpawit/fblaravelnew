<x-layout title="Tambah CMT">
    <form method="POST" action="{{ route('master.cmt.store') }}" class="flex flex-col gap-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @include('master-cmt.form')
    </form>
</x-layout>
