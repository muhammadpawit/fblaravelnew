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
            'tampil' => true,
            'harga_packing' => '0.00',
            'cucian_finishing' => '0.00',
            'cucian_finishing_luar' => '0.00',
            'buangbenang' => '0.00',
            'lobangkancing' => '0.00',
            'pasangkancing' => '0.00',
            'tress' => '0.00',

        ];
    }
}
