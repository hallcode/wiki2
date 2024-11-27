<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasUlids;

    protected $fillable = [
        "title",
        "mime_type",
        "directory",
        "size",
        "filename",
        "user_id",
        "type",
    ];

    protected $touches = ["media"];

    public function media()
    {
        $this->belongsTo(Media::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
