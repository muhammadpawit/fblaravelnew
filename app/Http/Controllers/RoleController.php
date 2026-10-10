<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveRoleRequest;
use App\Http\Requests\SearchMasterRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AccessControlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(SearchMasterRequest $request): View
    {

        $roles = Role::query()->with('permissions')->withCount('users')->orderBy('name')->paginate(15);

        return view('access.roles.index', ['roles' => $roles]);
    }

    public function create(): View
    {
        return view('access.roles.form', ['role' => new Role, 'permissions' => Permission::orderBy('group')->orderBy('id')->get()->groupBy('group')]);
    }

    public function store(SaveRoleRequest $request, AccessControlService $service): RedirectResponse
    {
        $service->saveRole(new Role, $request->validated());

        return to_route('access.roles.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Role $role): View
    {
        return view('access.roles.form', ['role' => $role, 'permissions' => Permission::orderBy('group')->orderBy('id')->get()->groupBy('group')]);
    }

    public function update(SaveRoleRequest $request, Role $role, AccessControlService $service): RedirectResponse
    {
        $service->saveRole($role, $request->validated());

        return to_route('access.roles.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Request $request, Role $role, AccessControlService $service): RedirectResponse
    {
        $service->deleteRole($role);

        return to_route('access.roles.index')->with('success', 'Data berhasil dihapus.');
    }
}
