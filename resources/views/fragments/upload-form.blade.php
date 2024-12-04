@php
    $errors = isset($errors) ? $errors : new \Illuminate\Support\MessageBag;
@endphp

<section class="upload-form" @click.outside="open = false">
    <header>
        <h1>Upload Media</h1>
        <p>You can use this form to upload images, video, audio, and documents.</p>
        <p>They will be uploaded and saved, and you will be given the markup that can be used to embed them in pages.</p>
    </header>
    <form id="upload-form"
          hx-post="/upload"
          hx-target="#upload-wrapper"
          hx-swap="innerHTML"
          hx-encoding='multipart/form-data'
    >
        @csrf

        <div id="loading-spinner" class="spinner" style="display: none;">Loading...</div>

        <section id="failed" class="alert danger" style="display: none;">
            <x-heroicon-c-x-mark />
            <header>There was an error on the server when trying to upload your file.</header>
            <p>
                This is probably because the file you tried to upload could not be processed.
                <br />Try converting it to a different format and then upload it again.
            </p>
        </section>

        @if($errors->all())
        <section class="alert danger">
            <x-heroicon-c-x-mark />
            <header>Please check the errors and try again.</header>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </section>
        @endif

        <section class="field">
            <header>
                <x-label for="file" />
            </header>
            <input type="file" name="file">
        </section>

        <section class="field">
            <header>
                <x-label for="title" />
            </header>
            <input type="text" name="title">
        </section>

        <section class="field">
            <header>
                <x-label for="description" />
                <p>
                    <small>
                        Enter a description of the file.
                    </small>
                </p>
            </header>
            <x-textarea name="description"/>
        </section>

    </form>
    <footer>
        <button class="primary" form="upload-form">Upload</button>
        <button @click="open = false" form="upload-form" type="reset">Cancel</button>
        <progress id='progress' value='0' max='100'></progress>

    </footer>
</section>
<script>
htmx.on('#upload-form', 'htmx:xhr:progress', function(evt) {
    htmx.find('#progress').setAttribute('value', evt.detail.loaded/evt.detail.total * 100)
});
</script>
<script>
    document.body.addEventListener("htmx:responseError", function (event) {
        if (event.detail.xhr.status === 500) {
            // Display an error message to the user
            const errorContainer = document.getElementById("failed");
            errorContainer.style.display = "grid"; // Ensure the message is visible
        }
    });
</script>
