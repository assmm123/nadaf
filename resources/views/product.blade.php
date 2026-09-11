@extends('layouts.app')

@section('title', $product->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags($product->description ?? ''), 150))

@section('content')
    <div class="container-x mt-8" x-data="{ img: 0 }">

        {{-- مسار التنقل --}}
        <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs text-gray-400">
            <a href="{{ route('home') }}" class="transition hover:text-gold-600">{{ __('nav.home') }}</a>
            <x-shop-icon name="chevron" class="h-3 w-3 rtl:rotate-180" />
            <a href="{{ route('category.show', $product->category->slug) }}" class="transition hover:text-gold-600">{{ $product->category->name }}</a>
            <x-shop-icon name="chevron" class="h-3 w-3 rtl:rotate-180" />
            <span class="font-bold text-navy-950">{{ $product->name }}</span>
        </nav>

        <div class="grid flex-col gap-12 lg:grid-cols-[1.02fr_.98fr] lg:items-start">

            {{-- ============ معرض الوسائط ============ --}}
            <div class="reveal">
                <div class="relative aspect-square overflow-hidden rounded-[22px] border border-navy-950/10 bg-sand">
                    @foreach ($product->media as $i => $media)
                        <div class="absolute inset-0 transition-opacity duration-500" :class="img === {{ $i }} ? 'opacity-100' : 'pointer-events-none opacity-0'">
                            @if ($media->type === 'video')
                                <video src="{{ $media->url() }}" controls playsinline class="h-full w-full object-cover" preload="metadata"></video>
                            @else
                                <img src="{{ $media->url() }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-700 hover:scale-[1.03]">
                            @endif
                        </div>
                    @endforeach
                    @if ($product->media->isEmpty())
                        <div class="absolute inset-0 grid place-items-center bg-gradient-to-br from-sand to-[#E2D5BC]">
                            <x-shop-icon name="bag" class="h-20 w-20 text-navy-950/15" />
                        </div>
                    @endif
                    @if ($product->old_price_usd)
                        <span class="badge-sale !top-4 !start-4">
                            -{{ round((1 - $product->price_usd / $product->old_price_usd) * 100) }}%
                        </span>
                    @endif
                </div>

                @if ($product->media->count() > 1)
                    <div class="thumbs mt-3 flex gap-2.5 overflow-x-auto pb-1">
                        @foreach ($product->media as $i => $media)
                            <button @click="img = {{ $i }}"
                                    class="relative h-[74px] w-[74px] shrink-0 overflow-hidden rounded-[14px] border-2 bg-sand transition-all duration-300 hover:-translate-y-1"
                                    :class="img === {{ $i }} ? 'border-gold-500' : 'border-transparent'">
                                @if ($media->type === 'video')
                                    <span class="absolute inset-0 z-10 grid place-items-center bg-navy-950/45 text-ivory">
                                        <x-shop-icon name="play" class="h-5 w-5" />
                                    </span>
                                    @if ($media->url()) <video src="{{ $media->url() }}" class="h-full w-full object-cover" muted preload="metadata"></video> @endif
                                @else
                                    <img src="{{ $media->url() }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ============ معلومات المنتج ============ --}}
            <div class="reveal">
                <h1 class="text-[clamp(1.5rem,3vw,2.3rem)] leading-[1.3] font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">
                    {{ $product->name }}
                </h1>
                @if ($product->name_en)
                    <p class="serif-lux mt-2 text-[15px] tracking-[.18em] uppercase text-gray-400">{{ $product->name_en }}</p>
                @endif

                @php
                    $cur = session('currency', 'usd');
                    $syp = $product->priceSyp();
                @endphp

                @if (inquiry_mode())
                    {{-- وضع الإخفاء الكامل --}}
                    <div class="mt-5 rounded-2xl border-s-4 border-[#5F7A60] bg-[#5F7A60]/8 p-5">
                        <p class="text-sm leading-7 font-bold text-[#4a634b]">
                            السعر عند التواصل — اختر الوسيلة الأنسب وسنرد عليك بأسرع وقت
                        </p>
                        <div class="mt-4 grid grid-cols-2 gap-2.5">
                            @foreach ($contactMethods as $cm)
                                <a href="{{ $cm->link() }}" target="_blank" rel="noopener"
                                   class="group flex flex-col items-center gap-2 rounded-2xl border border-[#5F7A60]/20 bg-white py-4 transition hover:-translate-y-1 hover:border-[#5F7A60]/50 hover:shadow-lg">
                                    <span class="grid h-12 w-12 place-items-center rounded-full bg-navy-950 text-gold-400 transition group-hover:bg-gold-500 group-hover:text-navy-950">
                                        <x-shop-icon name="{{ \App\Models\CommunicationMethod::TYPES[$cm->type]['icon'] ?? 'globe' }}" class="h-6 w-6" />
                                    </span>
                                    <span class="text-xs font-extrabold text-navy-950">{{ $cm->label ?? $cm->typeLabel() }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- السعر --}}
                    @if (show_normal_price())
                        <div class="mt-5 flex flex-wrap items-baseline gap-3.5">
                            <span class="text-[clamp(1.7rem,3vw,2.1rem)] font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">
                                {{ $cur === 'syp' ? fmt_syp($syp) : fmt_usd($product->price_usd) }}
                            </span>
                            @if ($product->old_price_usd)
                                <del class="text-base text-gray-400">
                                    {{ $cur === 'syp' ? fmt_syp(syp_from_usd($product->old_price_usd)) : fmt_usd($product->old_price_usd) }}
                                </del>
                            @endif
                        </div>
                        <p class="mt-1 text-sm tracking-wide text-gray-400">
                            {{ $cur === 'syp' ? fmt_usd($product->price_usd) : fmt_syp($syp) }}
                        </p>
                    @endif

                    {{-- سعر الجملة --}}
                    @if ($product->wholesale_price_usd && show_wholesale_price())
                        <div class="{{ price_mode() === 'second_big'
                            ? 'mt-5 rounded-2xl border-s-4 border-gold-500 bg-gold-50 p-4'
                            : 'mt-4 inline-flex items-center gap-2.5 rounded-full bg-gold-100/70 px-4 py-2.5' }}">
                            <div class="flex items-center gap-2.5">
                                <x-shop-icon name="sparkle" class="{{ price_mode() === 'second_big' ? 'h-6 w-6' : 'h-4 w-4' }} text-gold-600" />
                                <div>
                                    <span class="{{ price_mode() === 'second_big' ? 'block text-sm font-bold text-gold-700' : 'text-sm font-bold text-gold-600' }}">
                                        {{ price_mode() === 'second_big' ? 'السعر للكميات' : __('product.wholesale') }}:
                                    </span>
                                    <span class="{{ price_mode() === 'second_big' ? 'text-[26px] font-extrabold text-gold-600' : 'font-extrabold text-gold-600' }}" style="font-family:Almarai,Tajawal,sans-serif">
                                        {{ $cur === 'syp' ? fmt_syp($product->priceSyp(true)) : fmt_usd($product->wholesale_price_usd) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- إضافة السلة / الشراء --}}
                <div class="mt-7">
                    @if (inquiry_mode())
                        <a href="{{ whatsapp_inquiry_link('مرحبًا 👋، أرغب بالشراء: '.$product->name) }}"
                           target="_blank" rel="noopener"
                           class="btn-gold !w-full !py-3.5 !text-base">
                            <x-shop-icon name="whatsapp" class="h-5 w-5" />
                            اطلب عبر واتساب
                        </a>
                    @else
                        <livewire:add-to-cart :product="$product" />
                    @endif
                </div>

                {{-- الاستفسار --}}
                @if ($product->allow_inquiry && setting('inquiry_enabled_global', true) && $contactMethods->isNotEmpty())
                    <div id="inquiry" x-data="{ open: false }" class="mt-5">
                        <button @click="open = true" class="btn-outline w-full !py-3">
                            <x-shop-icon name="chat" class="h-4 w-4" />
                            {{ __('product.inquiry') }}
                        </button>

                        <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-[60] flex items-end justify-center bg-navy-950/55 p-4 backdrop-blur-sm sm:items-center" @click.self="open = false">
                            <div class="card-lux w-full max-w-sm p-6" x-transition.scale>
                                <div class="mb-5 flex items-center justify-between">
                                    <h3 class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('product.inquiry_title') }}</h3>
                                    <button @click="open = false" class="rounded-full p-1.5 text-gray-400 transition hover:bg-sand hover:text-navy-950">
                                        <x-shop-icon name="x" class="h-5 w-5" />
                                    </button>
                                </div>
                                <p class="mb-5 text-sm leading-6 text-gray-500">{{ __('product.inquiry_desc') }}</p>
                                <div class="space-y-2.5">
                                    @foreach ($contactMethods as $method)
                                        <a href="{{ $method->link() }}" target="_blank" rel="noopener"
                                           class="group flex items-center gap-3.5 rounded-2xl border border-navy-950/10 bg-white p-3.5 text-sm font-bold transition hover:-translate-y-0.5 hover:border-gold-500/60 hover:shadow-md">
                                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-navy-950 text-gold-400 transition group-hover:bg-gold-500 group-hover:text-navy-950">
                                                <x-shop-icon name="{{ \App\Models\CommunicationMethod::TYPES[$method->type]['icon'] ?? 'globe' }}" class="h-4.5 w-4.5" />
                                            </span>
                                            <span class="text-navy-950">{{ $method->label ?? $method->typeLabel() }}</span>
                                            <span class="ms-auto text-xs text-gray-400" dir="ltr">{{ $method->value }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- شريط الثقة --}}
                <div class="mt-8 grid grid-cols-3 gap-2.5 border-t border-navy-950/10 pt-7">
                    @foreach ([
                        ['icon' => 'truck', 'text' => __('checkout.local')],
                        ['icon' => 'store', 'text' => __('checkout.pickup')],
                        ['icon' => 'shield', 'text' => 'جودة مضمونة'],
                    ] as $trust)
                        <div class="group flex items-center gap-2.5 text-xs font-bold text-navy-950">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-sand text-gold-600 transition-all duration-300 group-hover:-translate-y-1 group-hover:bg-navy-950 group-hover:text-gold-400">
                                <x-shop-icon name="{{ $trust['icon'] }}" class="h-4.5 w-4.5" />
                            </span>
                            {{ $trust['text'] }}
                        </div>
                    @endforeach
                </div>

                {{-- الوصف --}}
                @if ($product->description)
                    <div class="mt-8 border-t border-navy-950/10 pt-7">
                        <h2 class="mb-3.5 text-base font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('product.description') }}</h2>
                        <p class="desc-card whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- منتجات مشابهة --}}
        @if ($related->isNotEmpty())
            <section class="mt-24 reveal">
                <div class="sec-head">
                    <span class="kicker">{{ __('product.related') }}</span>
                    <h2 class="section-title centered !mb-0">قد يناسبك أيضًا</h2>
                </div>
                <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
                    @foreach ($related as $rel)
                        @include('partials.product-card', ['product' => $rel])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
