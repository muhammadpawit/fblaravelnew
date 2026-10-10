<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Menu> */
class MenuFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return ['label' => 'Dashboard', 'route_name' => 'dashboard', 'group' => 'Utama', 'sort_order' => 0, 'is_active' => true];
    }
}
