<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageLink extends Model
{
    /**
     * Define the relationship to the parent page that contains the hyperlink.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentPage(): BelongsTo
    {
        return $this->belongsTo(Page::class, "parent_page_id");
    }

    /**
     * Define the relationship to the target page being linked.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function targetPage(): BelongsTo
    {
        return $this->belongsTo(Page::class, "target_page_id");
    }
}
