<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <style>
            [x-cloak]{display:none!important}
            body{font-family:'Inter',ui-sans-serif,system-ui,sans-serif;}
            h1,h2,h3,h4,.font-display{font-family:'Plus Jakarta Sans',ui-sans-serif,sans-serif;}
            /* Selector dinaikkan spesifisitasnya (html …) karena preflight Tailwind CDN
               disuntikkan setelah blok ini dan me-reset padding select/textarea ke 0. */
            html input[type="text"],html input[type="email"],html input[type="password"],
            html input[type="number"],html input[type="date"],html input[type="time"],
            html input[type="tel"],html select,html textarea{
                padding:0.625rem 0.875rem;
                font-size:0.875rem;
                line-height:1.25rem;
                border-radius:0.5rem;
                border-width:1px;
                border-style:solid;
                border-color:#D1D5DB;
                background-color:#fff;
                width:100%;
            }
            html select{
                -webkit-appearance:none;
                -moz-appearance:none;
                appearance:none;
                padding-right:2.5rem;
                background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.6' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position:right 0.625rem center;
                background-repeat:no-repeat;
                background-size:1.25rem 1.25rem;
            }
            html input[type="checkbox"]{
                width:1rem;height:1rem;
                border-width:1px;border-style:solid;border-color:#D1D5DB;
            }
            html input:focus,html select:focus,html textarea:focus{
                outline:none;
                border-color:#6366F1;
                box-shadow:0 0 0 3px rgba(99,102,241,0.15);
            }
        </style>
    </head>
    <body class="antialiased" style="background:#FBF9F5;">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <div class="lg:pl-60">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-gray-100">
                        <div class="py-4 px-5 lg:px-7 font-display">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="pb-24 lg:pb-10">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
