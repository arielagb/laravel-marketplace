<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Category;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function onboarding()
    {
        // Si le seller a déjà une boutique, on redirige
        if (auth()->user()->shop) {
            return redirect()->route('seller.pending');
        }
        $categories = Category::all();
        return view('users.sellers.onboarding', compact('categories'));
    }

    public function storeOnboarding(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255|unique:shops,name',
            'category_id'     => 'required|exists:categories,id',
            'description'     => 'required|string|min:20',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:255',
            'payment_method'  => 'required|in:mobile_money,bank',
            'payment_details' => 'required|string|max:255',
            'id_document'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'name.required'            => 'Le nom de la boutique est obligatoire.',
            'name.unique'              => 'Ce nom de boutique est déjà pris.',
            'category_id.required'     => 'Choisissez une catégorie.',
            'description.min'          => 'La description doit faire au moins 20 caractères.',
            'phone.required'           => 'Le numéro de téléphone est obligatoire.',
            'address.required'         => 'L\'adresse est obligatoire.',
            'payment_method.required'  => 'Choisissez un moyen de paiement.',
            'payment_details.required' => 'Les détails de paiement sont obligatoires.',
            'id_document.required'     => 'La pièce d\'identité est obligatoire.',
            'id_document.mimes'        => 'Le document doit être JPG, PNG ou PDF.',
            'id_document.max'          => 'Le fichier ne doit pas dépasser 2MB.',
        ]);

        // Sauvegarde du document d'identité dans storage/app/public/kyc/
        $documentPath = $request->file('id_document')->store('kyc', 'public');

        Shop::create([
            'user_id'         => auth()->id(),
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'description'     => $request->description,
            'category_id'     => $request->category_id,
            'phone'           => $request->phone,
            'address'         => $request->address,
            'payment_method'  => $request->payment_method,
            'payment_details' => $request->payment_details,
            'id_document'     => $documentPath,
            'status'          => 'pending',
        ]);

        return redirect()->route('seller.pending');
    }

    public function pending()
    {
        return view('users.sellers.pending');
    }
}