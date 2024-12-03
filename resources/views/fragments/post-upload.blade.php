<section class="upload-form" @click.outside="open = false">
    <header>
        <h1>File Uploaded: {{ $media->title }}</h1>
    </header>

    <p>You can embed this image in a page with the following markup:</p>

    <code>
        [#{{urlencode($media->title)}}]
    </code>

    <p>If you want to include a caption or a size, you can write it like this:</p>

    <code>
        [#{{urlencode($media->title)}}|width|caption...]
    </code>

    <p>The width is in pixels, the height will be calculated to keep the proportions the same.</p>

    <footer>
        <button @click="open = false" form="upload-form" type="reset">Done</button>
    </footer>
</section>
