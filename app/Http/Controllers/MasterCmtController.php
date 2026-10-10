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
    public function index(SearchMasterRequest $request): View
    {
        $search = $request->validated('q') ?? '';
        $cmts = MasterCmt::query()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('kode_cmt', 'like', '%'.$search.'%')
                        ->orWhere('cmt_name', 'like', '%'.$search.'%')
                        ->orWhere('cmt_job_desk', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('cmt_name')->orderBy('id')->paginate(15)->withQueryString();

        return view('master-cmt.index', compact('cmts', 'search'));
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
