<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function profile(Request $request)
     {
        return $request->user();
    }

    public function getUserDetails(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user,
            'msg' => 'User details retrieved successfully'
        ]);
    }

    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->get();
        return response()->json(['orders' => $orders]);
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $user->name = $request->nom;
        $user->adresse = $request->adresse;
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Profil mis à jour avec succès.'
        ]);
    }


}
