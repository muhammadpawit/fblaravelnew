<?php

namespace App\Models;

use Database\Factories\MasterJenisPoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterJenisPo extends Model
{
    /** @use HasFactory<MasterJenisPoFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'master_jenis_po';

    /** @var list<string> */
    protected $fillable = [
        'nama_jenis_po',
        'status',
        'perkalian',
        'harga_packing',
        'cucian_finishing',
        'cucian_finishing_luar',
        'buangbenang',
        'lobangkancing',
        'pasangkancing',
        'tress',
        'tampil',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'status' => 'integer',
        'perkalian' => 'decimal:4',
        'harga_packing' => 'decimal:2',
        'cucian_finishing' => 'decimal:2',
        'cucian_finishing_luar' => 'decimal:2',
        'buangbenang' => 'decimal:2',
        'lobangkancing' => 'decimal:2',
        'pasangkancing' => 'decimal:2',
        'tress' => 'decimal:2',
        'tampil' => 'boolean',
        'deleted_at' => 'datetime',
    ];
}
