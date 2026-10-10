<?php

namespace Database\Factories;

use App\Models\MasterCmt;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MasterCmt> */
class MasterCmtFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'kode_cmt' => fake()->unique()->bothify('CMT-#####'),
            'cmt_name' => fake()->company(),
            'cmt_job_desk' => fake()->randomElement(['JAHIT', 'SABLON', 'BORDIR']),
        ];
    }
}
