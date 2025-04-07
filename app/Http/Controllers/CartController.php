<?php

namespace App\Http\Controllers;

use  App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::find($request->product_id);

    // Récupérer la quantité déjà dans le panier
    $existingCartItem = Cart::where('user_id', Auth::id())
                            ->where('product_id', $request->product_id)
                            ->first();

    $existingQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
    $totalRequestedQuantity = $existingQuantity + $request->quantity;

    // Vérifier la disponibilité en stock
    if ($product->stock < $totalRequestedQuantity) {
        return response()->json([
            'success' => false,
            'message' => 'Stock insuffisant. Quantité disponible : ' . $product->stock,
        ], 400);
    }

    if ($existingCartItem) {
        // Mise à jour de la quantité dans le panier
        $existingCartItem->quantity = $totalRequestedQuantity;
        $existingCartItem->save();
    } else {
        // Ajout au panier
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Produit ajouté au panier.']);
}


      public function index(Request $request)
    {
        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get();

        return response()->json([
            'success' => true,
            'data' => $cartItems
        ]);
    }
    public function remove($productId, Request $request)
    {
        $user = $request->user();
        $cartItem = $user->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Produit non trouvé dans le panier.']);
    }


    public function removeAll(Request $request)
    {
        $user = $request->user();

        // Assuming you have a relationship between User and CartItem
        $cartItems = $user->cartItems();

        // Check if there are any items in the cart
        if ($cartItems->exists()) {
            // Delete all cart items
            $cartItems->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Aucun produit trouvé dans le panier.']);
    }



    public function updateQuantity($productId, Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $cartItem = $user->cartItems()->where('product_id', $productId)->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Produit non trouvé dans le panier.'], 404);
        }

        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produit introuvable.'], 404);
        }

        if ($request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant. Quantité disponible : ' . $product->stock,
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['success' => true, 'message' => 'Quantité mise à jour avec succès.']);
    }

}
