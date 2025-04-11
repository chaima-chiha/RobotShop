<?php

namespace App\Http\Controllers;

use id;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve all orders for the authenticated user
        $orders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->get();

        // Return the list of orders as a JSON response
        return response()->json(['success' => true, 'data' => $orders]);
    }
    public function show($id)

    {
        $user = Auth::user(); // récupère l'utilisateur connecté
        $order = Order::with('items.product')->find($id);

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
            $validatedData =
            $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'livraison' => 'required|in:domicile,retrait',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

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
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
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


}
