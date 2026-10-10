<x-layout title="Ubah CMT">
    <form method="POST" action="{{ route('master.cmt.update', $cmt) }}" class="flex flex-col gap-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @method('PUT')
        @include('master-cmt.form')
    </form>
</x-layout>
