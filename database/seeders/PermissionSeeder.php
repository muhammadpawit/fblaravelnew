<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('access.permissions') as $name => $attributes) {
            Permission::updateOrCreate(['name' => $name], $attributes);
        }
    }
}
