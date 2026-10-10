@csrf
@if ($errors->any())
    <div role="alert" class="rounded-lg bg-red-50 p-4 text-sm text-red-700">Data belum tersimpan. Periksa kolom yang ditandai di bawah.</div>
@endif
<p class="text-sm text-slate-500">Isi tarif dalam rupiah tanpa pemisah ribuan. Gunakan 0 jika tidak ada biaya.</p>
<div class="grid gap-5 sm:grid-cols-2">
    <x-form-input name="nama_jenis_po" label="Nama Jenis PO" :value="$jenisPo->nama_jenis_po" maxlength="100" required />
    <x-form-input name="perkalian" label="Faktor perkalian" type="number" :value="$jenisPo->perkalian" min="0.0001" max="99999999.9999" step="0.0001" required />
</div>
<fieldset class="rounded-lg border border-slate-200 p-5">
    <legend class="px-2 font-semibold text-slate-700">Tarif operasional (Rp)</legend>
    <div class="grid gap-5 sm:grid-cols-2">
        @foreach (['harga_packing' => 'Harga packing', 'cucian_finishing' => 'Cucian finishing', 'cucian_finishing_luar' => 'Cucian finishing luar', 'buangbenang' => 'Buang benang', 'lobangkancing' => 'Lubang kancing', 'pasangkancing' => 'Pasang kancing', 'tress' => 'Tress'] as $field => $label)
            <x-form-input :name="$field" :label="$label" type="number" :value="$jenisPo->{$field} ?? '0'" min="0" max="9999999999999.99" step="0.01" required />
        @endforeach
    </div>
</fieldset>
<div class="flex flex-wrap gap-6 rounded-lg bg-slate-50 p-5">
    <x-form-toggle name="status" label="Status aktif" :value="$jenisPo->status ?? true" />
    <x-form-toggle name="tampil" label="Tampilkan" :value="$jenisPo->tampil ?? true" />
</div>
<div class="flex items-center gap-4 border-t border-slate-200 pt-5">
    <button type="submit" class="rounded-lg bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800">Simpan Jenis PO</button>
    <a href="{{ route('master.jenis-po.index') }}" class="rounded-lg px-4 py-2.5 text-slate-600 hover:bg-slate-100">Batal</a>
</div>
