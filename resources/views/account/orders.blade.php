@extends('layouts.app')

@section('title', __('account.my_orders'))

@section('content')
    <div class="container-x mt-10">
        <div class="mb-9 flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="kicker mb-3">— رحلة طلبك —</span>
                <h1 class="text-3xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('account.my_orders') }}</h1>
            </div>
            <a href="{{ route('account.profile') }}" class="btn-outline !py-2.5">{{ __('account.profile') }}</a>
        </div>

        {{-- أقسام منفصلة: كل قسم كرت مستقل --}}
        @php
            $sections = [
                'pending' => [
                    'title' => 'طلبات جديدة — بانتظار المراجعة',
                    'icon' => 'bag',
                    'desc' => 'وصلتنا للتو ونراجع بياناتها وإثبات الدفع',
                    'ring' => 'ring-amber-400/70',
                    'chip' => 'bg-amber-100 text-amber-700',
                    'query' => fn ($q) => $q->whereIn('status', ['pending']),
                ],
                'accepted' => [
                    'title' => 'طلبات مقبولة — مؤكدة بختم الدفع الأخضر',
                    'icon' => 'check',
                    'desc' => 'تأكدت وثُبت عليها ختم قبض الدفع — قيد التحضير',
                    'ring' => 'ring-[#5F7A60]/50',
                    'chip' => 'bg-[#5F7A60]/12 text-[#4a634b]',
                    'query' => fn ($q) => $q->whereIn('status', ['confirmed', 'preparing']),
                ],
                'shipping' => [
                    'title' => 'قيد التسليم',
                    'icon' => 'truck',
                    'desc' => 'خارجة للشحن أو مع المندوب — حتى التسليم',
                    'ring' => 'ring-sky-400/50',
                    'chip' => 'bg-sky-100 text-sky-700',
                    'query' => fn ($q) => $q->whereIn('status', ['shipped']),
                ],
                'done' => [
                    'title' => 'مكتملة — مختومة بالذهبي',
                    'icon' => 'shield',
                    'desc' => 'سُلمت واعتمدت بختم التوثيق الرسمي',
                    'ring' => 'ring-gold-500/60',
                    'chip' => 'bg-gold-100 text-gold-700',
                    'query' => fn ($q) => $q->where('status', 'delivered'),
                ],
                'cancelled' => [
                    'title' => 'طلبات ملغية',
                    'icon' => 'x',
                    'desc' => 'طلبات لم تكتمل بياناتها أو أُلغيت',
                    'ring' => 'ring-[#8E2C33]/40',
                    'chip' => 'bg-[#8E2C33]/10 text-[#8E2C33]',
                    'query' => fn ($q) => $q->where('status', 'cancelled'),
                ],
            ];
            $total = auth()->user()->orders()->count();
        @endphp

        @if ($total === 0)
            <div class="card-lux mx-auto flex max-w-lg flex-col items-center gap-4 p-16 text-center">
                <span class="grid h-16 w-16 place-items-center rounded-full bg-sand text-gold-600">
                    <x-shop-icon name="bag" class="h-7 w-7" />
                </span>
                <p class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('account.no_orders') }}</p>
                <a href="{{ route('home') }}" class="btn-gold">{{ __('cart.continue_shopping') }}</a>
            </div>
        @else
            <div class="space-y-5">
                @foreach ($sections as $key => $sec)
                    @php
                        $orders = (clone $sec['query'])(auth()->user()->orders()->withCount('items')->latest())->get();
                        $count = $orders->count();
                        if ($count === 0) continue;
                    @endphp
                    <details class="card group overflow-hidden !rounded-3xl p-0 ring-2 {{ $sec['ring'] }}" open wire:key="sec-{{ $key }}">
                        <summary class="flex cursor-pointer flex-wrap items-center gap-4 p-6 select-none">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-navy-950 text-gold-400">
                                <x-shop-icon name="{{ $sec['icon'] }}" class="h-5 w-5" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $sec['title'] }}</p>
                                <p class="mt-0.5 text-xs text-gray-400">{{ $sec['desc'] }}</p>
                            </div>
                            <span class="badge !px-4 !py-1.5 {{ $sec['chip'] }}">{{ $count }} {{ $count === 1 ? 'طلب' : 'طلبات' }}</span>
                            <x-shop-icon name="chevron" class="h-4 w-4 text-gray-300 transition group-open:rotate-180 rtl:rotate-180" />
                        </summary>

                        <div class="border-t border-navy-950/8 p-5">
                            <div class="grid gap-3.5 lg:grid-cols-2">
                                @foreach ($orders as $order)
                                    <a href="{{ route('account.order', $order->order_code) }}"
                                       class="group rounded-2xl border border-navy-950/10 bg-white p-5 transition hover:-translate-y-0.5 hover:border-gold-500/50 hover:shadow-lg" wire:key="order-{{ $order->id }}">
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="font-code text-lg font-bold tracking-wider text-navy-950 transition group-hover:text-gold-600" dir="ltr">{{ $order->order_code }}</p>
                                            <span class="text-xs text-gray-400">{{ $order->created_at->format('Y/m/d H:i') }}</span>
                                        </div>

                                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-gray-500">
                                            <span class="flex items-center gap-1.5"><x-shop-icon name="bag" class="h-3.5 w-3.5 text-gold-600" /> {{ $order->items_count }} عنصر</span>
                                            <span class="flex items-center gap-1.5"><x-shop-icon name="card" class="h-3.5 w-3.5 text-gold-600" /> {{ $order->paymentMethod?->name ?? '—' }}</span>
                                            @if ($order->payment_confirmed_at)
                                                <span class="flex items-center gap-1 font-bold text-[#4a634b]"><x-shop-icon name="check" class="h-3 w-3" /> قبض مؤكد</span>
                                            @endif
                                            @if ($order->stamped_at)
                                                <span class="flex items-center gap-1 font-bold text-gold-600"><x-shop-icon name="shield" class="h-3.5 w-3.5" /> معتمد</span>
                                            @endif
                                        </div>

                                        <div class="mt-3.5 flex items-center justify-between border-t border-navy-950/6 pt-3">
                                            <span class="text-lg font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($order->total_usd) }}</span>
                                            <span class="text-[11px] text-gray-400">{{ fmt_syp($order->total_syp) }}</span>
                                            @if ($order->stamped_at || $order->payment_confirmed_at)
                                                <span class="rounded-full bg-gold-100 px-3 py-1 text-[11px] font-extrabold text-gold-700">🧾 فاتورتي</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
@endsection
