<?php

namespace App\Filament\Pages;

use App\Models\Category;
use Filament\Pages\Page;

/**
 * «منتجاتي» — بوابة كروت متساوية بالأقسام، والنقر يعرض منتجات القسم وحده ككروت.
 * الجرد الكلي (جدول المنتجات الكامل المفلتر) يبقى رابطاً احتياطياً أسفل البوابة.
 */
class MyProducts extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.my-products';

    public ?int $openCategoryId = null;

    public static function getNavigationLabel(): string
    {
        return 'منتجاتي';
    }

    public function getTitle(): string
    {
        return $this->openCategoryId ? 'منتجاتي — عرض معزول' : 'منتجاتي';
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
            ->map(fn (Category $cat) => [
                'id' => $cat->id,
                'name' => $cat->name_ar,
                'image' => $cat->imageUrl(),
                'count' => $cat->products->count(),
                'out' => $cat->products->filter(fn ($p) => $p->variants->sum('quantity') <= 0)->count(),
                'slug' => $cat->slug,
            ])->values();

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
                    'skus' => $p->variants->pluck('sku')->implode(' · '),
                    'low' => $p->variants->contains(fn ($v) => $v->quantity > 0 && $v->quantity <= $v->low_stock_threshold),
                ]);
            }
        }

        return [
            'categories' => $categories,
            'openCategory' => $openCategory,
            'openProducts' => $openProducts,
            'totalProducts' => \App\Models\Product::count(),
        ];
    }
}
