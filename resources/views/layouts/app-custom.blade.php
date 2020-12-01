<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.js" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-white flex flex-row flex-no-wrap overflow-auto">
            <aside class="bg-gray-900 w-48 flex-shrink-0">
                {{ $sidebarLinks }}
            </aside>
            <main class="flex-grow" style="min-width: 700px;">
                <header class="h-12 shadow-md">
                    @livewire('navigation-dropdown')
                </header>
                <div class="mx-auto p-4 gap-4 grid">
                    <div class="">
                        {{ $top }}
                    </div>

                    {{ $slot }}

                </div>
            </main>
        </div>
        <!-- Modals -->
        @stack('modals')

        @livewireScripts

        @stack('footerScripts');
    </body>
</html>
