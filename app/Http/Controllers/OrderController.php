<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\Menu;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'phone'        => 'required|string|max:30',
            'type'         => 'required|in:delivery,pickup',
            'address'      => 'required_if:type,delivery|nullable|string|max:255',
            'notes'        => 'nullable|string|max:500',
            'items'        => 'required|array|min:1|max:40',
            'items.*.id'   => 'required|string',
            'items.*.qty'  => 'required|integer|min:1|max:20',
        ]);

        // الأسعار تُحسب من السيرفر فقط ولا نثق بما يرسله المتصفح
        $catalog = Menu::catalog();
        $lines = [];
        $total = 0;
        foreach ($data['items'] as $row) {
            if (!isset($catalog[$row['id']])) {
                return response()->json(['message' => 'صنف غير موجود'], 422);
            }
            $p = $catalog[$row['id']];
            $sum = $p['price'] * $row['qty'];
            $total += $sum;
            $lines[] = ['name' => $p['name'], 'qty' => (int) $row['qty'], 'price' => $p['price'], 'sum' => $sum];
        }

        $order = Order::create([
            'name'    => $data['name'],
            'phone'   => $data['phone'],
            'type'    => $data['type'],
            'address' => $data['type'] === 'delivery' ? $data['address'] : null,
            'notes'   => $data['notes'] ?? null,
            'items'   => $lines,
            'total'   => $total,
        ]);

        $text = "طلب جديد #{$order->id}\n"
              . "الاسم: {$order->name}\nالهاتف: {$order->phone}\n"
              . ($order->type === 'delivery' ? "توصيل إلى: {$order->address}\n" : "استلام من المطعم\n")
              . "----------\n";
        foreach ($lines as $l) {
            $text .= "{$l['qty']} × {$l['name']} = {$l['sum']} ₪\n";
        }
        $text .= "----------\nالمجموع: {$total} ₪";
        if ($order->notes) {
            $text .= "\nملاحظات: {$order->notes}";
        }

        return response()->json([
            'number' => $order->id,
            'total'  => $total,
            'wa'     => 'https://wa.me/' . Menu::info()['wa'] . '?text=' . rawurlencode($text),
        ]);
    }
}
