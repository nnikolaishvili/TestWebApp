<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User role relation
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user's role is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role_id == Role::ADMIN;
    }

    /**
     * Check if user's role is editor
     *
     * @return bool
     */
    public function isEditor(): bool
    {
        return $this->role_id == Role::EDITOR;
    }

    /**
     * Check if user role is assistant
     *
     * @return bool
     */
    public function isAssistant(): bool
    {
        return $this->role_id == Role::ASSISTANT;
    }
}
