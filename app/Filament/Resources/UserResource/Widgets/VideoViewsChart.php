<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\VideoView;

class VideoViewsChart extends ChartWidget
{
    protected static ?string $heading = 'Évolution des vidéos regardées';

    protected function getData(): array
    {
        $monthlyViews = VideoView::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $data[] = $monthlyViews[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Vues de vidéos',
                    'data' => $data,
                    'borderColor' => '#16a34a', // vert
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

