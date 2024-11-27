@php
    $pageTitle = 'Category: ' . $category->title;
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('page.all') }}">Pages</a>
<a href="{{ route('cat.all') }}" class="active">Categories</a>
@endpush

@push('right-tabs')
<a href="{{ route('cat.edit', ['id' => $category->id]) }}">Edit Category</a>
@endpush

@section('content')
    <section>
        {!! $category->currentVersion->getHtml() !!}


        @forelse($category->pages->groupBy(fn ($c) => strtoupper(substr($c->title, 0, 1))) as $letter => $pages)
            <h5>{{ $letter }}</h5>
            <ol class="page-list">
            @foreach($pages as $page)
                <li>
                    <a href="{{ route('page.view', ['slug' => $page->slug]) }}">{{ $page->title }}</a>
                </li>
            @endforeach
            </ol>

        @empty
            <p>This category has no pages</p>
        @endforelse

    </section>
@endsection
