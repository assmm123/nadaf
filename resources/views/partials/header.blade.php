<header x-data="{ mobileMenu: false, scrolled: false }" @scroll.window="scrolled = $el.getBoundingClientRect().top < -8"
        class="sticky top-0 z-50 transition-all duration-300"
        :class="scrolled ? 'shadow-[0_1px_0_rgba(14,27,42,.06),0_12px_32px_rgba(14,27,42,.06)]' : ''">

    {{-- الشريط العلوي: اللغة والعملة — كحلي دمشقي --}}
    <div class="bg-navy-950 text-ivory bg-damask-lines">
        <div class="container-x flex h-9 items-center justify-between text-xs">
            <p class="hidden opacity-75 sm:block">{{ __('footer.tagline') }}</p>
            <div class="flex w-full items-center justify-end gap-5 sm:w-auto">
                <div class="flex items-center gap-1.5">
                    <x-shop-icon name="globe" class="h-3.5 w-3.5 opacity-60" />
                    <a href="{{ route('lang.switch', 'ar') }}" class="rounded-full px-2 py-0.5 transition {{ app()->getLocale() === 'ar' ? 'font-extrabold text-gold-400' : 'opacity-70 hover:text-gold-400 hover:opacity-100' }}">عربي</a>
                    <span class="opacity-30">|</span>
                    <a href="{{ route('lang.switch', 'en') }}" class="rounded-full px-2 py-0.5 transition {{ app()->getLocale() === 'en' ? 'font-extrabold text-gold-400' : 'opacity-70 hover:text-gold-400 hover:opacity-100' }}">EN</a>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('currency.switch', 'usd') }}" class="rounded-full px-2 py-0.5 transition {{ session('currency', 'usd') === 'usd' ? 'font-extrabold text-gold-400' : 'opacity-70 hover:text-gold-400 hover:opacity-100' }}">$</a>
                    <span class="opacity-30">|</span>
                    <a href="{{ route('currency.switch', 'syp') }}" class="rounded-full px-2 py-0.5 transition {{ session('currency') === 'syp' ? 'font-extrabold text-gold-400' : 'opacity-70 hover:text-gold-400 hover:opacity-100' }}">{{ __('common.syp') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- الشريط الرئيسي — عاجي حريري بضبابية زجاجية --}}
    <div class="border-b border-transparent bg-ivory/85 backdrop-blur-xl transition-all duration-300"
         :class="scrolled ? 'border-navy-950/10 bg-[#FBF8F2]/95' : ''">
        <div class="container-x flex h-[76px] items-center gap-5">

            {{-- زر الموبايل --}}
            <button class="rounded-full p-2.5 transition hover:bg-navy-950/5 lg:hidden" @click="mobileMenu = !mobileMenu" aria-label="{{ __('nav.menu') }}">
                <x-shop-icon name="menu" class="h-6 w-6 text-navy-950" />
            </button>

            {{-- الشعار — الدرع الذهبي --}}
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/logo-shield.svg') }}" alt="نداف"
                     class="h-12 w-auto transition-transform duration-500 group-hover:-rotate-6 group-hover:scale-105" width="40" height="48">
                <span class="flex flex-col items-start leading-none">
                    <span class="text-[22px] font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">نداف</span>
                    <span class="serif-lux-italic mt-1 text-[12px] tracking-[.12em] text-gold-600">NADAF — أناقة تليق بك</span>
                </span>
            </a>

            {{-- البحث — كبسولة أنيقة --}}
            <form action="{{ route('search') }}" method="GET" class="relative mx-auto hidden max-w-[440px] flex-1 md:block">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('nav.search_placeholder') }}"
                       class="w-full rounded-full border border-navy-950/10 bg-white px-5 py-2.5 text-sm outline-none transition focus:border-gold-500 focus:shadow-[0_0_0_3px_rgba(198,164,76,.14)] pe-11">
                <button type="submit" class="absolute top-1/2 -translate-y-1/2 text-gray-400 transition hover:text-gold-600 end-3" aria-label="{{ __('nav.search_placeholder') }}">
                    <x-shop-icon name="search" class="h-[17px] w-[17px]" />
                </button>
            </form>

            {{-- أدوات الهيدر --}}
            <div class="ms-auto flex items-center gap-1">
                @if (! inquiry_mode())
                    <livewire:header-cart />
                @endif

                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button class="flex items-center gap-2 rounded-full p-2.5 transition hover:bg-navy-950/5" @click="open = !open">
                            <x-shop-icon name="user" class="h-6 w-6 text-navy-950" />
                            <span class="hidden text-sm font-bold text-navy-950 lg:block">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak x-transition
                             class="absolute top-full z-50 mt-2 w-48 overflow-hidden rounded-2xl border border-navy-950/10 bg-white py-1.5 shadow-[0_10px_36px_rgba(14,27,42,.14)] end-0">
                            <div class="gold-rule mx-4 mb-1.5"></div>
                            <a href="{{ route('account.profile') }}" class="block px-5 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-gold-50 hover:text-gold-700">{{ __('account.profile') }}</a>
                            <a href="{{ route('account.orders') }}" class="block px-5 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-gold-50 hover:text-gold-700">{{ __('account.my_orders') }}</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ url('/admin') }}" class="block px-5 py-2.5 text-sm font-extrabold text-gold-600 transition hover:bg-gold-50">{{ __('nav.account') }} (Admin)</a>
                            @endif
                            <div class="gold-rule mx-4 my-1.5"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="block w-full px-5 py-2.5 text-start text-sm font-bold text-[#8E2C33] transition hover:bg-red-50">{{ __('nav.logout') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-outline hidden !px-5 !py-2 sm:inline-flex">{{ __('nav.login') }}</a>
                    <a href="{{ route('register') }}" class="btn-gold hidden !px-5 !py-2 sm:inline-flex">{{ __('nav.register') }}</a>
                    <a href="{{ route('login') }}" class="rounded-full p-2.5 transition hover:bg-navy-950/5 sm:hidden">
                        <x-shop-icon name="user" class="h-6 w-6 text-navy-950" />
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- شريط الأقسام — بخط ذهبي سفلي عند المرور --}}
    <nav class="hidden border-b border-navy-950/8 bg-ivory/90 backdrop-blur lg:block">
        <div class="container-x flex h-12 items-center gap-8 text-sm font-bold text-navy-950">
            <a href="{{ route('home') }}" class="relative py-3 transition hover:text-gold-600 after:absolute after:bottom-1.5 after:start-1/2 after:h-[2px] after:w-0 after:rounded-full after:bg-gold-500 after:transition-all after:duration-300 hover:after:start-0 hover:after:w-full">{{ __('nav.home') }}</a>
            @foreach ($headerCategories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="relative py-3 transition hover:text-gold-600 after:absolute after:bottom-1.5 after:start-1/2 after:h-[2px] after:w-0 after:rounded-full after:bg-gold-500 after:transition-all after:duration-300 hover:after:start-0 hover:after:w-full">{{ $cat->name }}</a>
            @endforeach
            <a href="{{ route('contact') }}" class="relative py-3 transition hover:text-gold-600 after:absolute after:bottom-1.5 after:start-1/2 after:h-[2px] after:w-0 after:rounded-full after:bg-gold-500 after:transition-all after:duration-300 hover:after:start-0 hover:after:w-full">{{ __('nav.contact') }}</a>
            @foreach ($footerPages as $page)
                <a href="{{ route('page.show', $page->slug) }}" class="py-3 opacity-70 transition hover:text-gold-600 hover:opacity-100">{{ $page->title }}</a>
            @endforeach
        </div>
    </nav>

    {{-- قائمة الموبايل --}}
    <div x-show="mobileMenu" x-cloak x-transition class="border-b border-navy-950/10 bg-white lg:hidden">
        <form action="{{ route('search') }}" method="GET" class="p-3">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('nav.search_placeholder') }}"
                   class="w-full rounded-full border border-navy-950/10 bg-ivory px-5 py-2.5 text-sm outline-none focus:border-gold-500">
        </form>
        <nav class="flex flex-col pb-3">
            <a href="{{ route('home') }}" class="px-5 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-gold-50 hover:text-gold-700">{{ __('nav.home') }}</a>
            @foreach ($headerCategories as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="px-5 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-gold-50 hover:text-gold-700">{{ $cat->name }}</a>
            @endforeach
            <a href="{{ route('contact') }}" class="px-5 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-gold-50 hover:text-gold-700">{{ __('nav.contact') }}</a>
            @guest
                <div class="flex gap-2 px-4 pt-3">
                    <a href="{{ route('login') }}" class="btn-outline flex-1">{{ __('nav.login') }}</a>
                    <a href="{{ route('register') }}" class="btn-gold flex-1">{{ __('nav.register') }}</a>
                </div>
            @endguest
        </nav>
    </div>
</header>
