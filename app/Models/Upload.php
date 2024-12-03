<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class Upload extends Model
{
    use HasUlids;

    protected $fillable = [
        "file_name",
        "media_id",
        "path",
        "size",
        "mime_type",
        "user_id",
        "type",
    ];

    protected $touches = ["media"];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function response()
    {
        return response(Storage::get($this->path), 200)
            ->header("Content-Type", $this->mime_type)
            ->header(
                "Content-Disposition",
                'inline; filename="' . $this->file_name . '"'
            );
    }

    public function url(): string
    {
        return route("file.view", ["fileName" => $this->file_name]);
    }

    public function getRaw()
    {
        return Storage::get($this->path);
    }

    public function getDimensions()
    {
        $file = $this->getRaw();
        $img = Image::read($file);
        return ["height" => $img->height(), "width" => $img->width()];
    }
}
