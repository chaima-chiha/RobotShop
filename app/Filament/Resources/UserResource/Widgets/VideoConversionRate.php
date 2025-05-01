<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Order;
use App\Models\Video;
use App\Models\User;
use App\Models\VideoView;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;



class VideoConversionRate extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Utilisateurs ayant vu au moins une vidéo
        $viewers = VideoView::distinct('user_id')->pluck('user_id');

        // Parmi eux, ceux ayant passé une commande
        $buyers = Order::whereIn('user_id', $viewers)->distinct()->pluck('user_id');

        $viewerCount = $viewers->count();
        $buyerCount = $buyers->count();

        // Taux de conversion en %
        $conversionRate = $viewerCount > 0
            ? round(($buyerCount / $viewerCount) * 100, 2)
            : 0;

             //  Nombre de clients inscrits ce mois-ci
             $newClients = User::role('client')
             ->whereMonth('created_at', now()->month)
             ->whereYear('created_at', now()->year)
             ->count();

        return [
            Stat::make('Taux de conversion vidéo → commande', $conversionRate . ' %')
                ->description("$buyerCount sur $viewerCount utilisateurs ont commandé après une vidéo")
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success'),

                Stat::make('Vidéos publiées', Video::count())
                ->description('Total des vidéos disponibles')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('info'),

                Stat::make('Nouveaux clients ce mois-ci', $newClients)
                ->description('Clients enregistrés ce mois-ci')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),


        ];
    }
}

