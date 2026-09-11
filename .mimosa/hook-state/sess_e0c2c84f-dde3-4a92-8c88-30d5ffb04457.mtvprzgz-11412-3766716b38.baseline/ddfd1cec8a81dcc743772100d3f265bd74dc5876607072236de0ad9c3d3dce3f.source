<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            OrderResource::changeStatusAction(false),
            OrderResource::stampAction(false),
            Action::make('invoice')
                ->label('طباعة الفاتورة')
                ->icon('heroicon-m-printer')
                ->color('gray')
                ->url(fn () => route('admin.invoice', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
