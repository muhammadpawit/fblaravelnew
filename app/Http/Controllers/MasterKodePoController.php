<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMasterKodePoRequest;
use App\Http\Requests\UpdateMasterKodePoRequest;
use App\Models\MasterJenisPo;
use App\Models\MasterKodePo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterKodePoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MasterKodePo::query()->latest('id');

            return datatables()->of($query)
                ->editColumn('tanggal_produksi', function (MasterKodePo $po) {
                    return $po->tanggal_produksi ? $po->tanggal_produksi->format('d/m/Y') : '-';
                })
                ->addColumn('action', function (MasterKodePo $po) {
                    return view('components.master-actions', [
                        'edit-permission' => 'kode-po.update',
                        'delete-permission' => 'kode-po.delete',
                        'edit' => route('master.kode-po.edit', $po),
                        'destroy' => route('master.kode-po.destroy', $po),
                        'label' => $po->kode_po,
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-kode-po.index');
    }

    public function create(): View
    {
        $kodePo = new MasterKodePo([
            'nama_po' => 'KDS',
            'jenis_po' => 'ATASAN TANGGUNG',
            'kategori_po' => 'DALAM',
            'jenis_ukuran' => 'Biasa',
            'type' => 'Biasa',
            'tanggal_produksi' => now()->toDateString(),
        ]);

        $jenisPoList = MasterJenisPo::query()
            ->where('status', 1)
            ->orderBy('nama_jenis_po')
            ->pluck('nama_jenis_po');

        return view('master-kode-po.create', compact('kodePo', 'jenisPoList'));
    }

    public function store(StoreMasterKodePoRequest $request): RedirectResponse
    {
        MasterKodePo::create($request->validated());

        return to_route('master.kode-po.index')->with('success', 'Master Kode PO berhasil ditambahkan.');
    }

    public function edit(MasterKodePo $kodePo): View
    {
        $jenisPoList = MasterJenisPo::query()
            ->where('status', 1)
            ->orderBy('nama_jenis_po')
            ->pluck('nama_jenis_po');

        return view('master-kode-po.edit', compact('kodePo', 'jenisPoList'));
    }

    public function update(UpdateMasterKodePoRequest $request, MasterKodePo $kodePo): RedirectResponse
    {
        $kodePo->update($request->validated());

        return to_route('master.kode-po.index')->with('success', 'Master Kode PO berhasil diperbarui.');
    }

    public function destroy(MasterKodePo $kodePo): RedirectResponse
    {
        $kodePo->delete();

        return to_route('master.kode-po.index')->with('success', 'Master Kode PO berhasil dihapus dari daftar.');
    }
}
