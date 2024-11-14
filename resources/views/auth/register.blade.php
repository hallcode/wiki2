@php
    $pageTitle = 'Create account';
@endphp

@extends('layout.base')

@push('left-tabs')
    @if(env('ALLOW_REGISTRATIONS'))
        <a href="/login">Login</a>
        <a href="/register" class="active">Sign up</a>
    @endif
@endpush

@section('content')
    <x-form method="POST" action="{{ route('register') }}">
        <!-- Name -->
        <section>
            <x-input-label for="username" :value="__('Username')" />
            <x-input-error :messages="$errors->get('username')" />
            <x-text-input id="username" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
        </section>

        <!-- Email Address -->
        <section>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" />
        </section>

        <!-- Password -->
        <section>
            <x-input-label for="password" :value="__('Password')" />
            <x-input-error :messages="$errors->get('password')" />

            <x-text-input id="password"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
        </section>

        <!-- Confirm Password -->
        <section>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-input-error :messages="$errors->get('password_confirmation')"/>

            <x-text-input id="password_confirmation"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
        </section>

        <section>
            <a href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </section>
    </x-form>
    @endsection
