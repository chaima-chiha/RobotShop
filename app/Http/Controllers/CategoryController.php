<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function productsByCategory(Request $request, $id)
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $filter = $request->query('filter', 'all');

        // Charger la catégorie avec ses produits associés
        $category = Category::with(['products' => function ($query) use ($filter, $thirtyDaysAgo) {
            // Appliquer les filtres si présents
            switch ($filter) {
                case 'promo':
                    $query->where('promotion', '>', 0);
                    break;
                case 'new':
                    $query->where(function ($q) use ($thirtyDaysAgo) {
                        $q->where('created_at', '>=', $thirtyDaysAgo)
                          ->orWhere('updated_at', '>=', $thirtyDaysAgo);
                    });
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }])->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        // Ajouter le nom de la catégorie à chaque produit
        $category->products->each(function ($product) use ($category, $thirtyDaysAgo) {
            $product->category_name = $category->name;

            // Marquer les produits récents
            $product->is_new = $product->created_at >= $thirtyDaysAgo || $product->updated_at >= $thirtyDaysAgo;

            // Marquer les produits en promotion
            $product->is_promoted = $product->promotion > 0;
        });

        // Appliquer le tri par nouveauté et promotion uniquement si aucun tri par prix ou nom n'est appliqué
        if (!in_array($filter, ['price_asc', 'price_desc', 'name_asc', 'name_desc'])) {
            $sorted = $category->products->sortByDesc('is_new')->sortByDesc('is_promoted')->values();
        } else {
            $sorted = $category->products->values();
        }

        return response()->json([
            'success' => true,
            'data' => $sorted
        ]);
    }
}



