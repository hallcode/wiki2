<?php

namespace App\Models;

use App\Traits\HasVersions;
use App\Traits\TracksChanges;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasVersions;
    use TracksChanges;
    use SoftDeletes;

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PageType::class);
    }

    public function outgoingLinks(): HasMany
    {
        return $this->hasMany(PageLink::class, "parent_page_id");
    }

    public function incomingLinks(): HasMany
    {
        return $this->hasMany(PageLink::class, "target_page_id");
    }

    public function linkedPages(): HasManyThrough
    {
        return $this->hasManyThrough(
            Page::class,
            PageLink::class,
            "parent_page_id", // Foreign key on PageLinks table
            "id", // Foreign key on Pages table
            "id", // Local key on Pages table
            "target_page_id" // Local key on PageLinks table
        );
    }

    public function linkingPages(): HasManyThrough
    {
        return $this->hasManyThrough(
            Page::class,
            PageLink::class,
            "target_page_id", // Foreign key on PageLinks table
            "id", // Foreign key on Pages table
            "id", // Local key on Pages table
            "parent_page_id" // Local key on PageLinks table
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function redirectTo(): BelongsTo
    {
        return $this->belongsTo(Page::class, "redirect_to");
    }

    public function slug(): Attribute
    {
        return Attribute::make(get: fn() => urlencode($this->title));
    }
}
