@extends('layouts.admin')
@section('title', 'Catégories')
@section('header', 'Gestion des catégories')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Formulaire ajout --}}
    <div class="bg-white rounded-xl shadow-sm p-6 h-fit">
        <h2 class="font-semibold text-gray-700 mb-4">➕ Nouvelle catégorie</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ex: Vêtements" required />
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                Créer la catégorie
            </button>
        </form>
    </div>

    {{-- Liste catégories --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4">🏷️ Catégories existantes</h2>

        @if($categories->isEmpty())
            <p class="text-gray-400 text-sm text-center py-8">Aucune catégorie créée</p>
        @else
            <div class="space-y-3">
                @foreach($categories as $category)
                <div class="flex items-center justify-between border border-gray-100 rounded-lg px-4 py-3">
                    <div>
                        <p class="font-medium text-gray-800">{{ $category->name }}</p>
                        <p class="text-xs text-gray-400">{{ $category->products_count }} produit(s)</p>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Modifier --}}
                        <button onclick="openEdit({{ $category->id }}, '{{ $category->name }}')"
                            class="text-xs text-indigo-500 hover:underline">
                            Modifier
                        </button>
                        {{-- Supprimer --}}
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Supprimer cette catégorie ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:underline">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Modal modification --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h2 class="font-semibold text-gray-700 mb-4">✏️ Modifier la catégorie</h2>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" id="editName"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required />
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    Sauvegarder
                </button>
                <button type="button" onclick="closeEdit()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEdit(id, name) {
        document.getElementById('editName').value = name;
        document.getElementById('editForm').action = '/admin/categories/' + id;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEdit() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

@endsection