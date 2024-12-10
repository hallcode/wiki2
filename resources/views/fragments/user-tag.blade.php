@if($user->page)
    <a class="user-tag" href="{{ route('page.view', ['slug' => $user->page->slug]) }}">
@else
    <span class="user-tag">
@endif
        <x-heroicon-s-user />
        {{ $user->username }}
@if($user->page)
    </a>
@else
    </span>
@endif
