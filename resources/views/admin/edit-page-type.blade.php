@extends("layout.base")

@php
$route = route('pageType.edit');
if (!empty($pageType)) {
    $route = route('pageType.edit', ['id' => $pageType->id]);
}

$colours = [
    "slate",
    "red",
    "orange",
    "amber",
    "yellow",
    "lime",
    "green",
    "emerald",
    "teal",
    "cyan",
    "sky",
    "blue",
    "indigo",
    "violet",
    "purple",
    "fuchsia",
    "pink",
    "rose",
]
@endphp

@push('left-tabs')
<a href="{{ route('pageType.all') }}">
    <x-heroicon-c-chevron-left />
    Back
</a>
@endpush

@section('content')
<p>
Use this form to create a new page type.
</p>

<x-form action="{{ $route }}">
    <section class="field">
        <header>
            <x-label for="title" />
            <x-error field="title" class="error" />
        </header>
        <x-input type="text" name="title" :value="empty($pageType) ? null : $pageType->title " />
    </section>

    <section class="field">
        <header>
            <x-label for="colour" />
            <x-error field="colour" class="error" />
        </header>
        <aside>
            <select name="colour" id="colour">
                @foreach($colours as $c)
                <option value="{{ $c }}" @if(!empty($pageType) && $pageType->colour == $c)selected @endif>
                    {{ ucfirst($c) }}
                </option>
                @endforeach
            </select>
        </aside>
    </section>

    <section class="field">
        <header>
            <x-label for="description" />
            <x-error field="description" class="error" />
        </header>
        <x-textarea name="description">{{ empty($pageType) ? null : $pageType->description }}</x-textarea>
    </section>

    <section class="field">
        <header>
            <label>Template</label>
            <x-error field="template" class="error" />
        </header>
        <textarea name="template" id="content-editor">{{ empty($pageType) ? null : $pageType->template }}</textarea>
    </section>

    <footer class="fixed">
        @if(empty($pageType))
            <button class="green">Create</button>
        @else
            <button class="primary">Save</button>
            @if($pageType->pages()->count() === 0)
            <x-form-button
                :action="route('pageType.delete', $pageType->id)"
                method="DELETE"
                class="red"
            >
                Delete
            </x-form-button>
            @endif
        @endif

    </footer>
</x-form>
@endsection

@push('scripts')
<script>
const easyMDE = new EasyMDE({element: document.getElementById('content-editor')});
</script>
@endpush
