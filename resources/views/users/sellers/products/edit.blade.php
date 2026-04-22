@extends('layouts.seller')
@section('title', 'Modifier le produit')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('seller.products') }}" class="text-gray-400 hover:text-gray-600">← Retour</a>
        <h1 class="text-2xl font-bold text-gray-800">Modifier : {{ $product->title }}</h1>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            <p class="font-semibold mb-2">⚠️ Corrige ces erreurs :</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('seller.products.update', $product) }}"
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">📦 Informations produit</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre du produit</label>
                <input type="text" name="title" value="{{ old('title', $product->title) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="">-- Choisir --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}"
                        step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        required />
                </div>
            </div>

            @if($product->images && is_array($product->images) && count($product->images) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Images actuelles</label>
                    <div class="flex gap-2 flex-wrap">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img) }}"
                                 class="w-20 h-20 rounded-lg object-cover border" />
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nouvelles images (remplace les actuelles)
                </label>
                <input type="file" name="images[]" multiple accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm" />
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_published" id="is_published" class="accent-indigo-600"
                    {{ old('is_published', $product->is_published) ? 'checked' : '' }} />
                <label for="is_published" class="text-sm text-gray-700">
                    Publié (visible par les acheteurs)
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition text-sm">
                Sauvegarder les modifications
            </button>
            <a href="{{ route('seller.products') }}"
               class="px-6 py-3 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                Annuler
            </a>
        </div>

    </form>
</div>

@endsection