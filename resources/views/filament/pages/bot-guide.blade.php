<x-filament-panels::page>
    <div class="mx-auto max-w-3xl space-y-6" wire:key="bot-guide">

        {{-- الحالة --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="mb-3 font-extrabold">🔌 حالة الاتصال</h2>
            <div class="grid gap-2.5 text-sm">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                    <span>التوكن الأساسي</span>
                    <span class="font-bold {{ $tokenExists ? 'text-green-600' : 'text-red-500' }}">{{ $tokenExists ? '✓ موجود' : '✗ غير موجود — أضفه من الإعدادات' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                    <span>الاستقصاء (استقبال أوامرك)</span>
                    <span class="font-bold {{ $pollingEnabled ? 'text-green-600' : 'text-amber-600' }}">{{ $pollingEnabled ? '✓ مفعّل' : 'معطّل — فعّله من الإعدادات' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2">
                    <span>chat_id المدير (حماية الأوامر)</span>
                    <span class="font-bold {{ $adminChatId ? 'text-green-600' : 'text-amber-600' }}">{{ $adminChatId ?: 'غير محدد' }}</span>
                </div>
                @if ($botUsername)
                    <div class="flex items-center justify-between rounded-lg bg-gold-100 px-3 py-2">
                        <span>افتح البوت في تيليجرام:</span>
                        <a href="https://t.me/{{ $botUsername }}" target="_blank" rel="noopener" class="font-extrabold text-gold-700 hover:underline">@{{ $botUsername }} ←</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- أوامر الجرد --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="mb-1 font-extrabold">📦 أوامر المخزون والجرد</h2>
            <p class="mb-3 text-xs text-gray-400">أرسل الكلمة في أي وقت — البوت يجيب فورًا</p>
            <div class="space-y-2 text-sm">
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">جرد</code>
                    <span>قائمة المخزون كاملة مرتبة من الأقل للكثير (15 صنف بالصفحة)</span>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">جرد 2</code>
                    <span>الصفحة الثانية من الجرد</span>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">منخفض</code>
                    <span>الأصناف التي بلغت حد التنبيه — مع اقتراح الشراء</span>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">نفد</code>
                    <span>الأصناف التي رصيدها صفر</span>
                </div>
            </div>
        </div>

        {{-- أوامر المبيعات --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="mb-1 font-extrabold">💰 أوامر المبيعات والطلبات</h2>
            <div class="space-y-2 text-sm">
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">مبيعات</code>
                    <span>مبيعات اليوم: الطلبات والمبالغ والأرباح</span>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">مبيعات أسبوع</code>
                    <span>أو «مبيعات شهر» — تقرير الفترة</span>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">طلبات</code>
                    <span>آخر 5 طلبات بحالاتها</span>
                </div>
            </div>
        </div>

        {{-- أوامر التعديل --}}
        <div class="rounded-2xl border border-gold-300 bg-gold-50 p-5">
            <h2 class="mb-1 font-extrabold text-gold-700">✏️ التعديل الآمن — بنظام المسودة</h2>
            <p class="mb-3 text-xs text-gold-600">كل تعديل يمر بمسودة تأكيد، ويُوثق في سجل حركات المخزون تلقائيًا</p>
            <div class="space-y-2 text-sm">
                <div class="rounded-lg bg-white px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">كمية 25 LEA05-BRN-L</code>
                    <p class="mt-1 text-xs text-gray-500">يُنشئ مسودة: «كمية هذا المتغير من 4 إلى 25» — ينتظر تأكيدك</p>
                </div>
                <div class="rounded-lg bg-white px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">سعر 12.5 CRV-01</code>
                    <p class="mt-1 text-xs text-gray-500">مسودة تعديل سعر المنتج</p>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-white px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">تأكيد</code>
                    <span>يطبق التعديل — أو</span>
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">إلغاء</code>
                    <span>للتراجع</span>
                </div>
                <p class="text-xs text-gray-400">⏱ المسودة تنتهي بعد 10 دقائق دون تأكيد</p>
            </div>
        </div>

        {{-- البحث --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="mb-1 font-extrabold">🔍 البحث</h2>
            <div class="space-y-2 text-sm">
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">كرافات</code>
                    <span>ابحث بالاسم — يعرض المخزون والسعر والSKU لكل متغير</span>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 rounded-lg bg-gray-50 px-3 py-2">
                    <code class="rounded bg-navy-900 px-2 py-0.5 text-xs font-bold text-gold-400">LEA05</code>
                    <span>أو ابحث بالكود الداخلي / SKU</span>
                </div>
            </div>
        </div>

        {{-- نصائح --}}
        <div class="rounded-2xl border border-gray-200 bg-navy-900 p-5 text-white">
            <h2 class="mb-2 font-extrabold text-gold-400">💡 نصائح مهمة</h2>
            <ul class="space-y-1.5 text-sm leading-7 opacity-90">
                <li>• الأوامر مقصورة عليك وح — أي شخص آخر يرسل للبوت يرى رفضًا</li>
                <li>• تعديل الكمية عبر البوت يُوثق كحركة «تعديل» في سجل المخزون باسم البوت</li>
                <li>• التقارير الصباحية تصلك 9:00 صباحًا والمسائية 9:00 مساءً تلقائيًا (إن فعلتها من الإعدادات)</li>
                <li>• إن لم يرد البوت: تأكد أن «استقبال الأوامر» مفعّل وأن المجدول يعمل</li>
            </ul>
        </div>
    </div>
</x-filament-panels::page>
