@extends('admin.layout')
@section('title', 'الأقسام')
@section('content')
<h1 class="h4 mb-3">الأقسام</h1>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card stat"><div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>القسم</th><th>الترتيب</th><th>عدد الأصناف</th><th></th></tr></thead>
                <tbody>
                @foreach ($categories as $c)
                    <tr>
                        <td><strong>{{ $c->name }}</strong></td><td>{{ $c->sort }}</td><td>{{ $c->items_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('حذف القسم {{ $c->name }}؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div></div>
    </div>
    <div class="col-lg-5">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="card stat"><div class="card-body">
            @csrf
            <h2 class="h6">إضافة قسم</h2>
            <div class="mb-2"><input class="form-control" name="name" placeholder="اسم القسم" required maxlength="80"></div>
            <div class="mb-3"><input class="form-control" name="sort" type="number" min="0" placeholder="الترتيب (اختياري)"></div>
            <button class="btn btn-dark w-100">إضافة</button>
            <p class="text-muted small mt-2 mb-0">ملاحظة: الأقسام المسماة "المشروبات الساخنة" و"المشروبات الباردة" تظهر بكروت صغيرة في الموقع.</p>
        </div></form>
    </div>
</div>
@endsection
