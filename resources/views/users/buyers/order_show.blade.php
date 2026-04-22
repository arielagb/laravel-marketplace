@extends('layouts.app')
@section('title', 'Commande #' . $order->id)

@section('content')

<div class="mb-6">
    <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Retour aux commandes</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Commande #{{ $order->id }}</h1>
        <span class="text-xs px-3 py-1 rounded-full font-medium bg-blue-100 text-blue-700">
            {{ $order->status }}
        </span>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="font-semibold text-gray-700 mb-4 border-b pb-2">Articles commandés</h2>
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                    @if($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                        <img src="{{ asset($item->product->images[0]) }}" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xl">📦</div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $item->product->title }}</p>
                    <p class="text-xs text-gray-400">{{ $item->quantity }} × {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</p>
                </div>
                <p class="font-bold text-gray-800">
                    {{ number_format($item->total_price, 0, ',', ' ') }} FCFA
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4 border-b pb-2">Résumé</h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>Sous-total</span>
                <span>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Livraison</span>
                <span class="text-green-600">Gratuite</span>
            </div>
            <div class="flex justify-between font-bold text-gray-800 border-t pt-2">
                <span>Total</span>
                <span>{{ number_format($order->total_amount + $order->shipping_fee, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
    </div>
</div>

@endsection