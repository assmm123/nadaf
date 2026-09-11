<a href="{{ route('cart.index') }}" class="relative rounded-lg p-2 hover:bg-navy-50" title="{{ __('nav.cart') }}" wire:key="header-cart">
    <x-shop-icon name="bag" class="h-6 w-6" />
    @if ($count > 0)
        <span class="absolute top-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-gold-500 px-1 text-[10px] font-extrabold text-navy-900 end-0">
            {{ $count }}
        </span>
    @endif
</a>
