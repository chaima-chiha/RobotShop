<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VideoController extends Controller
{

    public function show($id)
    {
        $video = Video::findOrFail($id);
        return response()->json(['success' => true, 'data' => $video]);
    }


    /*public function index(Request $request)
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
    }*/

    public function index(Request $request)
    {
        $niveau = $request->query('niveau');

        $query = Video::with(['category', 'files']);

        if ($niveau && in_array($niveau, ['Débutant', 'Intermédiaire', 'Avancé'])) {
            $query->where('niveau', $niveau);
        }

        $videos = $query->get();

        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }

    public function getWithProducts($id){
    $thirtyDaysAgo = Carbon::now()->subDays(30);

    $video = Video::with(['products.category', 'files'])->findOrFail($id);

    $video->products->each(function ($product) use ($thirtyDaysAgo) {
        $product->category_name = optional($product->category)->name;

        // Attribut calculé : nouveau
        $product->is_new = $product->created_at >= $thirtyDaysAgo || $product->updated_at >= $thirtyDaysAgo;

        // Attribut calculé : promotion
        $product->is_promoted = $product->promotion > 0;

        unset($product->category); // On supprime l'objet category de la réponse
    });

    return response()->json([
        'success' => true,
        'data' => $video
    ]);
}
}
