<x-filament-panels::page>
    <div class="space-y-6" id="report-capture" wire:key="reports-page">

        {{-- الفلاتر --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4" wire:key="filters">
            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="mb-1.5 block text-xs font-bold text-gray-500">الفترة</label>
                    <select wire:model.live="period" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="today">اليوم</option>
                        <option value="yesterday">أمس</option>
                        <option value="last7">آخر 7 أيام</option>
                        <option value="last30">آخر 30 يومًا</option>
                        <option value="this_week">هذا الأسبوع</option>
                        <option value="last_week">الأسبوع الماضي</option>
                        <option value="this_month" selected>هذا الشهر</option>
                        <option value="last_month">الشهر الماضي</option>
                        <option value="custom">فترة مخصصة</option>
                    </select>
                </div>
                @if ($period === 'custom')
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-gray-500">من</label>
                        <input type="date" wire:model.live="from" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-gray-500">إلى</label>
                        <input type="date" wire:model.live="to" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                @endif
            </div>
        </div>

        <p class="text-sm font-bold text-gray-500">الفترة: {{ $rangeLabel }}</p>

        {{-- البطاقات الإحصائية --}}
        <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6" wire:key="stats">
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">عدد الطلبات</p>
                <p class="mt-1 text-xl font-extrabold">{{ $summary['ordersCount'] }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">إجمالي المبيعات</p>
                <p class="mt-1 text-xl font-extrabold text-gold-600">${{ number_format($summary['salesUsd'], 2) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">المبيعات بالليرة</p>
                <p class="mt-1 text-xl font-extrabold">{{ number_format($summary['salesSyp']) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">الأرباح المقدّرة</p>
                <p class="mt-1 text-xl font-extrabold text-green-600">${{ number_format($summary['profitUsd'], 2) }}</p>
                <p class="text-[10px] text-gray-400">حسب الأصناف المعروفة التكلفة ({{ $summary['itemsWithCost'] }} عنصر)</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">عناصر مبيعة</p>
                <p class="mt-1 text-xl font-extrabold">{{ $summary['itemsSold'] }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">متوسط قيمة الطلب</p>
                <p class="mt-1 text-xl font-extrabold">${{ number_format($summary['avgUsd'], 2) }}</p>
            </div>
        </div>

        {{-- أزرار التصدير --}}
        <div class="flex flex-wrap items-center gap-2" wire:key="exports">
            <span class="text-sm font-bold text-gray-500">تصدير التقرير:</span>
            <a href="{{ route('admin.reports.export', ['period' => $period, 'from' => $from, 'to' => $to, 'type' => 'csv']) }}"
               class="rounded-lg bg-navy-900 px-4 py-2 text-sm font-bold text-white hover:bg-navy-800">📄 CSV</a>
            <a href="{{ route('admin.reports.export', ['period' => $period, 'from' => $from, 'to' => $to, 'type' => 'xlsx']) }}"
               class="rounded-lg bg-green-600 px-4 py-2 text-sm font-bold text-white hover:bg-green-700">📊 Excel (XLSX)</a>
            <a href="{{ route('admin.reports.export', ['period' => $period, 'from' => $from, 'to' => $to, 'type' => 'pdf']) }}"
               target="_blank" rel="noopener"
               class="rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700">🖨️ PDF (طباعة)</a>
            <button type="button" id="report-export-image"
               class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600">🖼️ صورة PNG</button>
            <button type="button" id="report-export-html"
               class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-bold text-white hover:bg-sky-700">💾 HTML</button>
        </div>

        {{-- التحليل البياني: رسم يومي للمبيعات + أشرطة الأقسام --}}
        <div class="grid gap-6 xl:grid-cols-3" wire:key="charts">
            <div class="rounded-xl border border-gray-200 bg-white p-5 xl:col-span-2">
                <h3 class="mb-4 font-extrabold">📈 المبيعات اليومية ($)</h3>
                @php
                    $maxDaily = max(1, collect($daily)->max('sales'));
                @endphp
                <div class="flex h-44 items-end gap-1 overflow-x-auto pb-1">
                    @foreach ($daily as $d)
                        @php $h = max(4, round($d['sales'] / $maxDaily * 160)); @endphp
                        <div class="group relative flex min-w-[26px] flex-1 flex-col items-center justify-end" title="{{ $d['day'] }}: ${{ number_format($d['sales'], 2) }}">
                            <span class="mb-1 hidden text-[10px] font-bold text-navy-900 group-hover:block">${{ number_format($d['sales'], 0) }}</span>
                            <div class="w-full rounded-t-md bg-gradient-to-t from-gold-600 to-gold-400 transition hover:from-gold-700" style="height: {{ $h }}px"></div>
                            <span class="mt-1 rotate-0 text-[9px] text-gray-400">{{ substr($d['day'], 5) }}</span>
                        </div>
                    @endforeach
                    @if (! count($daily))
                        <p class="w-full py-10 text-center text-sm text-gray-400">لا بيانات</p>
                    @endif
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <h3 class="mb-4 font-extrabold">🥧 توزيع الأقسام</h3>
                @php
                    $totalSales = max(0.01, collect($byCategory)->sum('sales'));
                @endphp
                <div class="space-y-3">
                    @foreach ($byCategory as $cat)
                        @php $pct = round($cat['sales'] / $totalSales * 100); @endphp
                        <div>
                            <div class="mb-1 flex justify-between text-xs">
                                <span class="font-bold">{{ $cat['name'] }}</span>
                                <span class="text-gray-500">{{ $pct }}% — ${{ number_format($cat['sales'], 2) }}</span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-navy-900 to-gold-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if (! count($byCategory))
                        <p class="py-6 text-center text-sm text-gray-400">لا بيانات</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2" wire:key="tables">
            {{-- حسب القسم --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <h3 class="mb-3 font-extrabold">المبيعات حسب القسم</h3>
                @if (count($byCategory))
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-start text-xs text-gray-500">
                            <th class="py-2 text-start">القسم</th><th class="py-2">الكمية</th>
                            <th class="py-2">المبيعات</th><th class="py-2">الأرباح</th>
                        </tr></thead>
                        <tbody>
                            @foreach ($byCategory as $row)
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 font-bold">{{ $row['name'] }}</td>
                                    <td class="py-2 text-center">{{ $row['qty'] }}</td>
                                    <td class="py-2 text-center text-gold-600">${{ number_format($row['sales'], 2) }}</td>
                                    <td class="py-2 text-center text-green-600">${{ number_format($row['profit'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="py-6 text-center text-sm text-gray-400">لا مبيعات في هذه الفترة</p>
                @endif
            </div>

            {{-- أفضل المنتجات --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <h3 class="mb-3 font-extrabold">أفضل المنتجات</h3>
                @if (count($byProduct))
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-start text-xs text-gray-500">
                            <th class="py-2 text-start">المنتج</th><th class="py-2">الكمية</th>
                            <th class="py-2">المبيعات</th><th class="py-2">الربح</th><th class="py-2">الهامش</th>
                        </tr></thead>
                        <tbody>
                            @foreach ($byProduct as $row)
                                @php $margin = $row['sales'] > 0 ? round($row['profit'] / $row['sales'] * 100) : 0; @endphp
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 font-bold">{{ $row['name'] }}</td>
                                    <td class="py-2 text-center">{{ $row['qty'] }}</td>
                                    <td class="py-2 text-center text-gold-600">${{ number_format($row['sales'], 2) }}</td>
                                    <td class="py-2 text-center text-green-600">${{ number_format($row['profit'], 2) }}</td>
                                    <td class="py-2 text-center">
                                        <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $margin >= 30 ? 'bg-green-100 text-green-700' : ($margin >= 15 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $margin }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="py-6 text-center text-sm text-gray-400">لا مبيعات في هذه الفترة</p>
                @endif
            </div>
        </div>

        {{-- السجل اليومي --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5" wire:key="daily">
            <h3 class="mb-3 font-extrabold">السجل اليومي</h3>
            @if (count($daily))
                <table class="w-full text-sm">
                    <thead><tr class="border-b text-start text-xs text-gray-500">
                        <th class="py-2 text-start">التاريخ</th>
                        <th class="py-2">الطلبات</th>
                        <th class="py-2">المبيعات $</th>
                        <th class="py-2">المبيعات ل.س</th>
                    </tr></thead>
                    <tbody>
                        @foreach ($daily as $row)
                            <tr class="border-b border-gray-50">
                                <td class="py-2 font-bold" dir="ltr">{{ $row['day'] }}</td>
                                <td class="py-2 text-center">{{ $row['orders'] }}</td>
                                <td class="py-2 text-center text-gold-600">${{ number_format($row['sales'], 2) }}</td>
                                <td class="py-2 text-center">{{ number_format($row['sales_syp']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="py-6 text-center text-sm text-gray-400">لا مبيعات في هذه الفترة</p>
            @endif
        </div>
    </div>

    {{-- تصدير الصورة و HTML — يعملان على كامل التقرير المعروض --}}
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script>
        const REPORT_RANGE = @js($rangeLabel);

        document.getElementById('report-export-image')?.addEventListener('click', async function () {
            const btn = this;
            btn.disabled = true; btn.textContent = '⏳ جارٍ التجهيز...';
            try {
                const canvas = await html2canvas(document.getElementById('report-capture'), { scale: 2, backgroundColor: '#f5f6f8', useCORS: true });
                const link = document.createElement('a');
                link.download = `تقرير-${REPORT_RANGE.replace(/[^\p{L}\p{N}]+/gu, '-')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
            } catch (e) { alert('تعذر إنشاء الصورة'); }
            btn.disabled = false; btn.textContent = '🖼️ صورة PNG';
        });

        document.getElementById('report-export-html')?.addEventListener('click', function () {
            const styles = Array.from(document.querySelectorAll('style'))
                .map(s => s.outerHTML).join('\n');
            const html = `<!DOCTYPE html><html lang="ar" dir="rtl"><head><meta charset="utf-8">
<title>تقرير ${REPORT_RANGE}</title>${styles}</head>
<body>${document.getElementById('report-capture').outerHTML}</body></html>`;
            const blob = new Blob([html], { type: 'text/html;charset=utf-8' });
            const link = document.createElement('a');
            link.download = `تقرير-${REPORT_RANGE.replace(/[^\p{L}\p{N}]+/gu, '-')}.html`;
            link.href = URL.createObjectURL(blob);
            link.click();
            URL.revokeObjectURL(link.href);
        });
    </script>
</x-filament-panels::page>
