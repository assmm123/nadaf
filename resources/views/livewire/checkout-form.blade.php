<div class="grid flex-col gap-7 lg:grid-cols-[1fr_380px] lg:items-start" wire:key="checkout-form">

    {{-- ============ النموذج ============ --}}
    <div class="flex-1 space-y-6">
        @error('general')
            <div class="rounded-2xl bg-[#8E2C33]/8 p-4 text-sm font-bold text-[#8E2C33]">{{ $message }}</div>
        @enderror
        @error('cart')
            <div class="rounded-2xl bg-[#8E2C33]/8 p-4 text-sm font-bold text-[#8E2C33]">
                {{ $message }}
                <a href="{{ route('cart.index') }}" class="ms-2 underline">{{ __('cart.title') }}</a>
            </div>
        @enderror

        {{-- 1. الاستلام --}}
        <div class="card overflow-hidden">
            <div class="flex items-center gap-3.5 bg-navy-950 px-6 py-4">
                <span class="grid h-8 w-8 place-items-center rounded-full bg-gold-500 text-sm font-extrabold text-navy-950" style="font-family:Almarai,sans-serif">1</span>
                <h3 class="font-extrabold text-ivory" style="font-family:Almarai,Tajawal,sans-serif">{{ __('checkout.shipping_method') }}</h3>
            </div>

            <div class="grid gap-3.5 p-6 sm:grid-cols-2">
                <label class="group flex cursor-pointer items-start gap-3.5 rounded-2xl border-2 border-navy-950/10 bg-white p-4.5 transition has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/60 hover:border-gold-500/50">
                    <input type="radio" value="pickup" wire:model.live="shipping_method" class="mt-1 accent-[#C6A44C]">
                    <span>
                        <span class="flex items-center gap-2 font-bold text-navy-950">
                            <x-shop-icon name="store" class="h-4.5 w-4.5 text-gold-600" /> {{ __('checkout.pickup') }}
                        </span>
                        <span class="mt-1 block text-xs leading-5 text-gray-500">{{ __('checkout.pickup_note') }}</span>
                    </span>
                </label>
                <label class="group flex cursor-pointer items-start gap-3.5 rounded-2xl border-2 border-navy-950/10 bg-white p-4.5 transition has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/60 hover:border-gold-500/50">
                    <input type="radio" value="local" wire:model.live="shipping_method" class="mt-1 accent-[#C6A44C]">
                    <span>
                        <span class="flex items-center gap-2 font-bold text-navy-950">
                            <x-shop-icon name="truck" class="h-4.5 w-4.5 text-gold-600" /> {{ __('checkout.local') }}
                        </span>
                        <span class="mt-1 block text-xs leading-5 text-gray-500">
                            {{ __('checkout.local_note') }}
                            @if (setting('shipping_enabled', true)) — {{ __('cart.shipping') }}: {{ fmt_price((float) setting('shipping_fee_usd', 0)) }} @endif
                        </span>
                    </span>
                </label>
            </div>

            @if ($shipping_method === 'local')
                <div class="grid gap-5 border-t border-navy-950/8 bg-sand/40 p-6 sm:grid-cols-3">
                    <div>
                        <label class="label">{{ __('checkout.city') }} *</label>
                        <input type="text" wire:model="city" class="input" placeholder="مثال: دمشق">
                        @error('city') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label">{{ __('checkout.address') }} *</label>
                        <textarea wire:model="address" rows="2" class="input" placeholder="الحي — الشارع — تفاصيل البناء"></textarea>
                        @error('address') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif
        </div>

        {{-- 2. الدفع --}}
        <div class="card overflow-hidden">
            <div class="flex items-center gap-3.5 bg-navy-950 px-6 py-4">
                <span class="grid h-8 w-8 place-items-center rounded-full bg-gold-500 text-sm font-extrabold text-navy-950" style="font-family:Almarai,sans-serif">2</span>
                <h3 class="font-extrabold text-ivory" style="font-family:Almarai,Tajawal,sans-serif">{{ __('checkout.payment_method') }}</h3>
            </div>

            <div class="p-6">
                @if (count($paymentMethods) === 0)
                    <p class="text-sm font-bold text-[#8E2C33]">{{ __('checkout.no_payment_methods') }}</p>
                @else
                    <div class="grid gap-3.5 sm:grid-cols-2">
                        @foreach ($paymentMethods as $method)
                            <label class="flex cursor-pointer items-center gap-3.5 rounded-2xl border-2 border-navy-950/10 bg-white p-4 transition has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/60 hover:border-gold-500/50"
                                   wire:key="pm-{{ $method['id'] }}">
                                <input type="radio" value="{{ $method['id'] }}" wire:model.live="payment_method_id" class="accent-[#C6A44C]">
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl transition {{ $payment_method_id === $method['id'] ? 'bg-gold-500 text-navy-950' : 'bg-sand text-gold-600' }}">
                                    <x-shop-icon name="{{ $method['icon'] }}" class="h-5 w-5" />
                                </span>
                                <span class="min-w-0">
                                    <span class="block truncate font-bold text-navy-950">{{ $method['name'] }}</span>
                                    <span class="block text-xs text-gray-400">{{ \App\Models\PaymentMethod::TYPES[$method['type']][app()->getLocale()] ?? $method['type'] }}</span>
                                </span>
                                @if ($method['is_default'])
                                    <span class="badge ms-auto shrink-0 bg-gold-100 text-gold-700">افتراضية</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method_id') <p class="mt-2.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror

                    {{-- لوحة تفاصيل الوسيلة المختارة --}}
                    @if ($selectedMethod)
                        <div class="mt-5 overflow-hidden rounded-2xl border border-gold-500/40" wire:key="pm-info-{{ $selectedMethod['id'] }}">
                            <div class="flex items-center gap-2.5 bg-gold-100/60 px-5 py-3">
                                <x-shop-icon name="check" class="h-4 w-4 text-gold-600" />
                                <p class="text-sm font-extrabold text-gold-700">{{ __('checkout.payment_instructions') }} — {{ $selectedMethod['name'] }}</p>
                            </div>
                            <div class="grid gap-x-7 gap-y-4 bg-sand/40 p-5 text-sm sm:grid-cols-2">
                                @if ($selectedMethod['account_name'])
                                    <div>
                                        <p class="text-xs text-gray-400">{{ __('checkout.account_holder') }}</p>
                                        <p class="font-bold text-navy-950">{{ $selectedMethod['account_name'] }}</p>
                                    </div>
                                @endif
                                @if ($selectedMethod['account_number'])
                                    <div>
                                        <p class="text-xs text-gray-400">{{ __('checkout.account_number') }}</p>
                                        <p class="font-code font-bold text-navy-950" dir="ltr">{{ $selectedMethod['account_number'] }}</p>
                                    </div>
                                @endif
                                @if (! empty($selectedMethod['iban']))
                                    <div>
                                        <p class="text-xs text-gray-400">IBAN</p>
                                        <p class="font-code font-bold tracking-[.08em] text-gold-600" dir="ltr">{{ $selectedMethod['iban'] }}</p>
                                    </div>
                                @endif
                                @if ($selectedMethod['barcode_path'])
                                    <div>
                                        <p class="mb-1.5 text-xs text-gray-400">باركود / QR</p>
                                        <img src="{{ $selectedMethod['barcode_url'] }}"
                                             alt="barcode" class="h-28 rounded-xl border border-navy-950/10 bg-white object-contain p-1.5">
                                    </div>
                                @endif
                                @if ($selectedMethod['instructions'])
                                    <div class="sm:col-span-2">
                                        <p class="text-xs text-gray-400">{{ __('checkout.payment_instructions') }}</p>
                                        <p class="leading-7 text-gray-600">{{ $selectedMethod['instructions'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- 3. إثبات الدفع --}}
        @if ($selectedMethod)
            <div class="card overflow-hidden {{ $requiresProof ? 'ring-2 ring-gold-500/60' : '' }}" wire:key="proof-card">
                <div class="flex items-center gap-3.5 px-6 py-4 {{ $requiresProof ? 'bg-gradient-to-l from-gold-400 to-gold-500 text-navy-950' : 'bg-sand/70' }}">
                    <span class="grid h-8 w-8 place-items-center rounded-full text-sm font-extrabold {{ $requiresProof ? 'bg-navy-950 text-gold-400' : 'bg-white text-navy-950' }}" style="font-family:Almarai,sans-serif">3</span>
                    <h3 class="font-extrabold" style="font-family:Almarai,Tajawal,sans-serif">{{ $requiresProof ? 'إثبات الدفع — مطلوب قبل توليد كود الطلب' : 'إثبات الدفع (اختياري)' }}</h3>
                </div>

                <div class="space-y-5 p-6">
                    @if ($requiresProof)
                        <p class="rounded-xl bg-gold-50 p-3.5 text-xs leading-6 font-bold text-gold-700">
                            بعد تحويل المبلغ: أدخل <b>رقم الحوالة</b> أو ارفع <b>صورة الإيصال (أو PDF)</b> — لن يُصدر كود الطلب دون أحدهما.
                        </p>
                    @endif

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="label">{{ $requiresProof ? 'رقم الحوالة / الإشعار *' : 'رقم الحوالة (إن وجد)' }}</label>
                            <input type="text" wire:model="payment_reference" class="input" placeholder="مثال: 8845213">
                            @error('payment_reference') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label">اسم مرسل الحوالة</label>
                            <input type="text" wire:model="payment_sender_name" class="input" placeholder="الاسم كما في الحوالة">
                            @error('payment_sender_name') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="label">إيصال الدفع (صورة أو PDF) {{ $requiresProof ? '— أو رقم الحوالة' : '' }}</label>
                        <input type="file" wire:model="proof" accept=".jpg,.jpeg,.png,.webp,.pdf"
                               class="block w-full cursor-pointer rounded-2xl border-2 border-dashed border-gold-500/45 bg-gold-50/40 px-4 py-3.5 text-sm text-gray-500 transition file:me-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-navy-950 file:px-5 file:py-2.5 file:font-bold file:text-ivory hover:border-gold-500 hover:bg-gold-50/70 hover:file:bg-gold-500 hover:file:text-navy-950">
                        @error('proof') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                        <p class="mt-1.5 text-[11px] text-gray-400">صورة أو PDF — بحد أقصى 5MB — تُحفظ بأمان وتراجعها الإدارة فقط</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- 4. الكوبون والملاحظات --}}
        <div class="card overflow-hidden">
            <div class="flex items-center gap-3.5 bg-navy-950 px-6 py-4">
                <span class="grid h-8 w-8 place-items-center rounded-full bg-gold-500 text-sm font-extrabold text-navy-950" style="font-family:Almarai,sans-serif">4</span>
                <h3 class="font-extrabold text-ivory" style="font-family:Almarai,Tajawal,sans-serif">خصومات وملاحظات</h3>
            </div>

            <div class="space-y-5 p-6">
                <div>
                    <label class="label">{{ __('checkout.coupon_code') }}</label>
                    <div class="flex max-w-md gap-2.5">
                        <input type="text" wire:model="coupon_code" class="coupon-input" placeholder="{{ __('cart.coupon_placeholder') }}">
                        <button wire:click="applyCoupon" wire:loading.attr="disabled" class="btn-outline shrink-0 !py-2.5">{{ __('cart.apply') }}</button>
                    </div>
                    @if ($couponMessage)
                        <p class="mt-2 text-xs font-bold {{ $appliedCouponId ? 'text-[#4a634b]' : 'text-[#8E2C33]' }}">{{ $couponMessage }}</p>
                    @endif
                    @error('coupon_code') <p class="mt-2 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">{{ __('account.notes') }}</label>
                    <textarea wire:model="notes" rows="2" class="input" placeholder="{{ __('checkout.notes_placeholder') }}"></textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ ملخص الطلب ============ --}}
    <aside class="lg:sticky lg:top-36">
        <div class="card-lux space-y-4 p-7 text-sm">
            <h3 class="text-lg font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ __('checkout.title') }}</h3>
            <div class="gold-rule"></div>

            <div class="max-h-56 space-y-2.5 overflow-y-auto pe-1.5">
                @foreach ($totals['items'] as $item)
                    <div class="flex items-start justify-between gap-3" wire:key="sum-{{ $item->key }}">
                        <span class="min-w-0">
                            <span class="line-clamp-1 font-bold text-navy-950">{{ $item->product->name }}</span>
                            <span class="text-xs text-gray-400">× {{ $item->qty }} @if($item->variant_label) ({{ $item->variant_label }}) @endif</span>
                        </span>
                        <span class="shrink-0 font-bold text-navy-950">{{ fmt_usd($item->line_usd) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="gold-rule"></div>

            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('cart.subtotal') }}</span>
                    <span class="font-bold text-navy-950">{{ fmt_usd($totals['subtotal_usd']) }}</span>
                </div>
                @if ($totals['discount_usd'] > 0)
                    <div class="flex justify-between text-[#4a634b]">
                        <span>{{ __('cart.discount') }}</span>
                        <span class="font-bold">-{{ fmt_usd($totals['discount_usd']) }}</span>
                    </div>
                @endif
                @if ($shipping_method === 'local')
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('cart.shipping') }}</span>
                        <span class="font-bold text-navy-950">{{ $totals['shipping_usd'] > 0 ? fmt_usd($totals['shipping_usd']) : '—' }}</span>
                    </div>
                @endif
            </div>

            <div class="gold-rule"></div>

            <div>
                <div class="flex items-baseline justify-between">
                    <span class="text-sm font-extrabold tracking-wide text-gray-500">{{ __('cart.grand_total') }}</span>
                    <span class="text-[26px] font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($totals['total_usd']) }}</span>
                </div>
                <p class="mt-1 text-end text-xs text-gray-400">{{ fmt_syp($totals['total_syp']) }}</p>
            </div>

            @if ($totals['has_wholesale'])
                <p class="badge w-full justify-center bg-gold-100 !py-1.5 text-gold-700">{{ __('checkout.wholesale_note') }}</p>
            @endif

            <button wire:click="confirm" wire:loading.attr="disabled"
                    class="btn-gold w-full !py-3.5 !text-base"
                    wire:loading.class="opacity-60">
                <span wire:loading.remove wire:target="confirm">{{ __('checkout.confirm') }}</span>
                <span wire:loading wire:target="confirm">...</span>
            </button>
            <p class="pt-1 text-center text-[11px] leading-5 text-gray-400">
                بتأكيدك الطلب سنتواصل معك في أسرع وقت لتأكيد التفاصيل — شكرًا لثقتك بنداف
            </p>
        </div>
    </aside>
</div>
