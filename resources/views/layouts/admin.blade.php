<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <title>Imperium Stellarum</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <meta name="description" content="Imperium Stellarum - a turn-based multiplayer browser game of galactic conquest." />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        
        <meta name="apple-mobile-web-app-title" content="Imperium Stellarum">
        <meta name="application-name" content="Imperium Stellarum">
        <meta name="msapplication-TileColor" content="#12141c">
        
        <meta name="theme-color" content="#12141c">
        
    </head>
    <body>
        <div class="wrapper">
            @include('shared.header')
            <main class="wrapper__body">
                @include('shared.drawer')
                <section class="wrapper__content">
                    <div class="wrapper__page">
                        <x-flash>{{ session('status') }}</x-flash>
                        @yield('content')
                        <!-- {{ var_dump(session()->all()) }} -->
                    </div>
                </section>
            </main>
            @include('shared.footer')
        </div>
        <script type="text/javascript" src="http://localhost:7777/chunk-vendors.js" defer></script>
        <script type="text/javascript" src="http://localhost:7777/app.js" defer></script>
        <script type="text/javascript" src="http://localhost:7777/admin.js" defer></script>
        
        <script type="text/javascript" src="http://localhost:7777/webpack-dev-server.js" defer></script>
        
    </body>
</html>
