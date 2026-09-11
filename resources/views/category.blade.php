@extends('layouts.app')

@section('title', $category->name)
@section('description', $category->name . ' — ' . __('footer.tagline'))

@section('content')
    <div class="container-x mt-8">

        {{-- مسار التنقل --}}
        <nav class="mb-5 flex items-center gap-2 text-xs text-gray-400">
            <a href="{{ route('home') }}" class="transition hover:text-gold-600">{{ __('nav.home') }}</a>
            <x-shop-icon name="chevron" class="h-3 w-3 rtl:rotate-180" />
            <span class="font-bold text-navy-950">{{ $category->name }}</span>
        </nav>

        {{-- رأس القسم --}}
        <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="kicker mb-3">— {{ __('home.categories_title') }} —</span>
                <h1 class="text-3xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $category->name }}</h1>
            </div>
            @if ($products->total() ?? null)
                <p class="text-sm text-gray-400">{{ $products->total() }} {{ __('account.items') }}</p>
            @endif
        </div>

        <div class="flex flex-col gap-7 lg:flex-row">

            {{-- ============ الفلاتر الجانبية ============ --}}
            <aside class="lg:w-64 lg:shrink-0">
                <form method="GET" action="{{ route('category.show', $category->slug) }}"
                      class="card-lux sticky top-36 space-y-6 p-6">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">

                    <h3 class="text-base font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">
                        تنقية النتائج
                    </h3>
                    <div class="gold-rule"></div>

                    @if ($colorOptions->isNotEmpty())
                        <div>
                            <p class="label">{{ __('product.color') }}</p>
                            <select name="color" class="input">
                                <option value="">—</option>
                                @foreach ($colorOptions as $opt)
                                    <option value="{{ $opt }}" @selected(request('color') === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($sizeOptions->isNotEmpty())
                        <div>
                            <p class="label">{{ __('product.size') }}</p>
                            <select name="size" class="input">
                                <option value="">—</option>
                                @foreach ($sizeOptions as $opt)
                                    <option value="{{ $opt }}" @selected(request('size') === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <p class="label">{{ __('cart.price') }} ($)</p>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" min="0" step="0.5" placeholder="0" value="{{ request('min_price') }}" class="input">
                            <span class="text-gold-500">—</span>
                            <input type="number" name="max_price" min="0" step="0.5" placeholder="99" value="{{ request('max_price') }}" class="input">
                        </div>
                    </div>

                    <div class="flex gap-2.5">
                        <button class="btn-gold flex-1 !py-2.5">{{ __('cart.apply') }}</button>
                        <a href="{{ route('category.show', $category->slug) }}"
                           class="btn-outline !px-4" title="مسح الفلاتر">✕</a>
                    </div>
                </form>
            </aside>

            {{-- ============ شبكة المنتجات ============ --}}
            <div class="flex-1">
                <form method="GET" action="{{ route('category.show', $category->slug) }}"
                      class="mb-6 flex items-center gap-3 text-sm">
                    @foreach (['color', 'size', 'min_price', 'max_price'] as $k)
                        @if (request($k) !== null) <input type="hidden" name="{{ $k }}" value="{{ request($k) }}"> @endif
                    @endforeach
                    <label class="whitespace-nowrap font-bold text-navy-950">{{ __('cart.total') }}:</label>
                    <select name="sort" onchange="this.form.submit()" class="input !w-auto !py-2">
                        <option value="latest" @selected(request('sort', 'latest') === 'latest')>{{ __('home.latest') }}</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>{{ __('cart.price') }} ↑</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>{{ __('cart.price') }} ↓</option>
                        <option value="bestselling" @selected(request('sort') === 'bestselling')>⭐ {{ __('home.featured') }}</option>
                    </select>
                </form>

                @if ($products->isNotEmpty())
                    <div class="grid grid-cols-2 gap-5 md:grid-cols-3">
                        @foreach ($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="mt-10">{{ $products->links() }}</div>
                @else
                    <div class="card-lux flex flex-col items-center gap-4 p-16 text-center">
                        <span class="grid h-16 w-16 place-items-center rounded-full bg-sand text-gold-600">
                            <x-shop-icon name="search" class="h-7 w-7" />
                        </span>
                        <p class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('search.no_results') }}</p>
                        <a href="{{ route('category.show', $category->slug) }}" class="btn-outline">{{ __('cart.continue_shopping') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
