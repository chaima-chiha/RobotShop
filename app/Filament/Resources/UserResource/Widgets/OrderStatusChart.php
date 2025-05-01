<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;


class OrderStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Répartition des statuts de commande';

    protected function getData(): array
    {
        $statuses = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'labels' => array_keys($statuses),
            'datasets' => [
                [
                    'data' => array_values($statuses),
                    'backgroundColor' => ['#22c55e', '#f97316', '#eab308', '#ef4444', '#3b82f6'], // couleurs personnalisées
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'pie';
    }
}
