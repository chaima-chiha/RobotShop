<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\ResetPasswordRequest;


class UserAuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        // Les données sont déjà validées ici
        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
            // Assigner automatiquement le rôle "client"
        $user->assignRole('client');

        return response()->json([
            'result' => $success,
            'msg' => "User registered successfully"

        ]);
    }
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



public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    return response()->json(['message' => 'Reset link sent to your email.']);
}


public function reset(ResetPasswordRequest $request)
{
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    if ($status != Password::PASSWORD_RESET) {
        return response()->json(['message' => __($status)], 422);
    }

    return response()->json(['message' => 'Password reset successfully.']);
}
}
