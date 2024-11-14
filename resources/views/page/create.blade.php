@php
    $pageTitle = 'New Page';
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('page.all') }}">Pages</a>
<a href="">Categories</a>
@endpush

@push('right-tabs')
<a href="{{ route('page.create') }}" class="active">
    <x-heroicon-c-plus />
    Create Page
</a>
@endpush

@section('content')
<p>
Use this form to create a new page.
</p>
<x-form>
    <section class="field">
        <header>
            <x-label for="title" />
            <x-error field="title" class="error" />
        </header>
        <x-input type="text" name="title" />
    </section>

    <section class="field">
        <header>
            <x-label for="type" />
            <x-error field="type" class="error" />
        </header>
        <aside>
            <select name="type" id="type">
                @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                @endforeach
            </select>
            <small>
                <a href="/admin/page-types">Manage page types</a>
            </small>
        </aside>
    </section>

    <footer class="fixed">
        <button class="green">Create</button>
    </footer>
</x-form>
@endsection
