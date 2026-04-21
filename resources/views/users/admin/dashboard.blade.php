@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin 🛡️</h1>
    <p class="text-gray-500 text-sm mt-1">Gestion de la plateforme</p>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        ✅ {{ session('success') }}
    </div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-10">
    <div class="bg-white rounded-xl shadow-sm p-6 text-center">
        <div class="text-3xl font-bold text-indigo-600">{{ $totalUsers }}</div>
        <div class="text-sm text-gray-500 mt-1">Utilisateurs</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 text-center">
        <div class="text-3xl font-bold text-green-600">{{ $totalShops }}</div>
        <div class="text-sm text-gray-500 mt-1">Boutiques actives</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 text-center">
        <div class="text-3xl font-bold text-amber-500">{{ $pendingShops->count() }}</div>
        <div class="text-sm text-gray-500 mt-1">En attente</div>
    </div>
</div>

{{-- Boutiques en attente --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">⏳ Boutiques en attente de validation</h2>

    @if($pendingShops->isEmpty())
        <p class="text-gray-400 text-sm text-center py-8">Aucune boutique en attente 🎉</p>
    @else
        <div class="space-y-4">
            @foreach($pendingShops as $shop)
            <div class="border border-gray-100 rounded-xl p-5 flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-semibold text-gray-800">{{ $shop->name }}</h3>
                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">En attente</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">{{ $shop->description }}</p>
                    <div class="text-xs text-gray-400 space-y-0.5">
                        <p>👤 Vendeur : {{ $shop->user->name }} — {{ $shop->user->email }}</p>
                        <p>📞 Téléphone : {{ $shop->phone }}</p>
                        <p>📍 Adresse : {{ $shop->address }}</p>
                        <p>💳 Paiement : {{ $shop->payment_method }} — {{ $shop->payment_details }}</p>
                        @if($shop->id_document)
                            <p>🪪 Document :
                                <a href="{{ asset('storage/' . $shop->id_document) }}"
                                   target="_blank"
                                   class="text-indigo-500 underline">
                                   Voir le document
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col gap-2 min-w-fit">
                    <form method="POST" action="{{ route('admin.shops.approve', $shop) }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600 transition">
                            ✅ Approuver
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.shops.reject', $shop) }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-red-400 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-500 transition">
                            ❌ Rejeter
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@endsection