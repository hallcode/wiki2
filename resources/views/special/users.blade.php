@php
    $pageTitle = 'Users';
@endphp

@extends("layout.base")

@push('right-tabs')
<a href="">Invite user</a>
@endpush

@section('content')
<p>
    All users:
</p>

<ul>
    @foreach($users as $user)
    <li>
        <a href="">
            {{$user->username}}
        </a>
    </li>
    @endforeach
</ul>
@endsection
