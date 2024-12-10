@php
    $pageTitle = 'Profile';
@endphp

@extends("layout.base")

@section('content')
    <section>
        @include('profile.partials.update-profile-information-form')
    </section>

    <section>
        <h2>Profile page</h2>
        <p>Here you can enter a name of a page you'd like your user account to link to.</p>
        <p>Wherever your username appears, it will link to this page.</p>
        <form method="post" action="{{ route('profile.updatePage') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')
            <section class="field">
                <header>
                    <label for="pageName">Page name</label>
                    <x-input-error :messages="$errors->get('pageName')" />
                </header>
                @if($user->page)
                    <input id="pageName" type="text" name="pageName" value="{{ $user->page->title }}">
                @else
                    <input id="pageName" type="text" name="pageName">
                @endif
            </section>
            <footer>
                <button class="primary">Save</button>
            </footer>
        </form>
    </section>

    <section>
        @include('profile.partials.update-password-form')
    </section>
@endsection
