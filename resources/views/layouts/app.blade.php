<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
            input[type="text"],input[type="email"],input[type="password"],input[type="number"],
            input[type="date"],input[type="time"],input[type="tel"],select,textarea{
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
            select{padding-right:2.25rem;}
            input[type="checkbox"]{border-width:1px;border-style:solid;border-color:#D1D5DB;}
            input:focus,select:focus,textarea:focus{
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
