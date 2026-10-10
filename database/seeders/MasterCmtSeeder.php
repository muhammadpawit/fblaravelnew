<?php

namespace Database\Seeders;

use App\Models\MasterCmt;
use Illuminate\Database\Seeder;

class MasterCmtSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            MasterCmt::factory()->count(3)->create();
        }
    }
}
