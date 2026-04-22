@extends('layouts.app')
@section('title', 'Mes commandes')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Mes commandes 📦</h1>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

@if($orders->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <div class="text-5xl mb-4">📦</div>
        <p class="text-lg font-medium">Aucune commande pour l'instant</p>
        <a href="{{ route('products.index') }}"
           class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
            Parcourir les produits
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500">Commande #{{ $order->id }}</p>
                    <p class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Boutique : {{ $order->shop->name }}</p>
                </div>
                <div class="text-right">
                    @php
                        $statusLabels = [
                            'created'   => ['label' => 'Créée', 'class' => 'bg-blue-100 text-blue-700'],
                            'paid'      => ['label' => 'Payée', 'class' => 'bg-green-100 text-green-700'],
                            'shipped'   => ['label' => 'Expédiée', 'class' => 'bg-indigo-100 text-indigo-700'],
                            'delivered' => ['label' => 'Livrée', 'class' => 'bg-green-100 text-green-800'],
                            'cancelled' => ['label' => 'Annulée', 'class' => 'bg-red-100 text-red-700'],
                            'refunded'  => ['label' => 'Remboursée', 'class' => 'bg-gray-100 text-gray-700'],
                        ];
                        $s = $statusLabels[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-700'];
                    @endphp
                    <span class="text-xs px-3 py-1 rounded-full font-medium {{ $s['class'] }}">
                        {{ $s['label'] }}
                    </span>
                    <p class="font-bold text-gray-800 mt-2">
                        {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                    </p>
                </div>
            </div>

            <div class="border-t pt-4 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    {{ $order->items->count() }} article(s)
                </p>
                <a href="{{ route('orders.show', $order) }}"
                   class="text-sm text-indigo-600 hover:underline">
                    Voir le détail →
                </a>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection