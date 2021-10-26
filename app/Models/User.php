<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ITEMS_PER_PAGE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'email_verified_at',
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
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });
    }

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

    /**
     * Scope a query to fetch users who is not admin
     *
     * @param $query
     */
    public function scopeIsNotAdmin($query)
    {
        $query->whereIn('role_id', [Role::EDITOR, Role::ASSISTANT]);
    }
}
