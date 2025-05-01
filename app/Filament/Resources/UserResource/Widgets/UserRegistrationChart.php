<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Carbon\Carbon;

class UserRegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'Évolution des inscriptions utilisateurs';

    protected function getData(): array
    {
        // Inscriptions par mois (année en cours)
        $monthlyRegistrations = User::role('client')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $monthlyRegistrations[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Inscriptions clients',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.3,
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
