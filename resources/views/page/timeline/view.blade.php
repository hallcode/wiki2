@php
    $pageTitle = 'Timeline for ' . $page->title;

    // Sort and group events
    $events = $page->events()->orderBy('date', 'desc')->get();
    $events = $events->groupBy(function(App\Models\Event $item, int $key) {
        return $item->date->year;
    });

    $contents = [];
@endphp

@extends("page.layout")

@push('right-tabs')
<a href="{{ route('page.timeline.create', ['slug' => $page->slug]) }}">
    <x-heroicon-c-plus />
    Create Event
</a>
@endpush

@section('content')
    @if($page->events()->count() < 1)
    <div class="default-box">
        <x-heroicon-o-face-frown />
        <header>There are no events on this timeline.</header>
        <p>You can create one using the 'Create Event' link at the top of this page</p>
    </div>
    @else

    <article class="timeline">
        @foreach($events as $year => $yearEvents)
            @php
                $contents[] = [
                    "level" => "h2",
                    "text" => $year,
                    "id" => "y__$year",
                ];
            @endphp
            <section class="year-section">
                <h1 id="y__{{ $year }}" class="year">{{ $year }}</h1>
                @foreach($yearEvents->groupBy(fn ($e) => $e->date->englishMonth) as $month => $events)
                    @php
                        $contents[] = [
                            "level" => "h3",
                            "text" => $month,
                            "id" => "m__" . strtolower($month) . "_$year",
                        ];
                    @endphp
                    <section class="month-section">
                        <h2 id="m__{{ strtolower($month) }}_{{ $year }}" class="month">
                            {{$month}}
                        </h2>
                        <ul class="dates">
                            @foreach($events->groupBy(fn ($e) => $e->date->day) as $date)
                                <li class="date-section">
                                    <header class="date">
                                        {{$date[0]->date->day}}
                                        <sup>{{$date[0]->date->format('S')}}</sup>
                                    </header>
                                    <ul class="events">
                                        @foreach($date as $event)
                                        <li class="details">
                                            <div>
                                                <h4 class="event-title">{{$event->title}}</h4>
                                                {!! $event->currentVersion->getHtml() !!}
                                            </div>
                                            <nav class="controls">
                                                <a href="{{ route('page.timeline.edit', ['slug' => $page->slug, 'id' => $event->id]) }}">Edit</a>
                                                | <x-form-button method="delete" :action="route('event.delete', ['id' => $event->id])" class="link-button">
                                                    Delete
                                                </x-form-button>
                                            </nav>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </section>
        @endforeach
    <article>

    @endif
@endsection


@push('sidebar')
<aside class="article-contents">
    <h1>Contents</h1>
    <ul>
    @foreach($contents as $h)
        <li class="{{$h['level']}}">
            <x-heroicon-c-chevron-right />
            <a href="#{{ $h['id'] }}">
                {{ $h["text"] }}
            </a>
        </li>
    @endforeach
    <ul>
</aside>
@endpush
