<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;
use App\Models\User;
use App\Models\Upload;
use App\Models\Media;
use App\Models\Meta;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class UploadController extends Controller
{
    protected $ALLOWED_MIME_TYPES = [
        // "application/msword",
        // "application/pdf",
        // "text/csv",
        // "application/vnd.ms-excel",
        // "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        // "audio/aiff",
        // "audio/3gpp2",
        // "audio/aacp",
        // "audio/adpcm",
        // "audio/flac",
        // "audio/mp4",
        // "audio/mpeg",
        // "audio/ogg",
        // "audio/opus",
        // "audio/vnd.wav",
        // "audio/webm",
        // "audio/x-matroska",
        // "audio/x-ms-wma",
        "image/avif",
        "image/bmp",
        "image/gif",
        "image/heic",
        "image/jpeg",
        "image/pjpeg",
        "image/png",
        "image/svg+xml",
        "image/webp",
        "image/x-adobe-dng",
        "image/x-canon-cr2",
        "image/x-canon-crw",
        "image/x-cmu-raster",
        "image/x-cmx",
        "image/x-epson-erf",
        "image/x-freehand",
        "image/x-fuji-raf",
        "image/x-kodak-dcr",
        "image/x-kodak-k25",
        "image/x-kodak-kdc",
        "image/x-minolta-mrw",
        "image/x-nikon-nef",
        "image/x-olympus-orf",
        "image/x-panasonic-raw",
        "image/x-pentax-pef",
        "image/x-sigma-x3f",
        "image/x-sony-arw",
        "image/x-sony-sr2",
        "image/x-sony-srf",
        // "text/tab-separated-values",
        // "video/3gpp",
        // "video/3gpp2",
        // "video/h261",
        // "video/h263",
        // "video/h264",
        // "video/mp4",
        // "video/mpeg",
        // "video/ogg",
        // "video/quicktime",
        // "video/webm",
        // "video/x-m4v",
        // "video/x-matroska",
        // "video/x-ms-wmv",
        // "video/x-msvideo",
    ];

    /**
     * Recieves a new file from the user, saves it in the right place and
     * returns the view fragment with the new file info and instructions to the user.
     */
    public function store(Request $request)
    {
        $maxUpload = env("MAX_UPLOAD_SIZE", 2000);
        $validator = Validator::make($request->all(), [
            "title" => "required|string|max:{$maxUpload}|unique:media",
            "file" =>
                "required|file|max:512000|mimetypes:" .
                implode(",", $this->ALLOWED_MIME_TYPES),
            "description" => "nullable|string|max:500",
        ]);

        if ($validator->fails()) {
            // Yes, not good to return a 200 when its an error, but the front-end
            // won't display the form unless a 2xx code is returned.
            return response()->view(
                "fragments.upload-form",
                [
                    "errors" => $validator->errors(),
                ],
                200
            );
        }

        $file = $request->file("file");

        // Get and save the metadata
        // Read the image first so if it fails we don't get a ton of mess left behind
        // in the database.
        $img = Image::read($file);

        $media = Media::create(["title" => Str::apa($request->title)]);
        $media->createVersion($request->get("description", "") ?? "");

        $folderName = Str::snake($media->title);
        $fileName =
            $folderName . "__original." . $file->getClientOriginalExtension();
        $path = Storage::putFileAs(
            Str::ascii("uploads/" . $folderName),
            $file,
            Str::ascii($fileName)
        );

        // Create the upload
        $upload = new Upload();
        $upload->media()->associate($media);
        $upload->user()->associate(auth()->user());
        $upload->file_name = Str::ascii($fileName);
        $upload->path = $path;
        $upload->type = "original";
        $upload->mime_type = $file->getMimeType();
        $upload->size = $file->getSize();
        $upload->save();

        $exifData = $img->exif();
        if (!empty($exifData)) {
            foreach ($img->exif() as $tag => $value) {
                try {
                    $this->storeExifMeta($media->id, $tag, $value);
                } catch (Exception $e) {
                    continue;
                }
            }
        }

        return view("fragments.post-upload", [
            "media" => $media,
            "upload" => $upload,
        ]);
    }

    protected function storeExifMeta(int $mediaId, string $key, mixed $value)
    {
        if (empty($value)) {
            return;
        }

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $this->storeExifMeta($mediaId, "{$key}.{$k}", $v);
            }
            return;
        }

        Meta::create([
            "has_meta_type" => Media::class,
            "has_meta_id" => $mediaId,
            "group" => "exif",
            "key" => $key,
            "value" => Str::ascii((string) $value),
        ]);
    }

    public function getBlankForm()
    {
        return view("fragments.upload-form");
    }
}
