@extends('layouts.app')
@section('title', 'produits')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Tous les produits</h1>
    <p class="text-gray-500 text-sm mt-1">{{ $products->total() }} produits disponibles</p>
</div>

{{-- Filtres --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-8 flex gap-4 flex-wrap items-center">
    <input type="text" id="searchInput" value="{{ request('search') }}"
        placeholder="Rechercher un produit..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 min-w-48" />

    <select id="categorySelect"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <button onclick="clearFilters()"
        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-300 rounded-lg transition">
        Effacer
    </button>
</div>

{{-- Grille produits --}}
@if($products->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-4">🔍</div>
        <p class="text-lg font-medium">Aucun produit trouvé</p>
        <p class="text-sm mt-1">Essaie avec d'autres mots-clés</p>
    </div>
@else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8" id="productsGrid">
        @foreach($products as $product)
        <a href="{{ route('products.show', $product) }}"
           class="product-card bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group"
           data-title="{{ strtolower($product->title) }}"
           data-category="{{ $product->category_id }}">

            {{-- Image --}}
            <div class="aspect-square bg-gray-100 overflow-hidden">
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <img src="{{ asset($product->images[0]) }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl">📦</div>
                @endif
            </div>

            {{-- Infos --}}
            <div class="p-4">
                <p class="text-xs text-indigo-500 mb-1">{{ $product->category->name ?? '' }}</p>
                <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2">{{ $product->title }}</h3>
                <p class="text-xs text-gray-400 mb-2 hover:text-indigo-500">
                    {{ $product->shop->name ?? '' }}
                </p>
                <p class="font-bold text-gray-900">
                    {{ number_format($product->price, 0, ',', ' ') }} FCFA
                </p>
                @if($product->stock_quantity <= 0)
                    <p class="text-xs text-red-500 mt-1">Rupture de stock</p>
                @elseif($product->stock_quantity <= 5)
                    <p class="text-xs text-orange-500 mt-1">Plus que {{ $product->stock_quantity }} restants</p>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    <div id="noResults" class="hidden text-center py-20 text-gray-400">
        <div class="text-5xl mb-4">🔍</div>
        <p class="text-lg font-medium">Aucun produit trouvé</p>
        <p class="text-sm mt-1">Essaie avec d'autres mots-clés</p>
    </div>

    {{-- Pagination --}}
    <div class="mt-4" id="pagination">
        {{ $products->withQueryString()->links() }}
    </div>
@endif

<script>
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const productCards = document.querySelectorAll('.product-card');
    const noResults = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');

    function filterProducts() {
        const query = searchInput.value.toLowerCase().trim();
        const categoryId = categorySelect.value;
        let visibleCount = 0;

        productCards.forEach(card => {
            const title = card.dataset.title;
            const cardCategory = card.dataset.category;

            const matchSearch = query === '' || title.includes(query);
            const matchCategory = categoryId === '' || cardCategory === categoryId;

            card.style.display = (matchSearch && matchCategory) ? '' : 'none';
            if (matchSearch && matchCategory) visibleCount++;
        });

        noResults.classList.toggle('hidden', visibleCount > 0);

        // Cache la pagination quand un filtre est actif
        if (pagination) {
            pagination.style.display = (query || categoryId) ? 'none' : '';
        }
    }

    searchInput.addEventListener('input', filterProducts);
    categorySelect.addEventListener('change', filterProducts);

    function clearFilters() {
        searchInput.value = '';
        categorySelect.value = '';
        productCards.forEach(card => card.style.display = '');
        noResults.classList.add('hidden');
        if (pagination) pagination.style.display = '';
        searchInput.focus();
    }
</script>

@endsection