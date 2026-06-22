<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Projects this user owns.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    /**
     * Bonus: issues this user is assigned to as a member, via issue_user.
     */
    public function assignedIssues(): BelongsToMany
    {
        return $this->belongsToMany(Issue::class, 'issue_user')->withTimestamps();
    }
}
