<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Store')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CDN (simple, no Vite needed) --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
    >
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    {{-- Navbar --}}
    @include('shared.navbar')

    <main class="flex-grow pt-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('shared.footer')
</body>
</html>
