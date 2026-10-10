<?php

namespace Database\Factories;

use App\Models\MasterJenisPo;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MasterJenisPo> */
class MasterJenisPoFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'nama_jenis_po' => fake()->unique()->bothify('PO-????-#####'),
            'status' => 1,
            'perkalian' => '12.0000',
        ];
    }
}
