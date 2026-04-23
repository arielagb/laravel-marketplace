<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') — Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col fixed top-0 left-0 h-full z-40">

        <div class="p-6 border-b border-gray-700">
            <a href="{{ route('home') }}" class="text-lg font-bold text-white">🛍️ Fafa</a>
            <p class="text-xs text-gray-400 mt-1">Panel Administration</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('admin.shops.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.shops*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                🏪 Boutiques
                @php
                    $pendingCount = \App\Models\Shop::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.categories*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                🏷️ Catégories
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.users*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                👥 Utilisateurs
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.orders*') ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                🧾 Commandes
            </a>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xs text-gray-400 hover:text-white mb-3">
                ← Retour à la marketplace
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-red-400 hover:text-red-300">
                    Déconnexion
                </button>
            </form>
        </div>

    </aside>

    {{-- Contenu principal --}}
    <div class="ml-64 flex-1 flex flex-col">

        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <div class="px-8 pt-4">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    ⚠️ {{ session('error') }}
                </div>
            @endif
        </div>

        <main class="flex-1 px-8 py-6">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>