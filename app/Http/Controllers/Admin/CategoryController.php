<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories', [
            'categories' => Category::withCount('items')->orderBy('sort')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:80|unique:categories,name',
            'sort' => 'nullable|integer|min:0|max:9999',
        ]);
        Category::create(['name' => $data['name'], 'sort' => $data['sort'] ?? 0]);

        return back()->with('ok', 'تمت إضافة القسم');
    }

    public function destroy(Category $category)
    {
        if ($category->items()->exists()) {
            return back()->withErrors(['category' => 'لا يمكن حذف قسم يحتوي أصنافاً، احذف أصنافه أو انقلها أولاً']);
        }
        $category->delete();

        return back()->with('ok', 'تم حذف القسم');
    }
}
