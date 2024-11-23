@php
    $pageTitle = 'Recent Changes';
@endphp

@extends("layout.base")

@section('content')
<p>
    This page shows the log of all recent changes on this wiki.
</p>

        @foreach($changes as $key => $group)
        <h5>{{ $key }}</h5>
        <ul class="change-list">
            @foreach($group as $change)
            <li>
                <time datetime="{{ $change->created_at }}">
                    {{ $change->created_at->format('H:i') }}
                </time>

                @if($change->changeable_type == 'App\Models\Page')
                <a href="{{ route('page.view', ['slug' => $change->changeable->slug]) }}">
                    {{ $change->changeable->title }}
                </a>
                @else
                    {{ $change->changeable->title }}
                @endif

                {{ $change->type }}

                by

                {{ $change->user->username }}

                @if($change->description)
                <em>({{$change->description}})</em>
                @endif
            </li>
            @endforeach
        </ul>
        @endforeach

@endsection
