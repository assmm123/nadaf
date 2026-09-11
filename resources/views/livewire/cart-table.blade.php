<div wire:key="cart-table">
    @if ($totals['items']->isEmpty())
        <div class="card-lux mx-auto flex max-w-lg flex-col items-center gap-5 p-16 text-center">
            <span class="grid h-20 w-20 place-items-center rounded-full bg-sand text-gold-600">
                <x-shop-icon name="bag" class="h-9 w-9" />
            </span>
            <div>
                <p class="text-lg font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('cart.empty') }}</p>
                <p class="mt-1.5 text-sm text-gray-400">{{ __('cart.empty_desc') }}</p>
            </div>
            <a href="{{ route('home') }}" class="btn-gold !px-8 !py-3">{{ __('cart.continue_shopping') }}</a>
        </div>
    @else
        <div class="grid flex-col gap-7 lg:grid-cols-[1fr_380px] lg:items-start">

            {{-- ============ عناصر السلة ============ --}}
            <div class="space-y-3.5">
                @foreach ($totals['items'] as $item)
                    <div class="card group flex items-center gap-5 p-4 transition hover:border-gold-500/50" wire:key="item-{{ $item->key }}">
                        @if ($img = $item->product->imageUrl())
                            <img src="{{ $img }}" alt="" class="h-20 w-20 shrink-0 rounded-2xl object-cover">
                        @else
                            <div class="grid h-20 w-20 shrink-0 place-items-center rounded-2xl bg-sand text-navy-950/25">
                                <x-shop-icon name="bag" class="h-7 w-7" />
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <a href="{{ route('product.show', $item->product->slug) }}" class="line-clamp-1 font-extrabold text-navy-950 transition hover:text-gold-600">
                                {{ $item->product->name }}
                            </a>
                            @if ($item->variant_label)
                                <p class="mt-1 text-xs tracking-wide text-gray-400">{{ $item->variant_label }}</p>
                            @endif
                            <p class="mt-1 text-sm font-extrabold text-gold-600">
                                {{ fmt_price($item->unit_usd, $item->unit_syp) }}
                            </p>
                            @if ($item->is_wholesale)
                                <span class="badge mt-1.5 bg-gold-100 text-gold-700">{{ __('cart.wholesale_applied') }}</span>
                            @endif
                        </div>

                        <div class="flex flex-col items-end gap-2.5">
                            <div class="inline-flex items-center rounded-full border border-navy-950/12 bg-white">
                                <button wire:click="$set('qty.{{ $item->key }}', {{ $item->qty }} - 1)" class="grid h-8 w-8 place-items-center rounded-full text-navy-950 transition hover:bg-sand">−</button>
                                <input type="number" min="1" class="w-12 border-0 bg-transparent text-center text-sm font-extrabold outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none"
                                       wire:model.live.debounce.400ms="qty.{{ $item->key }}">
                                <button wire:click="$set('qty.{{ $item->key }}', {{ $item->qty }} + 1)" class="grid h-8 w-8 place-items-center rounded-full text-navy-950 transition hover:bg-sand">+</button>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($item->line_usd) }}</span>
                                <button wire:click="remove('{{ $item->key }}')" wire:loading.attr="disabled"
                                        class="grid h-8 w-8 place-items-center rounded-full text-gray-300 transition hover:bg-[#8E2C33]/8 hover:text-[#8E2C33]"
                                        title="{{ __('cart.remove') }}">
                                    <x-shop-icon name="trash" class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ============ ملخص الطلب ============ --}}
            <aside class="lg:sticky lg:top-36">
                <div class="card-lux space-y-4 p-7">
                    <h3 class="text-lg font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('cart.title') }}</h3>
                    <div class="gold-rule"></div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">{{ __('cart.subtotal') }}</span>
                        <span class="font-bold text-navy-950">{{ fmt_usd($totals['subtotal_usd']) }}</span>
                    </div>
                    <div class="flex justify-end text-xs text-gray-400">
                        <span>{{ fmt_syp($totals['total_syp']) }}</span>
                    </div>

                    <div class="gold-rule"></div>

                    <div>
                        <div class="flex items-baseline justify-between">
                            <span class="text-sm font-extrabold tracking-wide text-gray-500">{{ __('cart.grand_total') }}</span>
                            <span class="text-[26px] font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($totals['total_usd']) }}</span>
                        </div>
                        <p class="mt-1 text-end text-xs text-gray-400">{{ fmt_syp($totals['total_syp']) }}</p>
                        <p class="mt-1 text-[11px] text-gray-400">{{ __('cart.shipping') }}: {{ __('cart.calculated_at_checkout') }}</p>
                    </div>

                    @auth
                        <a href="{{ route('checkout') }}" class="btn-gold w-full !py-3.5 !text-base">{{ __('cart.checkout') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-gold w-full !py-3.5 !text-base">{{ __('cart.login_to_checkout') }}</a>
                    @endauth
                    <a href="{{ route('home') }}" class="btn-outline w-full !py-3">{{ __('cart.continue_shopping') }}</a>

                    <p class="pt-1 text-center text-[11px] leading-5 text-gray-400">
                        بعد التأكيد سنتواصل معك لتأكيد التفاصيل — شكرًا لثقتك بنداف
                    </p>
                </div>
            </aside>
        </div>
    @endif
</div>
