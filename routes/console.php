<?php

use Illuminate\Support\Facades\Schedule;

// الجدولة اليومية — تُشغَّل من cron على الاستضافة أو من سكربت الدورة المحلية
Schedule::command('orders:archive')->daily()->at('02:00')->withoutOverlapping();

// أتمتة البوتات — تحتاج cron نشطًا على الاستضافة
// استقبال أوامر البوت التفاعلي كل دقيقة
Schedule::command('bot:automation poll')->everyMinute()
    ->when(fn () => \App\Models\Setting::bool('bot_polling_enabled') && \App\Models\Setting::get('telegram_command_bot_token'))
    ->withoutOverlapping();

// تقرير صباحي 9:00 ومسائي 21:00
Schedule::command('bot:automation morning')->daily()->at('09:00');
Schedule::command('bot:automation evening')->daily()->at('21:00');

// تنبيه السلات المتروكة مرتان يوميًا
Schedule::command('bot:automation abandoned')->twiceDaily(10, 18);

