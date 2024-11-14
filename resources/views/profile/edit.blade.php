@php
    $pageTitle = 'Profile';
@endphp

@extends("layout.base")

@section('content')
    <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        @include('profile.partials.update-profile-information-form')
    </section>

    <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        @include('profile.partials.update-password-form')
    </section>

    <section class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        @include('profile.partials.delete-user-form')
    </section>
@endsection
