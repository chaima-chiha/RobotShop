<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Video;
use Illuminate\Support\Str;

class VideoOrderStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Commandes par vidéo - Semaine en cours';

    protected function getData(): array
    {
        // Récupère toutes les vidéos avec les vues et les utilisateurs associés (avec leurs commandes)
        $videos = Video::with(['videoViews.user.orders' => function ($query) {
            $query->where('created_at', '>=', now()->subWeek());
        }])->get();

        $labels = [];
        $data = [];

        foreach ($videos as $video) {
            $count = 0;

            foreach ($video->videoViews as $view) {
                $user = $view->user;

                if ($user) {
                    $viewTime = $view->created_at;

                    // Filtrer les commandes faites dans les 2 heures après la vue
                    $relatedOrders = $user->orders->filter(function ($order) use ($viewTime) {
                        return $order->created_at->greaterThanOrEqualTo($viewTime)
                            && $order->created_at->lessThanOrEqualTo($viewTime->copy()->addHours(2));
                    });

                    $count += $relatedOrders->count();
                }
            }

            // N'afficher que les vidéos qui ont généré au moins une commande dans ce contexte
            if ($count > 0) {
                $labels[] = Str::limit($video->title, 20);
                $data[] = $count;
                $colors[] = '#' . substr(md5($video->id), 0, 6); // Génère une couleur hex unique par vidéo

            }
        }
  return [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Commandes générées après visualisation',
                'data' => $data,
                'backgroundColor' => $colors,
                'borderColor' => '#ffffff',
                'borderWidth' => 1,
            ],
        ],
    ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Tu peux changer en 'line' si besoin
    }
}
