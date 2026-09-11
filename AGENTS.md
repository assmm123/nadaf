# NADAF Store — Agent Guidelines

## Project Context

- **Name:** NADAF (متجر نداف) — e-commerce for ties, formal sets & accessories
- **Stack:** Laravel 13.31 + Livewire 3 + Filament 3 + Tailwind CSS 4
- **PHP:** 8.3 (local path: `E:\tools\php83\php.exe`)
- **Composer:** `E:\tools\composer.phar`
- **Database:** SQLite locally (`database/database.sqlite`) → MySQL on InfinityFree hosting
- **Local server:** `E:\tools\php83\php.exe artisan serve`
- **Build:** `npm run build` (Vite + Tailwind)

## Architecture

- **Monolith** — no separate API. Frontend uses Blade + Livewire components.
- **Services layer:** `CartService`, `CheckoutService`, `StockService`, `ReportService`, `TelegramService`, `TelegramBotService`, `AbandonedCartTracker`
- **Filament admin:** `/admin` — 13 resources, 7 custom pages, 3 widgets
- **Livewire components:** `AddToCart`, `CartTable`, `CheckoutForm`, `CheckoutModal`, `HeaderCart`, `FloatingChat`, `MyOrders`
- **Settings:** Key-value table with cache (`Setting::get()` / `Setting::set()`)
- **Bilingual:** Arabic (RTL) / English — locale stored in session
- **Dual currency:** USD (base) / SYP (auto-calculated from exchange rate, fixed per order)
- **PWA:** manifest + Service Worker + offline page

## Critical Rules

1. **Never expose `.env` or secrets.** `.env` is gitignored. Use `.env.example` / `.env.production` as templates.
2. **Stock is atomic.** All stock changes go through `StockService::record()` with `lockForUpdate`.
3. **Order snapshots.** `order_items` stores full product name/price at time of purchase — never reference live product data in invoices.
4. **Exchange rate is frozen per order.** `orders.exchange_rate` is set at checkout and never changes.
5. **Notifications must not fail the order.** All Telegram/email calls are wrapped in `try/catch` with `report()`.
6. **No rating/reputation system.** This was explicitly cancelled by the owner — do not add.
7. **Tests are broken.** `php artisan test` fails because migrations don't run on `:memory:` SQLite. Fix test setup before writing new tests.

## Before Making Changes

```bash
# Verify app boots
E:\tools\php83\php.exe artisan --version

# Check routes
E:\tools\php83\php.exe artisan route:list --except-vendor

# Run tests (currently broken — fix first)
E:\tools\php83\php.exe artisan test
```

## Common Commands

```bash
# Local dev
E:\tools\php83\php.exe artisan serve

# Reset database with seeds
E:\tools\php83\php.exe artisan migrate:fresh --seed

# Build assets
npm run build

# Regenerate PWA icons
E:\tools\php83\php.exe scripts/make-icons.php
```
