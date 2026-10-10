<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchMasterRequest;
use App\Http\Requests\StoreMasterCmtRequest;
use App\Http\Requests\UpdateMasterCmtRequest;
use App\Models\MasterCmt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MasterCmtController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        if ($request->ajax()) {
            $query = MasterCmt::query();
            return datatables()->of($query)
                ->addColumn('action', function($cmt) {
                    return view('components.master-actions', [
                        'edit-permission' => 'cmt.update',
                        'delete-permission' => 'cmt.delete',
                        'edit' => route('master.cmt.edit', $cmt),
                        'destroy' => route('master.cmt.destroy', $cmt),
                        'label' => $cmt->cmt_name
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master-cmt.index');
    }

    public function create(): View
    {
        return view('master-cmt.create', ['cmt' => new MasterCmt]);
    }

    public function store(StoreMasterCmtRequest $request): RedirectResponse
    {
        MasterCmt::create($request->validated());

        return to_route('master.cmt.index')->with('success', 'CMT berhasil ditambahkan.');
    }

    public function edit(MasterCmt $cmt): View
    {
        return view('master-cmt.edit', compact('cmt'));
    }

    public function update(UpdateMasterCmtRequest $request, MasterCmt $cmt): RedirectResponse
    {
        $cmt->update($request->validated());

        return to_route('master.cmt.index')->with('success', 'CMT berhasil diperbarui.');
    }

    public function destroy(MasterCmt $cmt): RedirectResponse
    {
        $cmt->delete();

        return to_route('master.cmt.index')->with('success', 'CMT berhasil dihapus dari daftar aktif.');
    }
}
