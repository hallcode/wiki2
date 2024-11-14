@php
    $pageTitle = 'Security check';
@endphp

@extends("layout.base")

@section('content')
    <p>{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>

    <x-form action="{{ route('password.confirm') }}">
        <section>
            <x-input-label for="password" :value="__('Password')" />
            <x-input-error :messages="$errors->get('password')" />
            <x-text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
        </section>

        <section class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </section>
    </x-form>
@endsection
