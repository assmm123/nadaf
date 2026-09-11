<div class="space-y-6" wire:key="add-to-cart">
    @if (count($colors) > 0)
        <div>
            <label class="label">{{ __('product.color') }}</label>
            <div class="flex flex-wrap gap-2.5">
                @foreach ($colors as $c)
                    <button type="button" wire:click="$set('color', '{{ $c }}' === color ? '' : '{{ $c }}')"
                            class="rounded-full px-5 py-2 text-sm font-bold transition-all duration-300 {{ $color === $c
                                ? 'bg-navy-950 text-gold-400 shadow-md'
                                : 'border border-navy-950/12 bg-white text-navy-950 hover:border-gold-500 hover:text-gold-600' }}">
                        {{ $c }}
                    </button>
                @endforeach
            </div>
            @error('color') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
        </div>
    @endif

    @if (count($sizes) > 0)
        <div>
            <label class="label">{{ __('product.size') }}</label>
            <div class="flex flex-wrap gap-2">
                @foreach ($sizes as $s)
                    <button type="button" wire:click="$set('size', '{{ $s }}' === size ? '' : '{{ $s }}')"
                            class="rounded-full px-5 py-2 text-sm font-bold tracking-wide transition-all duration-300 {{ $size === $s
                                ? 'bg-navy-950 text-gold-400 shadow-md'
                                : 'border border-navy-950/12 bg-white text-navy-950 hover:border-gold-500 hover:text-gold-600' }}">
                        {{ $s }}
                    </button>
                @endforeach
            </div>
            @error('size') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
        </div>
    @endif

    <div class="flex flex-wrap items-end gap-4">
        <div>
            <label class="label">{{ __('product.quantity') }}</label>
            <div class="inline-flex items-center rounded-full border border-navy-950/12 bg-white">
                <button type="button" wire:click="$set('qty', {{ '$qty' }} - 1)" class="grid h-11 w-11 place-items-center rounded-full text-lg text-navy-950 transition hover:bg-sand hover:text-gold-600">−</button>
                <input type="number" wire:model.live="qty" min="1" max="{{ max(1, $maxQty) }}"
                       class="w-14 border-0 bg-transparent text-center text-base font-extrabold outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                <button type="button" wire:click="$set('qty', {{ '$qty' }} + 1)" class="grid h-11 w-11 place-items-center rounded-full text-lg text-navy-950 transition hover:bg-sand hover:text-gold-600">+</button>
            </div>
        </div>

        <div class="pb-1.5">
            @if ($maxQty > 0 || ! $product->variants->count())
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#5F7A60]/10 px-3.5 py-1.5 text-xs font-extrabold text-[#4a634b]">
                    <x-shop-icon name="check" class="h-3 w-3" />
                    {{ $stockMessage }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#8E2C33]/10 px-3.5 py-1.5 text-xs font-extrabold text-[#8E2C33]">
                    {{ __('product.out_of_stock') }}
                </span>
            @endif
        </div>
    </div>
    @error('qty') <p class="text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror

    @if ($maxQty > 0 || ! $product->variants->count())
        <div class="flex flex-col gap-2.5 sm:flex-row">
            <button wire:click="add" wire:loading.attr="disabled" class="btn-gold flex-1 !py-3.5 !text-base">
                <x-shop-icon name="bag" class="h-4.5 w-4.5" />
                {{ __('product.add_to_cart') }}
            </button>
            <button wire:click="add(true)" wire:loading.attr="disabled" class="btn-primary flex-1 !py-3.5 !text-base">
                {{ __('product.buy_now') }}
            </button>
        </div>
    @else
        <button disabled class="btn w-full cursor-not-allowed !py-3.5 border border-navy-950/10 bg-[#f0ece3] !text-base font-extrabold text-gray-400">
            {{ __('product.out_of_stock') }}
        </button>
    @endif
</div>
