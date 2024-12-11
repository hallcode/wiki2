@php
    $pageTitle = 'Edit: ' . $page->title;
@endphp

@extends("page.layout")

@section('content')
<x-form action="{{ route('page.edit', ['slug' => $page->slug]) }}">

    <section class="field">
        <header>
            <x-label for="summary" />
            <p>
                <small>
                    Enter a breif description of the change you are making.
                </small>
            </p>
        </header>
        <x-textarea placeholder="Optional" name="summary"/>
    </section>

    <textarea name="content" id="content-editor">{{ $page->currentVersion->content }}</textarea>

    <section class="field">
        <header>
            <label>Categories</label>
        </header>
        <div x-data="tagInput()" x-init="$nextTick(() => loadTags())">
            <!-- Input for new tag -->
            <input
                type="text"
                placeholder="Add a category..."
                x-model="newTag"
                @keydown.enter.prevent="addTag()"
            />
            <small>Press enter to add</small>

            <!-- List of tags -->
            <ul class="category-input-list">
                <template x-for="(tag, index) in tags" :key="index">
                    <li>
                        <input name="categories[]" hidden :value="tag">
                        <span x-text="tag"></span>
                        <button @click.prevent="removeTag(index)">
                            <x-heroicon-c-x-mark />
                        </button>
                    </li>
                </template>
            </ul>
        </div>
    </section>

    <footer class="fixed">
        <button class="primary">Save</button>
        <!-- <button class="red">Archive page</button> -->
    </footer>
</x-form>

@endsection

@push('scripts')
<script>
const easyMDE = new EasyMDE({element: document.getElementById('content-editor')});
</script>

<script>
    var categories = [
        @if(isset($page))
            @foreach($page->categories->pluck("title") as $title)
            "{{ $title }}",
            @endforeach
        @endif
    ]

    function tagInput() {
        return {
            newTag: '',
            tags: [],
            addTag() {
                if (this.newTag.trim() !== '' && !this.tags.includes(this.newTag.trim())) {
                    this.tags.push(this.newTag.trim());
                }
                this.newTag = '';
            },
            removeTag(index) {
                this.tags.splice(index, 1);
            },
            loadTags() {
                if (this.tags.length > 0) {
                    return;
                }
                this.tags = categories;
            }
        };
    }
</script>
@endpush
