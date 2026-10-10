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
    public function index(\Illuminate\Http\Request $request)
    {
        if ($request->ajax()) {
            $query = MasterJenisPo::query();
            return datatables()->of($query)
                ->addColumn('action', function($po) {
                    return view('components.master-actions', [
                        'edit-permission' => 'jenis-po.update',
                        'delete-permission' => 'jenis-po.delete',
                        'edit' => route('master.jenis-po.edit', $po),
                        'destroy' => route('master.jenis-po.destroy', $po),
                        'label' => $po->nama_jenis_po
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('jenis-po.index');
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
