@php
    $pageTitle = 'Welcome';
@endphp

@extends("layout.base")

@section('content')
    <a href="{{ route('page.all') }}" class="block-link">
        <h1>Welcome to {{ env('APP_NAME') }}!</h1>
        <p>The secure wiki that only members can edit.</p>
        <p>{{ $pageCount }} articles and counting.</p>
    </a>

<article class="box-grid">
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
                <em> - Updated {{ $page->updated_at->diffForHumans() }}</em>
            </li>
            @empty
                <p>There are no old pages... so probably no pages at all.</p>
            @endforelse
        </ol>
    </section>

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
</article>

@guest
    If you can see this page sommets gone bare wrong.
@endguest
@endsection
