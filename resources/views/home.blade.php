@extends('layouts.app')

@section('title', __('nav.home'))
@section('description', __('footer.tagline'))

@section('content')
    {{-- ============ الهيرو / السلايدر ============ --}}
    <section class="relative overflow-hidden bg-navy-950 text-ivory" x-data="{ slide: 0, total: {{ max(1, $slides->count()) }}, start() { if (this.total > 1) setInterval(() => this.slide = (this.slide + 1) % this.total, 6000) } }" x-init="start()">
        {{-- الزخارف الخلفية --}}
        <div class="pointer-events-none absolute inset-0 bg-damask-lines"></div>
        <div class="pointer-events-none absolute inset-0 bg-gold-glow"></div>

        @if ($slides->isNotEmpty())
            {{-- شرائح السلايدر كخلفية --}}
            <div class="absolute inset-0">
                @foreach ($slides as $i => $slide)
                    <div class="absolute inset-0 transition-opacity duration-1000"
                         :class="slide === {{ $i }} ? 'opacity-100' : 'opacity-0'">
                        @if ($slide->videoUrl())
                            <video src="{{ $slide->videoUrl() }}" autoplay muted loop playsinline class="h-full w-full object-cover"></video>
                        @elseif ($slide->imageUrl())
                            <img src="{{ $slide->imageUrl() }}" alt="{{ $slide->title ?? 'Slide' }}" class="h-full w-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/45 to-navy-950/25"></div>
                    </div>
                @endforeach
            </div>
        @endif

            {{-- محتوى الهيرو --}}
        <div class="relative z-10 mx-auto flex min-h-[78vh] max-w-5xl flex-col items-center justify-center px-6 py-24 text-center">
            <span class="kicker mb-7">{{ $slides->first()?->title ?: 'تشكيلة 2026 — كرافات حرير' }}</span>
            <h1 class="text-[clamp(2.4rem,6.5vw,4.8rem)] leading-[1.2] font-extrabold tracking-tight text-ivory" style="font-family:Almarai,Tajawal,sans-serif">
                أناقة تُحكى <em class="serif-lux-italic not-italic font-medium text-gold-400">&nbsp;ولا تُوصف</em>
            </h1>
            <p class="mt-7 max-w-xl text-[clamp(1rem,1.6vw,1.2rem)] leading-8 text-ivory/75">
                {{ __('footer.tagline') }} — حيث تلتقي الفخامة بالتفاصيل الدمشقية الأصيلة.
            </p>

            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="#featured" class="btn-gold !px-9 !py-3.5 !text-base">{{ __('home.shop_now') }}</a>
                <a href="{{ route('contact') }}" class="btn-ivory !px-9 !py-3.5 !text-base">{{ __('nav.contact') }}</a>
            </div>

            @if ($slides->count() > 1)
                <div class="mt-14 flex gap-2.5">
                    @foreach ($slides as $i => $slide)
                        <button @click="slide = {{ $i }}" aria-label="Slide {{ $i + 1 }}"
                                class="h-[7px] rounded-full transition-all duration-500"
                                :class="slide === {{ $i }} ? 'w-8 bg-gold-500' : 'w-[7px] bg-ivory/35 hover:bg-ivory/60'"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ============ شريط الماركي ============ --}}
    <div class="marquee" aria-hidden="true">
        <div class="marquee-track">
            @foreach (array_merge($items = [
                ['icon' => 'store', 'text' => __('checkout.pickup')],
                ['icon' => 'truck', 'text' => __('checkout.local')],
                ['icon' => 'shield', 'text' => 'جودة مضمونة'],
                ['icon' => 'star', 'text' => 'أسعار جملة للكميات'],
                ['icon' => 'banknote', 'text' => 'إثبات دفع موثّق'],
                ['icon' => 'sparkle', 'text' => 'تشكيلات حصرية'],
            ], $items) as $item)
                <span class="marquee-item">
                    <x-shop-icon name="{{ $item['icon'] }}" class="h-4.5 w-4.5" />
                    {{ $item['text'] }}
                    <span class="dot">◆</span>
                </span>
            @endforeach
        </div>
    </div>

    {{-- ============ الأقسام ============ --}}
    @if ($categories->isNotEmpty())
        <section class="container-x mt-20 reveal">
            <div class="sec-head">
                <span class="kicker">{{ __('home.categories_title') }}</span>
                <h2 class="section-title centered !mb-0">تشكيلات تناسب كل مناسبة</h2>
            </div>

            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}"
                       class="group relative block overflow-hidden rounded-3xl bg-sand shadow-[0_1px_3px_rgba(14,27,42,.05)]"
                       style="aspect-ratio:4/4.6">
                        @if ($category->imageUrl())
                            <img src="{{ $category->imageUrl() }}" alt="{{ $category->name }}" loading="lazy"
                                 class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.07]">
                        @else
                            <div class="absolute inset-0 grid place-items-center bg-gradient-to-br from-navy-800 to-navy-950">
                                <x-shop-icon name="bag" class="h-12 w-12 text-ivory/40 transition-transform duration-500 group-hover:scale-110" />
                            </div>
                        @endif

                        {{-- التظليل المتدرج --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/25 to-transparent transition-opacity duration-500"></div>

                        {{-- زر "تفضل" --}}
                        <span class="absolute top-4 z-10 grid h-10 w-10 translate-y-2 place-items-center rounded-full border border-ivory/40 bg-navy-950/35 text-ivory opacity-0 backdrop-blur transition-all duration-400 group-hover:translate-y-0 group-hover:opacity-100 end-4 group-hover:bg-gold-500 group-hover:text-navy-950 group-hover:border-gold-500">
                            <x-shop-icon name="arrow" class="h-4 w-4 rtl:-scale-x-100" />
                        </span>

                        {{-- الشريط الذهبي السفلي --}}
                        <span class="absolute inset-x-5 bottom-0 h-[2px] origin-start scale-x-0 bg-gold-500 transition-transform duration-500 group-hover:scale-x-100"></span>

                        <div class="absolute inset-x-0 bottom-0 p-5 text-ivory">
                            <h3 class="text-lg font-extrabold" style="font-family:Almarai,Tajawal,sans-serif">{{ $category->name }}</h3>
                            @if ($category->products_count ?? null)
                                <span class="mt-1 block text-xs tracking-wider text-ivory/60">{{ $category->products_count }} {{ __('account.items') }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ المنتجات المميزة ============ --}}
    @if ($featured->isNotEmpty())
        <section id="featured" class="container-x mt-24 reveal">
            <div class="mb-9 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <span class="kicker mb-3">— {{ __('home.featured') }} —</span>
                    <h2 class="section-title !mb-0">قطع اختارها ذوقك</h2>
                </div>
                <a href="{{ route('category.show', $categories->first()?->slug ?? '#') }}" class="text-sm font-bold text-gold-600 transition hover:text-gold-700 hover:underline">{{ __('home.view_all') }} ←</a>
            </div>
            <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($featured as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ أحدث المنتجات ============ --}}
    @if ($latest->isNotEmpty())
        <section class="container-x mt-24 reveal">
            <div class="sec-head">
                <span class="kicker">{{ __('home.latest') }}</span>
                <h2 class="section-title centered !mb-0">وصل حديثًا إلى معرضنا</h2>
            </div>
            <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($latest as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ شريط الميزات الثلاثي ============ --}}
    <section class="container-x mt-24 reveal">
        <div class="grid gap-5 rounded-3xl border border-navy-950/8 bg-white p-8 shadow-[0_1px_3px_rgba(14,27,42,.04),0_14px_40px_rgba(14,27,42,.06)] sm:grid-cols-3 sm:p-10">
            @foreach ([
                ['icon' => 'truck', 'title' => __('checkout.local'), 'desc' => __('checkout.local_note')],
                ['icon' => 'check', 'title' => __('product.in_stock'), 'desc' => __('footer.tagline')],
                ['icon' => 'store', 'title' => __('checkout.pickup'), 'desc' => __('checkout.pickup_note')],
            ] as $feat)
                <div class="group flex items-center gap-4">
                    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-sand text-gold-600 transition-all duration-400 group-hover:-translate-y-1 group-hover:bg-navy-950 group-hover:text-gold-400">
                        <x-shop-icon name="{{ $feat['icon'] }}" class="h-6 w-6" />
                    </span>
                    <div>
                        <p class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $feat['title'] }}</p>
                        <p class="mt-1 text-xs leading-6 text-gray-500">{{ $feat['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- مسافة سفلية قبل الفوتر --}}
    <div class="h-4"></div>
@endsection
