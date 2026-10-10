@csrf
@if ($errors->any())
    <div role="alert" class="rounded-lg bg-red-50 p-4 text-sm text-red-700">Data belum tersimpan. Periksa kolom yang ditandai di bawah.</div>
@endif
<p class="text-sm text-slate-500">Kolom bertanda * wajib diisi.</p>
<div class="grid gap-5 sm:grid-cols-2">
    <x-form-input name="kode_cmt" label="Kode CMT" :value="$cmt->kode_cmt" maxlength="50" required />
    <x-form-input name="cmt_name" label="Nama CMT" :value="$cmt->cmt_name" maxlength="100" required />
    <div>
        <label for="cmt_job_desk" class="block text-sm font-medium text-slate-700">Kategori CMT <span aria-hidden="true" class="text-red-600">*</span></label>
        <select id="cmt_job_desk" name="cmt_job_desk" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 focus:outline-2 focus:outline-teal-600"
            @error('cmt_job_desk') aria-invalid="true" aria-describedby="category-error" @enderror>
            <option value="">Pilih kategori</option>
            @foreach (['JAHIT', 'SABLON', 'BORDIR'] as $category)
                <option value="{{ $category }}" @selected(old('cmt_job_desk', $cmt->cmt_job_desk) === $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('cmt_job_desk')<p id="category-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
    </div>
    <x-form-input name="telephone" label="Telepon" type="tel" :value="$cmt->telephone" maxlength="20" required />
    <div class="sm:col-span-2">
        <label for="alamat" class="block text-sm font-medium text-slate-700">Alamat <span aria-hidden="true" class="text-red-600">*</span></label>
        <textarea id="alamat" name="alamat" rows="3" maxlength="255" required class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:outline-2 focus:outline-teal-600"
            @error('alamat') aria-invalid="true" aria-describedby="alamat-error" @enderror>{{ old('alamat', $cmt->alamat) }}</textarea>
        @error('alamat')<p id="alamat-error" class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
    </div>
    <x-form-input name="email" label="Email" type="email" :value="$cmt->email" maxlength="50" />
    <x-form-input name="ongkos_transport_perdz" label="Ongkos transport per lusin (Rp)" type="number" :value="$cmt->ongkos_transport_perdz ?? '0'" min="0" max="9999999999999.99" step="0.01" required />
</div>
<fieldset class="rounded-lg border border-slate-200 p-5">
    <legend class="px-2 text-sm font-semibold text-slate-700">Rekening bank (opsional)</legend>
    <div class="grid gap-5 sm:grid-cols-3">
        <x-form-input name="bank" label="Bank" :value="$cmt->bank" maxlength="100" />
        <x-form-input name="an" label="Atas nama rekening" :value="$cmt->an" maxlength="100" />
        <x-form-input name="norek" label="Nomor rekening" :value="$cmt->norek" maxlength="100" />
    </div>
</fieldset>
<div class="flex items-center gap-4 border-t border-slate-200 pt-5">
    <button type="submit" class="rounded-lg bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800">Simpan CMT</button>
    <a href="{{ route('master.cmt.index') }}" class="rounded-lg px-4 py-2.5 text-slate-600 hover:bg-slate-100">Batal</a>
</div>
