<x-layout title="Pengguna">
    <div class="flex items-center justify-between gap-4"><p class="text-sm text-slate-500">{{ $users->total() }} data</p><a href="{{ route('access.users.create') }}" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white">Tambah Pengguna</a></div>
    <form method="GET" class="mt-4 flex items-end gap-3"><div class="flex-1"><x-form-input name="q" label="Cari pengguna" :value="$search" maxlength="100" placeholder="Nama, username, atau email" /></div><button class="rounded-lg border border-slate-300 bg-white px-4 py-2.5">Cari</button></form>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-50 text-slate-600"><tr><th scope="col" class="px-5 py-4">Pengguna</th><th scope="col" class="px-5 py-4">Email</th><th scope="col" class="px-5 py-4">Role</th><th scope="col" class="px-5 py-4">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse ($users as $item)
            <tr><td class="px-5 py-4"><p class="font-semibold">{{ $item->name }}</p><p class="text-xs text-slate-500">{{ $item->username }}</p></td><td class="px-5 py-4">{{ $item->email }}</td><td class="px-5 py-4">{{ $item->role?->name }}</td><td class="px-5 py-4"><x-master-actions :edit="route('access.users.edit', $item)" :destroy="route('access.users.destroy', $item)" :label="$item->name ?? $item->label" /></td></tr>
        @empty
            <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">Belum ada data yang sesuai.</td></tr>
        @endforelse
    </tbody></table></div>
    <div class="mt-5">{{ $users->links() }}</div>
</x-layout>
