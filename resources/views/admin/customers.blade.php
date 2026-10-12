@extends('admin.layout')
@section('title', 'الزبائن')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">الزبائن (بطاقات الولاء)</h1>
    <form method="GET" class="d-flex gap-2">
        <input class="form-control form-control-sm" name="q" value="{{ $q }}" placeholder="بحث بالاسم أو الرقم">
        <button class="btn btn-dark btn-sm">بحث</button>
    </form>
</div>
<div class="card stat"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>الاسم</th><th>الجوال</th><th>رمز البطاقة</th><th>النقاط</th><th>الزيارات</th><th>إجمالي الإنفاق</th><th></th></tr></thead>
        <tbody>
        @forelse ($customers as $c)
            <tr>
                <td><strong>{{ $c->name }}</strong></td>
                <td dir="ltr" class="text-end">{{ $c->phone }}</td>
                <td><code>{{ $c->card_code }}</code></td>
                <td><span class="badge text-bg-warning fs-6">{{ $c->points }}</span></td>
                <td>{{ $c->visits }}</td>
                <td>{{ rtrim(rtrim(number_format($c->total_spent, 2, '.', ''), '0'), '.') }} ₪</td>
                <td><a class="btn btn-sm btn-outline-dark" href="{{ route('admin.customers.show', $c) }}">الملف</a></td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-muted">لا يوجد زبائن بعد. يظهر الزبون هنا بعد تسليم أول طلب له.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@endsection
