<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Ajouter un article au panier.
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Vérifiez si l'article existe déjà dans le panier
        $cartItem = Cart::where('user_id', Auth::id())
                         ->where('product_id', $request->product_id)
                         ->first();

        if ($cartItem) {
            // Si l'article existe, mettez à jour la quantité
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Sinon, créez un nouvel article dans le panier
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
        $user = $request->user();
        $cartItem = $user->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Produit non trouvé dans le panier.']);
    }
}
