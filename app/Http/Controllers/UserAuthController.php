<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserAuthController extends Controller
{
    //
    function login(Request $request)
    {
        $input = $request->all();

        // Validation des champs requis
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Vérifie si l'utilisateur existe
        $user = User::where('email', $input['email'])->first();

        if (!$user || !Hash::check($input['password'], $user->password)) {
            return response()->json(['msg' => 'Invalid credentials'], 401);
        }

        // Génération d'un token d'accès
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json(['result' => $success, 'msg' => 'Login successful']);
    }
    function logout(Request $request)
    {
        // Suppression du token actuel
        $request->user()->currentAccessToken()->delete();

        return response()->json(['msg' => 'Logout successful']);
    }
    function signup(Request $request){
        $input =$request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token']= $user->createToken('MyApp')->plainTextToken;
        $success['name']= $user->name;
        return ['result'=> $success, 'msg'=>"user register successfully"];
    }
}
