@extends('layouts.app')

@section('title', __('account.title'))

@section('content')
    <div class="container-x mt-10">
        <div class="mb-9">
            <span class="kicker mb-3">— حسابي —</span>
            <h1 class="text-3xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('account.profile') }}</h1>
        </div>

        <div class="grid gap-7 lg:grid-cols-2 lg:items-start">
            {{-- الملف الشخصي --}}
            <div class="card-lux p-7">
                @if (session('success'))
                    <div class="mb-5 rounded-2xl bg-[#5F7A60]/10 p-3.5 text-sm font-bold text-[#4a634b]">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="label" for="name">{{ __('auth.name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="input">
                        @error('name') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="email">{{ __('auth.email') }}</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="input">
                    </div>
                    <div>
                        <label class="label" for="phone">{{ __('auth.phone') }}</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required dir="ltr" class="input">
                        @error('phone') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="label" for="password">{{ __('auth.password') }} <span class="text-xs font-normal text-gray-400">(اختياري)</span></label>
                            <input id="password" type="password" name="password" class="input" placeholder="••••••">
                            @error('password') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="password_confirmation">{{ __('auth.confirm_password') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="input" placeholder="••••••">
                        </div>
                    </div>

                    <button class="btn-gold !px-9 !py-3">{{ __('account.save') }}</button>
                </form>
            </div>

            {{-- آخر الطلبات --}}
            <div class="card-lux p-7">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-lg font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('account.my_orders') }}</h2>
                    <a href="{{ route('account.orders') }}" class="text-sm font-bold text-gold-600 transition hover:text-gold-700 hover:underline">{{ __('home.view_all') }} ←</a>
                </div>

                @if ($recentOrders->isEmpty())
                    <p class="py-10 text-center text-sm text-gray-400">{{ __('account.no_orders') }}</p>
                @else
                    <div class="space-y-3.5">
                        @foreach ($recentOrders as $order)
                            <a href="{{ route('account.order', $order->order_code) }}"
                               class="group flex items-center justify-between gap-4 rounded-2xl border border-navy-950/10 bg-white p-5 transition hover:-translate-y-0.5 hover:border-gold-500/50 hover:shadow-lg">
                                <div>
                                    <p class="font-code font-bold tracking-wider text-navy-950 transition group-hover:text-gold-600" dir="ltr">{{ $order->order_code }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $order->created_at->format('Y/m/d') }} — {{ $order->items_count ?? $order->items->count() }} {{ __('account.items') }}</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ status_badge_class($order->status) }}">{{ \App\Models\Order::statusLabel($order->status) }}</span>
                                    <p class="mt-1.5 text-base font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($order->total_usd) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
