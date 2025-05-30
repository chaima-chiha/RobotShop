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
        // Toutes les vues de vidéos avec utilisateur chargé
        $views = VideoView::with('user')->get();

        $totalViewers = collect(); // utilisateurs uniques ayant vu des vidéos
        $convertedUsers = collect(); // utilisateurs ayant commandé dans les 2h après une vue

        foreach ($views as $view) {
            $user = $view->user;

            if ($user) {
                $totalViewers->push($user->id);

                $hasRecentOrder = $user->orders()
                    ->where('created_at', '>=', $view->created_at)
                    ->where('created_at', '<=', $view->created_at->copy()->addHours(24))
                    ->exists();

                if ($hasRecentOrder) {
                    $convertedUsers->push($user->id);
                }
            }
        }

        // Supprimer les doublons
        $viewerCount = $totalViewers->unique()->count();
        $buyerCount = $convertedUsers->unique()->count();

        $conversionRate = $viewerCount > 0
            ? round(($buyerCount / $viewerCount) * 100, 2)
            : 0;

        // Nouveaux clients ce mois-ci
        $newClients = User::role('client')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Taux conversion vidéo → commande (≤ 24h)', $conversionRate . ' %')
                ->description("$buyerCount sur $viewerCount viewers ont commandé juste après 24H")
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
