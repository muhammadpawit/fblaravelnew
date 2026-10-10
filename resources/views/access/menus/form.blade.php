<x-layout :title="$menu->exists ? 'Ubah Menu' : 'Tambah Menu'">
    <form method="POST" action="{{ $menu->exists ? route('access.menus.update', $menu) : route('access.menus.store') }}" class="flex flex-col gap-6 rounded-xl border border-slate-200 bg-white p-6">
        @csrf
        @if ($menu->exists) @method('PUT') @endif
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form-input name="label" label="Label menu" :value="$menu->label" maxlength="100" required />
            <x-form-input name="group" label="Grup menu" :value="$menu->group ?? 'Utama'" maxlength="100" required />
            <div><label for="route_name" class="block text-sm font-medium">Halaman tujuan (kosongkan jika parent menu)</label>
                <select id="route_name" name="route_name" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5">
                    <option value="">Pilih halaman</option>
                    @foreach ($destinations as $route => $definition)<option value="{{ $route }}" @selected(old('route_name', $menu->route_name) === $route)>{{ $definition['label'] }}</option>@endforeach
                </select>
            </div>
            <div><label for="parent_id" class="block text-sm font-medium">Menu Induk (Opsional)</label>
                <select id="parent_id" name="parent_id" class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5">
                    <option value="">Tidak ada (Menu Utama)</option>
                    @foreach ($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id', $menu->parent_id) == $parent->id)>{{ $parent->label }}</option>@endforeach
                </select>
            </div>
            <x-form-input name="sort_order" label="Urutan (angka kecil lebih dahulu)" type="number" :value="$menu->sort_order ?? 0" min="0" max="9999" required />
        </div>
        <x-form-toggle name="is_active" label="Tampilkan di sidebar" :value="$menu->is_active ?? true" />
        <p class="text-sm text-slate-500">Menu hanya tampil bagi pengguna yang memiliki hak akses halaman tujuan. Satu halaman hanya dapat memiliki satu menu.</p>
        <div class="flex items-center gap-4"><button class="rounded-lg bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800" type="submit">Simpan</button><a href="{{ route('access.menus.index') }}" class="text-slate-600">Batal</a></div>
    </form>
</x-layout>
