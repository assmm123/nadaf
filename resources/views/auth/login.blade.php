@extends('layouts.app')

@section('title', __('auth.login_title'))

@section('content')
    <div class="container-x flex justify-center py-16">
        <div class="grid w-full max-w-4xl overflow-hidden rounded-[26px] border border-navy-950/10 bg-white shadow-[0_1px_3px_rgba(14,27,42,.05),0_20px_60px_rgba(14,27,42,.1)] lg:grid-cols-2">

            {{-- اللوحة الكحلية — هوية المتجر --}}
            <div class="relative hidden flex-col justify-center overflow-hidden bg-navy-950 p-14 text-ivory lg:flex">
                <div class="pointer-events-none absolute inset-0 bg-damask-lines"></div>
                <div class="pointer-events-none absolute inset-0 bg-gold-glow"></div>

                <div class="relative">
                    <span class="serif-lux-italic text-[15px] tracking-[.14em] text-gold-400">NADAF — أناقة تليق بك</span>
                    <p class="mt-5 max-w-sm text-[15px] leading-8 text-ivory/75">
                        انضم إلى عائلة نداف: تابع طلباتك، حمّل فواتيرك، وكن أول من يعرف بوصول التشكيلات الجديدة.
                    </p>
                    <p class="serif-lux-italic mt-8 text-[19px] text-gold-400/95">"التفاصيل الصغيرة تصنع الرجل الأنيق"</p>
                    <div class="mt-9 flex items-center gap-3 text-xs text-ivory/50">
                        <span class="h-px w-10 bg-gold-500/60"></span>
                        منذ 2014 في دمشق
                    </div>
                </div>
            </div>

            {{-- النموذج --}}
            <div class="p-10 sm:p-14">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-shield.svg') }}" alt="نداف" class="mx-auto h-16 w-auto" width="52" height="64">
                    <h1 class="mt-5 text-2xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('auth.login_title') }}</h1>
                    <p class="mt-2 text-sm text-gray-400">سجّل دخولك لمتابعة تجربتك الفاخرة</p>
                </div>

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="label" for="email">{{ __('auth.email') }}</label>
                        <div class="relative">
                            <x-shop-icon name="user" class="absolute top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-gray-400 start-4" />
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="input !ps-12" placeholder="name@example.com">
                        </div>
                        @error('email') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="password">{{ __('auth.password') }}</label>
                        <div class="relative">
                            <x-shop-icon name="lock" class="absolute top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-gray-400 start-4" />
                            <input id="password" type="password" name="password" required
                                   class="input !ps-12" placeholder="••••••••">
                        </div>
                        @error('password') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>

                    <label class="flex cursor-pointer items-center gap-2.5 text-sm text-gray-500 select-none">
                        <input type="checkbox" name="remember" value="1"
                               class="h-4 w-4 rounded-full border-navy-950/20 accent-gold-600">
                        تذكرني على هذا الجهاز
                    </label>

                    <button class="btn-gold w-full !py-3.5 !text-base">{{ __('auth.login_btn') }}</button>
                </form>

                <p class="mt-7 text-center text-sm text-gray-500">
                    {{ __('auth.no_account') }}
                    <a href="{{ route('register') }}" class="font-extrabold text-gold-600 transition hover:text-gold-700 hover:underline">{{ __('nav.register') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
