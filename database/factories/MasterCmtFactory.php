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
            'alamat' => fake()->streetAddress(),
            'telephone' => '081234567890',
            'ongkos_transport_perdz' => '0.00',
            'cmt_job_desk' => fake()->randomElement(['JAHIT', 'SABLON', 'BORDIR']),
        ];
    }
}
