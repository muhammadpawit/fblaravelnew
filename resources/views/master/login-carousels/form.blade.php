<x-layout :title="$carousel->exists ? 'Ubah Carousel' : 'Tambah Carousel'">
    <form method="POST" action="{{ $carousel->exists ? route('master.login-carousels.update', $carousel) : route('master.login-carousels.store') }}" class="mt-6 max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @if ($carousel->exists)
            @method('PUT')
        @endif
        <div class="space-y-5">
            <div>
                <x-form-input name="image" label="URL Gambar" :value="old('image', $carousel->image)" placeholder="https://..." required maxlength="255" />
            </div>
            <div>
                <x-form-input name="title" label="Judul" :value="old('title', $carousel->title)" placeholder="Contoh: Fitur Baru" maxlength="255" />
            </div>
            <div>
                <x-form-input name="subtitle" label="Subjudul" :value="old('subtitle', $carousel->subtitle)" placeholder="Deskripsi singkat..." maxlength="255" />
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <x-form-input type="number" name="order" label="Urutan" :value="old('order', $carousel->order ?? 0)" required />
                </div>
                <div class="flex items-center pt-8">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $carousel->is_active ?? true)) class="size-4 accent-teal-700"> Aktif (Ditampilkan)
                    </label>
                </div>
            </div>
        </div>
        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-teal-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-800">Simpan</button>
            <a href="{{ route('master.login-carousels.index') }}" class="rounded-lg px-4 py-2.5 text-slate-600 hover:bg-slate-100">Batal</a>
        </div>
    </form>
</x-layout>
