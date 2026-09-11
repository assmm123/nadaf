@extends('layouts.app')

@section('title', __('auth.register_title'))

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
                        أنشئ حسابك في نداف: طلباتك في متناولك، فواتيرك محفوظة، وعروض الجملة تصلك أولًا.
                    </p>
                    <p class="serif-lux-italic mt-8 text-[19px] text-gold-400/95">"أناقة تبدأ من التفاصيل"</p>
                    <div class="mt-9 flex items-center gap-3 text-xs text-ivory/50">
                        <span class="h-px w-10 bg-gold-500/60"></span>
                        منذ 2014 في دمشق
                    </div>
                </div>
            </div>

            {{-- النموذج --}}
            <div class="max-h-[90vh] overflow-y-auto p-10 sm:p-14">
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo-shield.svg') }}" alt="نداف" class="mx-auto h-16 w-auto" width="52" height="64">
                    <h1 class="mt-5 text-2xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('auth.register_title') }}</h1>
                    <p class="mt-2 text-sm text-gray-400">دقيقة واحدة تفصلك عن تجربة فاخرة</p>
                </div>

                <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="label" for="name">{{ __('auth.name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="input" placeholder="اسمك الكريم">
                        @error('name') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="email">{{ __('auth.email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="input" placeholder="name@example.com">
                        @error('email') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label" for="phone">{{ __('auth.phone') }}</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required dir="ltr" class="input" placeholder="+963 9xx xxx xxx">
                        @error('phone') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="label" for="password">{{ __('auth.password') }}</label>
                            <input id="password" type="password" name="password" required class="input" placeholder="••••••••">
                            @error('password') <p class="mt-1.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="password_confirmation">{{ __('auth.confirm_password') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required class="input" placeholder="••••••••">
                        </div>
                    </div>

                    <button class="btn-gold w-full !py-3.5 !text-base">{{ __('auth.register_btn') }}</button>
                </form>

                <p class="mt-7 text-center text-sm text-gray-500">
                    {{ __('auth.have_account') }}
                    <a href="{{ route('login') }}" class="font-extrabold text-gold-600 transition hover:text-gold-700 hover:underline">{{ __('nav.login') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
