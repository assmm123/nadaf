<?php

namespace App\Filament\Pages;

use App\Models\Category;
use Filament\Pages\Page;

/**
 * «أقسامي» — بوابة كروت متساوية (بنتو منتظم)، والنقر يبدّل بنفس الصفحة
 * إلى العرض المعزول للقسم: منتجاته + شريط أدواته (إعدادات/عرض/رجوع).
 */
class MyCategories extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.my-categories';

    public ?int $openCategoryId = null;

    public static function getNavigationLabel(): string
    {
        return 'أقسامي';
    }

    public function getTitle(): string
    {
        return $this->openCategoryId ? 'أقسامي — عرض معزول' : 'أقسامي';
    }

    public function openCategory(int $id): void
    {
        $this->openCategoryId = $id;
    }

    public function closeCategory(): void
    {
        $this->openCategoryId = null;
    }

    protected function getViewData(): array
    {
        $categories = Category::with(['products.variants'])->withCount('products')->orderBy('sort_order')->get()
            ->map(function (Category $cat) {
                $products = $cat->products;
                $low = 0;
                $out = 0;
                $stockValue = 0.0;
                $sold30 = 0;

                foreach ($products as $p) {
                    foreach ($p->variants as $v) {
                        if ($v->quantity <= 0) {
                            $out++;
                        } elseif ($v->quantity <= $v->low_stock_threshold) {
                            $low++;
                        }
                        $stockValue += $v->quantity * (float) $p->cost_usd;
                    }
                    $sold30 += (int) \App\Models\OrderItem::whereIn('variant_id', $p->variants->pluck('id'))
                        ->where('created_at', '>=', now()->subDays(30))
                        ->sum('quantity');
                }

                return [
                    'id' => $cat->id,
                    'name' => $cat->name_ar,
                    'nameEn' => $cat->name_en,
                    'image' => $cat->imageUrl(),
                    'count' => $products->count(),
                    'active' => $products->where('is_active', true)->count(),
                    'low' => $low,
                    'out' => $out,
                    'stockValue' => $stockValue,
                    'sold30' => $sold30,
                    'slug' => $cat->slug,
                ];
            });

        $openCategory = null;
        $openProducts = collect();

        if ($this->openCategoryId) {
            $cat = Category::with(['products.variants'])->find($this->openCategoryId);
            if ($cat) {
                $openCategory = $categories->firstWhere('id', $cat->id);
                $openProducts = $cat->products->map(fn ($p) => [
                    'model' => $p,
                    'image' => $p->imageUrl(),
                    'stock' => $p->variants->sum('quantity'),
                    'skus' => $p->variants->pluck('sku')->take(3)->implode(', '),
                ]);
            }
        }

        return [
            'categories' => $categories,
            'openCategory' => $openCategory,
            'openProducts' => $openProducts,
        ];
    }
}
