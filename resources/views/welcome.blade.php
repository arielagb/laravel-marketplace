@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

{{-- Hero Section --}}
<div class="text-center py-20 bg-gradient-to-br from-indigo-50 to-white rounded-2xl mb-12">
    <h1 class="text-4xl font-extrabold text-gray-800 mb-4">
        Bienvenue sur <span class="text-indigo-600">Marketplace</span>
    </h1>
    <p class="text-gray-500 text-lg mb-8 max-w-xl mx-auto">
        Achète et vends des vêtements, décorations et bien plus encore.
    </p>
    <div class="flex justify-center gap-4">
        <a href="{{ route('register') }}"
           class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
            Commencer
        </a>
        <a href="{{ route('login') }}"
           class="border border-indigo-600 text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
            Se connecter
        </a>
    </div>
</div>

{{-- Catégories --}}
<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Nos catégories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['emoji' => '👗', 'label' => 'Vêtements'],
            ['emoji' => '👟', 'label' => 'Chaussures'],
            ['emoji' => '🏠', 'label' => 'Décoration'],
            ['emoji' => '💍', 'label' => 'Accessoires'],
        ] as $cat)
        <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition cursor-pointer">
            <div class="text-4xl mb-2">{{ $cat['emoji'] }}</div>
            <div class="text-sm font-medium text-gray-700">{{ $cat['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Call to action vendeur --}}
<div class="bg-indigo-600 text-white rounded-2xl p-10 text-center">
    <h2 class="text-2xl font-bold mb-2">Tu veux vendre tes produits ?</h2>
    <p class="text-indigo-200 mb-6">Crée ta boutique en quelques minutes et commence à vendre.</p>
    <a href="{{ route('register') }}"
       class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
        Ouvrir ma boutique
    </a>
</div>

@endsection