<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTransaction extends Model
{
    public const LABELS = [
        'earn'    => 'نقاط مكتسبة',
        'redeem'  => 'استبدال بخصم',
        'adjust'  => 'تعديل يدوي',
        'reverse' => 'إلغاء نقاط',
    ];

    protected $fillable = ['customer_id', 'order_id', 'type', 'points', 'note'];
}
