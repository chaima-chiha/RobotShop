<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VideoOrderStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Commandes par vidéo - Semaine en cours';

    protected function getData(): array
    {
        $videos = Video::with(['videoViews' => function ($query) {
            $query->where('created_at', '>=', now()->subWeek());
        }, 'videoViews.user.orders' => function ($query) {
            $query->where('created_at', '>=', now()->subWeek());
        }])->get();

        $labels = [];
        $data = [];

        foreach ($videos as $video) {
            $count = 0;
            foreach ($video->videoViews as $view) {
                $user = $view->user;
                if ($user) {
                    $count += $user->orders->count();
                }
            }

            // Vérifie si la vidéo a des commandes associées
            if ($count > 0) {
                $labels[] = Str::limit($video->title, 20); // Limite le titre
                $data[] = $count;
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Commandes générées',
                    'data' => $data,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#1d4ed8',
                    'fill' => true,

                ],
            ],
            
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // ou 'line' si tu veux une ligne
    }
}
