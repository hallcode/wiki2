@php
    $pageTitle = 'Forgot password';
@endphp

@extends("layout.base")

@section('content')
    <p>
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-form method="POST" action="{{ route('password.email') }}">
        <!-- Email Address -->
        <section>
            <x-input-label for="email" :value="__('Email')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
        </section>

        <section>
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </section>
    </x-form>
    @endsection
