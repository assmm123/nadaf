<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('current_exchange_rate')) {
    function current_exchange_rate(): float
    {
        return (float) setting('exchange_rate', 15000);
    }
}

if (! function_exists('syp_from_usd')) {
    /** تحويل الدولار إلى ليرة مقرّبة لأقرب 100 */
    function syp_from_usd(float $usd): float
    {
        return round($usd * current_exchange_rate() / 100) * 100;
    }
}

if (! function_exists('fmt_usd')) {
    function fmt_usd($amount): string
    {
        return '$'.number_format((float) $amount, 2);
    }
}

if (! function_exists('fmt_syp')) {
    function fmt_syp($amount): string
    {
        return app()->getLocale() === 'en'
            ? 'SYP '.number_format((float) $amount, 0)
            : number_format((float) $amount, 0).' ل.س';
    }
}

if (! function_exists('fmt_price')) {
    /** عرض السعر بالعملة المختارة في الجلسة */
    function fmt_price(float $usd, ?float $syp = null): string
    {
        $syp = $syp ?? syp_from_usd($usd);

        return session('currency', 'usd') === 'syp' ? fmt_syp($syp) : fmt_usd($usd);
    }
}

if (! function_exists('media_duration')) {
    /** مدة ملف فيديو بالثواني عبر getID3 — 0 عند الفشل */
    function media_duration(string $absolutePath): float
    {
        try {
            $info = (new \getID3)->analyze($absolutePath);

            return (float) ($info['playtime_seconds'] ?? 0);
        } catch (Throwable) {
            return 0.0;
        }
    }
}

if (! function_exists('status_badge_class')) {
    function status_badge_class(string $status): string
    {
        return match (App\Models\Order::statusColor($status)) {
            'success' => 'bg-green-100 text-green-800',
            'danger' => 'bg-red-100 text-red-800',
            'warning' => 'bg-amber-100 text-amber-800',
            'info' => 'bg-sky-100 text-sky-800',
            'primary' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

if (! function_exists('inquiry_mode')) {
    /** وضع الاستفسار — إخفاء كامل للأسعار واستبدالها بوسائل تواصل */
    function inquiry_mode(): bool
    {
        return Setting::bool('hide_prices') || Setting::get('price_display_mode') === 'whatsapp';
    }
}

if (! function_exists('price_mode')) {
    /**
     * وضع عرض الأسعار: both | second_big | normal_big | whatsapp
     */
    function price_mode(): string
    {
        if (Setting::bool('hide_prices')) {
            return 'whatsapp';
        }

        return Setting::get('price_display_mode', 'both');
    }

    /** إظهار السعر العادي؟ */
    function show_normal_price(): bool
    {
        return in_array(price_mode(), ['both', 'normal_big'], true);
    }

    /** إظهار سعر الجملة/السعر الثاني؟ */
    function show_wholesale_price(): bool
    {
        return in_array(price_mode(), ['both', 'second_big'], true);
    }
}

if (! function_exists('whatsapp_inquiry_link')) {
    /** رابط واتساب جاهز بنص مسبق — رقم الواتس من الإعدادات، وإلا أول وسيلة واتساب مفعلة، وإلا هاتف المتجر */
    function whatsapp_inquiry_link(string $text = ''): string
    {
        $digits = preg_replace('/\D/', '', (string) setting('whatsapp_number', ''));

        if ($digits === '') {
            $method = App\Models\CommunicationMethod::where('type', 'whatsapp')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->first();
            $digits = $method ? preg_replace('/\D/', '', trim($method->value)) : '';
        }

        if ($digits === '') {
            $digits = preg_replace('/\D/', '', (string) setting('store_phone', ''));
        }

        $base = $digits !== '' ? "https://wa.me/{$digits}" : 'https://wa.me/';

        return $text !== '' ? $base.'?text='.rawurlencode($text) : $base;
    }
}
