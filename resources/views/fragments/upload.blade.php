<div class="upload-button" x-data="{ open: false }">
    <button @click="open = true"
            hx-get="{{ route('upload') }}"
            hx-target="#upload-wrapper"
    >
        Upload
        <x-heroicon-o-arrow-up-on-square-stack />
    </button>
    <div x-show="open" x-transition x-cloak id="upload-wrapper"></div>
</div>
