<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function profile(Request $request)
     {
        return $request->user();
    }

    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->get();
        return response()->json(['orders' => $orders]);
    }

    public function getUserDetails(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'msg' => 'User details retrieved successfully'
        ]);
    }

    public function updateUserDetails(Request $request)
    {
    $user = $request->user();

    // Valider les données entrantes
    $validatedData = $request->validate([

        'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    // Mettre à jour les données de l'utilisateur
    if (isset($validatedData['password'])) {
        $validatedData['password'] = Hash::make($validatedData['password']);
    }

    $user->update($validatedData);

    return response()->json([
        'user' => $user,
        'msg' => 'User details updated successfully'
    ]);
    }

}
