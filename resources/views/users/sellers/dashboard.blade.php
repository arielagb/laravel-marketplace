@extends('layouts.seller')
@section('title', 'Dashboard')
@section('header', 'Vue d\'ensemble')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Chiffre d'affaires</p>
        <p class="text-2xl font-bold text-indigo-600">{{ number_format($totalRevenue, 0, ',', ' ') }}</p>
        <p class="text-xs text-gray-400">FCFA</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Commandes totales</p>
        <p class="text-2xl font-bold text-green-600">{{ $totalOrders }}</p>
        <p class="text-xs text-gray-400">commandes</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Produits publiés</p>
        <p class="text-2xl font-bold text-amber-500">{{ $products->where('is_published', true)->count() }}</p>
        <p class="text-xs text-gray-400">sur {{ $products->count() }} total</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">En attente d'envoi</p>
        <p class="text-2xl font-bold text-red-500">
            {{ \App\Models\Order::where('shop_id', $shop->id)->where('status', 'paid')->count() }}
        </p>
        <p class="text-xs text-gray-400">commandes</p>
    </div>
</div>

{{-- Graphe ventes --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-8">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">📈 Ventes des 7 derniers jours</h2>
    <canvas id="salesChart" height="80"></canvas>
</div>

{{-- Dernières commandes --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-700">🧾 Dernières commandes</h2>
        <a href="{{ route('seller.orders') }}" class="text-xs text-indigo-500 hover:underline">Voir tout →</a>
    </div>

    @if($orders->isEmpty())
        <p class="text-gray-400 text-sm text-center py-8">Aucune commande pour l'instant</p>
    @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-400 text-xs">
                    <th class="pb-2">Commande</th>
                    <th class="pb-2">Acheteur</th>
                    <th class="pb-2">Montant</th>
                    <th class="pb-2">Statut</th>
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
                    <td class="py-3 text-gray-600">#{{ $order->id }}</td>
                    <td class="py-3 text-gray-600">{{ $order->user->name }}</td>
                    <td class="py-3 font-semibold">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                    <td class="py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $s['class'] }}">{{ $s['label'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Ventes (FCFA)',
                data: @json($salesData),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => number_format(value) + ' FCFA'
                    }
                }
            }
        }
    });

    function number_format(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }
</script>

@endsection