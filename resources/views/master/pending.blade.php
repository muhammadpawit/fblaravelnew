<x-layout :title="$title">
    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <h2 class="text-lg font-semibold">Pengelolaan data akan segera tersedia</h2>
        <p class="mt-2 text-slate-600">Fitur tambah, ubah, dan hapus {{ $title }} akan dibuat pada tahap berikutnya.</p>
        <a href="{{ route('dashboard') }}" class="mt-5 inline-block font-medium text-teal-700 hover:underline">Kembali ke dashboard</a>
    </div>
</x-layout>
