<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @if(isset($pageTitle))
        {{$pageTitle}} |
        @endif
        {{ env('APP_NAME') }}
    </title>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script src="//unpkg.com/alpinejs" defer></script>

    @stack('head')
    @bukStyles

</head>
