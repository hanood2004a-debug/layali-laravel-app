<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Customer;
use App\Models\Order;
use App\Support\Loyalty;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'orders'  => Order::count(),
            'today'   => Order::whereDate('created_at', today())->count(),
            'new'     => Order::where('status', 'new')->count(),
            'revenue' => (float) Order::where('status', '!=', 'cancelled')->sum('total'),
            'items'   => MenuItem::count(),
        ];

        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = today()->subDays($i);
            $days[] = ['label' => $d->format('m/d'), 'count' => Order::whereDate('created_at', $d)->count()];
        }

        $top = [];
        foreach (Order::where('status', '!=', 'cancelled')->latest()->limit(300)->get() as $o) {
            foreach ($o->items as $l) {
                $top[$l['name']] = ($top[$l['name']] ?? 0) + $l['qty'];
            }
        }
        arsort($top);

        return view('admin.dashboard', [
            'stats'  => $stats,
            'days'   => $days,
            'top'    => array_slice($top, 0, 5, true),
            'recent' => Order::latest()->limit(8)->get(),
        ]);
    }

    // يستدعيه المتصفح كل 15 ثانية للتنبيه بالطلبات الجديدة
    public function poll()
    {
        return response()->json([
            'latest' => (int) Order::max('id'),
            'new'    => Order::where('status', 'new')->count(),
        ]);
    }

    public function updatePayment(Request $request, Order $order)
    {
        $data = $request->validate(['payment_method' => 'required|in:' . implode(',', array_keys(Order::PAYMENT_METHODS))]);
        $paid = $request->boolean('paid');
        $order->update([
            'payment_method' => $data['payment_method'],
            'paid'           => $paid,
            'paid_at'        => $paid ? ($order->paid_at ?? now()) : null,
        ]);

        return back()->with('ok', "تم تحديث الدفع للطلب #{$order->id}");
    }

    public function orders(Request $request)
    {
        $status = $request->query('status');
        $orders = Order::when(isset(Order::STATUSES[$status]), fn ($q) => $q->where('status', $status))
            ->latest()->limit(100)->get();

        $customers = Customer::whereIn('phone', $orders->map(fn ($o) => Customer::normalizePhone($o->phone))->unique()->values())->get()->keyBy('phone');
        $loyalty = ['enabled' => Loyalty::enabled(), 'block' => Loyalty::block(), 'value' => Loyalty::value()];

        return view('admin.orders', compact('orders', 'status', 'customers', 'loyalty'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $data = $request->validate(['status' => 'required|in:' . implode(',', array_keys(Order::STATUSES))]);
        $was = $order->status;
        $order->update($data);

        // نقاط الولاء: تُمنح عند التسليم وتُسحب لو تراجع الطلب عن "تم التسليم"
        if ($was !== 'done' && $data['status'] === 'done') {
            Loyalty::award($order);
        } elseif ($was === 'done' && $data['status'] !== 'done') {
            Loyalty::reverse($order);
        }
        if ($data['status'] === 'cancelled') {
            Loyalty::cancelRedemption($order);
        }

        // عند التسليم نعتبر الطلب مدفوعاً (دفع عند الاستلام) ما لم يكن مسجلاً مسبقاً
        if ($data['status'] === 'done' && !$order->paid) {
            $order->update(['paid' => true, 'paid_at' => now()]);
        }

        return back()->with('ok', "تم تحديث حالة الطلب #{$order->id}");
    }
}
