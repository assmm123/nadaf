<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $sales = Order::where('status', '!=', 'cancelled')->sum('total_usd');
        $pending = Order::where('status', 'pending')->count();

        return [
            Stat::make('إجمالي المبيعات', '$'.number_format($sales, 2))
                ->description('بدون الطلبات الملغاة')
                ->color('success')
                ->icon('heroicon-m-banknotes'),
            Stat::make('طلبات جديدة', (string) $pending)
                ->description('بانتظار المراجعة')
                ->color($pending > 0 ? 'warning' : 'gray')
                ->icon('heroicon-m-clock'),
            Stat::make('المنتجات', (string) Product::count())
                ->description('منشورة: '.Product::active()->count())
                ->color('primary')
                ->icon('heroicon-m-cube'),
            Stat::make('العملاء', (string) User::where('role', 'customer')->count())
                ->description('حسابات مسجلة')
                ->color('info')
                ->icon('heroicon-m-users'),
        ];
    }
}
