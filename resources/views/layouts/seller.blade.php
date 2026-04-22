<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Vendeur') — {{ auth()->user()->shop->name ?? 'Ma boutique' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-md flex flex-col fixed top-0 left-0 h-full z-40">

        {{-- Logo + boutique --}}
        <div class="p-6 border-b">
            <a href="{{ route('home') }}" class="text-lg font-bold text-indigo-600">🛍️ Fafa</a>
            <p class="text-xs text-gray-400 mt-1 truncate">{{ auth()->user()->shop->name ?? '' }}</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('seller.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('seller.products') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('seller.products*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                📦 Mes produits
            </a>
            <a href="{{ route('seller.orders') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('seller.orders*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                🧾 Commandes
                @php
                    $pendingCount = auth()->user()->shop?->orders()->where('status', 'paid')->count() ?? 0;
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('seller.settings') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('seller.settings') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                ⚙️ Paramètres boutique
            </a>
        </nav>

        {{-- Bas sidebar --}}
        <div class="p-4 border-t">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xs text-gray-400 hover:text-gray-600 mb-3">
                ← Retour à la marketplace
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-red-400 hover:underline">
                    Déconnexion
                </button>
            </form>
        </div>

    </aside>

    {{-- Contenu principal --}}
    <div class="ml-64 flex-1 flex flex-col">

        {{-- Header --}}
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Messages flash --}}
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

        {{-- Page content --}}
        <main class="flex-1 px-8 py-6">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>