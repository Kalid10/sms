<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.bunny.net/css?family=inter:200,400,500,600,800" rel="stylesheet"/>
    <!-- Scripts -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="font-sans antialiased">
<div class="lg:hidden flex items-center justify-center flex-col gap-1 h-screen w-full">
    <h1 class="text-gray-400 text-center">Mobile site is under construction</h1>
    <h1 class="font-semibold">Open in Desktop instead</h1>
</div>
<div class="hidden lg:block">
    @inertia
</div>
</body>
</html>
