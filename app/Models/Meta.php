<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = "meta";

    protected $fillable = ["group", "key", "value"];

    public $timestamps = false;

    public function hasMeta()
    {
        return $this->morphs(__FUNCTION__, "has_meta_type", "has_meta_id");
    }
}
