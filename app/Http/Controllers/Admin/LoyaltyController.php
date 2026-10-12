<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\Setting;
use App\Support\Loyalty;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function settings()
    {
        $rate = Loyalty::rate();
        $block = Loyalty::block();
        $value = Loyalty::value();

        return view('admin.loyalty', [
            'enabled' => Loyalty::enabled(), 'rate' => $rate, 'block' => $block, 'value' => $value,
            'effective' => $block > 0 ? round($value * $rate / $block * 100, 1) : 0,
        ]);
    }

    public function saveSettings(Request $request)
    {
        $d = $request->validate([
            'rate'  => 'required|numeric|min:0.1|max:100',
            'block' => 'required|integer|min:10|max:100000',
            'value' => 'required|numeric|min:0.5|max:10000',
        ]);
        Setting::put('loyalty_enabled', $request->boolean('enabled') ? '1' : '0');
        Setting::put('loyalty_rate', (string) $d['rate']);
        Setting::put('loyalty_redeem_points', (string) $d['block']);
        Setting::put('loyalty_redeem_value', (string) $d['value']);

        return back()->with('ok', 'تم حفظ إعدادات برنامج الولاء');
    }

    public function customers(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $digits = preg_replace('/\D+/', '', $q);

        $customers = Customer::query()
            ->when($q !== '' && $digits !== '', fn ($x) => $x->where('phone', 'like', '%' . $digits . '%'))
            ->when($q !== '' && $digits === '', fn ($x) => $x->where('name', 'like', '%' . $q . '%'))
            ->orderByDesc('points')->limit(200)->get();

        return view('admin.customers', compact('customers', 'q'));
    }

    public function show(Customer $customer)
    {
        $msg = "مرحباً {$customer->name}، رقم بطاقة الولاء الخاصة بك في ليالي: {$customer->card_code}\n"
             . "لمعرفة نقاطك افتح: " . route('loyalty.card') . "\n(أدخل رقم جوالك ورمز البطاقة)";
        $wa = 'https://wa.me/970' . ltrim($customer->phone, '0') . '?text=' . rawurlencode($msg);

        return view('admin.customer', [
            'customer' => $customer,
            'tx'       => $customer->transactions()->latest()->limit(50)->get(),
            'wa'       => $wa,
            'block'    => Loyalty::block(),
            'value'    => Loyalty::value(),
        ]);
    }

    public function adjust(Request $request, Customer $customer)
    {
        $d = $request->validate([
            'points' => 'required|integer|not_in:0|between:-100000,100000',
            'note'   => 'required|string|max:120',
        ]);

        if ($customer->points + $d['points'] < 0) {
            return back()->withErrors(['points' => 'لا يمكن أن يصبح رصيد النقاط أقل من صفر']);
        }

        $customer->points += $d['points'];
        if ($d['points'] > 0) {
            $customer->lifetime_points += $d['points'];
        }
        $customer->save();
        LoyaltyTransaction::create(['customer_id' => $customer->id, 'type' => 'adjust', 'points' => $d['points'], 'note' => $d['note']]);

        return back()->with('ok', 'تم تعديل رصيد النقاط');
    }

    public function redeemOrder(Order $order)
    {
        [$ok, $message] = Loyalty::redeem($order);

        return $ok ? back()->with('ok', $message) : back()->withErrors(['loyalty' => $message]);
    }
}
