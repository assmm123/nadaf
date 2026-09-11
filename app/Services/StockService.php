<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * تسجيل حركة مخزون ذرية: تعدل الرصيد وتوثّق الحركة بالرصيد قبل/بعد.
     *
     * @param  int  $delta  موجب = دخول (استلام/إرجاع)، سالب = خروج (بيع)
     */
    public static function record(
        int $variantId,
        string $type,
        int $delta,
        ?int $orderId = null,
        ?int $purchaseInvoiceId = null,
        ?string $note = null,
    ): StockMovement {
        return DB::transaction(function () use ($variantId, $type, $delta, $orderId, $purchaseInvoiceId, $note) {
            $variant = ProductVariant::whereKey($variantId)->lockForUpdate()->firstOrFail();

            $before = (int) $variant->quantity;
            $after = $before + $delta;

            $variant->update(['quantity' => $after]);

            return StockMovement::create([
                'variant_id' => $variantId,
                'type' => $type,
                'quantity' => $delta,
                'balance_before' => $before,
                'balance_after' => $after,
                'order_id' => $orderId,
                'purchase_invoice_id' => $purchaseInvoiceId,
                'user_id' => auth()->id(),
                'note' => $note,
            ]);
        });
    }

    /** فحص انخفاض المخزون بعد عملية بيع — يرسل تنبيه تيليجرام لقناة الجرد */
    public static function checkLowStock(ProductVariant $variant): void
    {
        if ($variant->quantity <= $variant->low_stock_threshold) {
            try {
                TelegramService::lowStockAlert($variant);
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
