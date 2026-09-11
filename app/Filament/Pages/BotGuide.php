<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

/** دليل البوت التفاعلي — تعليمات كاملة بالأوامر وأمثلة الحالات */
class BotGuide extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?int $navigationSort = 12;

    protected static string $view = 'filament.pages.bot-guide';

    public function mount(): void
    {
        if (! Setting::get('bot_username')) {
            // اكتشف اسم مستخدم البوت تلقائيًا مرة واحدة
            $token = Setting::get('telegram_bot_token');
            if ($token) {
                try {
                    $res = \Illuminate\Support\Facades\Http::timeout(8)
                        ->get("https://api.telegram.org/bot{$token}/getMe")->json();
                    if ($res['ok'] ?? false) {
                        Setting::set('bot_username', $res['result']['username'] ?? '');
                    }
                } catch (\Throwable) {
                }
            }
        }
    }

    public static function getNavigationLabel(): string
    {
        return 'دليل البوت التفاعلي';
    }

    public function getTitle(): string
    {
        return 'دليل البوت التفاعلي — قائمة الأوامر';
    }

    public function botUsername(): ?string
    {
        return Setting::get('bot_username');
    }

    protected function getViewData(): array
    {
        return [
            'botUsername' => $this->botUsername(),
            'pollingEnabled' => Setting::bool('bot_polling_enabled'),
            'tokenExists' => (bool) Setting::get('telegram_bot_token'),
            'adminChatId' => Setting::get('telegram_admin_chat_id'),
        ];
    }
}
