@extends('layouts.app')
@section('title', $product->title)

@section('content')

<div class="mb-6">
    <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Retour aux produits</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-10">

    {{-- Images --}}
    <div class="space-y-3">
        @if($product->images && is_array($product->images) && count($product->images) > 0)
            <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden">
                <img src="{{ asset($product->images[0])}}"
                     class="w-full h-full object-cover" id="mainImage" />
            </div>
            @if(count($product->images) > 1)
                <div class="flex gap-2 flex-wrap">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/' . $img) }}"
                             class="w-16 h-16 rounded-lg object-cover cursor-pointer border-2 border-transparent hover:border-indigo-500"
                             onclick="document.getElementById('mainImage').src=this.src" />
                    @endforeach
                </div>
            @endif
        @else
            <div class="aspect-square bg-gray-100 rounded-xl flex items-center justify-center text-6xl">📦</div>
        @endif
    </div>

    {{-- Infos produit --}}
    <div>
        <p class="text-sm text-indigo-500 mb-2">{{ $product->category->name ?? '' }}</p>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->title }}</h1>
        <p class="text-gray-400 text-sm mb-4">Vendu par <span class="text-gray-600 font-medium">{{ $product->shop->name ?? '' }}</span></p>

        <p class="text-3xl font-bold text-gray-900 mb-6">
            {{ number_format($product->price, 0, ',', ' ') }} FCFA
        </p>

        @if($product->stock_quantity > 0)
            <p class="text-green-600 text-sm mb-6">✅ En stock ({{ $product->stock_quantity }} disponibles)</p>
        @else
            <p class="text-red-500 text-sm mb-6">❌ Rupture de stock</p>
        @endif

        @if($product->description)
            <div class="text-gray-600 text-sm leading-relaxed mb-6">
                {{ $product->description }}
            </div>
        @endif

        @auth
        @if(auth()->user()->isBuyer())
            @if($product->stock_quantity > 0)
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                        Ajouter au panier
                    </button>
                </form>
            @else
                <button disabled
                    class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-semibold cursor-not-allowed">
                    Rupture de stock
                </button>
            @endif
        @endif
        @else
            <a href="{{ route('login') }}"
            class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                Connecte-toi pour acheter
            </a>
        @endauth
    </div>
</div>

@endsection