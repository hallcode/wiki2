<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageType extends Model
{
    protected $fillable = ["title", "colour", "description", "template"];
    public $timestamps = false;

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, "type_id");
    }
}
