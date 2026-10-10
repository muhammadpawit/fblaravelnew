<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginCarousel extends Model
{
    /** @use HasFactory<\Database\Factories\LoginCarouselFactory> */
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'subtitle',
        'is_active',
        'order',
    ];
}
