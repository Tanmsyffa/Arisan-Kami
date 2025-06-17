<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Arisan App</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
<nav class="bg-indigo-600 text-white px-6 py-4">
    <span class="font-semibold">Arisan App</span>
    @auth
        <span class="ml-4">Halo, {{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
        <a href="{{ route('logout') }}"
           class="ml-6 underline"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
    @endauth
</nav>

<main class="container mx-auto px-6 py-4">
    @yield('content')
</main>
</body>
</html>
