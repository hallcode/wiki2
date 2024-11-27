@php
    $pageTitle = 'History: ' . $page->title;
    $versions = $page->versions()->orderBy('created_at', 'desc')->get();
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('page.view', ['slug' => $page->slug]) }}" class="active">Article</a>
<a href="{{ route('page.timeline', ['slug' => $page->slug]) }}">Timeline</a>
@endpush

@push('right-tabs')
<a href="{{ route('page.view', ['slug' => $page->slug]) }}">Read</a>
<a href="{{ route('page.edit', ['slug' => $page->slug]) }}">Edit</a>
<a href="{{ route('page.history', ['slug' => $page->slug]) }}" class="active">View History</a>
@endpush

@section('content')
<p>
    This page was created on the {{ $page->created_at->format('jS F Y \a\t H:i') }},
    by user {{ $page->user->username }}.
</p>

<table>
    <caption>
        Revisions
    </caption>
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Date</th>
            <th>User</th>
            <th>Word count</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($versions as $version)
        <tr>
            <td>
            @if($version->id == $page->currentVersion->id)
                <x-heroicon-c-check-circle />
                Current
            @endif
            </td>
            <td>
                <abbr title="{{$version->id}}">
                    ...{{ substr($version->id, 20) }}
                </abbr>
            </td>
            <td>
                {{ $version->created_at->format('j F Y \@ H:i') }}
            </td>
            <td>
                {{ $version->user->username }}
            </td>
            <td>
                @if ($loop->index < count($versions) - 1)
                    @if($version->word_count > $versions[$loop->index + 1]->word_count)
                        <span class="word-count" style="color: var(--green-800)">
                            <x-heroicon-c-chevron-up />
                            {{ $version->word_count }}
                            ({{ $version->word_count - $versions[$loop->index + 1]->word_count }})
                        </span>
                    @elseif($version->word_count < $versions[$loop->index + 1]->word_count)
                        <span class="word-count" style="color: var(--red-800)">
                            <x-heroicon-c-chevron-down />
                            {{ $version->word_count }}
                            ({{ $version->word_count - $versions[$loop->index + 1]->word_count }})

                        </span>
                    @else
                        <span class="word-count" style="color: var(--blue-800)">
                            <x-heroicon-c-minus />
                            {{ $version->word_count }}
                        </span>
                    @endif
                @else
                <span class="word-count" style="color: var(--blue-800)">{{ $version->word_count }}</span>
                @endif
            </td>
            <td>
                <a href="{{ route('page.view', ['slug' => $page->slug, 'version' => $version->id]) }}">
                    View
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
