@extends('layouts.admin')
@section('title', 'Boutiques')
@section('header', 'Gestion des boutiques')

@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-left text-gray-400 text-xs bg-gray-50">
                <th class="px-6 py-3">Boutique</th>
                <th class="px-6 py-3">Vendeur</th>
                <th class="px-6 py-3">Statut</th>
                <th class="px-6 py-3">Créée le</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($shops as $shop)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $shop->name }}</p>
                    <p class="text-xs text-gray-400 truncate max-w-xs">{{ $shop->description }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-gray-700">{{ $shop->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $shop->user->email }}</p>
                </td>
                <td class="px-6 py-4">
                    @php
                        $statusClass = match($shop->status) {
                            'active'   => 'bg-green-100 text-green-700',
                            'pending'  => 'bg-amber-100 text-amber-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            default    => 'bg-gray-100 text-gray-600',
                        };
                        $statusLabel = match($shop->status) {
                            'active'   => 'Active',
                            'pending'  => 'En attente',
                            'rejected' => 'Rejetée',
                            default    => $shop->status,
                        };
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="px-6 py-4 text-gray-400 text-xs">
                    {{ $shop->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4">
                    @if($shop->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.shops.approve', $shop) }}">
                                @csrf
                                <button type="submit" class="text-xs text-green-500 hover:underline">Approuver</button>
                            </form>
                            <form method="POST" action="{{ route('admin.shops.reject', $shop) }}">
                                @csrf
                                <button type="submit" class="text-xs text-red-400 hover:underline">Rejeter</button>
                            </form>
                        </div>
                    @else
                        <span class="text-xs text-gray-300">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $shops->links() }}
</div>

@endsection