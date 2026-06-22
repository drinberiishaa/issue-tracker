<?php

namespace App\Models;

use Database\Factories\IssueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Issue extends Model
{
    /** @use HasFactory<IssueFactory> */
    use HasFactory;

    public const STATUSES = ['open', 'in_progress', 'closed'];

    public const PRIORITIES = ['low', 'medium', 'high'];

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    /**
     * An issue belongs to a project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * An issue has many comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * An issue belongs to many tags (and vice versa) via issue_tag.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'issue_tag')->withTimestamps();
    }

    /**
     * Bonus: an issue can be assigned to many users (members) via issue_user.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'issue_user')->withTimestamps();
    }

    /**
     * Scope: filter by status if provided.
     */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    /**
     * Scope: filter by priority if provided.
     */
    public function scopePriority(Builder $query, ?string $priority): Builder
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }

    /**
     * Scope: filter by tag id if provided.
     */
    public function scopeTag(Builder $query, ?int $tagId): Builder
    {
        return $tagId
            ? $query->whereHas('tags', fn (Builder $q) => $q->where('tags.id', $tagId))
            : $query;
    }

    /**
     * Scope: text search across title and description.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
