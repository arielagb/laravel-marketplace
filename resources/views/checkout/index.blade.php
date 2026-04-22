@extends('layouts.app')
@section('title', 'Paiement')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Finaliser la commande 🛍️</h1>
        <p class="text-gray-500 text-sm mt-1">Vérifie ta commande et choisis ton moyen de paiement</p>
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

    <form method="POST" action="{{ route('checkout.process') }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Colonne gauche --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Récapitulatif articles --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="font-semibold text-gray-700 mb-4 border-b pb-2">📦 Récapitulatif</h2>
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                    <img src="{{ asset($item->product->images[0]) }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">📦</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">{{ $item->product->title }}</p>
                                <p class="text-xs text-gray-400">{{ $item->quantity }} × {{ number_format($item->product->price, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <p class="font-semibold text-gray-800 text-sm">
                                {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Choix du moyen de paiement --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="font-semibold text-gray-700 mb-4 border-b pb-2">💳 Moyen de paiement</h2>

                    <div class="space-y-3 mb-6">
                        <label class="flex items-center gap-3 border rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="payment_method" value="mixx" class="accent-indigo-600" {{ old('payment_method') === 'mixx' ? 'checked' : '' }} />
                            <div>
                                <p class="text-sm font-medium">📱 Mixx by Yas</p>
                                <p class="text-xs text-gray-400">Paiement via Mixx by Yas (ex-Tmoney)</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 border rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="payment_method" value="moov" class="accent-indigo-600" {{ old('payment_method') === 'moov' ? 'checked' : '' }} />
                            <div>
                                <p class="text-sm font-medium">📱 Moov Money</p>
                                <p class="text-xs text-gray-400">Paiement via Moov Money</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 border rounded-xl px-4 py-3 cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="payment_method" value="card" class="accent-indigo-600" {{ old('payment_method') === 'card' ? 'checked' : '' }} />
                            <div>
                                <p class="text-sm font-medium">💳 Carte bancaire</p>
                                <p class="text-xs text-gray-400">Visa / Mastercard</p>
                            </div>
                        </label>
                    </div>

                    {{-- Champs Mobile Money --}}
                    <div id="mobile_fields" class="hidden space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="+228 90 00 00 00" />
                            <p class="text-xs text-gray-400 mt-1">Tu recevras une notification pour confirmer le paiement</p>
                        </div>
                    </div>

                    {{-- Champs Carte bancaire --}}
                    <div id="card_fields" class="hidden space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                            <input type="text" name="card_number" value="{{ old('card_number') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="1234 5678 9012 3456" maxlength="19" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte</label>
                            <input type="text" name="card_name" value="{{ old('card_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="JEAN DUPONT" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                                <input type="text" name="card_expiry" value="{{ old('card_expiry') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="MM/AA" maxlength="5" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                <input type="text" name="card_cvv" value="{{ old('card_cvv') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="123" maxlength="4" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne droite — Total --}}
            <div class="bg-white rounded-xl shadow-sm p-6 h-fit">
                <h2 class="font-semibold text-gray-700 mb-4">Résumé</h2>
                <div class="space-y-2 text-sm text-gray-600 mb-6">
                    <div class="flex justify-between">
                        <span>Sous-total</span>
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

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition text-sm">
                    Confirmer le paiement 💳
                </button>

                <a href="{{ route('cart.index') }}"
                   class="block text-center text-sm text-gray-400 hover:underline mt-3">
                    ← Retour au panier
                </a>
            </div>

        </div>
    </form>
</div>

{{-- JS pour afficher/cacher les champs selon le moyen de paiement --}}
<script>
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const mobileFields = document.getElementById('mobile_fields');
    const cardFields = document.getElementById('card_fields');

    function updateFields() {
        const selected = document.querySelector('input[name="payment_method"]:checked')?.value;
        mobileFields.classList.toggle('hidden', !['mixx', 'moov'].includes(selected));
        cardFields.classList.toggle('hidden', selected !== 'card');
    }

    radios.forEach(r => r.addEventListener('change', updateFields));
    updateFields();
</script>

@endsection