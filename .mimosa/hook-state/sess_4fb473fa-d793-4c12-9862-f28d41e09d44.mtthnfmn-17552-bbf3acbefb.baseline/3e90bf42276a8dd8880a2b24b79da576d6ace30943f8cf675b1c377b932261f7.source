<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setMany([
            // بيانات المتجر
            'store_name_ar' => 'نداف',
            'store_name_en' => 'NADAF',
            'store_phone' => '+963 999 123 456',

            // العملة
            'exchange_rate' => '15000',

            // الشحن
            'shipping_enabled' => '1',
            'shipping_fee_usd' => '2',

            // الجملة
            'wholesale_min_quantity' => '10',
            'wholesale_min_amount_usd' => '200',

            // الاستفسار
            'inquiry_enabled_global' => '1',

            // ختم التوثيق
            'stamp_top_text' => 'متجر نداف — نداف',

            // الإشعارات
            'notify_telegram_enabled' => '0',
            'telegram_bot_token' => '',
            'telegram_chat_id' => '',
            'notify_email_enabled' => '0',
            'notify_email' => '',
        ]);
    }
}
