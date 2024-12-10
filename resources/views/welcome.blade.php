@php
    $pageTitle = 'Welcome';
@endphp

@extends("layout.base")

@section('content')

<section class="default-box">
    <header>Welcome to <big>{{ env('APP_NAME') }}</big>!</header>
    <p>The secure wiki that only members can edit.</p>
    <a href="{{ route('page.all') }}">{{ $pageCount }} articles and counting.</a>
</section>

<article class="box-grid">
    <section class="box">
        <header>
            <h1>On this day ({{ Carbon\Carbon::now()->format('jS M') }})</h1>
        </header>
        @forelse($events->groupBy(fn ($e) => $e->date->year) as $year => $events)
            <h4>{{$year}}</h4>
            <ul>
                @foreach($events as $event)
                    <li>{{ $event->title }}</li>
                @endforeach
            </ul>
        @empty
            <p>Nothing happened on this day.</p>
        @endforelse
    </section>

    <section class="box" style="--header-colour: var(--yellow-200)">
        <header>
            <h1>Old pages</h1>
        </header>
        <p>Why not help the wiki out by updating some of these pages.</p>
        <p>These are the most old:</p>
        <ol>
            @forelse($oldPages as $page)
            <li>
                <a href="{{ route('page.view', ['slug' => $page->slug]) }}">{{$page->title}}</a>
                <em> - {{ $page->updated_at->diffForHumans() }}</em>
            </li>
            @empty
                <p>There are no old pages... so probably no pages at all.</p>
            @endforelse
        </ol>
    </section>

    <section class="box" style="--header-colour: var(--cyan-200)">
        <header>
            <h1>Needed pages</h1>
        </header>
        <p>Why not help the wiki out by creating some of these needed pages:</p>
        <ol>
            @forelse($neededPages as $page)
            <li>
                <a href="{{ route('page.view', ['slug' => urlencode($page->link_text)]) }}" class="redlink">
                    {{$page->link_text}}
                </a>
                <em>({{ $page->link_count }})</em>
            </li>
            @empty
                <p>There are needed pages.</p>
            @endforelse
        </ol>
    </section>
</article>

<article>
    <h1>Recent uploads</h1>
    <p>
        <a href="{{ route('media.all') }}">See all</a>
    </p>
    <section class="gallery-wrapper">
        <div class="gallery">
            @foreach($media as $m)
                <figure>
                    <a href="{{ route('media.view', ['slug' => urlencode(Str::apa($m->title))]) }}">
                        <img src="{{ route('media.thumb', ['slug' => urlencode(Str::apa($m->title)), 'size' => 300]) }}" alt="{{ $m->title }}">
                    </a>
                    <figcaption>
                    {{ $m->title }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
</article>

@guest
    If you can see this page sommets gone bare wrong.
@endguest
@endsection
