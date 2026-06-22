<?php

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * A tag belongs to many issues (and vice versa) via issue_tag.
     */
    public function issues(): BelongsToMany
    {
        return $this->belongsToMany(Issue::class, 'issue_tag')->withTimestamps();
    }
}
