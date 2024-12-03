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
                        {{ date_format(date_create($summary->date), "H:i") }} -
                        @if ($summary->type == 'created')
                            <strong>
                        @endif
                        {{ class_basename($summary->changeable_type) }}
                        {{ Str::apa($summary->type) }}:
                        @if ($summary->type == 'created')
                            </strong>
                        @endif
                        @if(method_exists($summary->changeable, "getUrl"))
                            <a href="{{$summary->changeable->getUrl() }}">
                                {{ $summary->changeable->title ?? '#'.$summary->changeable->id }}
                            </a>
                        @else
                            {{ $summary->changeable->title ?? '#'.$summary->changeable->id }}
                        @endif
                        -
                        @if($summary->change_count > 1)
                        {{ $summary->change_count }} times
                        @endif
                        by {{ $summary->user->username }}
                </li>
            @endforeach
        </ol>
    </li>
    @endforeach
</ul>

@endsection
