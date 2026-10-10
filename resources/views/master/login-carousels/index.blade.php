<x-layout title="Login Carousel">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-slate-600">{{ $carousels->total() }} gambar dalam daftar.</p>
        @can('login-carousel.create')
        <a href="{{ route('master.login-carousels.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Tambah Carousel</a>
        @endcan
    </div>
    <form method="GET" action="{{ route('master.login-carousels.index') }}" class="mt-5 flex flex-wrap items-end gap-3">
        <div class="min-w-0 flex-1">
            <x-form-input name="q" label="Cari Carousel" :value="$search" placeholder="Judul atau Subjudul" maxlength="100" />
        </div>
        <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium hover:bg-slate-50">Cari</button>
        @if ($search !== '')<a href="{{ route('master.login-carousels.index') }}" class="px-3 py-2.5 text-sm text-teal-700">Reset</a>@endif
    </form>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <caption class="sr-only">Daftar Login Carousel</caption>
            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                <tr>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Gambar</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Judul</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Status / Urutan</th>
                    <th scope="col" class="whitespace-nowrap px-5 py-4 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($carousels as $carousel)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <img src="{{ $carousel->image }}" alt="Carousel" class="h-16 w-16 object-cover rounded shadow-sm">
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold">{{ $carousel->title ?: '(Tanpa Judul)' }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ Str::limit($carousel->subtitle, 50) }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($carousel->is_active)
                                <span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-medium text-teal-800">Aktif</span>
                            @else
                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-800">Tidak Aktif</span>
                            @endif
                            <p class="mt-2 text-xs text-slate-500">Urutan: {{ $carousel->order }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <x-master-actions edit-permission="login-carousel.update" delete-permission="login-carousel.delete" :edit="route('master.login-carousels.edit', $carousel)" :destroy="route('master.login-carousels.destroy', $carousel)" :label="$carousel->title" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-slate-500">{{ $search !== '' ? 'Tidak ada carousel yang cocok dengan pencarian.' : 'Belum ada carousel. Tambahkan yang pertama.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $carousels->links() }}</div>
</x-layout>
