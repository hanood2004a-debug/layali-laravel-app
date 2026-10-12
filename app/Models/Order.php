<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = [
        'new'       => ['جديد', 'warning'],
        'preparing' => ['قيد التحضير', 'info'],
        'done'      => ['تم التسليم', 'success'],
        'cancelled' => ['ملغي', 'secondary'],
    ];

    public const PAYMENT_METHODS = [
        'cash'     => 'نقداً',
        'card'     => 'بطاقة',
        'transfer' => 'حوالة / محفظة',
    ];

    protected $fillable = [
        'name', 'phone', 'type', 'address', 'notes', 'items', 'total', 'status',
        'payment_method', 'paid', 'paid_at',
        'customer_id', 'points_earned', 'points_redeemed', 'discount', 'loyalty_awarded',
    ];

    protected $casts = ['items' => 'array', 'paid' => 'boolean', 'paid_at' => 'datetime', 'loyalty_awarded' => 'boolean'];
}
