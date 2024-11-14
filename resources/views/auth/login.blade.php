@php
    $pageTitle = 'Login';
@endphp

@extends('layout.base')

@push('left-tabs')
    @if(env('ALLOW_REGISTRATIONS'))
        <a href="/login" class="active">Login</a>
        <a href="/register">Sign up</a>
    @endif
@endpush

@section('content')
    <x-form method="POST" action="{{ route('login') }}">
        <!-- Email Address -->
        <section>
            <x-input-label for="email" :value="__('Email')"/>
            <x-input-error :messages="$errors->get('email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        </section>

        <!-- Password -->
        <section class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-input-error :messages="$errors->get('password')"/>
            <x-text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
        </section>

        <!-- Remember Me -->
        <section>
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </section>

        <section class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </section>
    </x-form>

@endsection
