<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layout.html-head')

<body>
    @include('layout.site-header')

    <main id="wrapper">
        <header id="page-header">
            <h1>{{ isset($pageTitle) ? $pageTitle : 'untitled' }}</h1>
            <nav>
                <section>
                    @stack('left-tabs')
                </section>
                <section>
                    @stack('right-tabs')
                </section>
            </nav>
        </header>
        <aside id="sidebar" class="left-sidebar closed">
            @stack('sidebar')

            <nav class="site-menu">
                <ul class="menu-list">
                    <li>
                        <h2>Menu</h2>
                    </li>
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('page.all') }}">All Pages</a>
                    </li>
                    <li>
                        <a href="/">Random Page</a>
                    </li>
                </ul>
            </nav>
        </aside>
        <article>
            @yield('content')
        </article>
        <aside class="right-sidebar">
            @stack('right-sidebar')
        </aside>
    </main>


    @bukScripts
        @stack('scripts')
        <script>
            function toggleSidebar() {
                let sidebar = document.getElementById('sidebar');
                let toggleButton = document.getElementById('sidebar-toggle');

                sidebar.classList.toggle('closed')
                toggleButton.classList.toggle('is-open')
            }

            function setSidebarOpen() {
                let sidebar = document.getElementById('sidebar');
                let toggleButton = document.getElementById('sidebar-toggle');

                sidebar.classList.remove("closed");
                toggleButton.classList.add("is-open");
            }

            window.onload = function () {
                if (window.innerWidth >= 940) {
                    setSidebarOpen();
                }
            }

        </script>
</body>

</html>
