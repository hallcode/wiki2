<?php

namespace App\Models;

use App\Parsers\LinkParser;
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

    protected static $dontTrack = ["updated"];

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

    /**
     * Make the title pretty
     */
    protected function content(): Attribute
    {
        return Attribute::make(set: fn(string $value) => Str::apa($value));
    }

    public function calculateLinks($text = null)
    {
        if ($text == null) {
            $text = $this->currentVersion->content;
        }
        // Delete existing links before we recalculate
        PageLink::where("parent_page_id", $this->id)->delete();

        $parser = new LinkParser($text);
        $links = $parser->getLinks();
        foreach ($links as $link) {
            $pageLink = new PageLink();
            $pageLink->parent_page_id = $this->id;
            $pageLink->link_text = $link[1];

            $pageLink->target_exists = false;
            $duplicates = PageLink::where("parent_page_id", $this->id)->where(
                "link_text",
                $pageLink->link_text
            );

            $targetPage = Page::where("title", $link[1])->get();
            if ($targetPage->count() > 0) {
                $targetPage = $targetPage->first();
                $pageLink->target_exists = true;
                $pageLink->target_page_id = $targetPage->id;

                if ($link[2] == "") {
                    $pageLink->link_text = $link[1];
                } else {
                    $pageLink->link_text = $link[2];
                }

                $duplicates->where("target_page_id", $targetPage->id);
            }

            // Check for duplicates
            if ($duplicates->count() > 0) {
                continue;
            }

            $pageLink->save();
        }
    }

    public function slug(): Attribute
    {
        return Attribute::make(get: fn() => urlencode($this->title));
    }
}
