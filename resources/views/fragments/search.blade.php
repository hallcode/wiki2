<section>
    <div class="spinner" htmx-indicator></div>
    <header>Pages</header>
    <ul>
        @foreach($pages as $page)
        <li>
            <a href="{{ route('page.view', ['slug' => $page->slug]) }}">
                {{$page->title}}
            </a>
        </li>
        @endforeach
    </ul>

    <header>Media</header>
    <ul>
        @foreach($media as $m)
        <li>
            <a href="{{ route('media.view', ['slug' => urlencode($m->title)]) }}">
                {{$m->title}}
            </a>
        </li>
        @endforeach
    </ul>
</section>
