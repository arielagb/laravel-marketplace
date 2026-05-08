@extends('layouts.app')
@section('title', $shop->name)

@section('content')

{{-- Header boutique --}}
<div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
    <div class="flex items-start gap-6">

        {{-- Avatar boutique --}}
        <div class="w-20 h-20 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-3xl flex-shrink-0">
            {{ strtoupper(substr($shop->name, 0, 1)) }}
        </div>

        {{-- Infos --}}
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-bold text-gray-800">{{ $shop->name }}</h1>
                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">✅ Boutique vérifiée</span>
            </div>

            @if($shop->category)
                <p class="text-sm text-indigo-500 mb-2">{{ $shop->category->name }}</p>
            @endif

            @if($shop->description)
                <p class="text-gray-500 text-sm leading-relaxed max-w-2xl">{{ $shop->description }}</p>
            @endif

            <div class="flex items-center gap-6 mt-4 text-sm text-gray-400">
                <span>📦 {{ $products->count() }} produits</span>
                <span>🛍️ {{ $totalSales }} ventes</span>
                <span>📍 {{ $shop->address ?? 'Lomé, Togo' }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex gap-4 items-center">
    <input type="text" id="searchInput"
        placeholder="Rechercher dans cette boutique..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />

    <select id="categorySelect"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Toutes les catégories</option>
        @foreach($products->pluck('category')->filter()->unique('id') as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>

    <button onclick="clearFilters()"
        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
        Effacer
    </button>
</div>

{{-- Produits --}}
@if($products->isEmpty())
    <div class="text-center py-20 text-gray-400 bg-white rounded-xl shadow-sm">
        <div class="text-5xl mb-4">📦</div>
        <p class="text-lg font-medium">Aucun produit disponible</p>
        <p class="text-sm mt-1">Cette boutique n'a pas encore de produits publiés</p>
    </div>
@else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productsGrid">
        @foreach($products as $product)
        <a href="{{ route('products.show', $product) }}"
           class="product-card bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden group"
           data-title="{{ strtolower($product->title) }}"
           data-category="{{ $product->category_id }}">

            <div class="aspect-square bg-gray-100 overflow-hidden">
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <img src="{{ asset($product->images[0]) }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl">📦</div>
                @endif
            </div>

            <div class="p-4">
                <p class="text-xs text-indigo-500 mb-1">{{ $product->category->name ?? '' }}</p>
                <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2">{{ $product->title }}</h3>
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
    </div>
@endif

<script>
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const productCards = document.querySelectorAll('.product-card');
    const noResults = document.getElementById('noResults');

    function filterProducts() {
        const query = searchInput.value.toLowerCase().trim();
        const categoryId = categorySelect.value;
        let visibleCount = 0;

        productCards.forEach(card => {
            const matchSearch = query === '' || card.dataset.title.includes(query);
            const matchCategory = categoryId === '' || card.dataset.category === categoryId;

            card.style.display = (matchSearch && matchCategory) ? '' : 'none';
            if (matchSearch && matchCategory) visibleCount++;
        });

        noResults.classList.toggle('hidden', visibleCount > 0);
    }

    searchInput.addEventListener('input', filterProducts);
    categorySelect.addEventListener('change', filterProducts);

    function clearFilters() {
        searchInput.value = '';
        categorySelect.value = '';
        productCards.forEach(card => card.style.display = '');
        noResults.classList.add('hidden');
    }
</script>

@endsection