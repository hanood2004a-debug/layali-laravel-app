<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->range($request);

        // المبيعات = الطلبات المسلّمة فقط (تم التسليم)
        $orders = Order::where('status', 'done')
            ->whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->get();

        $sales  = (float) $orders->sum('total');
        $paid   = (float) $orders->where('paid', true)->sum('total');
        $count  = $orders->count();

        $byDay = $orders->groupBy(fn ($o) => $o->created_at->format('Y-m-d'))
            ->map(fn ($g) => ['count' => $g->count(), 'sum' => (float) $g->sum('total')])
            ->sortKeysDesc();

        $byMethod = [];
        foreach (Order::PAYMENT_METHODS as $key => $label) {
            $g = $orders->where('payment_method', $key);
            $byMethod[$key] = ['label' => $label, 'count' => $g->count(), 'sum' => (float) $g->sum('total')];
        }

        $items = [];
        foreach ($orders as $o) {
            foreach ($o->items as $l) {
                $items[$l['name']]['qty'] = ($items[$l['name']]['qty'] ?? 0) + $l['qty'];
                $items[$l['name']]['sum'] = ($items[$l['name']]['sum'] ?? 0) + $l['sum'];
            }
        }
        uasort($items, fn ($a, $b) => $b['sum'] <=> $a['sum']);

        $expenses = Expense::whereBetween('spent_at', [$from->toDateString(), $to->toDateString()])->get();
        $expTotal = (float) $expenses->sum('amount');
        $byCat    = $expenses->groupBy('category')->map(fn ($g) => (float) $g->sum('amount'))->sortDesc();

        $cashIn = (float) $orders->where('paid', true)->where('payment_method', 'cash')->sum('total');

        $presets = [
            ['اليوم', today()->toDateString(), today()->toDateString()],
            ['آخر 7 أيام', today()->subDays(6)->toDateString(), today()->toDateString()],
            ['هذا الشهر', now()->startOfMonth()->toDateString(), today()->toDateString()],
            ['الشهر الماضي', now()->subMonthNoOverflow()->startOfMonth()->toDateString(), now()->subMonthNoOverflow()->endOfMonth()->toDateString()],
        ];

        return view('admin.accounting', [
            'from' => $from, 'to' => $to, 'presets' => $presets,
            'sales' => $sales, 'paid' => $paid, 'unpaid' => $sales - $paid, 'count' => $count,
            'avg' => $count ? $sales / $count : 0,
            'byDay' => $byDay, 'byMethod' => $byMethod, 'items' => array_slice($items, 0, 15, true),
            'expTotal' => $expTotal, 'byCat' => $byCat, 'profit' => $sales - $expTotal,
            'cashIn' => $cashIn, 'cashNet' => $cashIn - $expTotal,
        ]);
    }

    private function range(Request $request): array
    {
        $to   = $this->parse($request->query('to')) ?? now();
        $from = $this->parse($request->query('from')) ?? now()->startOfMonth();
        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }
        if ($from->diffInDays($to) > 366) {
            $from = $to->copy()->subDays(366);
        }

        return [$from, $to];
    }

    private function parse(?string $value): ?Carbon
    {
        try {
            return $value ? Carbon::parse($value) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
