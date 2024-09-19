<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title-page')</title>

    @vite(['resources/sass/app.scss'])
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])    
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    @yield('content')
</body>
</html>
