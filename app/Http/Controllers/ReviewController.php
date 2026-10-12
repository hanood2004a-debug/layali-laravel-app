<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // حقل مخفي للروبوتات: إذا تعبّأ نتجاهل الطلب بصمت
        if ($request->filled('website')) {
            return response()->json(['message' => 'شكراً لك!']);
        }

        $data = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'name'         => 'required|string|min:2|max:60',
            'rating'       => 'required|integer|between:1,5',
            'comment'      => 'nullable|string|max:500',
        ], [
            'name.required'   => 'اكتب اسمك',
            'name.min'        => 'الاسم قصير جداً',
            'rating.required' => 'اختر عدد النجوم',
            'rating.between'  => 'التقييم من 1 إلى 5',
            'comment.max'     => 'الرأي طويل جداً (500 حرف كحد أقصى)',
        ]);

        $item = MenuItem::where('id', $data['menu_item_id'])->where('active', true)->firstOrFail();

        // منع تكرار التقييم لنفس الصنف من نفس الجهاز خلال 24 ساعة (نخزّن بصمة مشفّرة وليس الـ IP)
        $hash = hash('sha256', $request->ip() . config('app.key'));
        $recent = Review::where('menu_item_id', $item->id)->where('ip_hash', $hash)
            ->where('created_at', '>=', now()->subDay())->exists();
        if ($recent) {
            return response()->json(['message' => 'لقد أرسلت تقييماً لهذا الصنف مؤخراً، شكراً لك'], 429);
        }

        Review::create([
            'menu_item_id' => $item->id,
            'name'         => $data['name'],
            'rating'       => $data['rating'],
            'comment'      => $data['comment'] ?? null,
            'approved'     => false,
            'ip_hash'      => $hash,
        ]);

        return response()->json(['message' => 'شكراً لك! سيظهر رأيك بعد مراجعته من الإدارة.']);
    }
}
