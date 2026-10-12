<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $order = 0;
        foreach (config('access.menu_routes') as $route => $definition) {
            Menu::firstOrCreate(['route_name' => $route], [
                'label' => $definition['label'],
                'group' => str_starts_with($route, 'master.') ? 'Master Data' : (str_starts_with($route, 'access.') ? 'Administrasi' : 'Utama'),
                'sort_order' => $order,
                'is_active' => true,
                'permission_id' => $definition['permission'] === null ? null : Permission::where('name', $definition['permission'])->firstOrFail()->id,
            ]);
            $order += 10;
        }
    }
}
