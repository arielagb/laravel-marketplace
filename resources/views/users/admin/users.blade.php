@extends('layouts.admin')
@section('title', 'Utilisateurs')
@section('header', 'Gestion des utilisateurs')

@section('content')

{{-- Filtre --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex gap-4 flex-wrap items-center">
    <input type="text" id="searchInput"
        placeholder="Rechercher par nom ou email..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />

    <select id="roleSelect"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Tous les rôles</option>
        <option value="Admin">Admin</option>
        <option value="Seller">Vendeur</option>
        <option value="Buyer">Acheteur</option>
    </select>

    <select id="statusSelect"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Tous les statuts</option>
        <option value="actif">Actif</option>
        <option value="bloque">Bloqué</option>
    </select>

    <button onclick="clearFilters()"
        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
        Effacer
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b text-left text-gray-400 text-xs bg-gray-50">
                <th class="px-6 py-3">Utilisateur</th>
                <th class="px-6 py-3">Rôle</th>
                <th class="px-6 py-3">Inscrit le</th>
                <th class="px-6 py-3">Statut</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50" id="usersTable">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50 user-row"
                data-name="{{ strtolower($user->name) }}"
                data-email="{{ strtolower($user->email) }}"
                data-role="{{ $user->role?->label }}"
                data-status="{{ $user->is_blocked ? 'bloque' : 'actif' }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs px-2 py-1 rounded-full
                        {{ $user->role?->label === 'Admin' ? 'bg-purple-100 text-purple-700' :
                           ($user->role?->label === 'Seller' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $user->role?->label ?? '—' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-400 text-xs">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4">
                    @if($user->is_blocked)
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">Bloqué</span>
                    @else
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">Actif</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="text-xs text-indigo-500 hover:underline">
                            Voir
                        </a>
                        @if($user->role?->label !== 'Admin')
                            <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs {{ $user->is_blocked ? 'text-green-500' : 'text-red-400' }} hover:underline">
                                    {{ $user->is_blocked ? 'Débloquer' : 'Bloquer' }}
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div id="noResults" class="hidden text-center py-12 text-gray-400">
        <p class="text-lg">🔍</p>
        <p class="text-sm mt-1">Aucun utilisateur ne correspond à ta recherche</p>
    </div>
</div>

<div class="mt-4" id="pagination">
    {{ $users->links() }}
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const roleSelect = document.getElementById('roleSelect');
    const statusSelect = document.getElementById('statusSelect');
    const rows = document.querySelectorAll('.user-row');
    const noResults = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');

    function filterUsers() {
        const query = searchInput.value.toLowerCase().trim();
        const role = roleSelect.value;
        const status = statusSelect.value;
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name;
            const email = row.dataset.email;
            const rowRole = row.dataset.role;
            const rowStatus = row.dataset.status;

            const matchSearch = query === '' || name.includes(query) || email.includes(query);
            const matchRole = role === '' || rowRole === role;
            const matchStatus = status === '' || rowStatus === status;

            row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
            if (matchSearch && matchRole && matchStatus) visibleCount++;
        });

        noResults.classList.toggle('hidden', visibleCount > 0);
        pagination.style.display = (query || role || status) ? 'none' : '';
    }

    searchInput.addEventListener('input', filterUsers);
    roleSelect.addEventListener('change', filterUsers);
    statusSelect.addEventListener('change', filterUsers);

    function clearFilters() {
        searchInput.value = '';
        roleSelect.value = '';
        statusSelect.value = '';
        rows.forEach(row => row.style.display = '');
        noResults.classList.add('hidden');
        pagination.style.display = '';
        searchInput.focus();
    }
</script>

@endsection