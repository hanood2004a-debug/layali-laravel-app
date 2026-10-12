@extends('admin.layout')
@section('title', 'المصاريف')
@section('content')
@php $m = fn ($v) => rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.'); @endphp
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">المصاريف</h1>
    <form method="GET" class="d-flex gap-2 align-items-center">
        <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm" aria-label="الشهر">
        <button class="btn btn-dark btn-sm">عرض</button>
    </form>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <form method="POST" action="{{ route('admin.expenses.store') }}" class="card stat"><div class="card-body">
            @csrf
            <h2 class="h6 mb-3">إضافة مصروف</h2>
            <div class="mb-2"><label class="form-label small mb-0" for="spent_at">التاريخ</label>
                <input class="form-control" id="spent_at" type="date" name="spent_at" value="{{ old('spent_at', now()->toDateString()) }}" required></div>
            <div class="mb-2"><label class="form-label small mb-0" for="title">البيان</label>
                <input class="form-control" id="title" name="title" value="{{ old('title') }}" maxlength="120" placeholder="مثال: لحوم من المورد" required></div>
            <div class="mb-2"><label class="form-label small mb-0" for="category">النوع</label>
                <select class="form-select" id="category" name="category">
                    @foreach ($categories as $c)<option value="{{ $c }}" @selected(old('category') === $c)>{{ $c }}</option>@endforeach
                </select></div>
            <div class="mb-2"><label class="form-label small mb-0" for="amount">المبلغ (₪)</label>
                <input class="form-control" id="amount" type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" required></div>
            <div class="mb-3"><label class="form-label small mb-0" for="notes">ملاحظات</label>
                <input class="form-control" id="notes" name="notes" value="{{ old('notes') }}" maxlength="255"></div>
            <button class="btn btn-dark w-100">إضافة</button>
        </div></form>
    </div>

    <div class="col-lg-8">
        <div class="card stat"><div class="card-body">
            <div class="d-flex justify-content-between mb-2"><strong>مصاريف {{ $month }}</strong><strong class="text-danger">{{ $m($total) }} ₪</strong></div>
            <div class="table-responsive"><table class="table align-middle mb-0">
                <thead><tr><th>التاريخ</th><th>البيان</th><th>النوع</th><th>المبلغ</th><th></th></tr></thead>
                <tbody>
                @forelse ($expenses as $e)
                    <tr>
                        <td class="text-nowrap">{{ $e->spent_at->format('Y-m-d') }}</td>
                        <td>{{ $e->title }}@if ($e->notes)<div class="text-muted small">{{ $e->notes }}</div>@endif</td>
                        <td>{{ $e->category }}</td>
                        <td class="text-nowrap">{{ $m($e->amount) }} ₪</td>
                        <td>
                            <form method="POST" action="{{ route('admin.expenses.destroy', $e) }}" onsubmit="return confirm('حذف هذا المصروف؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">لا توجد مصاريف في هذا الشهر.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div></div>
    </div>
</div>
@endsection
