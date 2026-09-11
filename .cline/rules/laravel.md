# Laravel / Filament — قواعد مشروع B2

هذه القواعد مكملة للمعايير الهندسية العامة والقواعد العامة (في `~/.cline/rules/`). تسري على أي عمل داخل `E:\B2\المشروع`.

## Commands (دقيقة وجاهزة)
- التثبيت الكامل: `composer run setup`
- التطوير: `composer run dev`
- الاختبارات: `composer run test`
- تنسيق الكود: `./vendor/bin/pint`
- مراجعة التنسيق فقط: `./vendor/bin/pint --test`
- إنشاء موديل + ميجريشن: `php artisan make:model X -m`
- إنشاء مورد Filament: `php artisan make:filament-resource X`
- إنشاء مكوّن Livewire: `php artisan make:livewire X`

## Conventions
- PHP 8.3 + Laravel 13 + Filament 3 + Livewire 3 (راجع `composer.json` للتفاصيل).
- الموديلات: `$guarded = []` مع تحقق من نماذج Filament / `Validator`؛ لا `$fillable = ['*']`.
- كل تغيير للبنية = ميجريشن جديد؛ لا تعديل ميجريشن منشور.

- الواجهات العربية RTL: `<html lang="ar" dir="rtl">` + خصائص CSS المنطقية؛ لا `left/right` جامدة.
- الأسماء: تعبّر عن الغرض (`retryDelaySeconds` لا `x`؛) النمط العربي في التعليقات مسموح، وأسماء الكود بالإنجليزية.

- استعلامات Eloquent مُعاملة؛ لا `DB::raw` مع إدخال مستخدم بدون bindings (استخدم `->whereRaw(..., [$v])` عند الاضطرار).
- الأسرار في `.env` فقط؛ `.env.example` محدّث بكل متغير جديد .

## Boundaries
- لا تعدّل ملفات `vendor/` ولا `node_modules/` أبداً.

- لا تنفّذ `composer update` إلا بطلب صريح (يكفي `composer require` أو `composer install`).
- لا تلمس `database/database.sqlite` يدوياً; استخدم الميجريشنز.



## Testing
- قبل التسليم: `composer run test` تنجح كل الاختبارات + `./vendor/bin/pint --test` بلا مخالفات.
- كل إصلاح خطأ يأتي مع اختبار يمنع عودته (Pest/PHPUnit في `tests/`).