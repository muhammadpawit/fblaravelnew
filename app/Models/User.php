<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission(string $permission): bool
    {
        $this->loadMissing('role.permissions');

        return ! $this->trashed() && $this->role !== null
            && ($this->role->slug === 'administrator' || $this->role->permissions->contains('name', $permission));
    }

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'foto',
    ];

    /** @var list<string> */
    protected $hidden = ['password', 'remember_token', 'google_id'];

    /** @var array<string, string> */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
        'last_activity' => 'datetime',
        'hak_akses' => 'integer',
        'deleted_at' => 'datetime',
    ];
}
