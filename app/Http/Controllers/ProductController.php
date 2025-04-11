<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ProductController extends Controller
{
   
public function index(Request $request)
{
    $thirtyDaysAgo = Carbon::now()->subDays(30);
    $filter = $request->query('filter', 'all');
    $categoryId = $request->query('category', 'all');

    // Récupérer tous les produits avec leur catégorie
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

        // Marquer les produits récents
        $product->is_new = $product->created_at >= $thirtyDaysAgo || $product->updated_at >= $thirtyDaysAgo;

        // Marquer les produits en promotion
        $product->is_promoted = $product->promotion > 0;
       $product->remaining_stock = $product->remaining_stock; // expose le stock restant
       // $product->current_stock = $product->current_stock;

        unset($product->category);
    });

    // Appliquer le tri par nouveauté et promotion uniquement si aucun tri par prix ou nom n'est appliqué
    if (!in_array($filter, ['price_asc', 'price_desc', 'name_asc', 'name_desc'])) {
        $sorted = $products->sortByDesc('is_new')->sortByDesc('is_promoted')->values();
    } else {
        $sorted = $products->values();
    }

    return response()->json([
        'success' => true,
        'data' => $sorted
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
