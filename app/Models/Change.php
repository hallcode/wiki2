<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Change extends Model
{
    protected $fillable = ["type", "user_id", "description"];

    /**
     * Define the polymorphic relationship to the changeable parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function changeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Define the relationship to the user made this change.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
