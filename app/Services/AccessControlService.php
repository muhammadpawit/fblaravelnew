<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccessControlService
{
    /** @param array{name: string, permissions?: list<int|string>} $data */
    public function saveRole(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data): Role {
            if ($role->exists) {
                $role = Role::query()->lockForUpdate()->findOrFail($role->id);
            }
            if ($role->slug === 'administrator') {
                throw ValidationException::withMessages(['name' => 'Role Administrator dilindungi dan selalu memiliki seluruh permission.']);
            }
            $role->name = $data['name'];
            if (! $role->exists) {
                $role->slug = (string) Str::uuid();
            }
            $role->save();
            $permissionIds = $data['permissions'] ?? [];
            $selected = Permission::whereIn('id', $permissionIds)->pluck('name');
            foreach (['cmt', 'jenis-po'] as $module) {
                if ($selected->intersect([$module.'.create', $module.'.update', $module.'.delete'])->isNotEmpty()) {
                    $permissionIds[] = Permission::where('name', $module.'.view')->firstOrFail()->id;
                }
            }
            $role->permissions()->sync(array_unique($permissionIds));

            return $role;
        });
    }

    public function deleteRole(Role $role): void
    {
        DB::transaction(function () use ($role): void {
            $role = Role::query()->lockForUpdate()->findOrFail($role->id);
            if ($role->is_system || $role->users()->withTrashed()->exists()) {
                throw ValidationException::withMessages(['role' => 'Role sistem atau role yang masih digunakan pengguna tidak dapat dihapus.']);
            }
            $role->delete();
        });
    }

    /** @param array{name: string, username: string, email: string, role_id: int|string, password?: string|null} $data */
    public function saveUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $admin = Role::query()->where('slug', 'administrator')->lockForUpdate()->firstOrFail();
            if ($user->exists) {
                $user = User::query()->lockForUpdate()->findOrFail($user->id);
                if ($user->role_id === $admin->id && (int) $data['role_id'] !== $admin->id) {
                    $this->ensureAnotherAdministrator($user, $admin);
                }
                if ($user->email !== $data['email']) {
                    $user->google_id = null;
                    $user->email_verified_at = null;
                }
            }
            if (empty($data['password'])) {
                unset($data['password']);
            } elseif ($user->exists) {
                $user->remember_token = Str::random(60);
                if (config('session.driver') === 'database') {
                    DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
                }
            }
            Role::query()->lockForUpdate()->findOrFail($data['role_id']);
            $user->fill(collect($data)->except('role_id')->all());
            $user->role_id = $data['role_id'];
            $user->save();

            return $user;
        });
    }

    public function deleteUser(User $user, User $actor): void
    {
        DB::transaction(function () use ($user, $actor): void {
            $admin = Role::query()->where('slug', 'administrator')->lockForUpdate()->firstOrFail();
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            if ($user->is($actor)) {
                throw ValidationException::withMessages(['user' => 'Anda tidak dapat menghapus akun sendiri.']);
            }
            if ($user->role_id === $admin->id) {
                $this->ensureAnotherAdministrator($user, $admin);
            }
            $user->delete();
        });
    }

    public function saveMenu(Menu $menu, array $data): void
    {
        $permission = null;
        if (!empty($data['route_name'])) {
            $permission = config('access.menu_routes')[$data['route_name']]['permission'] ?? null;
        }
        
        $menu->fill($data);
        $menu->permission_id = $permission === null ? null : Permission::where('name', $permission)->firstOrFail()->id;
        $menu->save();
    }

    private function ensureAnotherAdministrator(User $user, Role $admin): void
    {
        if (! User::where('role_id', $admin->id)->whereKeyNot($user->id)->exists()) {
            throw ValidationException::withMessages(['role_id' => 'Minimal satu Administrator aktif harus tetap tersedia.']);
        }
    }
}
