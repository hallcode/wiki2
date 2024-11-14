<?php

namespace App\Models;

use App\Traits\HasVersions;
use App\Traits\TracksChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasVersions;
    use TracksChanges;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class);
    }
}
