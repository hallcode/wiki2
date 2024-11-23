@php
    $pageTitle = $page->title;
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('page.view', ['slug' => $page->slug]) }}" class="active">Article</a>
<a href="{{ route('page.timeline', ['slug' => $page->slug]) }}">Timeline</a>
<a href="/">Data</a>
@endpush

@push('right-tabs')
<a href="{{ route('page.view', ['slug' => $page->slug]) }}" class="active">Read</a>
<a href="{{ route('page.edit', ['slug' => $page->slug]) }}">Edit</a>
<a href="{{ route('page.history', ['slug' => $page->slug]) }}">View History</a>
@endpush

@push('sidebar')
@if(count($version->getHeadings()) > 2)
<aside class="article-contents">
    <h1>Contents</h1>
    <ul>
    @foreach($version->getHeadings() as $h)
        <li class="{{$h['level']}}">
            <x-heroicon-c-chevron-right />
            <a href="#{{ $h['id'] }}">
                {{ $h["text"] }}
            </a>
        </li>
    @endforeach
    <ul>
</aside>
@endif
@endpush

@push('right-sidebar')
<div id="infobox-wrapper" x-data={open:false} x-class="open ? 'show' ' : ''">
    <button id="infobox-toggle" @click="open = !open">Infobox</button>
    <div class="panel" x-show="open" x-transition>
        {!! $version->getInfoBoxHtml() !!}
    </div>
</div>
@endpush

@push('page-class')
{{ $page->type->colour }}
@endpush()

@section('content')
@if($page->currentVersion->id != $version->id)
<aside class="alert warning">
    <x-heroicon-o-exclamation-triangle />
    <header>You are currently viewing a previous version of this page!</header>
    <a href="{{ route('page.view', ['slug' => $page->slug]) }}">
        Go to the current version.
    </a>
</aside>
@endif

{!! $version->getHtml() !!}

@if($page->categories()->count() > 0)
<section class="info-bar">
    <header>Categories</header>
    <ul>
        @foreach($page->categories->pluck("title") as $title)
        <li>
            <a href="/">{{ $title }}</a>
        </li>
        @endforeach
    </ul>
</section>
@endif

<footer class="article-footer" style="background-color: var(--{{ $ageColour }}-100)">
Last updated
{{ $version->created_at->diffForHumans() }}
by
{{ $version->user->username }}.
</footer>
@endsection
