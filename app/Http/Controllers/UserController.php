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
    public function index(SearchMasterRequest $request): View
    {
        $search = $request->validated('q') ?? '';
        $users = User::query()->with('role')->when($search !== '', fn (Builder $query): Builder => $query->where(fn (Builder $query): Builder => $query->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('username', 'like', '%'.$search.'%')))->orderBy('name')->orderBy('id')->paginate(15)->withQueryString();

        return view('access.users.index', ['users' => $users, 'search' => $search]);
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
