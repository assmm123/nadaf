@extends('layouts.app')

@section('title', __('account.details') . ' ' . $order->order_code)

@section('content')
    <div class="container-x mt-8 max-w-5xl">
        <a href="{{ route('account.orders') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-bold text-gold-600 transition hover:text-gold-700 hover:underline">
            <x-shop-icon name="chevron" class="h-3.5 w-3.5 rotate-180 rtl:-rotate-180" /> {{ __('account.my_orders') }}
        </a>

        {{-- رأس الطلب --}}
        <div class="card-lux mb-7 flex flex-wrap items-center justify-between gap-5 p-7">
            <div>
                <p class="text-xs tracking-widest text-gray-400">{{ __('checkout.your_code') }}</p>
                <p class="font-code mt-2 text-3xl font-bold tracking-[.1em] text-navy-950" dir="ltr">{{ $order->order_code }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if ($order->stamped_at || $order->payment_confirmed_at)
                    <a href="{{ route('account.invoice', $order->order_code) }}" target="_blank" rel="noopener"
                       class="btn-gold !py-2.5 text-sm">🧾 فاتورتي</a>
                @endif
                <span class="badge !px-4 !py-1.5 text-sm {{ status_badge_class($order->status) }}">
                    {{ \App\Models\Order::statusLabel($order->status) }}
                </span>
            </div>
        </div>

        {{-- محطات سير الطلب --}}
        @php
            $flow = ['confirmed', 'preparing', 'shipped', 'delivered'];
            $statusesDone = [
                'placed' => true,
                'confirmed' => in_array($order->status, $flow),
                'paid' => (bool) $order->payment_confirmed_at,
                'shipped' => in_array($order->status, ['shipped', 'delivered']),
            ];
            $stations = [
                ['key' => 'placed', 'label' => 'استلام الطلب', 'icon' => 'bag', 'at' => $order->created_at],
                ['key' => 'confirmed', 'label' => 'تأكيد الطلب', 'icon' => 'check', 'at' => $order->statusHistory->firstWhere('to_status', 'confirmed')?->created_at],
                ['key' => 'paid', 'label' => 'توثيق الدفع', 'icon' => 'banknote', 'at' => $order->payment_confirmed_at],
                ['key' => 'shipped', 'label' => $order->status === 'delivered' ? 'تم التسليم' : 'الشحن / التسليم', 'icon' => 'truck', 'at' => $order->statusHistory->firstWhere('to_status', 'shipped')?->created_at ?? ($order->status === 'delivered' ? $order->updated_at : null)],
            ];
            $cancelled = $order->status === 'cancelled';
        @endphp
        <div class="card mb-7 overflow-x-auto p-7">
            <h2 class="mb-6 text-lg font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">سير الطلب</h2>
            @if ($cancelled)
                <div class="flex items-center gap-4 rounded-2xl bg-[#8E2C33]/8 p-5 text-[#8E2C33]">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-[#8E2C33]/12">
                        <x-shop-icon name="x" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-extrabold">أُلغي هذا الطلب</p>
                        <p class="mt-0.5 text-xs text-[#8E2C33]/70">{{ $order->statusHistory->last()?->note ?? 'تواصل معنا لأي استفسار' }}</p>
                    </div>
                </div>
            @else
                <div class="flex min-w-[520px] items-start">
                    @foreach ($stations as $i => $st)
                        @php $done = $statusesDone[$st['key']]; @endphp
                        <div class="relative flex flex-1 flex-col items-center text-center" wire:key="station-{{ $st['key'] }}">
                            {{-- الخط الواصل --}}
                            @if ($i > 0)
                                <span class="absolute top-5 h-[2px] w-full
                                      {{ $done ? 'bg-gold-500' : 'bg-navy-950/10' }}"
                                      style="inset-inline-start: -50%;"></span>
                            @endif
                            <span class="relative z-10 grid h-11 w-11 place-items-center rounded-full border-2 transition
                                         {{ $done ? 'border-gold-500 bg-gold-500 text-navy-950 shadow-[0_4px_14px_rgba(198,164,76,.4)]' : 'border-navy-950/10 bg-white text-gray-300' }}">
                                <x-shop-icon name="{{ $st['icon'] }}" class="h-5 w-5" />
                            </span>
                            <p class="mt-2.5 text-xs font-extrabold {{ $done ? 'text-navy-950' : 'text-gray-400' }}">{{ $st['label'] }}</p>
                            @if ($done && $st['at'])
                                <p class="mt-1 text-[10px] text-gray-400">{{ $st['at']->format('m/d H:i') }}</p>
                            @elseif ($done)
                                <p class="mt-1 text-[10px] font-bold text-gold-600">✓ تم</p>
                            @else
                                <p class="mt-1 text-[10px] text-gray-300">بالانتظار</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            {{-- المنتجات --}}
            <div class="card p-7 md:col-span-2">
                <h2 class="mb-5 text-lg font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('account.items') }}</h2>
                <div class="space-y-3.5">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between gap-3.5 border-b border-navy-950/6 pb-3.5 last:border-0 last:pb-0" wire:key="item-{{ $item->id }}">
                            <div>
                                <p class="font-bold text-navy-950">{{ $item->displayName() }}</p>
                                <p class="mt-1 text-xs text-gray-400">
                                    {{ __('product.quantity') }}: {{ $item->quantity }}
                                    @if ($item->is_wholesale) — <span class="font-bold text-gold-600">{{ __('cart.wholesale_applied') }}</span> @endif
                                </p>
                            </div>
                            <span class="shrink-0 font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($item->total_price_usd) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-2.5 border-t border-navy-950/10 pt-5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('cart.subtotal') }}</span>
                        <span class="font-bold text-navy-950">{{ fmt_usd($order->subtotal_usd) }}</span>
                    </div>
                    @if ($order->discount_usd > 0)
                        <div class="flex justify-between text-[#4a634b]">
                            <span>{{ __('cart.discount') }}</span>
                            <span class="font-bold">-{{ fmt_usd($order->discount_usd) }}</span>
                        </div>
                    @endif
                    @if ($order->shipping_usd > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('cart.shipping') }}</span>
                            <span class="font-bold text-navy-950">{{ fmt_usd($order->shipping_usd) }}</span>
                        </div>
                    @endif
                    <div class="gold-rule !my-3.5"></div>
                    <div class="flex items-baseline justify-between">
                        <span class="font-extrabold text-navy-950">{{ __('cart.grand_total') }}</span>
                        <span class="text-2xl font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($order->total_usd) }}</span>
                    </div>
                    <p class="text-end text-xs text-gray-400">{{ fmt_syp($order->total_syp) }} <span class="opacity-70">(1$ = {{ number_format($order->exchange_rate, 0) }})</span></p>
                </div>
            </div>

            {{-- معلومات إضافية --}}
            <div class="space-y-5">
                <div class="card p-6 text-sm">
                    <h3 class="mb-4 font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('account.details') }}</h3>
                    <div class="space-y-2.5 leading-7 text-gray-600">
                        <p><span class="text-gray-400">{{ __('account.date') }}:</span> {{ $order->created_at->format('Y/m/d') }}</p>
                        <p><span class="text-gray-400">{{ __('account.shipping_method') }}:</span> {{ $order->shipping_method === 'local' ? __('checkout.local') : __('checkout.pickup') }}</p>
                        @if ($order->shipping_address)
                            <p><span class="text-gray-400">{{ __('account.address') }}:</span> {{ $order->shipping_address }}</p>
                        @endif
                        <p><span class="text-gray-400">{{ __('account.payment') }}:</span> {{ $order->paymentMethod?->name ?? '—' }}</p>
                        @if ($order->notes)
                            <p><span class="text-gray-400">{{ __('account.notes') }}:</span> {{ $order->notes }}</p>
                        @endif
                    </div>
                </div>

                @if ($order->statusHistory->count() > 1)
                    <div class="card p-6 text-sm">
                        <h3 class="mb-4 font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('account.status') }}</h3>
                        <ol class="space-y-3">
                            @foreach ($order->statusHistory as $history)
                                <li class="flex items-center gap-2.5">
                                    <span class="h-2 w-2 rounded-full bg-gold-500 shadow-[0_0_0_3px_rgba(198,164,76,.15)]"></span>
                                    <span class="font-bold text-navy-950">{{ \App\Models\Order::statusLabel($history->to_status) }}</span>
                                    <span class="ms-auto text-xs text-gray-400">{{ $history->created_at?->format('m/d H:i') }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
