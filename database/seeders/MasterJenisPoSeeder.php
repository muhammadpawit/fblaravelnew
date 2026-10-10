<?php

namespace Database\Seeders;

use App\Models\MasterJenisPo;
use Illuminate\Database\Seeder;

class MasterJenisPoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            MasterJenisPo::factory()->count(3)->create();
        }
    }
}
