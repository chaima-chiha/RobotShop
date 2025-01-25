<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Password;


class UserAuthController extends Controller
{
    //
    public function login(LoginRequest $request)
{
    $input = $request->validated(); // Récupère uniquement les données validées

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


    public function forgotPassword(Request $request){

        // Validation de l'email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Envoi de l'e-mail de réinitialisation
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Vérifie le statut et renvoie une réponse
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Reset password link sent successfully.',
                'status' => __($status),
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to send reset password link.',
                'status' => __($status),
            ], 400);
        }

     }


    public function resetPassword(Request $request)
        {
        // Validation des champs requis
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed', // Assurez-vous que le champ "password_confirmation" est inclus
        ]);

        // Réinitialiser le mot de passe
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        // Vérifie le statut et renvoie une réponse
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password has been successfully reset.',
                'status' => __($status),
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to reset password.',
                'status' => __($status),
            ], 400);
        }
    }


}
