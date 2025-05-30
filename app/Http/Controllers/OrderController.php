<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve all orders for the authenticated user
        $orders = Order::with(['items.product', 'items.video'])
            ->where('user_id', $user->id)
            ->get();

        // Return the list of orders as a JSON response
        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function show($id)
    {
        $user = Auth::user(); // récupère l'utilisateur connecté
        $order = Order::with(['items.product', 'items.video'])->find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Commande introuvable'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'telephone' => 'required|string|max:20',
                'livraison' => 'required|in:domicile,retrait',
                'total' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|integer|exists:cart_items,id',
                'items.*.product_id' => 'nullable|exists:products,id',
                'items.*.video_id' => 'nullable|exists:videos,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0'
            ]);
            Log::info('validation donner', $validatedData);
            // Get the authenticated user
            $user = Auth::user();
            if (!$user) {
                throw new \Exception('User not authenticated.');
            }

            // Start a database transaction
            DB::beginTransaction();

            $order = new Order();
            $order->user_id = $user->id;
            $order->total = $validatedData['total'];
            $order->nom = $validatedData['nom'];
            $order->adresse = $validatedData['adresse'];
            $order->telephone = $validatedData['telephone'];
            $order->livraison = $validatedData['livraison'];
            if (!$order->save()) {
                throw new \Exception('Failed to save order.');
            }

            // Create order items
            foreach ($validatedData['items'] as $item) {
                Log::info('Creating order item:', $item); // Ajoutez cette ligne pour le log
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'video_id' => $item['video_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                if (!$orderItem) {
                    throw new \Exception('Failed to create order item.');
                }
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Commande créée avec succès.'
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error for debugging purposes
            Log::error('Order creation failed: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Erreur lors de la création de la commande.'], 500);
        }
    }



public function update(Request $request, Order $order)
{
    $request->validate([
        'nom' => 'required|string',
        'adresse' => 'required|string',
        'telephone' => 'required|string',
        'livraison' => 'required|string',
        'status' => 'nullable|in:en_attente,annulée',
        'items' => 'required|array',

        'items.*.product_id' => 'nullable|exists:products,id',
        'items.*.video_id' => 'nullable|exists:videos,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    $order->update([
        'nom' => $request->nom,
        'adresse' => $request->adresse,
        'telephone' => $request->telephone,
        'livraison' => $request->livraison,
        'status' => $request->status ?? $order->status,
    ]);

    // Supprimer les anciens items et recréer
    $order->items()->delete();

    foreach ($request->items as $item) {
        $order->items()->create([
            'product_id' => $item['product_id'] ?? null,
            'video_id' => $item['video_id'] ?? null,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    // Recalculer le total
    $total = collect($request->items)->sum(function ($p) {
        return $p['price'] * $p['quantity'];
    });

    $order->update(['total' => $total]);

    return response()->json(['success' => true, 'message' => 'Commande mise à jour.']);
}



}
