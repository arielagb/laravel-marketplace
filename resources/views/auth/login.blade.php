@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Connexion</h1>
        <p class="text-gray-500 text-sm mb-6">Content de te revoir 👋</p>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

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

            <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                Se connecter
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">S'inscrire</a>
        </p>

    </div>
</div>
@endsection