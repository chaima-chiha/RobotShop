<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Charger tous les produits avec leurs catégories associées
        $products = Product::with('category:id,name')->get();

        // Ajouter le nom de la catégorie à chaque produit
        $products->each(function ($product) {
            $product->category_name = $product->category->name;
            unset($product->category); // Optionnel : supprimer l'objet catégorie si vous n'en avez plus besoin
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }


    public function show($id)
    {
        $product = Product::with(['category', 'videos'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }


}
