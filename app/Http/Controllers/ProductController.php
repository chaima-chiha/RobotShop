<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class ProductController extends Controller
{

public function index(Request $request)
{
    $thirtyDaysAgo = Carbon::now()->subDays(30);
    $filter = $request->query('filter', 'all');
    $categoryId = $request->query('category', 'all');

    // Clé de cache basée sur les paramètres de filtre
    $cacheKey = "products_{$filter}_category_{$categoryId}";

    // Durée du cache en minutes (ex: 60 min)
    $products = Cache::remember($cacheKey, 60, function () use ($filter, $categoryId, $thirtyDaysAgo) {
        $productsQuery = Product::with('category:id,name');

        if ($categoryId !== 'all') {
            $productsQuery->where('category_id', $categoryId);
        }

        switch ($filter) {
            case 'promo':
                $productsQuery->where('promotion', '>', 0);
                break;
            case 'new':
                $productsQuery->where(function ($query) use ($thirtyDaysAgo) {
                    $query->where('created_at', '>=', $thirtyDaysAgo)
                          ->orWhere('updated_at', '>=', $thirtyDaysAgo);
                });
                break;
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
        }

        $products = $productsQuery->get();

        $products->each(function ($product) use ($thirtyDaysAgo) {
            $product->category_name = $product->category->name;
            $product->is_new = $product->created_at >= $thirtyDaysAgo || $product->updated_at >= $thirtyDaysAgo;
            $product->is_promoted = $product->promotion > 0;
            unset($product->category);
        });

        if (!in_array($filter, ['price_asc', 'price_desc', 'name_asc', 'name_desc'])) {
            $products = $products->sortByDesc('is_new')->sortByDesc('is_promoted')->values();
        } else {
            $products = $products->values();
        }

        return $products;
    });

    return response()->json([
        'success' => true,
        'data' => $products
    ]);
}



    public function show($id)
{
    $thirtyDaysAgo = Carbon::now()->subDays(30);

    $product = Product::with('category:id,name')->find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Produit non trouvé'
        ]);
    }

    // Ajouter les propriétés supplémentaires
    $product->category_name = $product->category ? $product->category->name : null;
    $product->is_new = $product->created_at >= $thirtyDaysAgo || $product->updated_at >= $thirtyDaysAgo;
    $product->is_promoted = $product->promotion > 0;

    unset($product->category);

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
