@php
    $pageTitle = 'All Pages';
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('page.all') }}" class="active">Pages</a>
<a href="">Categories</a>
@endpush

@push('right-tabs')
<a href="{{ route('page.create') }}">
    <x-heroicon-c-plus />
    Create Page
</a>
@endpush


@section('content')
    <p>All pages</p>

    <section>
        @if(count($pages) < 1)
            <div class="default-box">
                <x-heroicon-o-face-frown />
                <header>There are no pages on this wiki</header>
                <p>You can create one using the 'create page' link at the top of this page</p>
            </div>
        @else

        <ol>
        @foreach($pages as $page)
            <li>
                <a href="/">{{ $page->title }}</a>
            </li>
        @endforeach
        </ol>

        @endif


    </section>
@endsection
