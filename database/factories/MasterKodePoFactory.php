<?php

namespace Database\Factories;

use App\Models\MasterKodePo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MasterKodePo>
 */
class MasterKodePoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_po' => fake()->unique()->bothify('PO-????-#####'),
            'nama_po' => fake()->words(2, true),
            'jenis_po' => fake()->randomElement(['ATASAN TANGGUNG', 'CELANA', 'DRESS']),
            'model_po' => fake()->optional()->word(),
            'tanggal_produksi' => fake()->date(),
            'kategori_po' => 'DALAM',
            'kode_artikel' => fake()->optional()->bothify('ART-#####'),
            'jenis_ukuran' => 'Biasa',
            'type' => 'Biasa',
        ];
    }
}
