<?php

namespace App\Models;

use App\Traits\HasVersions;
use App\Traits\TracksChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Str;

class Media extends Model
{
    use SoftDeletes;
    use TracksChanges;
    use HasVersions;

    protected $fillable = ["title"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    /**
     * Get all meta records for this media item.
     */
    public function meta()
    {
        return $this->morphMany(Meta::class, "has_meta");
    }

    public function getThumbnail($size = "400")
    {
        // See if thumbnail exists
        $thumb = $this->uploads()
            ->where("type", "thumb.$size")
            ->first();

        if ($thumb) {
            return $thumb;
        }

        // If it's an image, create the thumbnail
        if ($this->getFileType() == "image") {
            $file = $this->getFile()->getRaw();
            $image = Image::read($file);
            $image = $image->scaleDown(width: $size);
            $folderName = Str::snake($this->title);
            $fileName = $folderName . "__thumb_{$size}.webp";
            $path = Str::ascii("uploads/" . $folderName . "/" . $fileName);

            $encodedImage = $image->toWebp(90);

            Storage::put($path, (string) $encodedImage);

            // Save the upload record
            return Upload::create([
                "file_name" => $fileName,
                "media_id" => $this->id,
                "path" => $path,
                "mime_type" => "image/webp",
                "type" => "thumb.$size",
                "user_id" => auth()->user()->id,
                "size" => strlen($encodedImage),
            ]);
        }
    }

    public function getFile()
    {
        return $this->uploads()
            ->where("type", "original")
            ->orderBy("created_at", "desc")
            ->first();
    }

    public function getFileType()
    {
        $file = $this->getFile();
        $primaryType = explode("/", $file->mime_type)[0];
        return $primaryType;
    }

    public function getPhotoMeta()
    {
        $tags = [
            "COMPUTED.ApertureFNumber",
            "IFD0.Make",
            "IFD0.Model",
            "IFD0.Software",
            "EXIF.ExposureTime",
            "EXIF.ISOSpeedRatings",
            "IFD0.DateTime",
            "EXIF.DateTimeOriginal",
            "EXIF.FocalLength",
            "EXIF.FocalLengthIn35mmFilm",
            "EXIF.ShutterSpeedValue",
        ];

        $tagNames = [
            "COMPUTED.ApertureFNumber" => "Aperture",
            "IFD0.Make" => "Camera Make",
            "IFD0.Model" => "Camera Model",
            "IFD0.Software" => "Software",
            "EXIF.ExposureTime" => "Exposure (seconds)",
            "EXIF.ISOSpeedRatings" => "ISO",
            "IFD0.DateTime" => "Date",
            "EXIF.DateTimeOriginal" => "Date",
            "EXIF.FocalLength" => "Focal Length",
            "EXIF.FocalLengthIn35mmFilm" => "Focal Length (35mm Eq.)",
            "EXIF.ShutterSpeedValue" => "Shutter speed",
        ];

        $data = $this->meta()
            ->where("group", "exif")
            ->whereIn("key", $tags)
            ->get();
        $output = [];
        foreach ($data as $row) {
            $name = $tagNames[$row->key];

            if (preg_match("/[0-9]+\/[0-9]+/", $row->value)) {
                // Calculate int from rational number
                $parts = explode("/", $row->value);
                $output[$name] = $parts[0] / $parts[1];
                continue;
            }

            $output[$name] = $row->value;
        }

        return $output;
    }

    public function getUrl(): string
    {
        return route("media.view", ["slug" => urlencode($this->title)]);
    }
}
