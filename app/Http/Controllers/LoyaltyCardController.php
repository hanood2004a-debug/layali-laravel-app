<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Support\Loyalty;
use Illuminate\Http\Request;

class LoyaltyCardController extends Controller
{
    public function show()
    {
        return view('loyalty.card', ['result' => null, 'enabled' => Loyalty::enabled()]);
    }

    public function lookup(Request $request)
    {
        $d = $request->validate([
            'phone' => 'required|string|max:20',
            'code'  => 'required|string|max:12',
        ], ['phone.required' => 'اكتب رقم جوالك', 'code.required' => 'اكتب رمز البطاقة']);

        $c = Customer::where('phone', Customer::normalizePhone($d['phone']))->first();

        // رسالة واحدة للحالتين حتى لا نكشف هل الرقم مسجّل
        if (!$c || !hash_equals(strtoupper($c->card_code), strtoupper(trim($d['code'])))) {
            return back()->withInput($request->only('phone'))->withErrors(['phone' => 'رقم الجوال أو رمز البطاقة غير صحيح']);
        }

        $block = Loyalty::block();
        $rewards = intdiv($c->points, $block);

        return view('loyalty.card', [
            'enabled' => Loyalty::enabled(),
            'result'  => [
                'name'      => explode(' ', trim($c->name))[0],
                'points'    => $c->points,
                'visits'    => $c->visits,
                'rewards'   => $rewards,
                'worth'     => $rewards * Loyalty::value(),
                'progress'  => round(($c->points % $block) / $block * 100),
                'remaining' => $block - ($c->points % $block),
                'tx'        => $c->transactions()->latest()->limit(10)->get(),
            ],
        ]);
    }
}
