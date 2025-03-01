<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   /* public function index()
    {

          $products = Product::all();
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

    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }*/
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filtrer par catégorie si le paramètre est présent
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }



    public function getByCategory($category_id)
    {
        $products = Product::where('category_id', $category_id)
            ->with(['category', 'videos'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
