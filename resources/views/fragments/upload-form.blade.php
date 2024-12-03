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
          enctype="multipart/form-data"
    >
        @csrf

        <div id="loading-spinner" class="spinner" style="display: none;">Loading...</div>

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
    </footer>
</section>
