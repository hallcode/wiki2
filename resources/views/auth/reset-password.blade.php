@php
    $pageTitle = 'Reset password';
@endphp

@extends('layout.base')

@section('content')
    <x-form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <section>
            <x-input-label for="email" :value="__('Email')" />
            <x-input-error :messages="$errors->get('email')" />
            <x-text-input id="email"  type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
        </section>

        <!-- Password -->
        <section class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"  type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </section>

        <!-- Confirm Password -->
        <section class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
        </section>

        <section class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </section>
    </x-form>
</x-guest-layout>
