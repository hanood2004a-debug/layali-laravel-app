<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('item')->orderBy('approved')->latest()->limit(200)->get();

        return view('admin.reviews', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['approved' => true]);

        return back()->with('ok', 'تم اعتماد التقييم وظهر في الموقع');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('ok', 'تم حذف التقييم');
    }
}
