@extends('layouts.admin')
@section('title', 'Commandes')
@section('header', 'Toutes les commandes')

@section('content')

{{-- Filtre --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex items-center gap-4">
    <input type="text" id="searchInput"
        placeholder="Rechercher par acheteur, boutique ou #commande..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
    <button onclick="clearSearch()"
        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
        Effacer
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-left text-gray-400 text-xs bg-gray-50">
                <th class="px-6 py-3">Commande</th>
                <th class="px-6 py-3">Acheteur</th>
                <th class="px-6 py-3">Boutique</th>
                <th class="px-6 py-3">Montant</th>
                <th class="px-6 py-3">Statut</th>
                <th class="px-6 py-3">Date</th>
            </tr>
        </thead>
        <tbody id="ordersTable" class="divide-y divide-gray-50">
            @foreach($orders as $order)
            @php
                $statusMap = [
                    "created"   => ["label" => "Creee",      "class" => "bg-blue-100 text-blue-700"],
                    "paid"      => ["label" => "Payee",      "class" => "bg-green-100 text-green-700"],
                    "shipped"   => ["label" => "Expediee",   "class" => "bg-indigo-100 text-indigo-700"],
                    "delivered" => ["label" => "Livree",     "class" => "bg-green-100 text-green-800"],
                    "cancelled" => ["label" => "Annulee",    "class" => "bg-red-100 text-red-700"],
                    "refunded"  => ["label" => "Remboursee", "class" => "bg-gray-100 text-gray-700"],
                ];
                $s = $statusMap[$order->status] ?? ["label" => $order->status, "class" => "bg-gray-100 text-gray-700"];
            @endphp
            <tr class="hover:bg-gray-50 order-row">
                <td class="px-6 py-4 font-medium text-gray-700">#{{ $order->id }}</td>
                <td class="px-6 py-4 text-gray-600 searchable">{{ $order->user->name }}</td>
                <td class="px-6 py-4 text-gray-600 searchable">{{ $order->shop->name }}</td>
                <td class="px-6 py-4 font-semibold">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                <td class="px-6 py-4">
                    <span class="text-xs px-2 py-1 rounded-full {{ $s['class'] }}">{{ $s['label'] }}</span>
                </td>
                <td class="px-6 py-4 text-gray-400 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div id="noResults" class="hidden text-center py-12 text-gray-400">
        <p class="text-lg">🔍</p>
        <p class="text-sm mt-1">Aucune commande ne correspond à ta recherche</p>
    </div>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('.order-row');
    const noResults = document.getElementById('noResults');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;

        rows.forEach(row => {
            const searchableCells = row.querySelectorAll('.searchable');
            const orderId = row.querySelector('td').textContent.toLowerCase();

            let match = orderId.includes(query);
            searchableCells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(query)) {
                    match = true;
                }
            });

            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        noResults.classList.toggle('hidden', visibleCount > 0);
    });

    function clearSearch() {
        searchInput.value = '';
        rows.forEach(row => row.style.display = '');
        noResults.classList.add('hidden');
        searchInput.focus();
    }
</script>

@endsection