<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

/** «طلباتي» — بوابة كروت حالات (بنتو منتظم) + عرض معزول بنفس الصفحة */
class MyOrders extends Component
{
    public ?string $openSection = null;

    public function openSection(string $key): void
    {
        $this->openSection = $key;
    }

    public function closeSection(): void
    {
        $this->openSection = null;
    }

    public function getSectionsProperty(): array
    {
        return [
            ['key' => 'pending', 'title' => 'طلبات جديدة', 'desc' => 'بانتظار المراجعة وإثبات الدفع', 'icon' => 'bag', 'color' => 'from-amber-600 to-amber-400'],
            ['key' => 'accepted', 'title' => 'مقبولة', 'desc' => 'مؤكدة بختم قبض الدفع الأخضر', 'icon' => 'check', 'color' => 'from-green-700 to-emerald-400'],
            ['key' => 'shipping', 'title' => 'قيد التسليم', 'desc' => 'مع المندوب في الطريق إليك', 'icon' => 'truck', 'color' => 'from-sky-700 to-sky-400'],
            ['key' => 'done', 'title' => 'مكتملة', 'desc' => 'سُلمت واعتمدت بالختم الذهبي', 'icon' => 'store', 'color' => 'from-gold-700 to-gold-400'],
            ['key' => 'cancelled', 'title' => 'ملغية', 'desc' => 'طلبات غير مكتملة أو ملغاة', 'icon' => 'x', 'color' => 'from-red-800 to-red-500'],
        ];
    }

    public function getCountsProperty(): array
    {
        $base = auth()->user()->orders();

        return [
            'pending' => (clone $base)->whereIn('status', ['pending'])->count(),
            'accepted' => (clone $base)->whereIn('status', ['confirmed', 'preparing'])->count(),
            'shipping' => (clone $base)->where('status', 'shipped')->count(),
            'done' => (clone $base)->where('status', 'delivered')->count(),
            'cancelled' => (clone $base)->where('status', 'cancelled')->count(),
        ];
    }

    public function getSectionOrdersProperty(): array
    {
        if (! $this->openSection) {
            return [];
        }

        $base = auth()->user()->orders()->withCount('items')->latest();

        $orders = match ($this->openSection) {
            'pending' => (clone $base)->whereIn('status', ['pending'])->get(),
            'accepted' => (clone $base)->whereIn('status', ['confirmed', 'preparing'])->get(),
            'shipping' => (clone $base)->where('status', 'shipped')->get(),
            'done' => (clone $base)->where('status', 'delivered')->get(),
            'cancelled' => (clone $base)->where('status', 'cancelled')->get(),
            default => collect(),
        };

        return $orders->map(fn (Order $o) => [
            'model' => $o,
            'code' => $o->order_code,
            'date' => $o->created_at->format('Y/m/d H:i'),
            'items' => $o->items_count,
            'payment' => $o->paymentMethod?->name ?? '—',
            'paid' => (bool) $o->payment_confirmed_at,
            'stamped' => (bool) $o->stamped_at,
            'total' => fmt_usd($o->total_usd),
            'totalSyp' => fmt_syp($o->total_syp),
            'url' => route('account.order', $o->order_code),
        ])->all();
    }

    public function render()
    {
        // الخصائص المحسوبة لا تصل Blade تلقائياً — تمرير صريح (فخ معروف)
        return view('livewire.my-orders', [
            'sections' => $this->sections,
            'counts' => $this->counts,
            'sectionOrders' => $this->sectionOrders,
        ])->layout('layouts.app');
    }
}
