<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://cdn.jsdelivr.net" />
        <x-prezet.meta />

        <!-- Scripts -->
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/lite-youtube-embed@0.3.3/src/lite-yt-embed.min.js"
        ></script>
        <script
            defer
            src="https://unpkg.com/@benbjurstrom/alpinejs-zoomable@0.4.0/dist/cdn.min.js"
        ></script>
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.14.1/dist/cdn.min.js"
        ></script>
        <script
            defer
            src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"
        ></script>
        @vite(['resources/css/prezet.css'])
        @stack('jsonld')

        <script>
            ;(function () {
                const stored = localStorage.getItem('theme')
                const prefersDark = window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches
                const useDark =
                    stored === 'dark' || (stored === null && prefersDark)

                if (useDark) {
                    document.documentElement.classList.add('dark')
                }
            })()
        </script>
    </head>
    <body class="font-sans antialiased dark:bg-zinc-900">
        <div class="min-h-screen">
            <x-prezet.header />
            <div class="container">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
