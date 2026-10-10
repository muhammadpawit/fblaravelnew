<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMasterRequest;
use App\Http\Requests\StoreJenisPoRequest;
use App\Http\Requests\UpdateJenisPoRequest;
use App\Models\MasterJenisPo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MasterJenisPoController extends Controller
{
    public function index(SearchMasterRequest $request): View
    {
        $search = $request->validated('q') ?? '';
        $jenisPos = MasterJenisPo::query()
            ->when($search !== '', fn (Builder $query): Builder => $query->where('nama_jenis_po', 'like', '%'.$search.'%'))
            ->orderBy('nama_jenis_po')->orderBy('id')->paginate(15)->withQueryString();

        return view('jenis-po.index', compact('jenisPos', 'search'));
    }

    public function create(): View
    {
        return view('jenis-po.create', ['jenisPo' => new MasterJenisPo]);
    }

    public function store(StoreJenisPoRequest $request): RedirectResponse
    {
        MasterJenisPo::create($request->validated());

        return to_route('master.jenis-po.index')->with('success', 'Jenis PO berhasil ditambahkan.');
    }

    public function edit(MasterJenisPo $jenisPo): View
    {
        return view('jenis-po.edit', compact('jenisPo'));
    }

    public function update(UpdateJenisPoRequest $request, MasterJenisPo $jenisPo): RedirectResponse
    {
        $jenisPo->update($request->validated());

        return to_route('master.jenis-po.index')->with('success', 'Jenis PO berhasil diperbarui.');
    }

    public function destroy(MasterJenisPo $jenisPo): RedirectResponse
    {
        $jenisPo->delete();

        return to_route('master.jenis-po.index')->with('success', 'Jenis PO berhasil dihapus dari daftar.');
    }
}
