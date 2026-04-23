@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('header', 'Vue d\'ensemble')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Utilisateurs</p>
        <p class="text-2xl font-bold text-indigo-600">{{ $totalUsers }}</p>
        <p class="text-xs text-gray-400">inscrits</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Boutiques actives</p>
        <p class="text-2xl font-bold text-green-600">{{ $totalShops }}</p>
        <p class="text-xs text-gray-400">boutiques</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Commandes</p>
        <p class="text-2xl font-bold text-amber-500">{{ $totalOrders }}</p>
        <p class="text-xs text-gray-400">au total</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Chiffre d'affaires</p>
        <p class="text-2xl font-bold text-emerald-600">{{ number_format($totalRevenue, 0, ',', ' ') }}</p>
        <p class="text-xs text-gray-400">FCFA</p>
    </div>
</div>

{{-- Graphes --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">📈 Nouvelles inscriptions (7 jours)</h2>
        <canvas id="usersChart" height="120"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">🧾 Commandes (7 jours)</h2>
        <canvas id="ordersChart" height="120"></canvas>
    </div>
</div>

{{-- Boutiques en attente --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-700">⏳ Boutiques en attente</h2>
        <a href="{{ route('admin.shops.index') }}" class="text-xs text-indigo-500 hover:underline">Voir tout →</a>
    </div>

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
                        <p>👤 {{ $shop->user->name }} — {{ $shop->user->email }}</p>
                        <p>📞 {{ $shop->phone }}</p>
                        <p>📍 {{ $shop->address }}</p>
                        <p>💳 {{ $shop->payment_method }} — {{ $shop->payment_details }}</p>
                        @if($shop->id_document)
                            <p>🪪 <a href="{{ asset('storage/' . $shop->id_document) }}"
                                   target="_blank" class="text-indigo-500 underline">Voir le document</a></p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col gap-2 min-w-fit">
                    <form method="POST" action="{{ route('admin.shops.approve', $shop) }}">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600 transition">
                            ✅ Approuver
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.shops.reject', $shop) }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-400 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-500 transition">
                            ❌ Rejeter
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const chartOptions = {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    };

    new Chart(document.getElementById('usersChart'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($usersData),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderRadius: 6,
            }]
        },
        options: chartOptions
    });

    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($ordersData),
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderRadius: 6,
            }]
        },
        options: chartOptions
    });
</script>

@endsection