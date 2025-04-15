<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
   /* public function index()
    {
        $videos = Video::with('category')->get();

        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return response()->json(['success' => true, 'data' => $video]);
    }*/


    public function index(Request $request)
    {
        $niveau = $request->query('niveau');

        $query = Video::with('category');

        if ($niveau && in_array($niveau, ['Débutant', 'Intermédiaire', 'Avancé'])) {
            $query->where('niveau', $niveau);
        }

        $videos = $query->get();

        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }



}
