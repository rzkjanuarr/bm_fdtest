<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/assets/logo.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#fe0006" />
    <link rel="stylesheet" href="/css/color.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <title>Book Management</title>
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="partials-ready">
    @inertia
</body>
</html>
