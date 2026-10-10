<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\SearchMasterRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\AccessControlService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()->with('role');
            return datatables()->of($query)
                ->addColumn('action', function($user) {
                    if ($user->id === auth()->id()) return '<span class="text-xs text-slate-500">Akun Anda</span>';
                    return view('components.master-actions', [
                        'edit-permission' => 'access.manage',
                        'delete-permission' => 'access.manage',
                        'edit' => route('access.users.edit', $user),
                        'destroy' => route('access.users.destroy', $user),
                        'label' => $user->name
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('access.users.index');
    }

    public function create(): View
    {
        return view('access.users.form', ['user' => new User, 'roles' => Role::orderBy('name')->get()]);
    }

    public function store(SaveUserRequest $request, AccessControlService $service): RedirectResponse
    {
        $service->saveUser(new User, $request->validated());

        return to_route('access.users.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('access.users.form', ['user' => $user, 'roles' => Role::orderBy('name')->get()]);
    }

    public function update(SaveUserRequest $request, User $user, AccessControlService $service): RedirectResponse
    {
        $service->saveUser($user, $request->validated());

        return to_route($user->is($request->user()) ? 'dashboard' : 'access.users.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user, AccessControlService $service): RedirectResponse
    {
        $service->deleteUser($user, $request->user());

        return to_route('access.users.index')->with('success', 'Data berhasil dihapus.');
    }
}
