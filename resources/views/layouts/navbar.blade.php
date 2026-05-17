<nav class="bg-transparent shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 flex items-center justify-between h-16">

        <a href="{{ route('home') }}" class="flex items-center shrink-0">
            <img class="h-7 w-auto object-contain" src="{{ asset('uploads/logo.png') }}" alt="Logo">
        </a>

        <div class="flex items-center gap-6">
            @auth
                <span class="text-gray-600 text-sm">Bonjour, {{ auth()->user()->name }}</span>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:underline">
                        Admin
                    </a>
                @elseif(auth()->user()->isSeller())
                    <a href="{{ route('seller.products') }}" class="text-sm text-indigo-600 hover:underline">
                        Ma boutique
                    </a>
                @else
                    <a href="{{ route('cart.index') }}" class="text-sm text-indigo-600 hover:underline">
                        Panier
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:underline">
                        Commandes
                    </a>
                    <a href="{{ route('dashboard_buyer') }}" class="text-sm text-indigo-600 hover:underline">
                        Mon espace
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-500 hover:underline">Déconnexion</button>
                </form>

            @else
                <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Produits</a>
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600">Connexion</a>
                <a href="{{ route('register') }}" class="bg-black text-white text-sm px-4 py-2 rounded hover:bg-gray-800">S'inscrire</a>
            @endauth
        </div>

    </div>
</nav>