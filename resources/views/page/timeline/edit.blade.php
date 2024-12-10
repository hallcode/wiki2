@php
    if (isset($event)) {
        $pageTitle = 'Edit Event: ' . $event->title;
    } else if ( isset($page) ) {
        $pageTitle = 'Create event for ' . $page->title;
    } else {
        $pageTitle = 'Create Event';
    }

    $formRoute = route('page.timeline', ['slug' => $page->slug]);
    if (!empty($event)) {
        $formRoute = route('page.timeline.save', ['slug' => $page->slug, 'id' => $event->id]);
    }
@endphp

@extends("page.layout")

@section('content')
<x-form action="{{ $formRoute }}" method="post">
    <section class="field">
        <header>
            <x-label for="title" />
            <p><small>
                Be descriptive but brief, this will be used in the "On this day" section.
                Use the present tense.
            </small></p>
            <x-error field="title" class="error" />
        </header>
        <x-input name="title" value="{{ $event->title ?? '' }}" />
    </section>

    <section class="field">
        <header>
            <label>Date</label>
            <x-error field="date" class="error" />
        </header>
        <div class="input-group">
            <label>
                <x-input name="date" style="width: 6ch" value="{{ $event->date->day ?? '' }}" />
                <small>Date</small>
            </label>
            <label>
                <x-input name="month" style="width: 6ch" value="{{ $event->date->month ?? '' }}" />
                <small>Month</small>
            </label>
            <label>
                <x-input name="year" style="width: 10ch" value="{{ $event->date->year ?? '' }}" />
                <small>Year</small>
            </label>
    </section>

    <x-error field="content" class="error" />
    <textarea name="content" id="content-editor">{{ $event->currentVersion->content ?? '' }}</textarea>

    <footer class="fixed">
        @if(empty($event))
            <button class="green">Create</button>
        @else
            <button class="primary">Save</button>
        @endif

    </footer>
</x-form>
@endsection

@push('scripts')
<script>
const easyMDE = new EasyMDE({element: document.getElementById('content-editor')});
</script>
@endpush
