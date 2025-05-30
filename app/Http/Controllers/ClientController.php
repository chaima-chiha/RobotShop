<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
        'telephone' => 'nullable|string|max:20',
    ]);

    $user = $request->user();
    $user->name = $request->nom;
    $user->adresse = $request->adresse;
    $user->telephone = $request->telephone;
    $user->save();

    return response()->json([
        'success' => true,
        'user' => $user,
        'message' => 'Profil mis à jour avec succès.'
    ]);
}


public function addVideoView(Request $request, Video $video)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Utilisateur non authentifié.'
        ], 401);
    }

    // Cherche la vue existante
    $view = VideoView::where('user_id', $user->id)
        ->where('video_id', $video->id)
        ->first();

    if ($view) {
        // Met à jour la date de visualisation à maintenant
        $view->created_at = now();
        $view->save();
    } else {
        // Crée une nouvelle vue
        VideoView::create([
            'user_id' => $user->id,
            'video_id' => $video->id,
            'created_at' => now(), // facultatif, Eloquent le fait automatiquement
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Visualisation enregistrée.'
    ]);
}





    public function videoHistory(Request $request)
    {

        $user = $request->user();

        // On récupère toutes les vidéos que l'utilisateur a vues
        $videos = Video::whereIn('id', function ($query) use ($user) {
            $query->select('video_id')
                  ->from('video_views')
                  ->where('user_id', $user->id);
        })->get();

        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }
}
