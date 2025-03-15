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

    /**
     * Mettre à jour un article du panier.
     */
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
                         ->where('id', $id)
                         ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['success' => true, 'message' => 'Panier mis à jour.']);
    }

    /**
     * Supprimer un article du panier.
     */
    public function removeFromCart($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())
                         ->where('id', $id)
                         ->firstOrFail();

        $cartItem->delete();

        return response()->json(['success' => true, 'message' => 'Produit supprimé du panier.']);
    }

    /**
     * Lister les articles du panier.
     */
    public function getCartItems()
    {
        $cartItems = Cart::with('product') // Assurez-vous d'avoir une relation 'product' dans le modèle Cart
                         ->where('user_id', Auth::id())
                         ->get();

        return response()->json(['success' => true, 'data' => $cartItems]);
    }
}
