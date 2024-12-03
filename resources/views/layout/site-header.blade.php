<header class="site-header">
    <aside id="left-buttons">
    </aside>

    <section class="masthead" x-data="{ open: false }">
        <aside>
            {{ env('APP_NAME') }}
        </aside>
        @auth
            <form action="/search" class="site-search">
                <input name="full" value="true" hidden />
                <input name="query"
                       autocomplete="off"
                       type="search"
                       hx-get="/search"
                       hx-trigger="keyup delay:300ms changed"
                       hx-target="#search-results"
                       @keyup="$event.target.value.length > 2 ? open = true : open = false"
                >
                <button>
                    <x-heroicon-c-magnifying-glass />
                    Search
                </button>
            </form>
            <article id="search-results" x-show="open" x-transition @click.outside="open = false" x-cloak></article>
        @endauth
    </section>

    <section>
        <nav class="horizontal-nav">
        @auth
            @include('fragments.upload')
            <div x-data="{ open: false }">
                <button class="menu-button" @click="open = !open" @click.outside="open = false">
                    <x-heroicon-c-bars-3 />
                </button>
                <nav x-show="open" x-transition class="user-menu" x-cloak>
                    <header>
                        <h1>User Menu</h1>
                        <button class="menu-button" @click="open = !open" @click.outside="open = false">
                            <x-heroicon-c-x-mark />
                        </button>
                    </header>

                    <ul>
                        <li>
                            <a href="{{ route('profile.edit') }}">Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('media.all') }}">Media Browser</a>
                        </li>
                        <li>
                            <x-form-button action="/logout" class="link-button">
                                Logout
                            </x-form-button>
                        </li>
                    </ul>
                </nav>
            </div>
        @else
            <a href="/login">Login</a>
        @endauth
        </nav>
    </section>
</header>


@push('scripts')
<script>

</script>
@endpush
