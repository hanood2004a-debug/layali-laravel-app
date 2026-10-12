<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index()
    {
        $items = MenuItem::with('category')->orderBy('category_id')->orderBy('sort')->orderBy('id')->get();

        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.items.form', ['item' => new MenuItem(['active' => true]), 'categories' => $this->categories()]);
    }

    public function store(Request $request)
    {
        MenuItem::create($this->data($request));

        return redirect()->route('admin.items.index')->with('ok', 'تمت إضافة الصنف');
    }

    public function edit(MenuItem $item)
    {
        return view('admin.items.form', ['item' => $item, 'categories' => $this->categories()]);
    }

    public function update(Request $request, MenuItem $item)
    {
        $item->update($this->data($request, $item));

        return redirect()->route('admin.items.index')->with('ok', 'تم حفظ التعديلات');
    }

    public function destroy(MenuItem $item)
    {
        $this->deleteImage($item->image);
        $item->delete();

        return back()->with('ok', 'تم حذف الصنف');
    }

    private function categories()
    {
        return Category::orderBy('sort')->orderBy('id')->get();
    }

    private function data(Request $request, ?MenuItem $item = null): array
    {
        $v = $request->validate([
            'name'        => 'required|string|max:120',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0|max:9999',
            'description' => 'nullable|string|max:255',
            'details'     => 'nullable|string|max:1000',
            'ingredients' => 'nullable|string|max:300',
            'sort'        => 'nullable|integer|min:0|max:9999',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = [
            'name'        => $v['name'],
            'category_id' => $v['category_id'],
            'price'       => $v['price'],
            'description' => $v['description'] ?? null,
            'details'     => $v['details'] ?? null,
            'ingredients' => $v['ingredients'] ?? null,
            'sort'        => $v['sort'] ?? 0,
            'active'      => $request->boolean('active'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(20) . '.' . $file->extension();
            $file->move(public_path('images/menu'), $name);
            if ($item) {
                $this->deleteImage($item->image);
            }
            $data['image'] = 'menu/' . $name;
        }

        return $data;
    }

    // نحذف فقط الصور المرفوعة من لوحة التحكم (داخل images/menu)
    private function deleteImage(?string $path): void
    {
        if ($path && str_starts_with($path, 'menu/')) {
            @unlink(public_path('images/' . $path));
        }
    }
}
