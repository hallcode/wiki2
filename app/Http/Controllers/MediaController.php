<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Str;

class MediaController extends Controller
{
    public function all()
    {
        $media = Media::has("uploads")
            ->orderBy("created_at", "desc")
            ->paginate(15);
        return view("media.all", ["media" => $media]);
    }

    public function single(string $slug)
    {
        $title = urldecode($slug);
        $media = Media::where("title", $title)->firstOrFail();
        return view("media.single", ["media" => $media]);
    }

    public function getFile(string $fileName)
    {
        $file = Upload::where("file_name", $fileName)->firstOrFail();
        return $file->response();
    }

    public function getThumbnail(string $slug, $size)
    {
        $file = Media::where(
            "title",
            Str::apa(urldecode($slug))
        )->firstOrFail();
        return $file->getThumbnail($size)->response();
    }
}
