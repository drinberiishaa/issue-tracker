<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'start_date',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'deadline' => 'date',
        ];
    }

    /**
     * A project has many issues.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * The user who owns / created this project.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Convenience counts for list views without loading full collections.
     */
    public function openIssuesCount(): int
    {
        return $this->issues->where('status', '!=', 'closed')->count();
    }
}
