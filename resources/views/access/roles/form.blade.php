<x-layout :title="$role->exists ? 'Ubah Role' : 'Tambah Role'">
    <form method="POST" action="{{ $role->exists ? route('access.roles.update', $role) : route('access.roles.store') }}" class="flex flex-col gap-6 rounded-xl border border-slate-200 bg-white p-6">
        @csrf
        @if ($role->exists) @method('PUT') @endif
        @if ($role->slug === 'administrator')
            <p class="rounded-lg bg-teal-50 p-4 text-teal-800">Administrator memiliki seluruh permission dan tidak dapat diubah.</p>
        @endif
        <x-form-input name="name" label="Nama role" :value="$role->name" maxlength="100" required :disabled="$role->slug === 'administrator'" />
        <p class="text-sm text-slate-500">Centang hak akses yang diberikan. Hak tambah, ubah, atau hapus otomatis menyertakan hak lihat pada modul yang sama. Permission administrasi memberi kewenangan mengelola seluruh akses pengguna.</p>
        @foreach ($permissions as $group => $items)
            <fieldset class="rounded-lg border border-slate-200 p-5"><legend class="px-2 font-semibold">{{ $group }}</legend>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ($items as $permission)
                        <label class="flex items-start gap-3 text-sm"><input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                            @checked(in_array($permission->id, old('permissions', $errors->any() ? [] : $role->permissions->modelKeys())))
                            @disabled($role->slug === 'administrator') class="mt-1 size-4 accent-teal-600">
                            <span>{{ $permission->label }}<span class="block text-xs text-slate-500">{{ $permission->name }}</span></span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
        <div class="flex items-center gap-4"><button class="rounded-lg bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800" type="submit">Simpan</button><a href="{{ route('access.roles.index') }}" class="text-slate-600">Batal</a></div>
    </form>
</x-layout>
