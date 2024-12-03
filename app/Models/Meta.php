<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends Model
{
    protected $table = "meta";

    protected $fillable = [
        "has_meta_type",
        "has_meta_id",
        "group",
        "key",
        "value",
    ];

    public $timestamps = false;

    /**
     * Get the parent model of the meta record.
     */
    public function hasMeta(): MorphTo
    {
        return $this->morphTo();
    }
}
