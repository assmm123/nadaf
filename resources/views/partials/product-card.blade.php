{{-- كرت منتج فاخر — Imperial Damask --}}
@props(['product'])

@php
    $cur = session('currency', 'usd');
    $syp = $product->priceSyp();
    $img = $product->imageUrl();
    $imgs = $product->media->filter(fn ($m) => $m->type === 'image')->take(2);
    $img2 = $imgs->count() > 1 ? $imgs[1]->url() : null;
    $discount = $product->old_price_usd ? round((1 - $product->price_usd / $product->old_price_usd) * 100) : 0;
    $stock = $product->inStock();
@endphp

<article class="group relative flex flex-col overflow-hidden rounded-[18px] border border-navy-950/9 bg-white p-2.5 transition-all duration-400 hover:-translate-y-[7px] hover:border-gold-500/40 hover:shadow-[0_2px_6px_rgba(14,27,42,.05),0_22px_44px_rgba(14,27,42,.10)]">

    {{-- الصورة + التأثيرات --}}
    <a href="{{ route('product.show', $product->slug) }}" class="relative block overflow-hidden rounded-[13px] bg-sand" style="aspect-ratio:1/1">
        @if ($img)
            <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy"
                 class="absolute inset-0 h-full w-full object-cover transition-all duration-700 {{ $img2 ? 'group-hover:opacity-0 group-hover:scale-[1.04]' : 'group-hover:scale-[1.05]' }}">
            @if ($img2)
                <img src="{{ $img2 }}" alt="" loading="lazy"
                     class="absolute inset-0 h-full w-full scale-[1.04] object-cover opacity-0 transition-all duration-700 group-hover:opacity-100">
            @endif
        @else
            <div class="absolute inset-0 grid place-items-center bg-gradient-to-br from-sand to-[#E2D5BC]">
                <x-shop-icon name="bag" class="h-12 w-12 text-navy-950/20 transition-transform duration-500 group-hover:scale-110" />
            </div>
        @endif

        {{-- شارة الخصم --}}
        @if ($discount > 0)
            <span class="badge-sale">-{{ $discount }}%</span>
        @endif

        {{-- شارة نفاد المخزون --}}
        @if (! $stock)
            <div class="absolute inset-0 flex items-center justify-center bg-ivory/75 backdrop-blur-[2px]">
                <span class="badge bg-navy-950 !px-4 !py-1.5 text-ivory">{{ __('product.out_of_stock') }}</span>
            </div>
        @endif

        {{-- أزرار سريعة عند التحويم --}}
        @if ($stock && ! inquiry_mode())
            <div class="absolute inset-x-2.5 bottom-2.5 flex translate-y-[10px] gap-2 opacity-0 transition-all duration-350 group-hover:translate-y-0 group-hover:opacity-100">
                <span class="flex flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-full bg-navy-950 px-3 py-2.5 text-xs font-extrabold text-ivory shadow-lg transition hover:bg-gold-500 hover:text-navy-950" style="font-family:Almarai,Tajawal,sans-serif"
                      onclick="event.preventDefault(); $dispatch('open-checkout', { product: '{{ $product->slug }}' })">
                    <x-shop-icon name="bag" class="h-3.5 w-3.5" />
                    {{ __('product.add_to_cart') }}
                </span>
                @if ($product->allow_inquiry && setting('inquiry_enabled_global', true))
                    <a href="{{ route('product.show', $product->slug) }}#inquiry" class="grid h-auto w-11 shrink-0 place-items-center rounded-full bg-ivory/95 px-0 text-navy-950 shadow-lg transition hover:bg-white hover:text-gold-600">
                        <x-shop-icon name="chat" class="h-4 w-4" />
                    </a>
                @endif
            </div>
        @endif
    </a>

    {{-- الجسم --}}
    <div class="flex flex-1 flex-col px-1.5 pb-1.5 pt-3.5">
        <a href="{{ route('product.show', $product->slug) }}" class="line-clamp-1 text-[15px] font-extrabold text-[#1B242E] transition group-hover:text-gold-700">
            {{ $product->name }}
        </a>

        @if (inquiry_mode())
            <a href="{{ whatsapp_inquiry_link('مرحبًا 👋، أستفسر عن: '.$product->name) }}"
               target="_blank" rel="noopener"
               class="mt-2.5 inline-flex items-center gap-1.5 self-start rounded-full border border-[#5F7A60]/30 bg-[#5F7A60]/8 px-3.5 py-1.5 text-xs font-extrabold text-[#4a634b] transition hover:bg-[#5F7A60] hover:text-white">
                <x-shop-icon name="whatsapp" class="h-4 w-4" />
                استفسار عبر واتساب
            </a>
        @else
            {{-- حالة المخزون --}}
            <div class="mt-1.5 flex items-center gap-2 text-[11.5px] tracking-wide text-gray-400">
                @if ($stock)
                    <span class="flex items-center gap-1.5 font-bold text-[#5F7A60]">
                        <span class="h-[7px] w-[7px] rounded-full bg-[#5F7A60] shadow-[0_0_0_3px_rgba(95,122,96,.15)]"></span>
                        {{ __('product.in_stock') }}
                    </span>
                @else
                    <span class="flex items-center gap-1.5 font-bold text-[#8E2C33]">
                        <span class="h-[7px] w-[7px] rounded-full bg-[#8E2C33] shadow-[0_0_0_3px_rgba(142,44,51,.15)]"></span>
                        {{ __('product.out_of_stock') }}
                    </span>
                @endif
            </div>

            {{-- السعر --}}
            <div class="mt-2.5 flex flex-wrap items-baseline gap-x-2.5 gap-y-1">
                <span class="text-[19px] font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">
                    {{ $cur === 'syp' ? fmt_syp($syp) : fmt_usd($product->price_usd) }}
                </span>
                @if ($product->old_price_usd)
                    <del class="text-xs text-gray-400">
                        {{ $cur === 'syp' ? fmt_syp(syp_from_usd($product->old_price_usd)) : fmt_usd($product->old_price_usd) }}
                    </del>
                @endif
            </div>
            <div class="mt-0.5 text-[11px] tracking-wide text-gray-400">
                {{ $cur === 'syp' ? fmt_usd($product->price_usd) : fmt_syp($syp) }}
            </div>

            {{-- سعر الجملة --}}
            @if ($product->wholesale_price_usd && show_wholesale_price() && ! inquiry_mode())
                <div class="mt-2.5 inline-flex items-center gap-1.5 self-start rounded-full bg-gold-100/70 px-3 py-1 text-[11px] font-extrabold text-gold-700">
                    <x-shop-icon name="sparkle" class="h-3 w-3" />
                    {{ __('product.wholesale') }}:
                    {{ $cur === 'syp' ? fmt_syp($product->priceSyp(true)) : fmt_usd($product->wholesale_price_usd) }}
                </div>
            @endif
        @endif
    </div>
</article>
