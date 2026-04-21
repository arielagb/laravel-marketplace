@extends('layouts.app')
@section('title', 'Créer ma boutique')
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Crée ta boutique 🏪</h1>
        <p class="text-gray-500 mt-2">Remplis ces informations pour commencer à vendre</p>
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

    <form method="POST" action="{{ route('seller.onboarding.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Infos boutique --}}
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">📦 Informations boutique</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la boutique</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ex: Mode & Style Paris" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie principale</label>
                <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description de la boutique</label>
                <textarea name="description" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Décris ce que tu vends, ton style, tes valeurs... (min. 20 caractères)" required>{{ old('description') }}</textarea>
            </div>
        </div>

        {{-- KYC --}}
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">🪪 Vérification d'identité</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="+228 90 00 00 00" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input type="text" name="address" value="{{ old('address') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Lomé, Togo" required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pièce d'identité (CNI, passeport...)</label>
                <input type="file" name="id_document" accept=".jpg,.jpeg,.png,.pdf"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required />
                <p class="text-xs text-gray-400 mt-1">Format accepté : JPG, PNG ou PDF — max 2MB</p>
            </div>
        </div>

        {{-- Paiement --}}
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">💳 Informations de paiement</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Moyen de paiement préféré</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 border rounded-lg px-4 py-3 cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                        <input type="radio" name="payment_method" value="mobile_money" class="accent-indigo-600"
                            {{ old('payment_method') === 'mobile_money' ? 'checked' : '' }} required />
                        <span class="text-sm font-medium">📱 Mobile Money</span>
                    </label>
                    <label class="flex items-center gap-2 border rounded-lg px-4 py-3 cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                        <input type="radio" name="payment_method" value="bank" class="accent-indigo-600"
                            {{ old('payment_method') === 'bank' ? 'checked' : '' }} />
                        <span class="text-sm font-medium">🏦 Compte bancaire</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro / IBAN / détails</label>
                <input type="text" name="payment_details" value="{{ old('payment_details') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ex: +228 90 00 00 00 (Moov Money)" required />
            </div>
        </div>

        <button type="submit"
            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition text-sm">
            Soumettre ma demande 🚀
        </button>

    </form>
</div>

@endsection