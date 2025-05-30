<?php

namespace App\Http\Controllers;
use App\Models\VideoActivationCode;
use App\Models\Video;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
class VideoController extends Controller
{

    public function show($id)
    {
        $video = Video::findOrFail($id);
        return response()->json(['success' => true, 'data' => $video]);
    }

public function index(Request $request)
{
    $niveau = $request->query('niveau');

    // Clé unique selon le niveau
    $cacheKey = $niveau ? "videos_niveau_{$niveau}" : "videos_all";

    // Mise en cache pendant 60 minutes
    $videos = Cache::remember($cacheKey, 60, function () use ($niveau) {
        $query = Video::with(['category', 'files']);

        if ($niveau && in_array($niveau, ['Débutant', 'Intermédiaire', 'Avancé'])) {
            $query->where('niveau', $niveau);
        }

        return $query->get();
    });

    return response()->json([
        'success' => true,
        'data' => $videos
    ]);
}


public function getWithProducts($id)
{
    $thirtyDaysAgo = Carbon::now()->subDays(30);

    $video = Video::with(['products.category', 'files'])->findOrFail($id);

    $video->products->each(function ($product) use ($thirtyDaysAgo) {
        $product->category_name = optional($product->category)->name;
        $product->is_new = $product->created_at >= $thirtyDaysAgo || $product->updated_at >= $thirtyDaysAgo;
        $product->is_promoted = $product->promotion > 0;
        unset($product->category);
    });

    // Chercher des vidéos similaires
    $similarVideos = Video::where('niveau', $video->niveau)
                    ->where('id', '!=', $video->id)
                    ->take(4) // Par exemple, limite à 4 vidéos
                    ->get();

    return response()->json([
        'success' => true,
        'data' => $video,
        'similar_videos' => $similarVideos
    ]);
}

public function verifyActivationCode(Request $request, Video $video)
{
    $request->validate([
        'code' => 'required|string'
    ]);

    $user = $request->user();

    $activation =VideoActivationCode::where('user_id', $user->id)
        ->where('video_id', $video->id)
        ->where('code', $request->code)
        ->first();

    return response()->json([
        'valid' => $activation !== null
    ]);
}

}
