@php
    $pageTitle = 'All Categories';
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('page.all') }}">Pages</a>
<a href="{{ route('cat.all') }}" class="active">Categories</a>
@endpush

@section('content')
    <section>
        @if(count($categories) < 1)
            <div class="default-box">
                <x-heroicon-o-face-frown />
                <header>There are no categories on this wiki</header>
            </div>
        @else


        @foreach($categories->groupBy(fn ($c) => strtoupper(substr($c->title, 0, 1))) as $letter => $group)
            <h5>{{ $letter }}</h5>
            <ol class="page-list">
            @foreach($group as $category)
                <li>
                    <a href="{{ route('cat.view', ['slug' => $category->slug]) }}">{{ $category->title }}</a>
                    <em>({{ $category->pages()->count() }})</em>
                </li>
            @endforeach
            </ol>

        @endforeach
            <p>There are no categories in this wiki!</p>
        @endif

    </section>
@endsection
