<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Version extends Model
{
    use HasUlids;

    protected $fillable = ["user_id", "content", "word_count"];
    protected $touches = ["versionable"];

    /**
     * Define the inverse polymorphic relationship to the parent model (Page, Post, Comment, etc.).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Define the relationship to the user who created the version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
