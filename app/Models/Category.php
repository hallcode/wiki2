<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\HasVersions;
use App\Traits\TracksChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasVersions;
    use TracksChanges;

    public $timestamps = false;

    protected $fillable = ["title", "user_id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class);
    }

    public function slug(): Attribute
    {
        return Attribute::make(get: fn() => urlencode($this->title));
    }
}
