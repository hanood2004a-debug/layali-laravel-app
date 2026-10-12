@extends('admin.layout')
@section('title', 'الأصناف')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">الأصناف ({{ $items->count() }})</h1>
    <a href="{{ route('admin.items.create') }}" class="btn btn-dark">+ إضافة صنف</a>
</div>
<div class="card stat"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th></th><th>الصنف</th><th>القسم</th><th>السعر</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
        @forelse ($items as $i)
            <tr>
                <td><img class="thumb" src="{{ $i->image_url }}" alt=""></td>
                <td><strong>{{ $i->name }}</strong><div class="text-muted small">{{ $i->description }}</div></td>
                <td>{{ $i->category->name }}</td>
                <td>{{ $i->price + 0 }} ₪</td>
                <td>@if ($i->active)<span class="badge text-bg-success">ظاهر</span>@else<span class="badge text-bg-secondary">مخفي</span>@endif</td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.items.edit', $i) }}" class="btn btn-sm btn-outline-dark">تعديل</a>
                    <form method="POST" action="{{ route('admin.items.destroy', $i) }}" class="d-inline" onsubmit="return confirm('حذف {{ $i->name }} نهائياً؟')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">حذف</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-muted">لا توجد أصناف.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@endsection
