@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="shrink-0">
                <img class="h-16 w-auto object-contain" src="{{ asset('uploads/logo.png') }}" alt="Logo">
            </a>
        </div>

        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Créer un compte</h1>
            <p class="text-gray-500 text-sm mb-6">Rejoins-nous !</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Jean Dupont"
                    required
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="ton@email.com"
                    required
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input
                    type="password"
                    name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="••••••••"
                    required
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="••••••••"
                    required
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tu es :</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 border rounded-lg px-4 py-3 cursor-pointer hover:border-black has-[:checked]:border-black has-[:checked]:bg-gray-100">
                        <input type="radio" name="role_id" value="2" class="accent-black" required />
                        <span class="text-sm font-medium">Vendeur</span>
                    </label>
                    <label class="flex items-center gap-2 border rounded-lg px-4 py-3 cursor-pointer hover:border-black has-[:checked]:border-black has-[:checked]:bg-gray-100">
                        <input type="radio" name="role_id" value="3" class="accent-black" required />
                        <span class="text-sm font-medium">Acheteur</span>
                    </label>
                </div>
            </div>

            <button
                type="submit"
                class="w-full bg-black text-white py-2 rounded-lg text-sm font-semibold hover:bg-gray-800 transition">
                Créer mon compte
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Déjà un compte ?
            <a href="{{ route('login') }}" class="text-black hover:underline font-medium">Se connecter</a>
        </p>

    </div>
</div>
@endsection