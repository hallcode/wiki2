@php
    $pageTitle = 'Welcome';
@endphp

@extends("layout.base")

@section('content')
    <p>Welcome to the home page</p>

    @guest
        If you can see this page sommets gone bare wrong.
    @endguest
@endsection
