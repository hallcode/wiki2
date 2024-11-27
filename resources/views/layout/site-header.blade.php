<header class="site-header">
    <aside id="left-buttons">
    </aside>

    <section class="masthead">
        <aside>
            {{ env('APP_NAME') }}
        </aside>
        @auth
            <form action="/search" class="site-search">
                <input name="query" type="search">
                <button>
                    <x-heroicon-c-magnifying-glass />
                    Search
                </button>
            </form>
        @endauth
    </section>

    <section>
        <nav class="horizontal-nav">
        @auth
            <button>
                Upload
                <x-heroicon-o-arrow-up-on-square-stack />
            </button>
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
                            <a href="">Media Browser</a>
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
