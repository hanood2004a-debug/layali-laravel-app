<?php

namespace App\Support;

use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class Loyalty
{
    public static function enabled(): bool
    {
        return Setting::get('loyalty_enabled') === '1';
    }

    public static function rate(): float
    {
        return max(0.0, (float) Setting::get('loyalty_rate'));
    }

    public static function block(): int
    {
        return max(1, (int) Setting::get('loyalty_redeem_points'));
    }

    public static function value(): float
    {
        return max(0.0, (float) Setting::get('loyalty_redeem_value'));
    }

    /** منح النقاط عند تسليم الطلب (مرة واحدة فقط لكل طلب) */
    public static function award(Order $order): void
    {
        if (!self::enabled() || $order->loyalty_awarded) {
            return;
        }

        DB::transaction(function () use ($order) {
            $c = Customer::forOrder($order);
            $points = (int) floor((float) $order->total * self::rate());

            $c->points += $points;
            $c->lifetime_points += $points;
            $c->total_spent = (float) $c->total_spent + (float) $order->total;
            $c->visits += 1;
            $c->save();

            if ($points > 0) {
                LoyaltyTransaction::create(['customer_id' => $c->id, 'order_id' => $order->id, 'type' => 'earn', 'points' => $points, 'note' => 'طلب #' . $order->id]);
            }
            $order->update(['customer_id' => $c->id, 'points_earned' => $points, 'loyalty_awarded' => true]);
        });
    }

    /** سحب النقاط إذا تراجع الطلب عن حالة "تم التسليم" */
    public static function reverse(Order $order): void
    {
        if (!$order->loyalty_awarded || !$order->customer_id) {
            return;
        }

        DB::transaction(function () use ($order) {
            if ($c = Customer::find($order->customer_id)) {
                $e = (int) $order->points_earned;
                $c->points = max(0, $c->points - $e);
                $c->lifetime_points = max(0, $c->lifetime_points - $e);
                $c->total_spent = max(0, (float) $c->total_spent - (float) $order->total);
                $c->visits = max(0, $c->visits - 1);
                $c->save();

                if ($e > 0) {
                    LoyaltyTransaction::create(['customer_id' => $c->id, 'order_id' => $order->id, 'type' => 'reverse', 'points' => -$e, 'note' => 'تراجع الطلب #' . $order->id . ' عن التسليم']);
                }
            }
            $order->update(['loyalty_awarded' => false, 'points_earned' => 0]);
        });
    }

    /** إرجاع النقاط المستبدلة وإلغاء الخصم إذا أُلغي الطلب */
    public static function cancelRedemption(Order $order): void
    {
        if ($order->points_redeemed <= 0) {
            return;
        }

        DB::transaction(function () use ($order) {
            if ($c = Customer::find($order->customer_id)) {
                $c->points += $order->points_redeemed;
                $c->save();
                LoyaltyTransaction::create(['customer_id' => $c->id, 'order_id' => $order->id, 'type' => 'adjust', 'points' => $order->points_redeemed, 'note' => 'استرجاع نقاط الطلب الملغي #' . $order->id]);
            }
            $order->update(['total' => (float) $order->total + (float) $order->discount, 'discount' => 0, 'points_redeemed' => 0]);
        });
    }

    /** استبدال نقاط الزبون بخصم على الطلب (تتم من الإدارة فقط) @return array{0: bool, 1: string} */
    public static function redeem(Order $order): array
    {
        if (!self::enabled()) {
            return [false, 'برنامج الولاء متوقف حالياً'];
        }
        if (!in_array($order->status, ['new', 'preparing'], true)) {
            return [false, 'الاستبدال متاح على الطلبات الجديدة أو قيد التحضير فقط'];
        }
        if ($order->points_redeemed > 0) {
            return [false, 'تم تطبيق خصم الولاء على هذا الطلب مسبقاً'];
        }

        $customer = Customer::where('phone', Customer::normalizePhone($order->phone))->first();
        $block = self::block();
        $value = self::value();
        if (!$customer || $customer->points < $block || $value <= 0) {
            return [false, 'رصيد نقاط الزبون غير كافٍ للاستبدال'];
        }

        $discount = min($value, (float) $order->total);
        if ($discount <= 0) {
            return [false, 'لا يمكن تطبيق الخصم على هذا الطلب'];
        }
        $points = (int) ceil($discount / $value * $block);

        DB::transaction(function () use ($order, $customer, $discount, $points) {
            $customer->points -= $points;
            $customer->save();
            LoyaltyTransaction::create(['customer_id' => $customer->id, 'order_id' => $order->id, 'type' => 'redeem', 'points' => -$points, 'note' => 'خصم ' . (float) $discount . ' ₪ على الطلب #' . $order->id]);
            $order->update(['customer_id' => $customer->id, 'discount' => $discount, 'points_redeemed' => $points, 'total' => (float) $order->total - $discount]);
        });

        return [true, 'تم تطبيق خصم ' . (float) $discount . ' ₪ مقابل ' . $points . ' نقطة'];
    }
}
