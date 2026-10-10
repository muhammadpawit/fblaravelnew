<x-layout title="Daftar Permission">
    <p class="text-sm text-slate-600">Permission terhubung ke aksi aplikasi. Atur pemberiannya melalui menu Role & Permission.</p>
    <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200 bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-50"><tr><th class="px-5 py-4">Permission</th><th class="px-5 py-4">Modul</th><th class="px-5 py-4">Role terkait</th></tr></thead><tbody class="divide-y divide-slate-100">
        @foreach ($permissions as $permission)<tr><td class="px-5 py-4"><p>{{ $permission->label }}</p><code class="text-xs text-slate-500">{{ $permission->name }}</code></td><td class="px-5 py-4">{{ $permission->group }}</td><td class="px-5 py-4">{{ $permission->roles_count }}</td></tr>@endforeach
    </tbody></table></div>
</x-layout>
