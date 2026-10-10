<x-layout title="Role & Permission">
    <div class="flex items-center justify-between gap-4"><p class="text-sm text-slate-500">{{ $roles->total() }} data</p><a href="{{ route('access.roles.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">Tambah Role & Permission</a></div>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-600"><tr><th scope="col" class="px-5 py-4">Role</th><th scope="col" class="px-5 py-4">Pengguna aktif</th><th scope="col" class="px-5 py-4">Permission</th><th scope="col" class="px-5 py-4">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse ($roles as $item)
            <tr><td class="px-5 py-4 font-semibold">{{ $item->name }} @if ($item->is_system)<span class="text-xs text-slate-500">(sistem)</span>@endif</td><td class="px-5 py-4">{{ $item->users_count }}</td><td class="px-5 py-4">{{ $item->slug === 'administrator' ? 'Seluruh permission' : $item->permissions->count().' permission' }}</td><td class="px-5 py-4">@if ($item->slug === 'administrator')<span class="text-xs text-slate-500">Dilindungi</span>@else<x-master-actions :edit="route('access.roles.edit', $item)" :destroy="route('access.roles.destroy', $item)" :label="$item->name ?? $item->label" />@endif</td></tr>
        @empty
            <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">Belum ada data yang sesuai.</td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-5">{{ $roles->links() }}</div>
</x-layout>
