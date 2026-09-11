<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'code',
        'supplier_id',
        'status',
        'total_cost',
        'notes',
        'created_by',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateCode(): string
    {
        $last = static::max('id') ?: 0;

        return 'PI-'.str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === 'draft' && $this->items()->exists();
    }
}
