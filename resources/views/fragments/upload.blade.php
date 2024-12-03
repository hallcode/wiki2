<div x-data="{ open: false }">
    <button @click="open = true">
        Upload
        <x-heroicon-o-arrow-up-on-square-stack />
    </button>
    <div x-show="open" x-transition x-cloak id="upload-wrapper">
        @include('fragments.upload-form')
    </div>
</div>
