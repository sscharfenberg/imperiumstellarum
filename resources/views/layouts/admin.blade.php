<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <title>Imperium Stellarum</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <meta name="description" content="Imperium Stellarum - a turn-based multiplayer browser game of galactic conquest." />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">
        <link rel="manifest" href="/assets/favicons/site.webmanifest">
        <link rel="mask-icon" href="/assets/favicons/safari-pinned-tab.svg" color="#6fc3df">
        <link rel="shortcut icon" href="/assets/favicons/favicon.ico">
        <meta name="apple-mobile-web-app-title" content="Imperium Stellarum">
        <meta name="application-name" content="Imperium Stellarum">
        <meta name="msapplication-TileColor" content="#12141c">
        <meta name="msapplication-config" content="/assets/favicons/browserconfig.xml">
        <meta name="theme-color" content="#12141c">
        <link rel="stylesheet" type="text/css" href="/assets/app.f2ece42e32138a72.css" />
        <link rel="stylesheet" type="text/css" href="/assets/admin.c3398431e3244d13.css" />
        
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
        <script type="text/javascript" src="/assets/chunk-vendors.7e677a910272047b.js" defer></script>
        <script type="text/javascript" src="/assets/app.f2ece42e32138a72.js" defer></script>
        <script type="text/javascript" src="/assets/admin.c3398431e3244d13.js" defer></script>
        
    </body>
</html>
