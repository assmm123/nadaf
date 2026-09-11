<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest())
            ->heading('آخر الطلبات')
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('الكود')
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('user.name')->label('العميل'),
                Tables\Columns\TextColumn::make('items_count')->label('العناصر')->counts('items'),
                Tables\Columns\TextColumn::make('total_usd')->label('الإجمالي')->money('USD'),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Order::statusLabel($state))
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'preparing' => 'primary',
                        'shipped' => 'gray',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('التاريخ')->dateTime('m/d H:i'),
            ])
            ->recordUrl(fn (Order $record) => OrderResource::getUrl('view', ['record' => $record]));
    }
}
