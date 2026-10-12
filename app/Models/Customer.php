<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['phone', 'name', 'card_code', 'points', 'lifetime_points', 'total_spent', 'visits'];

    public function transactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    /** يوحّد صيغ الأرقام: +970598..., 00970598..., 598..., 0598-... كلها تصبح 0598xxxxxx */
    public static function normalizePhone(?string $phone): string
    {
        $d = preg_replace('/\D+/', '', (string) $phone);
        foreach (['00970', '00972', '970', '972'] as $prefix) {
            if (str_starts_with($d, $prefix)) {
                $d = substr($d, strlen($prefix));
                break;
            }
        }

        return ($d !== '' && $d[0] !== '0') ? '0' . $d : $d;
    }

    public static function newCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // بدون أحرف متشابهة (O/0, I/1)
        do {
            $code = 'LY-';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (static::where('card_code', $code)->exists());

        return $code;
    }

    public static function forOrder(Order $order): self
    {
        return static::firstOrCreate(
            ['phone' => self::normalizePhone($order->phone)],
            ['name' => $order->name, 'card_code' => self::newCode()]
        );
    }
}
