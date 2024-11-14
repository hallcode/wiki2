@php
    $pageTitle = 'Edit: ' . $page->title;
@endphp

@extends("layout.base")

@push('left-tabs')
<a href="{{ route('page.view', ['slug' => $page->slug]) }}">View</a>
@endpush

@push('right-tabs')
<a href="{{ route('page.edit', ['slug' => $page->slug]) }}" class="active">Edit</a>
<a href="">View History</a>
@endpush


@section('content')
<x-form>
    <textarea name="content" id="content-editor">{{ $page->currentVersion->content }}</textarea>

    <footer class="fixed">
        <button class="primary">Save</button>
        <button class="red">Archive page</button>
    </footer>
</x-form>

@endsection

@push('scripts')
<script>
const easyMDE = new EasyMDE({element: document.getElementById('content-editor')});
</script>
@endpush
