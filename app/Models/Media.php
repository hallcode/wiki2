<?php

namespace App\Models;

use App\Traits\HasVersions;
use App\Traits\TracksChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;
    use TracksChanges;
    use HasVersions;

    protected $fillable = [
        "title",
        "mime_type",
        "directory",
        "size",
        "filename",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class);
    }
}
