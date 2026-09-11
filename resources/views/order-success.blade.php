@extends('layouts.app')

@section('title', __('checkout.order_created_title'))

@section('content')
    <div class="container-x mt-14 flex justify-center pb-16">
        <div class="card-lux relative w-full max-w-md overflow-hidden p-10 text-center">

            {{-- زخرفة خلفية خفيفة --}}
            <div class="pointer-events-none absolute inset-0 bg-damask-star opacity-60"></div>

            <div class="relative">
                <div class="mx-auto mb-6 grid h-20 w-20 place-items-center rounded-full bg-gold-500/12 text-gold-600 shadow-[0_0_0_8px_rgba(198,164,76,.08)]">
                    <x-shop-icon name="check" class="h-9 w-9" />
                </div>

                <h1 class="text-2xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('checkout.order_created_title') }}</h1>

                <p class="mt-4 text-sm leading-7 text-gray-500">
                    شكرًا <b class="text-navy-950">{{ auth()->user()->name }}</b> لثقتك بنداف — طلبك قيد المراجعة وسيتم الرد عليك في أسرع وقت ممكن.
                </p>

                <p class="mt-7 text-xs tracking-widest text-gray-400">{{ __('checkout.your_code') }}</p>
                <div class="mt-3 rounded-2xl border-2 border-dashed border-gold-500/70 bg-gold-50/60 px-8 py-5">
                    <span class="font-code text-[34px] font-bold tracking-[.14em] text-navy-950" dir="ltr">{{ $order->order_code }}</span>
                </div>
                <p class="mt-3 text-xs text-gray-400">{{ __('checkout.code_note') }}</p>

                <div class="mt-7 space-y-2 rounded-2xl bg-sand/50 p-5 text-sm">
                    <div class="flex items-baseline justify-between">
                        <span class="text-gray-500">{{ __('cart.grand_total') }}</span>
                        <span class="text-xl font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($order->total_usd) }}</span>
                    </div>
                    <p class="text-end text-xs text-gray-400">{{ fmt_syp($order->total_syp) }}</p>
                </div>

                <div class="mt-8 flex flex-col gap-2.5">
                    <a href="{{ route('account.order', $order->order_code) }}" class="btn-primary">{{ __('checkout.view_my_orders') }}</a>
                    <a href="{{ route('home') }}" class="btn-outline">{{ __('cart.continue_shopping') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
