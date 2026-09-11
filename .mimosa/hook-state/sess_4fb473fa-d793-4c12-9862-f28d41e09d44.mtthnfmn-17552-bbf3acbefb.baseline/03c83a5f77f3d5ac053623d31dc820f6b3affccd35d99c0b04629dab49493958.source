<?php

namespace App\Filament\Pages;

use App\Services\ReportService;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 11;

    protected static string $view = 'filament.pages.reports';

    public string $period = 'this_month';
    public ?string $from = null;
    public ?string $to = null;

    public array $summary = [];
    public array $byCategory = [];
    public array $byProduct = [];
    public array $daily = [];
    public string $rangeLabel = '';

    public function mount(): void
    {
        $this->loadReport();
    }

    public function updatedPeriod(): void
    {
        $this->loadReport();
    }

    public function updatedFrom(): void
    {
        if ($this->period === 'custom') {
            $this->loadReport();
        }
    }

    public function updatedTo(): void
    {
        if ($this->period === 'custom') {
            $this->loadReport();
        }
    }

    public function loadReport(): void
    {
        [$from, $to, $label] = ReportService::resolveRange($this->period, $this->from, $this->to);

        $this->summary = ReportService::summary($from, $to);
        $this->byCategory = ReportService::byCategory($from, $to);
        $this->byProduct = ReportService::byProduct($from, $to);
        $this->daily = ReportService::daily($from, $to);
        $this->rangeLabel = $label.' ('.$from->format('Y/m/d').' — '.$to->format('Y/m/d').')';
    }

    public static function getNavigationLabel(): string
    {
        return 'السجلات والأرباح';
    }

    public function getTitle(): string
    {
        return 'سجل المبيعات والأرباح';
    }
}
