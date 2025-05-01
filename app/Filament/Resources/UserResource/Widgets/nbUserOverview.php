<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Video;
use App\Models\VideoView;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class nbUserOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Nombre total de clients
        $clientCount = User::role('client')->count();

        // Nombre total de commandes
        $orderCount = Order::count();

        // Chiffre d'affaires
        $revenue = Order::sum('total');

        // 🔢 Évolution mensuelle sur les 6 derniers mois
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total')
            ->toArray();

        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count')
            ->toArray();

        $monthlyClients = User::role('client')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count')
            ->toArray();
         


        return [

            Stat::make('Chiffre d\'affaires', number_format($revenue, 2) . ' TND')
            ->description('Total des ventes')
            ->descriptionIcon('heroicon-m-banknotes')
            ->chart($this->fillMissingMonths($monthlyRevenue))
            ->color('primary'),

            Stat::make('Nombre de clients', $clientCount)
                ->description('Clients enregistrés')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($this->fillMissingMonths($monthlyClients))
                ->color('success'),

                Stat::make('Nombre de commandes', $orderCount)
                ->description('Commandes passées')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart($this->fillMissingMonths($monthlyOrders))
                ->color('info'),



                Stat::make('Total des vues', VideoView::count())
                ->description('Toutes les visualisations enregistrées')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),
        ];
    }

    // Fonction utilitaire pour toujours avoir 6 points (même si pas de données certains mois)
    private function fillMissingMonths(array $data): array
    {
        return array_pad($data, 6, 0);
    }
}
