<?php

namespace App\Models;

use Database\Factories\MasterCmtFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCmt extends Model
{
    /** @use HasFactory<MasterCmtFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'master_cmt';

    /** @var list<string> */
    protected $fillable = [
        'kode_cmt',
        'cmt_name',
        'cmt_job_desk',
        'alamat',
        'telephone',
        'email',
        'an',
        'norek',
        'bank',
        'ongkos_transport_perdz',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'ongkos_transport_perdz' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];
}
