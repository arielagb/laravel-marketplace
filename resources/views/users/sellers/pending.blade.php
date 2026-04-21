@extends('layouts.app')
@section('title', 'Demande en cours')
@section('content')

<div class="max-w-lg mx-auto text-center py-20">
    <div class="text-6xl mb-6">⏳</div>
    <h1 class="text-2xl font-bold text-gray-800 mb-3">Demande en cours de validation</h1>
    <p class="text-gray-500 mb-6">
        Ton dossier a bien été reçu. Notre équipe va l'examiner sous 24-48h.
        Tu recevras une confirmation dès que ta boutique sera activée.
    </p>
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700">
        💡 En attendant, tu peux explorer la marketplace en tant qu'acheteur.
    </div>
    <form method="POST" action="{{ route('logout') }}" class="mt-8">
        @csrf
        <button type="submit" class="text-sm text-red-400 hover:underline">Se déconnecter</button>
    </form>
</div>

@endsection