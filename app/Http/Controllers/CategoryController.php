<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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

    public function productsByCategory($id)
{
    $category = Category::with('products')->find($id);

    if (!$category) {
        return response()->json([
            'success' => false,
            'message' => 'Catégorie non trouvée'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $category->products
    ]);
}
}
