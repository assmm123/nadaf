<x-filament-panels::page>
    <div wire:key="my-cats-root">

        {{-- ===== البوابة: كروت متساوية ===== --}}
        @if (! $openCategory)
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" wire:key="gate">
                @forelse ($categories as $cat)
                    <button type="button" wire:click="openCategory({{ $cat['id'] }})"
                            class="group relative aspect-[4/3] overflow-hidden rounded-2xl text-start shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-gold-500"
                            wire:key="cat-{{ $cat['id'] }}">
                        {{-- الخلفية --}}
                        @if ($cat['image'])
                            <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}"
                                 class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-110">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-navy-900 via-navy-800 to-navy-700"></div>
                            <x-heroicon-o-rectangle-stack class="absolute left-1/2 top-1/2 h-14 w-14 -translate-x-1/2 -translate-y-1/2 text-gold-400/40 transition group-hover:scale-110" />
                        @endif
                        {{-- طبقة التدرج --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/25 to-transparent"></div>

                        {{-- محتوى الكرت --}}
                        <div class="absolute inset-x-0 bottom-0 p-4">
                            <p class="text-lg font-extrabold text-white">{{ $cat['name'] }}</p>
                            <p class="text-[11px] tracking-wide text-gold-400">{{ $cat['nameEn'] }}</p>
                            <div class="mt-2 flex items-center gap-2 text-[11px]">
                                <span class="rounded-full bg-white/20 px-2.5 py-1 font-bold text-white backdrop-blur">{{ $cat['count'] }} منتج</span>
                                @if ($cat['low'] > 0)
                                    <span class="rounded-full bg-amber-500/90 px-2 py-1 font-bold text-white">{{ $cat['low'] }} منخفض</span>
                                @endif
                                @if ($cat['out'] > 0)
                                    <span class="rounded-full bg-red-600/90 px-2 py-1 font-bold text-white">{{ $cat['out'] }} نافد</span>
                                @endif
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center text-gray-400">
                        لا أقسام بعد — أضف أول قسم من قائمة «الأقسام»
                    </div>
                @endforelse
            </div>

            {{-- رابط الجرد الكلي الاحتياطي --}}
            <div class="mt-6 text-center">
                <a href="{{ \App\Filament\Resources\ProductResource::getUrl('index') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 transition hover:text-gold-600">
                    <x-heroicon-o-table-cells class="h-4 w-4" />
                    الجرد الكلي — جدول كل المنتجات مع الفلاتر
                </a>
            </div>

        {{-- ===== العرض المعزول لقسم واحد ===== --}}
        @else
            {{-- شريط الكرت: اسم + أدواته --}}
            <div class="mb-6 flex flex-wrap items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <button wire:click="closeCategory"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-500 transition hover:bg-navy-900 hover:text-gold-400"
                        title="رجوع للكروت">
                    <x-heroicon-o-arrow-uturn-right class="h-5 w-5 rtl:rotate-180" />
                </button>
                @if ($openCategory['image'])
                    <img src="{{ $openCategory['image'] }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                @else
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-navy-900 text-gold-400">
                        <x-heroicon-o-rectangle-stack class="h-6 w-6" />
                    </span>
                @endif
                <div class="flex-1">
                    <p class="text-lg font-extrabold leading-tight">{{ $openCategory['name'] }}</p>
                    <p class="text-[11px] text-gray-400">{{ $openCategory['active'] }} منشور — قيمة المخزون ${{ number_format($openCategory['stockValue'], 2) }} — مبيعات 30 يوم: {{ $openCategory['sold30'] }}</p>
                </div>
                <a href="{{ \App\Filament\Resources\CategoryResource::getUrl('index') }}"
                   class="rounded-xl bg-navy-900 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-navy-800">⚙️ إعدادات القسم</a>
                <a href="{{ route('category.show', $openCategory['slug']) }}" target="_blank" rel="noopener"
                   class="rounded-xl border border-gray-200 px-4 py-2.5 text-xs font-bold text-gray-600 transition hover:border-gold-500 hover:text-gold-600">👁️ عرض بالمتجر</a>
            </div>

            {{-- منتجات القسم — كروت --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($openProducts as $p)
                    @php $m = $p['model']; @endphp
                    <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-lg"
                         wire:key="p-{{ $m->id }}">
                        <div class="relative aspect-video overflow-hidden bg-cream">
                            @if ($p['image'])
                                <img src="{{ $p['image'] }}" alt="{{ $m->name_ar }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                            @else
                                <div class="flex h-full items-center justify-center text-gray-200"><x-heroicon-o-cube class="h-10 w-10" /></div>
                            @endif
                            @if (! $m->is_active)
                                <span class="badge absolute top-2 start-2 bg-red-500 text-white">غير منشور</span>
                            @elseif ($p['stock'] <= 0)
                                <span class="badge absolute top-2 start-2 bg-navy-900 text-white">نافد</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="line-clamp-1 font-extrabold">{{ $m->name_ar }}</p>
                            <p class="mt-0.5 text-[11px] text-gray-400" dir="ltr">{{ $p['skus'] ?: '—' }}</p>
                            <div class="mt-2 flex items-center justify-between text-xs">
                                <span class="font-extrabold text-gold-600">${{ number_format($m->price_usd, 2) }}</span>
                                <span class="{{ $p['stock'] <= 3 ? 'font-bold text-red-500' : 'text-gray-400' }}">مخزون: {{ $p['stock'] }}</span>
                            </div>
                            <a href="{{ \App\Filament\Resources\ProductResource::getUrl('edit', ['record' => $m]) }}"
                               class="mt-3 block rounded-lg bg-gray-50 py-2 text-center text-xs font-bold text-navy-900 transition hover:bg-gold-500">
                                ✏️ تعديل المنتج
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center text-gray-400">
                        لا منتجات في هذا القسم
                        <a href="{{ \App\Filament\Resources\ProductResource::getUrl('create') }}" class="ms-2 font-bold text-gold-600 hover:underline">إضافة منتج لهذا القسم ←</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ \App\Filament\Resources\ProductResource::getUrl('create') }}"
                   class="rounded-xl bg-gold-500 px-5 py-2.5 text-sm font-extrabold text-navy-900 transition hover:bg-gold-400">＋ منتج جديد في هذا القسم</a>
                <button wire:click="closeCategory"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-bold text-gray-500 transition hover:border-gold-500 hover:text-gold-600">← رجوع للكروت</button>
            </div>
        @endif
    </div>
</x-filament-panels::page>
