<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'الطلبات — آخر 30 يومًا';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $counts = Order::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('day')
            ->pluck('total', 'day');

        $days = collect();
        $keys = collect();
        for ($i = 29; $i >= 0; $i--) {
            $days->push(now()->subDays($i)->format('m/d'));
            $keys->push(now()->subDays($i)->format('Y-m-d'));
        }
        $data = $keys->map(fn ($day) => $counts[$day] ?? 0)->values();

        return [
            'datasets' => [
                [
                    'label' => 'الطلبات',
                    'data' => $data->all(),
                    'backgroundColor' => '#C9A84C',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $days->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
