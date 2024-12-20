<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layout.html-head')

<body>
    @include('layout.site-header')
    <main>
        <article>
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
            <div class="base-wrapper @stack('page-class')">
                <aside id="sidebar" class="left-sidebar" x-data="{ open: false }" x-show="open" x-transition @click.outside="open = false"x-cloak>
                    <template x-teleport="#left-buttons">
                        <button id="sidebar-toggle" class="menu-button" @click="open = !open">
                            <x-heroicon-c-chevron-double-left x-show="open" />
                            <x-heroicon-c-chevron-double-right x-show="!open" />
                        </button>
                    </template>
                    @stack('sidebar')
                    <nav class="site-menu">
                        <ul class="menu-list">
                            <li>
                                <h2>Pages Menu</h2>
                            </li>
                            <li>
                                <a href="/">Home</a>
                            </li>
                            <li>
                                <a href="{{ route('page.all') }}">All Pages</a>
                            </li>
                            <li>
                                <a href="{{ route('cat.all') }}">Categories</a>
                            </li>
                            <li>
                                <a href="{{ route('recent-changes') }}">Recent Changes</a>
                            </li>
                            <li>
                                <a href="{{ route('page.random') }}">Random Page</a>
                            </li>
                            <li>
                                <a href="{{ route('page.create') }}">
                                    Create Page
                                </a>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <section class="article">
                    @yield('content')
                </section>

                <aside class="right-sidebar">
                    @stack('right-sidebar')
                </aside>
            </div>
        </article>

        <footer id="site-footer">
            <header>
                    {{ env('APP_NAME') }}
            </header>
            <p>
                The content on this site is &copy; 2014-{{ date('Y') }}. All rights reserved.
            </p>
            <p>
                Wiki2 software by <a href="https://github.com/hallcode">Hallcode</a>.
            </p>
        </footer>

    </main>


    @bukScripts
    @stack('scripts')
    @stack('scripts-end')

    <script src="https://unpkg.com/htmx.org@2.0.3" integrity="sha384-0895/pl2MU10Hqc6jd4RvrthNlDiE9U1tWmX7WRESftEDRosgxNsQG/Ze9YMRzHq" crossorigin="anonymous"></script>
</body>

</html>
