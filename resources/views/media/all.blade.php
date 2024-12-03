@php
    $pageTitle = 'Media Library';
@endphp

@extends("layout.base")

@section('content')
    <section>
        @if($media->total() < 1)
            <div class="default-box">
                <x-heroicon-o-face-frown />
                <header>Nothing has been uploaded to this wiki!</header>
            </div>
        @else

        <div class="media-grid">
            @foreach($media as $m)
                @if($m->getFileType() == "image")
                <a href="{{ route('media.view', ['slug' => urlencode($m->title)]) }}">
                    <figure>
                        <img src="{{ route('media.thumb', ['slug' => urlencode($m->title), 'size' => 250]) }}" alt="{{ $m->title }}">
                        <figcaption>{{ $m->title }}</figcaption>
                    </figure>
                </a>
                @else
                <figure>
                    <figcaption>{{ $m->title }}</figcaption>
                </figure>
                @endif
            @endforeach
        </div>

        <footer class="paginator">
            <aside>
                @if(!$media->onFirstPage())
                <a href="{{ $media->previousPageUrl() }}">
                    <x-heroicon-o-chevron-left />
                    Previous
                </a>
                @endif
            </aside>
            <nav>
                @if(!$media->onFirstPage())
                    @foreach($media->getUrlRange(1, $media->currentPage() -1) as $i => $link)
                    <a href="{{$link}}">{{ $i }}</a>
                    @endforeach
                @endif

                <a href="/" class="current-page">{{ $media->currentPage() }}</a>

                @if($media->currentPage() < $media->lastPage())
                    @foreach($media->getUrlRange($media->currentPage() +1, $media->lastPage()) as $i => $link)
                    <a href="{{$link}}">{{ $i }}</a>
                    @endforeach
                @endif
            </nav>
            <aside>
                @if($media->hasMorePages())
                <a href="{{ $media->nextPageUrl() }}">
                    Next
                    <x-heroicon-o-chevron-right />
                </a>
                @endif
            </aside>
        </footer>

        @endif
    </section>
@endsection
