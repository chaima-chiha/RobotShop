<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
    use App\Models\Order;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;

    class Stats extends ChartWidget
    {
        protected static ?string $heading = 'Chiffre d\'affaires mensuel';

        protected function getData(): array
        {
            // On récupère les ventes par mois sur l'année en cours
            $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            // Préparer les données pour chaque mois de Janvier à Décembre
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $data = [];

            for ($i = 1; $i <= 12; $i++) {
                $data[] = $monthlyRevenue[$i] ?? 0;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Chiffre d\'affaires (TND)',
                        'data' => $data,
                        'borderColor' => '#16a34a', // success = green
                         'backgroundColor' => 'rgba(22, 163, 74, 0.2)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
                'labels' => $labels,
            ];
        }

        protected function getType(): string
        {
            return 'line';
        }
    }


