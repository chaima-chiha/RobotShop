<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Video;
use  App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    $product = Product::findOrFail($request->product_id);

    // Récupérer ou créer le panier de l'utilisateur
    $cart = $request->user()->cart ?? Cart::create(['user_id' => $request->user()->id]);

    // Vérifier si le produit est déjà dans le panier
    $cartItem = $cart->items()->where('product_id', $request->product_id)->first();

    if ($cartItem) {
        // Mise à jour de la quantité
        $newQuantity = $cartItem->quantity + $request->quantity;
        if ($newQuantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant. Quantité disponible : ' . $product->stock,
            ], 400);
        }
        $cartItem->update(['quantity' => $newQuantity]);
    } else {
        // Ajouter un nouvel article au panier
        if ($request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant. Quantité disponible : ' . $product->stock,
            ], 400);
        }

        // Créer un nouvel article dans le panier
        $cart->items()->create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Produit ajouté au panier.']);
}




public function index(Request $request)
{
    $user = $request->user();
    $cart = $user->cart;

    if (!$cart || $cart->items->isEmpty()) {
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Le panier est vide.',
        ]);
    }

    // Charger à la fois la relation product et video
    $items = $cart->items()->with(['product', 'video'])->get();

    return response()->json([
        'success' => true,
        'data' => $items,
    ]);
}
public function remove($itemId, Request $request)
{
    $user = $request->user();
    $cart = $user->cart;

    if (!$cart) {
        return response()->json(['success' => false, 'message' => 'Panier non trouvé.'], 404);
    }

    $cartItem = $cart->items()->find($itemId);

    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'Élément non trouvé dans le panier.'], 404);
    }

    $cartItem->delete();

    return response()->json(['success' => true, 'message' => 'Élément supprimé du panier.']);
}


public function removeAll(Request $request)
{
    $user = $request->user();

    // Récupérer le panier de l'utilisateur
    $cart = $user->cart;

    if (!$cart) {
        return response()->json(['success' => false, 'message' => 'Aucun panier trouvé.'], 404);
    }

    // Vérifier si le panier contient des articles
    if ($cart->items->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'Aucun produit trouvé dans le panier.']);
    }

    // Supprimer tous les articles du panier
    $cart->items()->delete();

    // Optionnel : Supprimer le panier lui-même
    // $cart->delete();

    return response()->json(['success' => true, 'message' => 'Tous les produits ont été supprimés du panier.']);
}
public function updateQuantity($itemId, Request $request)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $user = $request->user();
    $cart = $user->cart;

    if (!$cart) {
        return response()->json(['success' => false, 'message' => 'Panier non trouvé.'], 404);
    }

    $cartItem = $cart->items()->find($itemId);

    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'Élément non trouvé dans le panier.'], 404);
    }

    if ($cartItem->product_id) {
        $product = Product::find($cartItem->product_id);
        if ($request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuffisant. Quantité disponible : ' . $product->stock,
            ], 400);
        }
    }

    $cartItem->update(['quantity' => $request->quantity]);

    return response()->json(['success' => true, 'message' => 'Quantité mise à jour avec succès.']);
}



public function addVideoToCart(Request $request)
{
    $request->validate([
        'video_id' => 'required|exists:videos,id',
        'quantity' => 'nullable|integer|min:1',
    ]);
    $videoId = $request->video_id;

    $video = Video::findOrFail($request->video_id);
    $user = $request->user();
    $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);

    $cartItem = $cart->items()->where('video_id', $video->id)->first();

    if ($cartItem) {
        $cartItem->update(['quantity' => $cartItem->quantity + ($request->quantity ?? 1)]);
    } else {
        $cart->items()->create([
            'video_id' => $video->id,
            'quantity' => $request->quantity ?? 1,
            'price' => $video->price,
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Vidéo ajoutée au panier.']);
}


}
