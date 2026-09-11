<div class="container-x mt-10" wire:key="my-orders-root">
    <div class="mb-9 flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="kicker mb-3">— رحلة طلبك —</span>
            <h1 class="text-3xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">طلباتي</h1>
        </div>
        <a href="{{ route('account.profile') }}" class="btn-outline !py-2.5">حسابي</a>
    </div>

    {{-- ===== البوابة: كروت الحالات ===== --}}
    @if (! $openSection)
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3" wire:key="gate">
            @foreach ($sections as $sec)
                @php $c = $counts[$sec['key']]; @endphp
                <button type="button" wire:click="openSection('{{ $sec['key'] }}')"
                        class="group relative aspect-[16/9] overflow-hidden rounded-3xl text-start shadow-[0_1px_3px_rgba(14,27,42,.05),0_14px_40px_rgba(14,27,42,.08)] transition duration-400 hover:-translate-y-1.5 hover:shadow-[0_2px_6px_rgba(14,27,42,.06),0_24px_54px_rgba(14,27,42,.14)] focus:outline-none focus:ring-2 focus:ring-gold-500"
                        wire:key="sec-{{ $sec['key'] }}">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $sec['color'] }} transition duration-700 group-hover:scale-110"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-950/55 via-transparent to-transparent"></div>
                    <div class="pointer-events-none absolute inset-0 bg-damask-lines opacity-70"></div>

                    <x-shop-icon name="{{ $sec['icon'] }}" class="absolute end-5 top-5 h-10 w-10 text-ivory/25 transition duration-500 group-hover:scale-110 group-hover:text-ivory/45" />

                    {{-- الشريط الذهبي السفلي --}}
                    <span class="absolute inset-x-6 bottom-0 h-[2px] origin-start scale-x-0 bg-gold-500 transition-transform duration-500 group-hover:scale-x-100"></span>

                    <div class="absolute inset-x-0 bottom-0 p-5">
                        <div class="flex items-end justify-between">
                            <div>
                                <p class="text-lg font-extrabold text-ivory" style="font-family:Almarai,Tajawal,sans-serif">{{ $sec['title'] }}</p>
                                <p class="mt-0.5 text-[11px] text-ivory/65">{{ $sec['desc'] }}</p>
                            </div>
                            <span class="rounded-full bg-ivory/20 px-3.5 py-1.5 text-lg font-extrabold text-ivory backdrop-blur">{{ $c }}</span>
                        </div>
                    </div>
                </button>
            @endforeach
        </div>
    @else
        {{-- ===== العرض المعزول ===== --}}
        @php
            $sec = collect($sections)->firstWhere('key', $openSection);
        @endphp
        <div class="mb-7 flex items-center gap-4">
            <button wire:click="closeSection"
                    class="grid h-11 w-11 place-items-center rounded-full bg-navy-950 text-gold-400 transition hover:bg-gold-500 hover:text-navy-950">
                <x-shop-icon name="chevron" class="h-5 w-5 rotate-180" />
            </button>
            <div>
                <p class="text-xl font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $sec['title'] }} <span class="ms-2 text-sm font-normal text-gray-400">({{ count($sectionOrders) }})</span></p>
                <p class="mt-0.5 text-xs text-gray-400">{{ $sec['desc'] }}</p>
            </div>
        </div>

        <div class="grid gap-3.5 lg:grid-cols-2">
            @forelse ($sectionOrders as $o)
                <a href="{{ $o['url'] }}"
                   class="group rounded-2xl border border-navy-950/10 bg-white p-5 transition hover:-translate-y-0.5 hover:border-gold-500/50 hover:shadow-lg" wire:key="o-{{ $o['code'] }}">
                    <div class="flex items-center justify-between">
                        <p class="font-code font-bold tracking-wider text-navy-950 transition group-hover:text-gold-600" dir="ltr">{{ $o['code'] }}</p>
                        <span class="text-xs text-gray-400">{{ $o['date'] }}</span>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><x-shop-icon name="bag" class="h-3.5 w-3.5 text-gold-600" /> {{ $o['items'] }} عنصر</span>
                        <span class="flex items-center gap-1.5"><x-shop-icon name="card" class="h-3.5 w-3.5 text-gold-600" /> {{ $o['payment'] }}</span>
                        @if ($o['paid'])
                            <span class="flex items-center gap-1 font-bold text-[#4a634b]"><x-shop-icon name="check" class="h-3 w-3" /> قبض مؤكد</span>
                        @endif
                        @if ($o['stamped'])
                            <span class="flex items-center gap-1 font-bold text-gold-600"><x-shop-icon name="shield" class="h-3.5 w-3.5" /> معتمد</span>
                        @endif
                    </div>
                    <div class="mt-3.5 flex items-center justify-between border-t border-navy-950/6 pt-3">
                        <span class="text-lg font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ $o['total'] }}</span>
                        <span class="text-[11px] text-gray-400">{{ $o['totalSyp'] }}</span>
                        @if ($o['stamped'] || $o['paid'])
                            <span class="rounded-full bg-gold-100 px-3 py-1 text-[11px] font-extrabold text-gold-700">🧾 فاتورتي</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-3xl border-2 border-dashed border-navy-950/12 bg-sand/30 p-12 text-center text-gray-400">
                    لا طلبات في هذا القسم —
                    <a href="{{ route('home') }}" class="font-bold text-gold-600 transition hover:text-gold-700 hover:underline">ابدأ التسوق ←</a>
                </div>
            @endforelse
        </div>

        <button wire:click="closeSection"
                class="btn-outline mt-7 !py-2.5">← رجوع للأقسام</button>
    @endif
</div>
