@csrf
@if ($errors->any())
    <div role="alert" class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Data belum dapat disimpan. Periksa kolom berikut:</p>
        <ul class="mt-2 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label for="kode_po" class="block text-sm font-semibold text-slate-800">Kode PO <span class="text-red-600">*</span></label>
        <input type="text" id="kode_po" name="kode_po" value="{{ old('kode_po', $kodePo->kode_po) }}" required maxlength="50"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="Contoh: KSOK27064">
        @error('kode_po')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nama_po" class="block text-sm font-semibold text-slate-800">Nama PO <span class="text-red-600">*</span></label>
        <input type="text" id="nama_po" name="nama_po" value="{{ old('nama_po', $kodePo->nama_po) }}" required maxlength="100"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="KDS">
        @error('nama_po')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="jenis_po" class="block text-sm font-semibold text-slate-800">Jenis PO <span class="text-red-600">*</span></label>
        <input type="text" list="jenis_po_options" id="jenis_po" name="jenis_po" value="{{ old('jenis_po', $kodePo->jenis_po) }}" required maxlength="100"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="ATASAN TANGGUNG">
        <datalist id="jenis_po_options">
            @if(isset($jenisPoList))
                @foreach ($jenisPoList as $jenis)
                    <option value="{{ $jenis }}"></option>
                @endforeach
            @endif
        </datalist>
        @error('jenis_po')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="model_po" class="block text-sm font-semibold text-slate-800">Model PO</label>
        <input type="text" id="model_po" name="model_po" value="{{ old('model_po', $kodePo->model_po) }}" maxlength="100"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="">
        @error('model_po')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_produksi" class="block text-sm font-semibold text-slate-800">Tanggal Produksi <span class="text-red-600">*</span></label>
        <input type="date" id="tanggal_produksi" name="tanggal_produksi"
            value="{{ old('tanggal_produksi', $kodePo->tanggal_produksi ? (is_string($kodePo->tanggal_produksi) ? $kodePo->tanggal_produksi : $kodePo->tanggal_produksi->format('Y-m-d')) : date('Y-m-d')) }}" required
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600">
        @error('tanggal_produksi')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="kategori_po" class="block text-sm font-semibold text-slate-800">Kategori Po <span class="text-red-600">*</span></label>
        <input type="text" id="kategori_po" name="kategori_po" value="{{ old('kategori_po', $kodePo->kategori_po) }}" required maxlength="50"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="DALAM">
        @error('kategori_po')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="kode_artikel" class="block text-sm font-semibold text-slate-800">Kode Artikel</label>
        <input type="text" id="kode_artikel" name="kode_artikel" value="{{ old('kode_artikel', $kodePo->kode_artikel) }}" maxlength="50"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="">
        @error('kode_artikel')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="jenis_ukuran" class="block text-sm font-semibold text-slate-800">Jenis Ukuran : <span class="text-red-600">*</span></label>
        <input type="text" id="jenis_ukuran" name="jenis_ukuran" value="{{ old('jenis_ukuran', $kodePo->jenis_ukuran) }}" required maxlength="50"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="Biasa">
        @error('jenis_ukuran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="type" class="block text-sm font-semibold text-slate-800">Type : <span class="text-red-600">*</span></label>
        <input type="text" id="type" name="type" value="{{ old('type', $kodePo->type) }}" required maxlength="50"
            class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-teal-600 focus:outline-2 focus:outline-teal-600"
            placeholder="Biasa">
        @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="flex items-center gap-4 border-t border-slate-200 pt-5">
    <button type="submit"
        :disabled="submitting"
        class="inline-flex items-center gap-2 rounded-lg bg-teal-700 px-5 py-2.5 font-medium text-white hover:bg-teal-800 disabled:opacity-50">
        <svg x-show="submitting" class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span x-text="submitting ? 'Menyimpan...' : 'Simpan Kode PO'">Simpan Kode PO</span>
    </button>
    <a href="{{ route('master.kode-po.index') }}" class="rounded-lg px-4 py-2.5 text-slate-600 hover:bg-slate-100">Batal</a>
</div>
