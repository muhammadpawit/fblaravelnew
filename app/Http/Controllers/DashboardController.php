<?php

namespace App\Http\Controllers;

use App\Models\MasterCmt;
use App\Models\MasterJenisPo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard', [
            'activeCmtCount' => $request->user()->can('cmt.view') ? MasterCmt::query()->count() : null,
            'activeJenisPoCount' => $request->user()->can('jenis-po.view') ? MasterJenisPo::query()->where('status', 1)->where('tampil', true)->count() : null,
        ]);
    }
}
