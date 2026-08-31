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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            /* html … agar menang dari preflight Tailwind CDN yang dimuat belakangan */
            html input[type="text"],html input[type="email"],html input[type="password"]{
                padding:0.625rem 0.875rem;
                font-size:0.875rem;
                line-height:1.25rem;
                border-radius:0.5rem;
                border-width:1px;
                border-style:solid;
                border-color:#D1D5DB;
                background-color:#fff;
            }
            html input[type="checkbox"]{
                width:1rem;height:1rem;
                border-width:1px;border-style:solid;border-color:#D1D5DB;
            }
            html input:focus{
                outline:none;
                border-color:#6366F1;
                box-shadow:0 0 0 3px rgba(99,102,241,0.15);
            }
        </style>
    </head>
    <body class="text-gray-900 antialiased" style="font-family:'Inter',ui-sans-serif,system-ui;background:#FBF9F5;">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10">
            <div class="w-full sm:max-w-md">
                <div class="flex justify-center mb-7">
                    <x-brand-lockup class="w-52" />
                </div>

                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl px-7 py-7">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
