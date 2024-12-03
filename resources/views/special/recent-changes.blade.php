@php
    $pageTitle = 'Recent Changes';
@endphp

@extends("layout.base")

@section('content')
<p>
    This page shows the log of all recent changes on this wiki.
</p>

<ul class="changes-list">
    @foreach ($changes->groupBy(fn ($ch) => date_format(date_create($ch->date), 'l jS \\of F Y')) as $date => $changes)
    <li class="date">
        <h1>{{ $date }}</h1>
        <ol>
            @foreach ($changes as $summary)
                <li class="change">
                    @if(method_exists($summary->changeable, "getUrl"))
                        <aside>
                            <a href="{{$summary->changeable->getUrl() }}">
                                View {{ class_basename($summary->changeable_type) }}
                            </a>
                        </aside>
                    @endif
                    <header>
                        <h2>
                            ({{ class_basename($summary->changeable_type) }})
                            {{ $summary->changeable->title ?? '#'.$summary->changeable->id }}
                        </h2>
                    </header>
                    <p>
                        {{ Str::apa($summary->type) }}
                        @if($summary->change_count > 1)
                        {{ $summary->change_count }} times
                        @endif
                        by {{ $summary->user->username }}
                        at
                        {{ date_format(date_create($summary->date), "H:i") }}
                    </p>
                </li>
            @endforeach
        </ol>
    </li>
    @endforeach
</ul>

@endsection
