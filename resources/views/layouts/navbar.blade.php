<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 flex items-center justify-between h-16">

        <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
            Marketplace
        </a>

        <div class="flex items-center gap-6">
            @auth
                <span class="text-gray-600 text-sm">Bonjour, {{ auth()->user()->name }}</span>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard_admin') }}" class="text-sm text-indigo-600 hover:underline">Dashboard</a>
                @elseif(auth()->user()->isSeller())
                    <a href="{{ route('dashboard_seller') }}" class="text-sm text-indigo-600 hover:underline">Dashboard</a>
                @else
                    <a href="{{ route('dashboard_buyer') }}" class="text-sm text-indigo-600 hover:underline">Dashboard</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 hover:underline">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600">Connexion</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded hover:bg-indigo-700">S'inscrire</a>
            @endauth
        </div>

    </div>
</nav>