@php
    $pageTitle = 'Media: ' . $media->title;
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('media.all') }}">
    <x-heroicon-c-chevron-left />
    All Media
</a>
@endpush

@section('content')
@if($media->getFileType() == 'image')
<figure class="media-main">
    <img src="{{ route('media.thumb', ['slug' => urlencode($media->title), 'size' => 750]) }}" alt="{{ $media->title }}">
</figure>
<nav class="links">
    <code>[#{{ urlencode($media->title) }}|230|Optional caption]</code>
    <a href="{{ route('file.view', ['fileName' => $media->getFile()->file_name]) }}">View Original</a>
</nav>
@endif

<article>
    <h2>Description</h2>
    {!! $media->currentVersion->getHtml() !!}

    <h2>Metadata</h2>
    <table class="metatable">
        <tbody>
            <tr>
                <td>File type</td>
                <td>{{ $media->getFile()->mime_type }}</td>
            </tr>
            <tr>
                <td>File size</td>
                <td>{{ $media->getFile()->size * 0.000001 }} (Mb)</td>
            </tr>
            <tr>
                <td>Dimensions</td>
                <td>{{ $media->getFile()->getDimensions()['width'] }}x{{ $media->getFile()->getDimensions()['height'] }} (px)</td>
            </tr>
            <tr>
                <td>Uploaded by</td>
                <td>{{ $media->getFile()->user->username }}</td>
            </tr>
            <tr>
                <td>Uploaded on</td>
                <td>{{ $media->getFile()->created_at }}</td>
            </tr>
            @if(count($media->getPhotoMeta()) > 0)
            <th colspan="2">Exif Data</th>
            @endif
            @foreach($media->getPhotoMeta() as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</article>
@endsection
