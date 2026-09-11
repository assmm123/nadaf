<footer class="relative mt-20 bg-navy-950 text-ivory">
    {{-- الخط الذهبي العلوي --}}
    <div class="absolute inset-x-0 top-0 h-[2px]" style="background:linear-gradient(90deg,transparent,#c6a44c,transparent)"></div>
    {{-- زخرفة الخطوط الحريرية --}}
    <div class="pointer-events-none absolute inset-0 bg-damask-lines"></div>

    <div class="container-x relative grid gap-12 py-16 md:grid-cols-4">

        {{-- العمود الأول: الهوية --}}
        <div>
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <img src="{{ asset('images/logo-shield.svg') }}" alt="نداف"
                     class="h-14 w-auto transition-transform duration-500 group-hover:-rotate-6 group-hover:scale-105" width="46" height="56">
                <div class="leading-none">
                    <span class="text-[22px] font-extrabold text-ivory" style="font-family:Almarai,Tajawal,sans-serif">نداف</span>
                    <p class="serif-lux-italic mt-1.5 text-[12px] tracking-[.12em] text-gold-400">NADAF — أناقة تليق بك</p>
                </div>
            </a>
            <p class="mt-5 max-w-xs text-sm leading-7 text-ivory/70">{{ __('footer.tagline') }}</p>
            <p class="serif-lux-italic mt-5 text-[15px] text-gold-400/90">"التفاصيل الصغيرة تصنع الرجل الأنيق"</p>
        </div>

        {{-- العمود الثاني: روابط سريعة --}}
        <div>
            <h3 class="mb-5 text-sm font-extrabold tracking-[.15em] text-gold-400" style="font-family:Almarai,Tajawal,sans-serif">{{ __('footer.quick_links') }}</h3>
            <ul class="space-y-3 text-sm text-ivory/75">
                <li><a href="{{ route('home') }}" class="inline-block transition hover:translate-x-[-4px] hover:text-gold-400">{{ __('nav.home') }}</a></li>
                <li><a href="{{ route('cart.index') }}" class="inline-block transition hover:translate-x-[-4px] hover:text-gold-400">{{ __('nav.cart') }}</a></li>
                @foreach ($footerPages as $page)
                    <li><a href="{{ route('page.show', $page->slug) }}" class="inline-block transition hover:translate-x-[-4px] hover:text-gold-400">{{ $page->title }}</a></li>
                @endforeach
            </ul>
        </div>

        {{-- العمود الثالث: تواصل معنا --}}
        <div>
            <h3 class="mb-5 text-sm font-extrabold tracking-[.15em] text-gold-400" style="font-family:Almarai,Tajawal,sans-serif">{{ __('footer.contact_us') }}</h3>
            <ul class="space-y-3 text-sm text-ivory/75">
                @foreach ($contactMethods as $method)
                    <li>
                        <a href="{{ $method->link() }}" target="_blank" rel="noopener" class="group flex items-center gap-3 transition hover:text-gold-400">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-ivory/20 transition group-hover:border-gold-400 group-hover:text-gold-400">
                                <x-shop-icon name="{{ \App\Models\CommunicationMethod::TYPES[$method->type]['icon'] ?? 'globe' }}" class="h-4 w-4" />
                            </span>
                            <span class="min-w-0">
                                <span class="block">{{ $method->label ?? $method->typeLabel() }}</span>
                                <span class="block text-xs text-ivory/45" dir="ltr">{{ $method->value }}</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- العمود الرابع: شارة الاطمئنان --}}
        <div>
            <h3 class="mb-5 text-sm font-extrabold tracking-[.15em] text-gold-400" style="font-family:Almarai,Tajawal,sans-serif">{{ __('checkout.pickup') }}</h3>
            <div class="space-y-3.5 text-sm text-ivory/75">
                <div class="flex items-center gap-3">
                    <x-shop-icon name="truck" class="h-5 w-5 shrink-0 text-gold-400" />
                    <span>{{ __('checkout.local') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <x-shop-icon name="store" class="h-5 w-5 shrink-0 text-gold-400" />
                    <span>{{ __('checkout.pickup') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <x-shop-icon name="shield" class="h-5 w-5 shrink-0 text-gold-400" />
                    <span>{{ __('product.in_stock') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- الشريط السفلي --}}
    <div class="relative border-t border-ivory/10">
        <div class="container-x flex flex-col items-center justify-between gap-3 py-5 text-xs text-ivory/50 sm:flex-row">
            <p>© {{ date('Y') }} {{ app()->getLocale() === 'en' ? setting('store_name_en', 'NADAF') : setting('store_name_ar', 'نداف') }} — {{ __('footer.rights') }}</p>
            <p class="flex items-center gap-2">
                <span class="serif-lux-italic text-gold-400/80">Crafted with care</span>
                <span class="text-gold-500">❦</span>
                {{ __('home.shop_now') }}
            </p>
        </div>
    </div>
</footer>
