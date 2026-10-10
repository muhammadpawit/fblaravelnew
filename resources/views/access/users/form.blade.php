<x-layout :title="$user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna'">
    <form method="POST" action="{{ $user->exists ? route('access.users.update', $user) : route('access.users.store') }}" class="flex flex-col gap-6 rounded-xl border border-slate-200 bg-white p-6">
        @csrf
        @if ($user->exists) @method('PUT') @endif
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form-input name="name" label="Nama" :value="$user->name" maxlength="255" required />
            <x-form-input name="username" label="Username" :value="$user->username" maxlength="50" required />
            <x-form-input name="email" label="Email" type="email" :value="$user->email" maxlength="255" required />
            <div><label for="role_id" class="block text-sm font-medium">Role</label>
                <select id="role_id" name="role_id" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5">
                    <option value="">Pilih role</option>
                    @foreach ($roles as $role)<option value="{{ $role->id }}" @selected((string) old('role_id', $user->role_id) === (string) $role->id)>{{ $role->name }}</option>@endforeach
                </select>
            </div>
            <x-form-input name="password" label="Password (minimal 12 karakter)" type="password" autocomplete="new-password" :required="! $user->exists" />
            <x-form-input name="password_confirmation" label="Konfirmasi password" type="password" autocomplete="new-password" :required="! $user->exists" />
        </div>
        @if ($user->exists)<p class="text-sm text-slate-500">Kosongkan password untuk mempertahankan password saat ini. Mengubah email akan melepas tautan Google agar identitas diverifikasi ulang.</p>@endif
        <div class="flex items-center gap-4"><button class="rounded-lg bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800" type="submit">Simpan</button><a href="{{ route('access.users.index') }}" class="text-slate-600">Batal</a></div>
    </form>
</x-layout>
