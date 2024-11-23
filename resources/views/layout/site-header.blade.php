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
            <button class="menu-button">
                <x-heroicon-c-bars-3 />
            </button>
        @else
            <a href="/login">Login</a>
        @endauth
        </nav>
    </section>
</header>
