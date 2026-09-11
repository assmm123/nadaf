<?php

namespace App\Filament\Pages;

use App\Models\ProductVariant;
use App\Services\StockService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class StockCount extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 15;

    protected static ?string $navigationGroup = 'المخزون والجرد';

    protected static string $view = 'filament.pages.stock-count';

    public array $counts = [];

    public string $search = '';

    public function mount(): void
    {
        $this->loadCounts();
    }

    public function updatedSearch(): void
    {
        $this->loadCounts();
    }

    protected function loadCounts(): void
    {
        $query = ProductVariant::with('product');

        if ($this->search !== '') {
            $query->whereHas('product', function ($q) {
                $q->where('name_ar', 'like', "%{$this->search}%")
                    ->orWhere('name_en', 'like', "%{$this->search}%");
            });
        }

        $this->counts = $query->orderBy('id')
            ->limit(200)
            ->get()
            ->map(fn ($v) => [
                'id' => $v->id,
                'name' => $v->product->name_ar.($v->label() ? " ({$v->label()})" : ''),
                'book' => (int) $v->quantity,
                'actual' => (string) $v->quantity,
            ])
            ->toArray();
    }

    /** الفرق = العدّ الفعلي - الدفتري */
    public function diff(array $row): int
    {
        return (int) $row['actual'] - $row['book'];
    }

    public function apply(): void
    {
        $applied = 0;
        foreach ($this->counts as $row) {
            $actual = (int) $row['actual'];
            if ($actual < 0 || $actual === $row['book']) {
                continue;
            }

            $delta = $actual - $row['book'];
            StockService::record(
                $row['id'],
                'adjust',
                $delta,
                null,
                null,
                'تسوية جرد فعلي ('.$row['book'].' → '.$actual.')',
            );
            $applied++;
        }

        $this->loadCounts();

        Notification::make()
            ->title($applied > 0 ? "تم تسجيل تسوية الجرد لـ {$applied} صنفًا" : 'لا توجد فروقات للتسوية')
            ->{$applied > 0 ? 'success' : 'info'}()
            ->send();
    }

    public static function getNavigationLabel(): string
    {
        return 'الجرد الفعلي';
    }

    public function getTitle(): string
    {
        return 'الجرد الفعلي — عدّ المخزون وتسوية الفروقات';
    }
}
