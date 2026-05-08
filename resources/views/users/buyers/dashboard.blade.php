@extends('layouts.app')
@section('title', 'Mon espace')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Bonjour, {{ auth()->user()->name }} 👋</h1>
    <p class="text-gray-500 text-sm mt-1">Bienvenue dans ton espace acheteur</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <a href="{{ route('products.index') }}"
       class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition text-center group">
        <div class="text-4xl mb-3">🛍️</div>
        <h2 class="font-semibold text-gray-800 group-hover:text-indigo-600">Parcourir les produits</h2>
        <p class="text-sm text-gray-400 mt-1">Découvre nos articles</p>
    </a>

</div>

@endsection