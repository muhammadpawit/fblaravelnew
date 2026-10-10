<x-layout title="Menu">
    <div class="flex items-center justify-between gap-4"><p class="text-sm text-slate-500">{{ $menus->total() }} data</p><a href="{{ route('access.menus.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">Tambah Menu</a></div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-600"><tr><th scope="col" class="px-5 py-4">Label / Grup</th><th scope="col" class="px-5 py-4">Tujuan</th><th scope="col" class="px-5 py-4">Urutan / Status</th><th scope="col" class="px-5 py-4">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse ($menus as $item)
            <tr><td class="px-5 py-4"><p class="font-semibold">{{ $item->label }}</p><p class="text-xs text-slate-500">{{ $item->group }}</p></td><td class="px-5 py-4"><p>{{ $item->route_name }}</p><p class="text-xs text-slate-500">{{ $item->permission?->label ?? 'Semua pengguna login' }}</p></td><td class="px-5 py-4">{{ $item->sort_order }} · {{ $item->is_active ? 'Aktif' : 'Disembunyikan' }}</td><td class="px-5 py-4"><x-master-actions :edit="route('access.menus.edit', $item)" :destroy="route('access.menus.destroy', $item)" :label="$item->name ?? $item->label" /></td></tr>
        @empty
            <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">Belum ada data yang sesuai.</td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-5">{{ $menus->links() }}</div>
</x-layout>
