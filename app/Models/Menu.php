<?php

namespace App\Models;

use Database\Factories\MenuFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    /** @use HasFactory<MenuFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['label', 'route_name', 'group', 'sort_order', 'is_active', 'parent_id'];

    protected $casts = ['sort_order' => 'integer', 'is_active' => 'boolean'];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }
}
