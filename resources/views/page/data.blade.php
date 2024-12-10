@php
    $pageTitle = 'Data: ' . $page->title;
@endphp

@extends("page.layout")

@section('content')
<h2>Pages</h2>
<section style="display: flex; width: 100%; gap: 2ch">
    <section style="flex: 1 1 50%">
        <h3 style="margin-top:0">
            Links to
        </h3>
        <ol>
        @foreach($page->outgoingLinks as $link)
        <li>
            @if(!empty($link->targetPage))
            <a href="{{ route('page.view', ['slug' => urlencode($link->targetPage->title)]) }}">
                {{ $link->targetPage->title }}
            </a>
            @else
            <a href="{{ route('page.view', ['slug' => urlencode($link->link_text)]) }}" class="redlink">
                {{ $link->link_text }}
            </a>
            @endif
        </li>
        @endforeach
        </ol>
    </section>

    <section style="flex: 1 1 50%">
        <h3 style="margin-top:0">
            Linked from
        </h3>
        <ol>
        @foreach($page->incomingLinks as $link)
        <li>
            @if(!empty($link->parentPage))
            <a href="{{ route('page.view', ['slug' => urlencode($link->parentPage->title)]) }}">
                {{ $link->parentPage->title }}
            </a>
            @else
            <a href="{{ route('page.view', ['slug' => urlencode($link->link_text)]) }}" class="redlink">
                {{ $link->link_text }}
            </a>
            @endif
        </li>
        @endforeach
        </ol>
    </section>
</section>
@endsection
