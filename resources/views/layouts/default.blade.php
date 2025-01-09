<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pointage')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-[75rem] bg-gray-100 dark:bg-neutral-700">
    @include('layouts.navbar')
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
