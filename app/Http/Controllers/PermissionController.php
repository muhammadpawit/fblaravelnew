<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function __invoke(): View
    {
        return view('access.permissions', ['permissions' => Permission::withCount('roles')->orderBy('group')->orderBy('id')->get()]);
    }
}
