<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Fafa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .text-gold { color: #C9A84C; }
        .bg-gold { background-color: #C9A84C; }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link.active { background: rgba(201,168,76,0.12); color: #C9A84C; border-left: 3px solid #C9A84C; }
        .sidebar-link:not(.active):hover { background: rgba(255,255,255,0.05); color: #fff; }
    </style>
</head>
<body class="min-h-screen" style="background:#F5F5F3">

<div class="flex min-h-screen">

    <aside class="w-64 flex flex-col fixed top-0 left-0 h-full z-40" style="background:#0A0A0A">

        <div class="p-6 border-b border-gray-800">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-widest text-gold">FAFA</a>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Administration</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard',        'label' => 'Dashboard',      'match' => 'admin.dashboard'],
                    ['route' => 'admin.shops.index',      'label' => 'Boutiques',      'match' => 'admin.shops*'],
                    ['route' => 'admin.categories.index', 'label' => 'Categories',     'match' => 'admin.categories*'],
                    ['route' => 'admin.users.index',      'label' => 'Utilisateurs',   'match' => 'admin.users*'],
                    ['route' => 'admin.orders.index',     'label' => 'Commandes',      'match' => 'admin.orders*'],
                    ['route' => 'admin.commissions.index','label' => 'Commissions',    'match' => 'admin.commissions*'],
                ];
            @endphp

            @foreach($adminNav as $item)
            <a href="{{ route($item['route']) }}"
               class="sidebar-link flex items-center justify-between px-4 py-3 rounded-lg text-sm text-gray-400
                      {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                <span>{{ $item['label'] }}</span>
                @if($item['route'] === 'admin.shops.index')
                    @php $pendingCount = \App\Models\Shop::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    @endif
                @endif
            </a>
            @endforeach
        </nav>

        <div class="p-4 border-t border-gray-800 space-y-2">
            <a href="{{ route('home') }}"
               class="block text-xs text-gray-500 hover:text-gray-300 transition py-1">
                Retour a la marketplace
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition">
                    Deconnexion
                </button>
            </form>
        </div>

    </aside>

    <div class="ml-64 flex-1 flex flex-col">

        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between">
            <h1 class="text-base font-semibold text-gray-800 tracking-wide">@yield('header', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-400">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm bg-gold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <div class="px-8 pt-4">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    {{ session('error') }}
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