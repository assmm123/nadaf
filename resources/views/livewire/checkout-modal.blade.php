<div wire:key="checkout-modal-root" x-data="{ show: @js($open) }" x-effect="show = $wire.open">
    <div x-show="show" x-cloak style="display: none"
         class="fixed inset-0 z-[70] flex items-center justify-center overflow-y-auto bg-navy-950/70 p-3 backdrop-blur-sm"
         @keydown.escape.window="$wire.close()" @click.self="$wire.close()">
        <div class="relative my-6 w-full max-w-lg overflow-hidden rounded-3xl border border-gold-500/20 bg-white shadow-[0_24px_80px_rgba(14,27,42,.35)]" @click.outside="">

            {{-- الرأس --}}
            <div class="flex items-center justify-between border-b border-navy-950/8 px-5 py-4">
                <h2 class="flex items-center gap-2.5 text-base font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">
                    @if ($step === 'wallets')
                        <x-shop-icon name="card" class="h-5 w-5 text-gold-600" /> إتمام الدفع — اختر محفظتك
                    @elseif ($step === 'details')
                        <x-shop-icon name="wallet" class="h-5 w-5 text-gold-600" /> تفاصيل التحويل
                    @else
                        <x-shop-icon name="check" class="h-5 w-5 text-[#5F7A60]" /> تم بنجاح
                    @endif
                </h2>
                <button wire:click="close" class="rounded-full p-1.5 text-gray-400 transition hover:bg-sand hover:text-navy-950">
                    <x-shop-icon name="x" class="h-5 w-5" />
                </button>
            </div>

            {{-- مؤشر الخطوات --}}
            @if ($step !== 'success')
                <div class="flex items-center gap-1.5 px-5 pt-5">
                    <span class="h-1.5 flex-1 rounded-full {{ $step === 'wallets' ? 'bg-gradient-to-l from-gold-400 to-gold-600' : 'bg-gold-500' }}"></span>
                    <span class="h-1.5 flex-1 rounded-full {{ $step === 'details' ? 'bg-gradient-to-l from-gold-400 to-gold-600' : 'bg-navy-950/10' }}"></span>
                </div>
            @endif

            <div class="max-h-[75vh] overflow-y-auto p-5">

                {{-- ============ 1) شبكة المحافظ ============ --}}
                @if ($step === 'wallets')
                    {{-- ملخص المنتجات --}}
                    @php $first = $cartItems->first(); @endphp
                    @if ($first)
                        <div class="mb-5 rounded-2xl border-s-4 border-gold-500 bg-sand/45 p-4.5">
                            @if ($cartItems->count() === 1)
                                <p class="text-[15px] font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $first->product->name }}</p>
                                @if ($first->product->description)
                                    <p class="mt-1.5 line-clamp-3 text-sm leading-7 text-gray-600">{{ \Illuminate\Support\Str::limit($first->product->description, 180) }}</p>
                                @endif
                            @else
                                <p class="text-sm font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">{{ $cartItems->count() }} منتجات في طلبك</p>
                                <ul class="mt-1.5 space-y-1 text-sm text-gray-600">
                                    @foreach ($cartItems->take(3) as $item)
                                        <li>• {{ $item->product->name }} × {{ $item->qty }}</li>
                                    @endforeach
                                    @if ($cartItems->count() > 3)
                                        <li class="text-xs text-gray-400">و{{ $cartItems->count() - 3 }} أخرى...</li>
                                    @endif
                                </ul>
                            @endif
                            <div class="mt-2.5 flex items-baseline gap-2.5 border-t border-gold-500/25 pt-2.5">
                                <span class="text-xs text-gray-500">الإجمالي:</span>
                                <span class="text-lg font-extrabold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($totals['total_usd']) }}</span>
                                <span class="text-xs text-gray-400">{{ fmt_syp($totals['total_syp']) }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- طريقة الاستلام --}}
                    <div class="mb-5">
                        <p class="mb-2.5 text-sm font-bold text-navy-950">طريقة الاستلام</p>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button wire:click="$set('shipping_method', 'pickup')"
                                    class="flex items-center gap-2 rounded-2xl border-2 px-4 py-3 text-sm font-bold transition {{ $shipping_method === 'pickup' ? 'border-gold-500 bg-gold-50/60' : 'border-navy-950/10 hover:border-gold-500/50' }}">
                                <x-shop-icon name="store" class="h-4.5 w-4.5 text-gold-600" /> استلام من المحل
                            </button>
                            <button wire:click="$set('shipping_method', 'local')"
                                    class="flex items-center gap-2 rounded-2xl border-2 px-4 py-3 text-sm font-bold transition {{ $shipping_method === 'local' ? 'border-gold-500 bg-gold-50/60' : 'border-navy-950/10 hover:border-gold-500/50' }}">
                                <x-shop-icon name="truck" class="h-4.5 w-4.5 text-gold-600" /> توصيل محلي
                            </button>
                        </div>
                    </div>

                    {{-- شبكة المحافظ --}}
                    <p class="mb-3.5 text-sm font-bold text-navy-950">اختر محفظة الدفع</p>
                    <div class="grid grid-cols-2 gap-3.5 sm:grid-cols-4">
                        @foreach ($paymentMethods as $method)
                            <button wire:click="selectWallet({{ $method['id'] }})"
                                    class="group flex flex-col items-center gap-2.5 rounded-2xl border-2 border-navy-950/8 bg-white p-3.5 transition hover:-translate-y-1 hover:border-gold-500 hover:shadow-lg">
                                <span class="grid h-16 w-16 place-items-center overflow-hidden rounded-2xl bg-sand transition group-hover:bg-gold-100">
                                    @if ($method['icon_url'])
                                        <img src="{{ $method['icon_url'] }}" alt="{{ $method['name'] }}" class="h-full w-full object-contain p-2">
                                    @else
                                        <x-shop-icon name="{{ $method['icon'] }}" class="h-8 w-8 text-navy-950" />
                                    @endif
                                </span>
                                <span class="text-center text-[13px] font-bold leading-4 text-navy-950">{{ $method['name'] }}</span>
                                @if ($method['requires_proof'])
                                    <span class="text-[10px] font-bold text-gold-600">يتطلب إثباتًا</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    @error('payment_method_id') <p class="mt-2.5 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror

                    <p class="mt-5 text-center text-[11px] leading-5 text-gray-400">
                        بالنقر على المحفظة تظهر تفاصيل التحويل الخاصة بها
                    </p>
                @endif

                {{-- ============ 2) تفاصيل المحفظة ============ --}}
                @if ($step === 'details' && $selectedMethod)
                    <button wire:click="back" class="mb-4 inline-flex items-center gap-1.5 text-sm font-bold text-gold-600 transition hover:text-gold-700 hover:underline">
                        <x-shop-icon name="chevron" class="h-3.5 w-3.5 rotate-180" /> رجوع للمحافظ
                    </button>

                    {{-- رأس المحفظة --}}
                    <div class="relative mb-5 flex items-center gap-4 overflow-hidden rounded-2xl bg-navy-950 p-4.5">
                        <div class="pointer-events-none absolute inset-0 bg-damask-lines"></div>
                        <span class="relative grid h-14 w-14 shrink-0 place-items-center overflow-hidden rounded-2xl bg-ivory/10">
                            @if ($selectedMethod['icon_url'])
                                <img src="{{ $selectedMethod['icon_url'] }}" alt="" class="h-full w-full object-contain p-1.5">
                            @else
                                <x-shop-icon name="{{ $selectedMethod['icon'] }}" class="h-7 w-7 text-gold-400" />
                            @endif
                        </span>
                        <div class="relative">
                            <p class="text-base font-extrabold text-ivory" style="font-family:Almarai,Tajawal,sans-serif">{{ $selectedMethod['name'] }}</p>
                            <p class="mt-0.5 text-xs text-gold-400">{{ \App\Models\PaymentMethod::TYPES[$selectedMethod['type']][app()->getLocale()] ?? '' }}</p>
                        </div>
                    </div>

                    {{-- بيانات التحويل --}}
                    <div class="mb-5 space-y-3 rounded-2xl border border-gold-500/40 bg-sand/45 p-4.5 text-sm">
                        @if ($selectedMethod['account_name'])
                            <div class="flex justify-between"><span class="text-gray-500">اسم المستلم</span><b class="text-navy-950">{{ $selectedMethod['account_name'] }}</b></div>
                        @endif
                        @if ($selectedMethod['account_number'])
                            <div class="flex justify-between"><span class="text-gray-500">الرقم</span><b class="font-code text-navy-950" dir="ltr">{{ $selectedMethod['account_number'] }}</b></div>
                        @endif
                        @if (! empty($selectedMethod['iban']))
                            <div class="flex justify-between"><span class="text-gray-500">IBAN</span><b class="font-code tracking-[.06em] text-gold-600" dir="ltr">{{ $selectedMethod['iban'] }}</b></div>
                        @endif
                        @if ($selectedMethod['barcode_path'])
                            <img src="{{ $selectedMethod['barcode_url'] }}" alt="barcode" class="mx-auto h-28 rounded-xl border border-navy-950/10 bg-white object-contain p-1.5">
                        @endif
                        @if ($selectedMethod['instructions'])
                            <p class="border-t border-gold-500/25 pt-2.5 text-[13px] leading-7 text-gray-600">{{ $selectedMethod['instructions'] }}</p>
                        @endif
                    </div>

                    {{-- عنوان التوصيل --}}
                    @if ($shipping_method === 'local')
                        <div class="mb-5 grid gap-3.5 sm:grid-cols-3">
                            <div>
                                <label class="label !text-[13px]">المدينة *</label>
                                <input type="text" wire:model="city" class="input" placeholder="دمشق">
                                @error('city') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label !text-[13px]">العنوان *</label>
                                <input type="text" wire:model="address" class="input" placeholder="الحي — الشارع — تفاصيل">
                                @error('address') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    @endif

                    {{-- إثبات الدفع --}}
                    @if ($requiresProof)
                        <p class="mb-2.5 rounded-xl bg-gold-50 p-3 text-xs leading-6 font-bold text-gold-700">
                            بعد تحويل المبلغ: أدخل رقم الحوالة أو ارفع صورة الإيصال — لن يُصدر الكود بدونهما
                        </p>
                    @endif
                    <div class="mb-5 grid gap-3.5 sm:grid-cols-2">
                        <div>
                            <label class="label !text-[13px]">رقم الحوالة {{ $requiresProof ? '*' : '' }}</label>
                            <input type="text" wire:model="payment_reference" class="input" placeholder="8845213">
                            @error('payment_reference') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label !text-[13px]">اسم مرسل الحوالة</label>
                            <input type="text" wire:model="payment_sender_name" class="input" placeholder="الاسم كما في الحوالة">
                        </div>
                    </div>

                    {{-- الحقول المخصصة --}}
                    @if (! empty($selectedMethod['dynamic_fields']))
                        <div class="mb-5 grid gap-3.5 sm:grid-cols-2">
                            @foreach ($selectedMethod['dynamic_fields'] as $df)
                                <div>
                                    <label class="label !text-[13px]">{{ $df['label'] }}</label>
                                    @if (($df['type'] ?? 'text') === 'textarea')
                                        <textarea wire:model="dynamicData.{{ $df['label'] }}" rows="2" class="input"></textarea>
                                    @else
                                        <input type="text" wire:model="dynamicData.{{ $df['label'] }}" class="input">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-5">
                        <label class="label !text-[13px]">إيصال الدفع (صورة أو PDF)</label>
                        <input type="file" wire:model="proof" accept=".jpg,.jpeg,.png,.webp,.pdf"
                               class="block w-full cursor-pointer rounded-2xl border-2 border-dashed border-gold-500/45 bg-gold-50/40 px-4 py-3 text-sm text-gray-500 transition file:me-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-navy-950 file:px-4 file:py-2.5 file:font-bold file:text-ivory hover:border-gold-500 hover:bg-gold-50/70 hover:file:bg-gold-500 hover:file:text-navy-950">
                        @error('proof') <p class="mt-1 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-5">
                        <label class="label !text-[13px]">ملاحظات (اختياري)</label>
                        <textarea wire:model="notes" rows="2" class="input" placeholder="أي تفاصيل إضافية"></textarea>
                    </div>

                    {{-- الإجمالي + تأكيد --}}
                    <div class="mb-4 flex items-center justify-between rounded-2xl bg-navy-950 px-5 py-4">
                        <span class="text-sm font-bold text-ivory/75">المبلغ المطلوب</span>
                        <span class="text-lg font-extrabold text-gold-400" style="font-family:Almarai,Tajawal,sans-serif">{{ fmt_usd($totals['total_usd']) }}
                            <span class="text-[11px] font-normal text-ivory/50">/ {{ fmt_syp($totals['total_syp']) }}</span>
                        </span>
                    </div>
                    @error('cart') <p class="mb-3 text-xs font-bold text-[#8E2C33]">{{ $message }}</p> @enderror
                    <button wire:click="confirm" wire:loading.attr="disabled" wire:target="confirm"
                            class="btn-gold w-full !py-3.5 !text-base" wire:loading.class="opacity-60">
                        <span wire:loading.remove wire:target="confirm">✓ تأكيد الدفع</span>
                        <span wire:loading wire:target="confirm">جارٍ الإرسال...</span>
                    </button>
                @endif

                {{-- ============ 3) النجاح ============ --}}
                @if ($step === 'success')
                    <div class="relative flex flex-col items-center py-5 text-center">
                        <div class="pointer-events-none absolute inset-0 bg-damask-star opacity-50"></div>

                        <div class="relative">
                            <div class="mx-auto mb-5 grid h-16 w-16 place-items-center rounded-full bg-gold-500/12 text-gold-600 shadow-[0_0_0_8px_rgba(198,164,76,.08)]">
                                <x-shop-icon name="check" class="h-8 w-8" />
                            </div>
                            <h3 class="text-lg font-extrabold text-navy-950" style="font-family:Almarai,Tajawal,sans-serif">تم استلام طلبك بنجاح</h3>
                            <p class="mt-2.5 max-w-sm text-sm leading-7 text-gray-500">
                                شكرًا <b class="text-navy-950">{{ auth()->user()->name }}</b> لثقتك بنداف — طلبك قيد المراجعة وسيتم الرد عليك في أسرع وقت.
                            </p>

                            <p class="mt-6 text-xs tracking-widest text-gray-400">كود طلبك — احتفظ به</p>
                            <div class="mt-2.5 rounded-2xl border-2 border-dashed border-gold-500/70 bg-gold-50/60 px-8 py-4">
                                <span class="font-code text-[28px] font-bold tracking-[.14em] text-navy-950" dir="ltr">{{ $orderCode }}</span>
                            </div>

                            @if ($orderTotals)
                                <p class="mt-3 text-sm font-bold text-gold-600" style="font-family:Almarai,Tajawal,sans-serif">
                                    {{ fmt_usd($orderTotals['total_usd']) }}
                                    <span class="text-xs font-normal text-gray-400">/ {{ fmt_syp($orderTotals['total_syp']) }}</span>
                                </p>
                            @endif

                            <div class="mt-7 flex w-full flex-col gap-2.5">
                                <a href="{{ route('account.orders') }}" class="btn-primary w-full">📦 طلباتي</a>
                                <a href="{{ route('home') }}" class="btn-outline w-full">متابعة التسوق</a>
                                <button wire:click="close" class="text-xs font-bold text-gray-400 transition hover:text-navy-950">إغلاق</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
