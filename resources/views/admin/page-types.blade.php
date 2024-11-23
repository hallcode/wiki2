@php
    $pageTitle = 'Page Types';
@endphp

@extends("layout.base")

@push('right-tabs')
<a href="{{ route('pageType.create') }}">
    <x-heroicon-c-plus />
    Create Page Type
</a>
@endpush

@section('content')
<p>
    Every page must have a type; this describes what the page is. It is similar to a category
    except that each page will only have one type, so whereas a page can have many overlapping categories,
    the page types are mutually exclusive (i.e. a page cannot be a Person and a Building).
</p>

<h3>Page types</h3>

<ul class="page-list">
    @foreach($pageTypes as $pt)
    <li>
        <a href="{{ route('pageType.edit', ['id' => $pt->id]) }}">
            {{ $pt->title }}
        </a>
    </li>
    @endforeach
</ul>
</section>
@endsection
