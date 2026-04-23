@extends('layouts.admin')
@section('title', 'Profil — ' . $user->name)
@section('header', 'Détail utilisateur')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Retour aux utilisateurs</a>
</div>

{{-- Infos principales --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-2xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h2>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
            </div>
        </div>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Rôle</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                    {{ $user->role?->label === 'Admin' ? 'bg-purple-100 text-purple-700' :
                       ($user->role?->label === 'Seller' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                    {{ $user->role?->label ?? '—' }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Statut</span>
                @if($user->is_blocked)
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Bloqué</span>
                @else
                    <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Actif</span>
                @endif
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Inscrit le</span>
                <span class="text-gray-600">{{ $user->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        @if($user->role?->label !== 'Admin')
            <form method="POST" action="{{ route('admin.users.block', $user) }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="w-full py-2 rounded-lg text-sm font-medium transition
                           {{ $user->is_blocked ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-red-50 text-red-500 hover:bg-red-100' }}">
                    {{ $user->is_blocked ? '✅ Débloquer' : '🚫 Bloquer' }}
                </button>
            </form>
        @endif
    </div>

    {{-- Infos boutique si Seller --}}
    @if($user->role?->label === 'Seller')
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">🏪 Boutique</h3>
            @if($shop)
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Nom</p>
                        <p class="font-medium text-gray-800">{{ $shop->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Statut</p>
                        @php
                            $statusClass = match($shop->status) {
                                'active'   => 'bg-green-100 text-green-700',
                                'pending'  => 'bg-amber-100 text-amber-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default    => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">{{ $shop->status }}</span>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Téléphone</p>
                        <p class="text-gray-700">{{ $shop->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Adresse</p>
                        <p class="text-gray-700">{{ $shop->address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Paiement</p>
                        <p class="text-gray-700">{{ $shop->payment_method ?? '—' }} — {{ $shop->payment_details ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Catégorie principale</p>
                        <p class="text-gray-700">{{ $shop->category->name ?? '—' }}</p>
                    </div>
                </div>
                @if($shop->id_document)
                    <a href="{{ asset('storage/' . $shop->id_document) }}" target="_blank"
                       class="text-xs text-indigo-500 hover:underline">
                        🪪 Voir le document KYC
                    </a>
                @endif
            @else
                <p class="text-gray-400 text-sm">Ce vendeur n'a pas encore créé de boutique.</p>
            @endif
        </div>
    @endif

    {{-- Stats si Buyer --}}
    @if($user->role?->label === 'Buyer')
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">🛒 Activité acheteur</h3>
            <div class="grid grid-cols-2 gap-4 text-center">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-2xl font-bold text-indigo-600">{{ $orders->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Commandes passées</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-2xl font-bold text-green-600">
                        {{ number_format($orders->sum('total_amount'), 0, ',', ' ') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">FCFA dépensés</p>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Produits si Seller --}}
@if($user->role?->label === 'Seller' && $products->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">📦 Produits ({{ $products->count() }})</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($products as $product)
            <div class="border border-gray-100 rounded-xl p-3">
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <img src="{{ asset($product->images[0]) }}"
                         class="w-full h-24 object-cover rounded-lg mb-2" />
                @else
                    <div class="w-full h-24 bg-gray-100 rounded-lg mb-2 flex items-center justify-center text-2xl">📦</div>
                @endif
                <p class="text-xs font-medium text-gray-800 truncate">{{ $product->title }}</p>
                <p class="text-xs text-indigo-600 font-bold">{{ number_format($product->price, 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-gray-400">Stock : {{ $product->stock_quantity }}</p>
            </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Commandes si Buyer --}}
@if($user->role?->label === 'Buyer' && $orders->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-4 border-b pb-2">🧾 Historique commandes</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-400 text-xs">
                    <th class="pb-2">Commande</th>
                    <th class="pb-2">Boutique</th>
                    <th class="pb-2">Montant</th>
                    <th class="pb-2">Statut</th>
                    <th class="pb-2">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($orders as $order)
                @php
                    $statusLabels = [
                        'created'   => ['label' => 'Créée',     'class' => 'bg-blue-100 text-blue-700'],
                        'paid'      => ['label' => 'Payée',     'class' => 'bg-green-100 text-green-700'],
                        'shipped'   => ['label' => 'Expédiée',  'class' => 'bg-indigo-100 text-indigo-700'],
                        'delivered' => ['label' => 'Livrée',    'class' => 'bg-green-100 text-green-800'],
                        'cancelled' => ['label' => 'Annulée',   'class' => 'bg-red-100 text-red-700'],
                    ];
                    $s = $statusLabels[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-700'];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="py-3 font-medium text-gray-700">#{{ $order->id }}</td>
                    <td class="py-3 text-gray-600">{{ $order->shop->name }}</td>
                    <td class="py-3 font-semibold">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                    <td class="py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $s['class'] }}">{{ $s['label'] }}</span>
                    </td>
                    <td class="py-3 text-gray-400 text-xs">{{ $order->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection