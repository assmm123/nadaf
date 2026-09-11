<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ $order->stamped_at ? 'فاتورة نظامية معتمدة' : 'إيصال طلب' }} — {{ $order->order_code }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Tajawal', 'Poppins', sans-serif; color: #1a2a3a; padding: 28px; max-width: 800px; margin: 0 auto; background: #eef1f5; }
        .doc { background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 14px rgba(26,42,58,.08); }

        /* ===== الهيدر: شكلان مختلفان كليًا ===== */
        .head { padding: 22px 30px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .head.draft { background: #f8f5ef; border-bottom: 2px dashed #c8d2dc; }
        .head.certified { background: linear-gradient(120deg, #1a2a3a 0%, #22354a 55%, #2e4560 100%); border-bottom: 3px solid #c9a84c; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand .name { font-size: 22px; font-weight: 800; letter-spacing: 3px; }
        .brand .sub { font-size: 11px; margin-top: 2px; }
        .head.draft .brand .name { color: #1a2a3a; }
        .head.draft .brand .sub { color: #6b7a89; }
        .head.certified .brand .name { color: #fff; }
        .head.certified .brand .sub { color: #c9a84c; }
        .doc-type { text-align: center; }
        .doc-type .t1 { font-size: 16px; font-weight: 800; padding: 6px 18px; border-radius: 30px; display: inline-block; }
        .head.draft .doc-type .t1 { background: #eef1f5; color: #6b7a89; border: 1.5px dashed #9fb0c0; }
        .head.certified .doc-type .t1 { background: linear-gradient(120deg, #c9a84c, #e8cd8a); color: #1a2a3a; border: none; box-shadow: 0 2px 8px rgba(0,0,0,.25); }
        .doc-type .t2 { font-size: 11px; margin-top: 5px; }
        .head.draft .doc-type .t2 { color: #9fb0c0; }
        .head.certified .doc-type .t2 { color: #c9a84c; }

        .banner { padding: 9px 30px; font-size: 12.5px; font-weight: 700; text-align: center; }
        .banner.draft { background: #eef1f5; color: #6b7a89; border-bottom: 1px solid #dde4ec; }
        .banner.certified { background: linear-gradient(90deg, #f3ead3, #faf5e6, #f3ead3); color: #8f6b25; border-bottom: 1px solid #e3d5ac; }

        .code-box { text-align: center; }
        .code-box .lbl { font-size: 11px; }
        .code-box b { font-size: 20px; letter-spacing: 2px; }
        .head.draft .code-box .lbl { color: #9fb0c0; } .head.draft .code-box b { color: #1a2a3a; }
        .head.certified .code-box .lbl { color: #c9a84c; } .head.certified .code-box b { color: #fff; }

        /* ===== الجسم ===== */
        .body { padding: 22px 30px; }
        .meta { display: grid; grid-template-columns: 1fr 1fr; gap: 7px 24px; font-size: 13px; background: #f8f5ef; padding: 14px 16px; border-radius: 10px; }
        .meta span { color: #6b7a89; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e4e9ef; padding: 9px 12px; text-align: start; font-size: 13.5px; }
        th { font-weight: 800; }
        th { background: #f2f5f8; }
        .doc.certified th { background: #f3ead3; }
        .totals { margin-top: 16px; margin-inline-start: auto; width: 285px; }
        .totals div { display: flex; justify-content: space-between; padding: 5px 0; font-size: 14px; }
        .totals .grand { border-top: 2px solid #1a2a3a; font-weight: 800; font-size: 16px; }
        .doc.certified .totals .grand span:last-child { color: #a8873a; }

        /* ===== الفوتر + الختم ===== */
        .foot-row { margin-top: 22px; display: flex; justify-content: space-between; align-items: flex-end; gap: 14px; }
        .foot { flex: 1; text-align: center; font-size: 12px; color: #6b7a89; border-top: 1px solid #e4e9ef; padding-top: 12px; }
        .stamp-holder { text-align: center; width: 185px; display: flex; flex-direction: column; align-items: center; gap: 10px; }
        .stamp-by { font-size: 10px; color: #6b7a89; margin-top: 2px; }
        .stamp-label { font-size: 11px; font-weight: 800; padding: 2px 12px; border-radius: 20px; }
        .stamp-label.green { background: #e8f5ee; color: #177a4a; border: 1px solid #bfe3d0; }
        .stamp-label.gold { background: #f8f1dd; color: #8f6b25; border: 1px solid #e2cd93; }
        .draft-note { margin-top: 14px; text-align: center; font-size: 11px; color: #9fb0c0; }
        .proof { display: inline-block; margin-top: 6px; background: #e8f5ee; color: #177a4a; border: 1px solid #bfe3d0; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }

        .toolbar { text-align: center; margin-bottom: 18px; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; }
        .toolbar button, .toolbar a { display: inline-block; border: 0; padding: 10px 22px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; font-family: inherit; }
        .btn-print { background: #1a2a3a; color: #fff; }
        .btn-image { background: #c9a84c; color: #1a2a3a; }
        .btn-pdf { background: #e8edf3; color: #1a2a3a; border: 1.5px solid #c8d2dc !important; }
        .btn-wa { background: #25d366; color: #fff; }
        .toolbar button:disabled { opacity: .6; cursor: wait; }
        @media print { .toolbar { display: none; } body { padding: 0; background: #fff; } .doc { box-shadow: none; border-radius: 0; } }
    </style>
</head>
<body>

    <div class="toolbar">
        <button class="btn-print" onclick="window.print()">🖨️ طباعة</button>
        <button class="btn-pdf" id="download-pdf">📄 تحميل PDF</button>
        <button class="btn-image" id="download-image">🖼️ تحميل صورة</button>
        <button class="btn-wa" id="send-whatsapp">💬 إرسال عبر واتساب</button>
    </div>

    @php
        $stamped = (bool) $order->stamped_at;
        // إعدادات محرر الفاتورة — يتحكم بها الأدمن من الإعدادات
        $showLogo = setting('invoice_show_logo', true);
        $showGreen = setting('invoice_show_green_stamp', true);
        $showGold = setting('invoice_show_gold_stamp', true);
        $titleDraft = setting('invoice_title_draft', 'إيصال طلب');
        $titleCertified = setting('invoice_title_certified', 'فاتورة نظامية معتمدة');
        $subtitle = setting('invoice_subtitle', 'كرافات وأطقم رسمية وإكسسوارات');
        $greenLabel = setting('invoice_green_stamp_label', '✓ تم قبض الدفع');
        $goldLabel = setting('invoice_gold_stamp_label', 'فاتورة نظامية معتمدة');
        $greenPos = setting('invoice_green_stamp_position', 'bottom-left');
        $goldPos = setting('invoice_gold_stamp_position', 'bottom-left');
        $greenSize = (int) setting('invoice_green_stamp_size', 140);
        $goldSize = (int) setting('invoice_gold_stamp_size', 170);
        $footerNote = setting('invoice_footer_note', 'شكرًا لتسوقكم من متجر نداف — جميع الحقوق محفوظة');
        $colorPrimary = setting('invoice_color_primary', '#1a2a3a');
        $colorAccent = setting('invoice_color_accent', '#c9a84c');
        $borderStyle = $stamped ? 'border-bottom: 3px solid '.$colorAccent.';' : 'border-bottom: 2px dashed #c8d2dc;';
    @endphp

    {{-- ألوان مخصصة من محرر الفاتورة (تتجاوز CSS الافتراضي) --}}
    <style>
        .doc { --brand: {{ $colorPrimary }}; --accent: {{ $colorAccent }}; }
        .head.certified { background: linear-gradient(120deg, {{ $colorPrimary }} 0%, {{ $colorPrimary }} 55%, {{ $colorPrimary }}e6 100%); {{ $borderStyle }} }
        .head.certified .brand .sub, .head.certified .doc-type .t2 { color: {{ $colorAccent }}; }
        .doc-type .t1.certified-badge { background: linear-gradient(120deg, {{ $colorAccent }}, {{ $colorAccent }}e0); }
        .banner.certified { background: linear-gradient(120deg, {{ $colorAccent }}22, {{ $colorAccent }}11); border-color: {{ $colorAccent }}; color: {{ $colorPrimary }}; }
        .grand { color: {{ $colorPrimary }}; }
        .stamp-label.gold { background: {{ $colorAccent }}22; color: {{ $colorPrimary }}; border-color: {{ $colorAccent }}; }
    </style>

    <div class="doc {{ $stamped ? 'certified' : 'draft' }}" id="invoice">
        <div class="head {{ $stamped ? 'certified' : 'draft' }}">
            <div class="brand">
                @if ($showLogo)
                    <img src="{{ asset('favicon.svg') }}" alt="" style="height:46px;">
                @endif
                <div class="name">
                    {{ strtoupper(setting('store_name_en', 'NADAF')) }}
                    <div class="sub">{{ setting('store_name_ar', 'نداف') }} — {{ $subtitle }}</div>
                </div>
            </div>
            <div class="doc-type">
                <span class="t1 {{ $stamped ? 'certified-badge' : '' }}">{{ $stamped ? $titleCertified : $titleDraft }}</span>
                <div class="t2">{{ $stamped ? 'وثيقة رسمية بختم التوثيق' : 'وثيقة مبدئية — قيد المراجعة' }}</div>
            </div>
            <div class="code-box">
                <div class="lbl">كود الطلب</div>
                <b>{{ $order->order_code }}</b>
            </div>
        </div>

        <div class="banner {{ $stamped ? 'certified' : 'draft' }}">
            @if ($stamped)
                ✓ اعتُمد هذا الطلب وخُتم بختم التوثيق الرسمي بتاريخ {{ $order->stamped_at->format('Y/m/d — H:i') }}{{ $order->stampedBy ? ' بواسطة '.$order->stampedBy->name : '' }}
            @else
                هذا إيصال مبدئي لطلبك — ستتحول تلقائيًا إلى فاتورة نظامية معتمدة بختم التوثيق بعد اعتماد الإدارة للطلب
            @endif
        </div>

        <div class="body">
            <div class="meta">
                <div><span>التاريخ والوقت:</span> {{ $order->created_at->format('Y/m/d — H:i') }}</div>
                <div><span>الحالة:</span> {{ \App\Models\Order::statusLabel($order->status) }}</div>
                <div><span>اسم العميل:</span> {{ $order->user->name }}</div>
                <div><span>هاتف العميل:</span> {{ $order->user->phone }}</div>
                <div><span>طريقة الاستلام:</span> {{ $order->shipping_method === 'local' ? 'توصيل محلي' : 'استلام من المحل' }}</div>
                <div><span>طريقة الدفع:</span> {{ $order->paymentMethod?->name ?? '—' }}</div>
                @if ($order->city)
                    <div><span>المدينة:</span> {{ $order->city }}</div>
                @endif
                @if ($order->payment_reference)
                    <div><span>رقم الحوالة:</span> {{ $order->payment_reference }}</div>
                @endif
                @if ($order->payment_sender_name)
                    <div><span>اسم مرسل الحوالة:</span> {{ $order->payment_sender_name }}</div>
                @endif
                @if ($order->shipping_address)
                    <div style="grid-column: span 2;"><span>العنوان:</span> {{ $order->shipping_address }}</div>
                @endif
                @if ($order->notes)
                    <div style="grid-column: span 2;"><span>ملاحظات:</span> {{ $order->notes }}</div>
                @endif
                @if ($order->payment_proof_path)
                    <div style="grid-column: span 2;"><span class="proof">✓ إثبات الدفع مرفق مع الطلب</span></div>
                @endif
            </div>

            <table>
                <thead>
                    <tr><th>#</th><th>المنتج</th><th>الكمية</th><th>سعر الوحدة</th><th>المجموع</th></tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->displayName() }} @if($item->is_wholesale) <small style="color:#c9a84c;">(جملة)</small> @endif</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ fmt_usd($item->unit_price_usd) }}</td>
                            <td>{{ fmt_usd($item->total_price_usd) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <div><span>المجموع الفرعي</span><span>{{ fmt_usd($order->subtotal_usd) }}</span></div>
                @if ($order->discount_usd > 0)
                    <div><span>الخصم</span><span>-{{ fmt_usd($order->discount_usd) }}</span></div>
                @endif
                @if ($order->shipping_usd > 0)
                    <div><span>الشحن</span><span>{{ fmt_usd($order->shipping_usd) }}</span></div>
                @endif
                <div class="grand"><span>الإجمالي</span><span>{{ fmt_usd($order->total_usd) }}</span></div>
                <div style="font-size:12px; color:#6b7a89;"><span></span><span>{{ fmt_syp($order->total_syp) }} <span style="opacity:.7;">(1$ = {{ number_format($order->exchange_rate, 0) }})</span></span></div>
            </div>

            {{-- الأختام: موضع كل ختم على حدة من محرر الفاتورة --}}
            @php
                $posStyle = fn (string $pos) => match ($pos) {
                    'bottom-center' => 'margin-inline: auto;',
                    'bottom-right' => 'margin-inline-start: auto; margin-inline-end: 0;',
                    default => '',
                };
                $anyStamp = ($showGreen && $order->payment_confirmed_at) || ($showGold && $stamped);
            @endphp
            @if ($anyStamp)
                <div class="stamp-holder" style="{{ $posStyle($goldPos === 'bottom-center' ? 'bottom-center' : 'bottom-left') }}">
                    @if ($showGreen && $order->payment_confirmed_at)
                        <div style="{{ $posStyle($greenPos) }}">
                            <span class="stamp-label green">{{ $greenLabel }}</span>
                            <x-stamp
                                variant="green"
                                :bottomText="$order->payment_confirmed_at->format('Y/m/d — H:i')"
                                :size="$greenSize"
                                :image="setting('stamp_green_path')"
                            />
                            @if ($order->paymentConfirmedBy)
                                <p class="stamp-by">بواسطة: {{ $order->paymentConfirmedBy->name }}</p>
                            @endif
                        </div>
                    @endif
                    @if ($showGold && $stamped)
                        <div style="{{ $posStyle($goldPos) }}">
                            <span class="stamp-label gold">{{ $goldLabel }}</span>
                            <x-stamp
                                :topText="setting('stamp_top_text', setting('store_name_ar', 'نداف'))"
                                :bottomText="$order->stamped_at->format('Y/m/d — H:i')"
                                :size="$goldSize"
                                :image="setting('stamp_gold_path')"
                            />
                            @if ($order->stampedBy)
                                <p class="stamp-by">ختم بواسطة: {{ $order->stampedBy->name }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="foot-row">
                <div class="foot">
                    {{ $footerNote }} — {{ setting('store_phone') }} © {{ date('Y') }}
                </div>
            </div>

            @unless ($stamped)
                <p class="draft-note">تُصدر هذه الوثيقة آليًا عند استلام الطلب — لا تُعد إثبات دفع ولا سند استلام نهائي</p>
            @endunless
        </div>
    </div>

    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
    <script>
        const WA_LINK = @js($waLink);
        const ORDER_CODE = @js($order->order_code);

        /** توليد صورة الفاتورة عالية الدقة (مشتركة بين الأزرار) */
        async function renderInvoice() {
            return await html2canvas(document.getElementById('invoice'), { scale: 2, backgroundColor: '#ffffff', useCORS: true });
        }

        function downloadCanvas(canvas, filename) {
            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // 🖼️ تحميل صورة PNG
        document.getElementById('download-image').addEventListener('click', async function () {
            const btn = this;
            btn.disabled = true; btn.textContent = '⏳ جارٍ التجهيز...';
            try {
                downloadCanvas(await renderInvoice(), `فاتورة-${ORDER_CODE}.png`);
            } catch (e) { alert('تعذر إنشاء الصورة — استخدم زر الطباعة واختر "حفظ كـ PDF"'); }
            btn.disabled = false; btn.textContent = '🖼️ تحميل صورة';
        });

        // 📄 تحميل PDF حقيقي (صورة الفاتورة داخل صفحة A4)
        document.getElementById('download-pdf').addEventListener('click', async function () {
            const btn = this;
            btn.disabled = true; btn.textContent = '⏳ جارٍ التجهيز...';
            try {
                const canvas = await renderInvoice();
                const img = canvas.toDataURL('image/jpeg', 0.95);
                const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                const pw = 210, ph = 297, margin = 8;
                const iw = pw - margin * 2;
                const ih = canvas.height * iw / canvas.width;
                pdf.addImage(img, 'JPEG', margin, margin, iw, Math.min(ih, ph - margin * 2));
                pdf.save(`فاتورة-${ORDER_CODE}.pdf`);
            } catch (e) { alert('تعذر إنشاء PDF — استخدم زر الطباعة واختر "حفظ كـ PDF"'); }
            btn.disabled = false; btn.textContent = '📄 تحميل PDF';
        });

        // 💬 واتساب — صورة الفاتورة تُنسخ للحافظة (تُلصق مباشرة في المحادثة) وتُنزَّل كنسخة احتياطية
        document.getElementById('send-whatsapp').addEventListener('click', async function () {
            const btn = this;
            btn.disabled = true; btn.textContent = '⏳ جارٍ التجهيز...';
            let copied = false;
            try {
                const canvas = await renderInvoice();
                try {
                    const blob = await new Promise(r => canvas.toBlob(r, 'image/png'));
                    await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
                    copied = true;
                } catch (e) {}
                downloadCanvas(canvas, `فاتورة-${ORDER_CODE}.png`);
            } catch (e) {
                alert('تعذر إنشاء الصورة — استخدم زر الطباعة واختر "حفظ كـ PDF"');
                btn.disabled = false; btn.textContent = '💬 إرسال عبر واتساب';
                return;
            }
            alert(copied
                ? '✓ نُسخت صورة الفاتورة إلى الحافظة\nالصقها في محادثة واتساب (Ctrl+V) بعد فتحها'
                : 'نُزّلت صورة الفاتورة على جهازك\nأرفقها يدويًا في محادثة واتساب بعد فتحها');
            window.open(WA_LINK, '_blank', 'noopener');
            btn.disabled = false; btn.textContent = '💬 إرسال عبر واتساب';
        });
    </script>
</body>
</html>
