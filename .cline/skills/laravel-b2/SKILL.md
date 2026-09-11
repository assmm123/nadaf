---
name: laravel-b2
description: Develop features in this Laravel 13 + Filament 3 + Livewire 3 application (المشروع. Use when working on any PHP model/migration/controller or seeder, Blade view or Livewire component, Filament resource/widget/panel page, form validation, RTL Arabic UI, PDF generation (dompdf),, spreadsheet import/export (openspout),, or audio/media metadata handling (getid3) in this workspace.
---

# Laravel B2 — تطوير ميزات Laravel + Filament + Livewire

## 1. البنية والتقنية (وثّقها المصدر: composer.json)

| التقنية | الإصدار | الاستخدام |
|---|---|---|
| PHP | ^8.3 | اللغة الأساسية |
| laravel/framework | ^13.17 | إطار العمل |
| filament/filament | ^3.3 | لوحات الإدارة والموارد (Resources/Widgets/Pages) |
| livewire/livewire | ^3.8 | المكونات التفاعلية داخل Blade |
| barryvdh/laravel-dompdf | ^3.1 | توليد PDF |
| openspout/openspout | ^4.32 | جداول البيانات (Excel/CSV) |
| james-heinrich/getid3 | ^1.9 | بيانات الوسائط (audio/video metadata) |

## 2. الأوامر المعتمدة (من composer.json)

```sh
composer run setup   # تثبيت كامل: composer install + .env + key:generate + migrate + npm build
composer run dev      # خادم التطوير (php artisan dev)
composer run test      # config:clear + php artisan test
./vendor/bin/pint      # تنسيق الكود (Laravel Pint#
php artisan make:model X -m     # موديل + ميجريشن
php artisan make:filament-resource X  # مورد Filament (سؤال: --generate دار أم لا#
php artisan make:livewire X         # مكوّن Livewire
php artisan filament:upgrade      # ترقية بنية Filament (يُشغَّل تلقائياً بعد composer update#
```

## 3. قواعد الكود

- **موديلات Eloquent**: guarded = [] أو guarded صريح دائماً (حماية mass assignment)، العلاقات تُسمّى جمعاً للمتعدد، مفرداً للواحد؛ استخدم `casts` لكل التحويلات الأنواع; الـ `fillable` السيئ — `$fillable = ['*']` ممنوع، الأفضل `$guarded = []` مع `Form::make` في Filament.
- **الميجريشنز**: كل تغيير للبنية = ميجريشن جديد، لا تعدّل ميجريشن منشورا بعد `php artisan migrate` على أي بيئة; استخدم `foreignId()->constrained()->cascadeOnDelete()` للعلاقات; `down()` يحذف تماماً ما أنشأه `up()`.
- **Filament 3**: الموارد تتحكم بالإنشاء/التعديل عبر `Forms\Components` ونماذج `Tables\Columns`; عدّل `Resource::getPages()`, `getRelations()`, `getNavigationLabel()` بحسب السياق؛ استخدم `->rules()`, `->unique(ignoreRecord: true)`, `->required()` في حقول النماذج؛ للأعمدة المخصصة `Columns\TextColumn::make()` مع `->formatStateUsing()`.
- **Livewire 3**: المكوّن ملف واحد (`app/Livewire/*.php`) + فيو Blade (`resources/views/livewire/*.blade.php`); خاصيات عامة = حالة الواجهة; استخدم `#[Computed]` للمشتقات، و`wire:model.live` للمدخلات الفورية، و`wire:poll` للتحديث الدوري عند الحاجة.

- **RTL عربي**: كل واجهات Blade تدعم `dir="rtl"` عبر لغة القوالب (`<html lang="ar" dir="rtl">`) وخصائص CSS المنطقية (`margin-inline-start`, `padding-inline-end`...); لا تستخدم `left/right` جامدة; في Filament فعّل دعم RTL (قائمة الإعدادات والثيمات) وراجع `config/filament.php` عند الحاجة.ر	
- **المعالجة**: أخطاء تُعالج في موضعها أو تُصعَّد بوضوح; استخدم `Log::error(...)` للسياق مع `report()` عند الرغبة بالتنبيه; إدخال المستخدم يمر عبر `Form::validate()` أو `Validator` مع قواعد صريحة قبل أي استخدام.



## 4. سير العمل لميزة جديدة

1. **افهم**: اقرأ المسار الحالي (Controller/Resource/Model) والتزم بأسلوبه. ما "تم"؟ 
2. **المسار الأبسط**: ميزة → موديل+ميجريشن → Filament Resource أو Livewire component → واجهة RTL → اختبار (Pest/PHPUnit).
3. **نفّذ بشرائح عمودية**: شغّل `composer run dev` وتحقق من كل خطوة قبل الانتقال.

4. **تحقق قبل التسليم**: `composer run test` + `./vendor/bin/pint --test` + مراجعة المسار السعيد والحالات الفارغة/الخطأ.


## 5. أوامر تحقق سريعة

```sh
php artisan route:list --path=API      # استعراض المسارات
php artisan about                   # حالة التطبيق (الإصدارات والبيئة#
php artisan migrate:status         # حالة الميجريشنز
./vendor/bin/pint --test             # تنسيق الكود بلا كتابة
```

## مراجع
- التوثيق: https://laravel.com/docs/13.x و https://filamentphp.com/docs/3.x و https://livewire.laravel.com/docs/3.x
- القواعد العامة: قواعد المشروع `.cline/rules/` والمهارات العالمية في `~/.cline/skills/`.