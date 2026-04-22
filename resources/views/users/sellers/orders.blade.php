@extends('layouts.seller')
@section('title', 'Commandes')
@section('header', 'Mes commandes')

@section('content')

@if($orders->isEmpty())
    <div class="text-center py-20 text-gray-400 bg-white rounded-xl shadow-sm">
        <div class="text-5xl mb-4">🧾</div>
        <p class="text-lg font-medium">Aucune commande pour l'instant</p>
    </div>
@else
    <form method="POST" action="{{ route('seller.orders.ship') }}">
        @csrf

        <div class="flex justify-between items-center mb-4">
            <p class="text-sm text-gray-500">{{ $orders->count() }} commande(s) au total</p>
            <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                📦 Marquer comme expédiées
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-gray-400 text-xs bg-gray-50">
                        <th class="px-4 py-3">
                            <input type="checkbox" id="selectAll" class="accent-indigo-600" />
                        </th>
                        <th class="px-4 py-3">Commande</th>
                        <th class="px-4 py-3">Acheteur</th>
                        <th class="px-4 py-3">Articles</th>
                        <th class="px-4 py-3">Montant</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($orders as $order)
                    @php
                        $statusLabels = [
                            'created'   => ['label' => 'Créée',    'class' => 'bg-blue-100 text-blue-700'],
                            'paid'      => ['label' => 'Payée',    'class' => 'bg-green-100 text-green-700'],
                            'shipped'   => ['label' => 'Expédiée', 'class' => 'bg-indigo-100 text-indigo-700'],
                            'delivered' => ['label' => 'Livrée',   'class' => 'bg-green-100 text-green-800'],
                            'cancelled' => ['label' => 'Annulée',  'class' => 'bg-red-100 text-red-700'],
                        ];
                        $s = $statusLabels[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-700'];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($order->status === 'paid')
                                <input type="checkbox" name="order_ids[]"
                                       value="{{ $order->id }}"
                                       class="accent-indigo-600" />
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-700">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $order->user->name }}</td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $order->items->count() }} article(s)
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-800">
                            {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $s['class'] }}">
                                {{ $s['label'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    <script>
        // Sélectionner/désélectionner toutes les commandes payées
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('input[name="order_ids[]"]')
                    .forEach(cb => cb.checked = this.checked);
        });
    </script>
@endif

@endsection