<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('query');

    if (!$query) {
        return response()->json([
            'success' => false,
            'message' => 'Aucun mot-clé de recherche fourni'
        ], 400);
    }

    $product = Product::where('name', 'LIKE', "%{$query}%")->first();
    $category = Category::where('name', 'LIKE', "%{$query}%")->first();

    if ($product) {
        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'category' => null
            ]
        ]);
    } elseif ($category) {
        return response()->json([
            'success' => true,
            'data' => [
                'product' => null,
                'category' => $category
            ]
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Aucun résultat trouvé'
        ], 404);
    }
}

}
