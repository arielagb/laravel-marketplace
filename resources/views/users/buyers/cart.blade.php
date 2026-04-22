@extends('layouts.app')
@section('title', 'Mon panier')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Mon panier 🛒</h1>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
        {{ session('error') }}
    </div>
@endif

@if($cartItems->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-4">🛒</div>
        <p class="text-lg font-medium">Ton panier est vide</p>
        <a href="{{ route('products.index') }}"
           class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
            Parcourir les produits
        </a>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Liste articles --}}
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4">

                {{-- Image --}}
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                    @if($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                        <img src="{{ asset($item->product->images[0]) }}"
                             class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">{{ $item->product->title }}</h3>
                    <p class="text-xs text-gray-400">{{ $item->product->shop->name ?? '' }}</p>

                    @if($item->product->is_deleted || !$item->product->is_published)
                        <p class="text-xs text-red-500 mt-1 font-medium">⚠️ Ce produit n'est plus disponible</p>
                    @elseif($item->product->stock_quantity <= 0)
                        <p class="text-xs text-orange-500 mt-1 font-medium">⚠️ Rupture de stock</p>
                    @else
                        <p class="text-indigo-600 font-bold mt-1">
                            {{ number_format($item->product->price, 0, ',', ' ') }} FCFA
                        </p>
                    @endif
                </div>

                {{-- Quantité --}}
                <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                           min="1" max="{{ $item->product->stock_quantity }}"
                           class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <button type="submit" class="text-xs text-indigo-500 hover:underline">Maj</button>
                </form>

                {{-- Sous-total --}}
                <div class="text-right min-w-fit">
                    <p class="font-bold text-gray-800">
                        {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} FCFA
                    </p>

                    <form method="POST" action="{{ route('cart.remove', $item) }}" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:underline">Retirer</button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Résumé commande --}}
        <div class="bg-white rounded-xl shadow-sm p-6 h-fit">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Résumé</h2>

            <div class="space-y-2 text-sm text-gray-600 mb-4">
                <div class="flex justify-between">
                    <span>Articles ({{ $cartItems->count() }})</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between">
                    <span>Livraison</span>
                    <span class="text-green-600">Gratuite</span>
                </div>
                <div class="border-t pt-2 flex justify-between font-bold text-gray-800">
                    <span>Total</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <button class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition text-sm">
                Passer la commande
            </button>
        </div>

    </div>
@endif

@endsection