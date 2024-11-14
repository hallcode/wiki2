<header class="site-header">
    <aside>
        <button id="sidebar-toggle" class="menu-button" onclick="toggleSidebar()">
            <x-heroicon-c-chevron-double-left class="close" />
            <x-heroicon-c-chevron-double-right class="open" />
        </button>
    </aside>

    <section class="masthead">
        <aside>
            Logo
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
        @auth
            <button class="menu-button" onclick="toggleUserMenu()">
                <x-heroicon-c-bars-3 />
            </button>
            <nav id="user-menu" class="user-menu closed">
                <header>
                    User Menu
                    <button class="menu-button" onclick="toggleUserMenu()">
                        <x-heroicon-c-x-mark />
                    </button>
                </header>
                <a href="{{ route('profile.edit') }}">Profile</a>
                @stack('user-menu')
                <a href="#">Settings</a>
                <x-logout />
            </nav>
        @else
            <a href="/login">Login</a>
        @endauth
    </section>

</header>


@push('scripts')
    <script>
        function toggleUserMenu() {
            let userMenu = document.getElementById('user-menu');
            userMenu.classList.toggle('closed')
        }

    </script>
@endpush
