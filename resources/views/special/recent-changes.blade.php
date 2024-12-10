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
                @php
                    if (empty($summary->changeable)) {
                        continue;
                    }
                @endphp
                <li class="change">
                    <p>
                        @if(method_exists($summary->changeable, "getUrl"))
                            <a href="{{$summary->changeable->getUrl() }}">
                                {{ $summary->changeable->title ?? '#'.$summary->changeable->id }}
                            </a>
                        @else
                            {{ $summary->changeable->title ?? '#'.$summary->changeable->id }}
                        @endif
                        •
                        @if ($summary->type == 'created')
                            <strong>
                        @endif
                        {{ class_basename($summary->changeable_type) }}
                        {{ $summary->type }}
                        @if ($summary->type == 'created')
                            </strong>
                        @endif
                        @if($summary->change_count > 1)
                        {{ $summary->change_count }} times
                        @endif
                    </p>
                    <ul class="change-meta">
                        <li>{{ date_format(date_create($summary->date), "H:i") }}</li>
                        <li>{!! $summary->user->getTag() !!}</li>
                    </ul>
                </li>
            @endforeach
        </ol>
    </li>
    @endforeach
</ul>

@endsection
