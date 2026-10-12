<?php

namespace Database\Seeders;

use App\Models\MasterKodePo;
use Illuminate\Database\Seeder;

class MasterKodePoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            return;
        }

        foreach ([
            ['kode_po' => 'DEMO-PO-001', 'nama_po' => 'Nama PO Contoh 1', 'jenis_po' => 'ATASAN TANGGUNG'],
            ['kode_po' => 'DEMO-PO-002', 'nama_po' => 'Nama PO Contoh 2', 'jenis_po' => 'CELANA'],
            ['kode_po' => 'DEMO-PO-003', 'nama_po' => 'Nama PO Contoh 3', 'jenis_po' => 'DRESS'],
        ] as $po) {
            $kodePo = MasterKodePo::withTrashed()->updateOrCreate(
                ['kode_po' => $po['kode_po']],
                $po + [
                    'model_po' => null,
                    'tanggal_produksi' => now()->toDateString(),
                    'kategori_po' => 'DALAM',
                    'kode_artikel' => null,
                    'jenis_ukuran' => 'Biasa',
                    'type' => 'Biasa',
                ],
            );

            if ($kodePo->trashed()) {
                $kodePo->restore();
            }
        }
    }
}
