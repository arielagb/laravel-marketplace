@extends('layouts.admin')
@section('title', 'Commissions')
@section('header', 'Gestion des commissions')

@section('content')

{{-- Stats globales --}}
<div class="grid grid-cols-2 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <p class="text-xs text-gray-400 mb-1 uppercase tracking-wide">Total à percevoir</p>
        <p class="text-3xl font-bold text-amber-500">
            {{ number_format($totalPending, 0, ',', ' ') }} FCFA
        </p>
        <p class="text-xs text-gray-400 mt-1">Commissions non réglées</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <p class="text-xs text-gray-400 mb-1 uppercase tracking-wide">Total perçu</p>
        <p class="text-3xl font-bold text-green-600">
            {{ number_format($totalSettled, 0, ',', ' ') }} FCFA
        </p>
        <p class="text-xs text-gray-400 mt-1">Commissions réglées</p>
    </div>
</div>

{{-- Liste boutiques --}}
<div class="space-y-4">
    @foreach($shops as $shop)
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-start justify-between gap-6">

            {{-- Infos boutique --}}
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <h3 class="font-semibold text-gray-800">{{ $shop->name }}</h3>
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">
                        Taux actuel : {{ $shop->getCommissionRate() }}%
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-4 text-sm mb-4">
                    <div class="bg-amber-50 rounded-lg p-3 text-center">
                        <p class="text-amber-600 font-bold text-lg">
                            {{ number_format($shop->pending_amount ?? 0, 0, ',', ' ') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">FCFA à percevoir</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <p class="text-green-600 font-bold text-lg">
                            {{ number_format($shop->total_amount ?? 0, 0, ',', ' ') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">FCFA perçus</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <p class="text-gray-700 font-bold text-lg">
                            {{ $shop->pending_commissions_count ?? 0 }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">commandes en attente</p>
                    </div>
                </div>

                {{-- Marquer comme réglé --}}
                @if(($shop->pending_amount ?? 0) > 0)
                <form method="POST" action="{{ route('admin.commissions.settle') }}" class="flex items-end gap-3">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}" />
                    <div class="flex-1">
                        <label class="block text-xs text-gray-500 mb-1">Note de paiement (optionnel)</label>
                        <input type="text" name="notes"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                            placeholder="Ex: Virement Mobile Money du 15/05/2026" />
                    </div>
                    <button type="submit"
                        onclick="return confirm('Marquer toutes les commissions de {{ $shop->name }} comme réglées ?')"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition whitespace-nowrap">
                        Marquer réglées
                    </button>
                </form>
                @else
                    <p class="text-xs text-green-600 font-medium">Toutes les commissions sont réglées</p>
                @endif
            </div>

            {{-- Modifier le taux --}}
            <div class="w-48 border-l pl-6">
                <p class="text-xs text-gray-500 mb-2 font-medium">Modifier le taux</p>
                <form method="POST" action="{{ route('admin.commissions.rate', $shop) }}">
                    @csrf
                    <div class="flex items-center gap-2">
                        <input type="number" name="commission_rate"
                            value="{{ $shop->commission_override ?? 10 }}"
                            min="0" max="100" step="0.5"
                            class="w-20 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                        <span class="text-sm text-gray-400">%</span>
                    </div>
                    <button type="submit"
                        class="mt-2 w-full bg-indigo-600 text-white px-3 py-2 rounded-lg text-xs font-medium hover:bg-indigo-700 transition">
                        Appliquer
                    </button>
                </form>
            </div>

        </div>
    </div>
    @endforeach

    @if($shops->isEmpty())
        <div class="text-center py-16 text-gray-400 bg-white rounded-xl shadow-sm">
            <p class="text-lg font-medium">Aucune boutique active</p>
        </div>
    @endif
</div>

@endsection