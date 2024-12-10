@php
    $leftTabs = [
        'Article' => 'page.view',
        'Timeline' => 'page.timeline',
        'Data' => 'page.data',
    ];

    $rightTabs = [
        'Read' => 'page.view',
        'Edit' => 'page.edit',
        'View History' => 'page.history'
    ];
@endphp

@extends("layout.base")

@push('left-tabs')
@foreach($leftTabs as $title => $route)
<a href="{{ route($route, ['slug' => $page->slug]) }}" class="{{ request()->routeIs($route) ? 'active' : '' }}">
    {{ $title }}
</a>
@endforeach
@endpush

@push('right-tabs')
@foreach($rightTabs as $title => $route)
<a href="{{ route($route, ['slug' => $page->slug]) }}" class="{{ request()->routeIs($route) ? 'active' : '' }}">
    {{ $title }}
</a>
@endforeach
@endpush

@section('content')
@yield('content')
@endsection
