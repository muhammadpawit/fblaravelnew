<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterKodePo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_kode_po';

    /** @var list<string> */
    protected $fillable = [
        'kode_po',
        'nama_po',
        'jenis_po',
        'model_po',
        'tanggal_produksi',
        'kategori_po',
        'kode_artikel',
        'jenis_ukuran',
        'type',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'tanggal_produksi' => 'date:Y-m-d',
        'deleted_at' => 'datetime',
    ];
}
