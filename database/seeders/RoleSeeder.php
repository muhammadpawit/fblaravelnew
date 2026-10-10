<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        foreach (['administrator' => 'Administrator', 'operator' => 'Operator', 'viewer' => 'Viewer'] as $slug => $name) {
            $role = Role::firstOrCreate(['slug' => $slug], ['name' => $name, 'is_system' => $slug !== 'operator']);
            if ($slug === 'administrator') {
                $role->permissions()->syncWithoutDetaching(Permission::pluck('id'));
            } elseif ($slug === 'operator' && $role->wasRecentlyCreated) {
                $role->permissions()->sync(Permission::where('name', '!=', 'access.manage')->where('name', 'not like', '%.delete')->pluck('id'));
            }
        }
    }
}
