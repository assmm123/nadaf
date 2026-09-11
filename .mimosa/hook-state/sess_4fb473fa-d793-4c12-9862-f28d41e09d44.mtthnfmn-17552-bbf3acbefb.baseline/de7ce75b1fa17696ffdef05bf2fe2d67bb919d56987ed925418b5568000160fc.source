<?php

namespace App\Http\Controllers;

use App\Models\Order;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        $order->load(['items', 'user', 'paymentMethod']);

        return view('admin.invoice', [
            'order' => $order,
            'waLink' => self::whatsappLink($order),
        ]);
    }

    /** نص الفاتورة المنسق للواتساب + رابط جاهز للإرسال */
    public static function whatsappLink(Order $order): string
    {
        $storeName = setting('store_name_ar', 'نداف');
        $lines = [
            "🧾 *فاتورة طلب — {$storeName}*",
            '━━━━━━━━━━━━━',
            "كود الطلب: *{$order->order_code}*",
            'التاريخ: '.$order->created_at->format('Y/m/d H:i'),
            "العميل: {$order->user->name}",
            '━━━━━━━━━━━━━',
        ];

        $i = 1;
        foreach ($order->items as $item) {
            $lines[] = "{$i}) {$item->displayName()} × {$item->quantity} = ".fmt_usd($item->total_price_usd);
            $i++;
        }

        $lines[] = '━━━━━━━━━━━━━';
        if ($order->discount_usd > 0) {
            $lines[] = 'الخصم: '.fmt_usd($order->discount_usd);
        }
        if ($order->shipping_usd > 0) {
            $lines[] = 'الشحن: '.fmt_usd($order->shipping_usd);
        }
        $lines[] = "*الإجمالي: ".fmt_usd($order->total_usd)." / ".fmt_syp($order->total_syp).'*';
        if ($order->paymentMethod) {
            $lines[] = 'طريقة الدفع: '.$order->paymentMethod->name;
        }
        if ($order->payment_reference) {
            $lines[] = 'رقم الحوالة: '.$order->payment_reference;
        }
        $lines[] = 'الحالة: '.Order::statusLabel($order->status);
        $lines[] = '━━━━━━━━━━━━━';
        $lines[] = "شكرًا لتسوقكم من متجر {$storeName} 💛";

        $phone = preg_replace('/\D/', '', $order->user->phone ?? '');

        return $phone
            ? "https://wa.me/{$phone}?text=".rawurlencode(implode("\n", $lines))
            : 'https://wa.me/?text='.rawurlencode(implode("\n", $lines));
    }
}
