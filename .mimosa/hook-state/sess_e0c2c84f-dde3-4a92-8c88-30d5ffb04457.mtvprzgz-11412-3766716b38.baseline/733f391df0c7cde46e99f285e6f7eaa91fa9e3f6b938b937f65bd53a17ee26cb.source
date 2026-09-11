<x-filament-panels::page>
    <div wire:key="my-products-root">

        {{-- ===== البوابة: كرت لكل قسم ===== --}}
        @if (! $openCategory)
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" wire:key="gate">
                @forelse ($categories as $cat)
                    <button type="button" wire:click="openCategory({{ $cat['id'] }})"
                            class="group relative aspect-[4/3] overflow-hidden rounded-2xl text-start shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                            wire:key="pcat-{{ $cat['id'] }}">
                        @if ($cat['image'])
                            <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}"
                                 class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-110">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-navy-900 via-navy-800 to-navy-700"></div>
                            <x-heroicon-o-cube class="absolute left-1/2 top-1/2 h-14 w-14 -translate-x-1/2 -translate-y-1/2 text-gold-400/40" />
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/25 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-4">
                            <p class="text-lg font-extrabold text-white">{{ $cat['name'] }}</p>
                            <div class="mt-2 flex items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-white/20 px-2.5 py-1 font-bold text-white backdrop-blur">{{ $cat['count'] }} منتج</span>
                                @if ($cat['out'] > 0)
                                    <span class="rounded-full bg-red-600/90 px-2 py-1 font-bold text-white">{{ $cat['out'] }} نافد</span>
                                @endif
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center text-gray-400">لا أقسام بعد</div>
                @endforelse
            </div>

            <div class="mt-6 text-center">
                <a href="{{ \App\Filament\Resources\ProductResource::getUrl('index') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 transition hover:text-gold-600">
                    <x-heroicon-o-table-cells class="h-4 w-4" />
                    الجرد الكلي — جدول كل المنتجات ({{ $totalProducts }}) مع الفلاتر والبحث
                </a>
            </div>

        {{-- ===== منتجات القسم المعزولة ===== --}}
        @else
            <div class="mb-6 flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <button wire:click="closeCategory"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-500 transition hover:bg-navy-900 hover:text-gold-400">
                    <x-heroicon-o-arrow-uturn-right class="h-5 w-5 rtl:rotate-180" />
                </button>
                @if ($openCategory['image'])
                    <img src="{{ $openCategory['image'] }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                @endif
                <div class="flex-1">
                    <p class="text-lg font-extrabold leading-tight">{{ $openCategory['name'] }}</p>
                    <p class="text-[11px] text-gray-400">{{ $openProducts->count() }} منتج — {{ $openCategory['out'] }} نافد</p>
                </div>
                <a href="{{ \App\Filament\Resources\ProductResource::getUrl('create') }}"
                   class="rounded-xl bg-gold-500 px-4 py-2.5 text-xs font-extrabold text-navy-900 transition hover:bg-gold-400">＋ منتج جديد</a>
                <a href="{{ \App\Filament\Resources\ProductResource::getUrl('index', ['category' => $openCategory['id']]) }}"
                   class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-500 transition hover:border-gold-500 hover:text-gold-600">جدول هذا القسم</a>
            </div>

            {{-- كروت المنتجات --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($openProducts as $p)
                    @php $m = $p['model']; @endphp
                    <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-lg"
                         wire:key="pp-{{ $m->id }}">
                        <div class="relative aspect-square overflow-hidden bg-cream">
                            @if ($p['image'])
                                <img src="{{ $p['image'] }}" alt="{{ $m->name_ar }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                            @else
                                <div class="flex h-full items-center justify-center text-gray-200"><x-heroicon-o-cube class="h-12 w-12" /></div>
                            @endif
                            @if ($p['stock'] <= 0)
                                <span class="badge absolute top-2 start-2 bg-navy-900 text-white">نافد</span>
                            @elseif ($p['low'])
                                <span class="badge absolute top-2 start-2 bg-amber-500 text-white">منخفض</span>
                            @endif
                            @if ($m->old_price_usd)
                                <span class="badge absolute top-2 end-2 bg-red-500 text-white">خصم</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="line-clamp-1 font-extrabold">{{ $m->name_ar }}</p>
                            <p class="mt-0.5 line-clamp-1 text-[10px] text-gray-400" dir="ltr">{{ $p['skus'] ?: '—' }}</p>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-base font-extrabold text-gold-600">${{ number_format($m->price_usd, 2) }}</span>
                                @if ($m->old_price_usd)
                                    <del class="text-[11px] text-gray-300">${{ number_format($m->old_price_usd, 2) }}</del>
                                @endif
                                <span class="ms-auto text-[11px] {{ $p['stock'] <= 3 ? 'font-bold text-red-500' : 'text-gray-400' }}">{{ $p['stock'] }} قطعة</span>
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <a href="{{ \App\Filament\Resources\ProductResource::getUrl('edit', ['record' => $m]) }}"
                                   class="rounded-lg bg-gray-50 py-2 text-center text-xs font-bold text-navy-900 transition hover:bg-gold-500">✏️ تعديل</a>
                                <a href="{{ route('product.show', $m->slug) }}" target="_blank" rel="noopener"
                                   class="rounded-lg border border-gray-100 py-2 text-center text-xs font-bold text-gray-500 transition hover:border-gold-500 hover:text-gold-600">👁️ عرض</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center text-gray-400">
                        لا منتجات في هذا القسم — <a href="{{ \App\Filament\Resources\ProductResource::getUrl('create') }}" class="font-bold text-gold-600 hover:underline">أضف أول منتج ←</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                <button wire:click="closeCategory"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-bold text-gray-500 transition hover:border-gold-500 hover:text-gold-600">← رجوع للأقسام</button>
            </div>
        @endif
    </div>
</x-filament-panels::page>
