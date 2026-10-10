<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoginCarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\LoginCarousel::create([
            'image' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=2070&auto=format&fit=crop',
            'title' => 'Kelola Produksi Lebih Cepat dan Terintegrasi',
            'subtitle' => 'Pantau order, bahan baku, progress produksi, pengiriman, hingga laporan harian dalam satu sistem.',
            'is_active' => true,
            'order' => 1,
        ]);

        \App\Models\LoginCarousel::factory(3)->create();
    }
}
