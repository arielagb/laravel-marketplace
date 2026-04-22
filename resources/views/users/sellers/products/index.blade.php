@extends('layouts.seller')
@section('title', 'Mes produits')
@section('header', 'Mes produits')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('seller.products.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
        + Ajouter un produit
    </a>
</div>

@if($products->isEmpty())
    <div class="text-center py-16 text-gray-400 bg-white rounded-xl shadow-sm">
        <div class="text-5xl mb-4">📦</div>
        <p class="text-lg font-medium">Aucun produit pour l'instant</p>
        <a href="{{ route('seller.products.create') }}"
           class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
            + Ajouter un produit
        </a>
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="pb-3 pr-4">Produit</th>
                        <th class="pb-3 pr-4">Catégorie</th>
                        <th class="pb-3 pr-4">Prix</th>
                        <th class="pb-3 pr-4">Stock</th>
                        <th class="pb-3 pr-4">Statut</th>
                        <th class="pb-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 pr-4">
                            <div class="flex items-center gap-3">
                                @if($product->images && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ asset($product->images[0]) }}"
                                         class="w-12 h-12 rounded-lg object-cover" />
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-xl">📦</div>
                                @endif
                                <span class="font-medium text-gray-800">{{ $product->title }}</span>
                            </div>
                        </td>
                        <td class="py-4 pr-4 text-gray-500">{{ $product->category->name ?? '—' }}</td>
                        <td class="py-4 pr-4 font-semibold text-gray-800">
                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="py-4 pr-4">
                            @if($product->stock_quantity <= 0)
                                <span class="text-red-500 font-medium">{{ $product->stock_quantity }}</span>
                            @elseif($product->stock_quantity <= 5)
                                <span class="text-orange-500 font-medium">{{ $product->stock_quantity }}</span>
                            @else
                                <span class="text-gray-600">{{ $product->stock_quantity }}</span>
                            @endif
                        </td>
                        <td class="py-4 pr-4">
                            @if($product->is_published)
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Publié</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full">Brouillon</span>
                            @endif
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('seller.products.edit', $product) }}"
                                   class="text-indigo-500 hover:underline text-xs">Modifier</a>
                                <form method="POST" action="{{ route('seller.products.destroy', $product) }}"
                                      onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline text-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection