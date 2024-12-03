@php
    $pageTitle = 'Search';
@endphp

@extends("layout.base")

@section('content')
<p>
    Search results:
</p>

<ul>
    @foreach($pages as $page)
    <li>
        <a href="{{ route('page.view', ['slug' => $page->slug]) }}">
            {{$page->title}}
        </a>
    </li>
    @endforeach
</ul>
@endsection
