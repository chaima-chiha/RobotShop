<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;


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

    public function indexNew()
    {
        // Calculer la date d'il y a 30 jours
        $thirtyDaysAgo = Carbon::now()->subDays(30);



        // Récupérer les produits créés ou mis à jour dans les 30 derniers jours
        $recentProducts = Product::where('created_at', '>=', $thirtyDaysAgo)
                                 ->orWhere('updated_at', '>=', $thirtyDaysAgo)
                                 ->get();
    // Ajouter le nom de la catégorie à chaque produit
        $recentProducts->each(function ($product) {
            $product->category_name = $product->category->name;
            unset($product->category); // Optionnel : supprimer l'objet catégorie si vous n'en avez plus besoin
        });
        // Retourner les produits récents en JSON
        return response()->json([
            'success' => true,
            'data' => $recentProducts
        ]);
    }

    public function indexPromo()
{
    // Récupérer les produits en promotion (où promotion n'est pas null)
    $promoProducts = Product::where('promotion', '>', 0)
                           ->get();
 // Ajouter le nom de la catégorie à chaque produit
    $promoProducts->each(function ($product) {
    $product->category_name = $product->category->name;
    unset($product->category); // Optionnel : supprimer l'objet catégorie si vous n'en avez plus besoin
});
    return response()->json([
        'success' => true,
        'data' => $promoProducts
    ]);
}
}
