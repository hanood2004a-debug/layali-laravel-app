@extends('admin.layout')
@section('title', 'الطلبات')
@section('content')
<h1 class="h4 mb-3">الطلبات</h1>
<div class="mb-3 d-flex flex-wrap gap-2">
    <a class="btn btn-sm {{ $status ? 'btn-outline-dark' : 'btn-dark' }}" href="{{ route('admin.orders') }}">الكل</a>
    @foreach (\App\Models\Order::STATUSES as $k => $st)
        <a class="btn btn-sm {{ $status === $k ? 'btn-dark' : 'btn-outline-dark' }}" href="{{ route('admin.orders', ['status' => $k]) }}">{{ $st[0] }}</a>
    @endforeach
</div>

@forelse ($orders as $o)
    <div class="card stat mb-3"><div class="card-body">
        <div class="d-flex flex-wrap justify-content-between gap-2">
            <strong>طلب #{{ $o->id }} — {{ $o->name }}</strong>
            <span class="text-muted small">{{ $o->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div class="my-2">
            <a href="tel:{{ $o->phone }}">{{ $o->phone }}</a> —
            {{ $o->type === 'delivery' ? 'توصيل: ' . $o->address : 'استلام من المطعم' }}
        </div>
        <ul class="mb-2">@foreach ($o->items as $l)<li>{{ $l['qty'] }} × {{ $l['name'] }} = {{ $l['sum'] }} ₪</li>@endforeach</ul>
        @if ($o->notes)<div class="text-muted mb-2">ملاحظات: {{ $o->notes }}</div>@endif
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <strong>المجموع: {{ $o->total + 0 }} ₪</strong>
            <form method="POST" action="{{ route('admin.orders.update', $o) }}" class="d-flex gap-2">
                @csrf @method('PATCH')
                <select name="status" class="form-select form-select-sm">
                    @foreach (\App\Models\Order::STATUSES as $k => $st)
                        <option value="{{ $k }}" @selected($o->status === $k)>{{ $st[0] }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-dark text-nowrap">حفظ</button>
            </form>
        </div>
        @php $cust = $customers[\App\Models\Customer::normalizePhone($o->phone)] ?? null; @endphp
        @if ($loyalty['enabled'])
            <div class="small mt-2 d-flex flex-wrap align-items-center gap-2">
                @if ($o->discount > 0)<span class="badge text-bg-success">خصم الولاء: − {{ $o->discount + 0 }} ₪ ({{ $o->points_redeemed }} نقطة)</span>@endif
                @if ($cust)
                    <span class="text-muted">نقاط الزبون: <strong>{{ $cust->points }}</strong> — <a href="{{ route('admin.customers.show', $cust) }}">ملفه</a></span>
                    @if (in_array($o->status, ['new', 'preparing']) && $o->points_redeemed == 0 && $cust->points >= $loyalty['block'])
                        <form method="POST" action="{{ route('admin.orders.redeem', $o) }}" onsubmit="return confirm('تطبيق خصم الولاء على هذا الطلب؟')">@csrf<button class="btn btn-sm btn-outline-success">استبدال النقاط بخصم</button></form>
                    @endif
                @else
                    <span class="text-muted">زبون جديد — تُنشأ بطاقته عند التسليم</span>
                @endif
            </div>
        @endif
        <form method="POST" action="{{ route('admin.orders.payment', $o) }}" class="d-flex flex-wrap align-items-center gap-2 mt-2 pt-2 border-top">
            @csrf @method('PATCH')
            <span class="small text-muted">الدفع:</span>
            <select name="payment_method" class="form-select form-select-sm" style="width:auto">
                @foreach (\App\Models\Order::PAYMENT_METHODS as $k => $label)
                    <option value="{{ $k }}" @selected($o->payment_method === $k)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="hidden" name="paid" value="0">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="paid" value="1" id="paid{{ $o->id }}" @checked($o->paid)>
                <label class="form-check-label small" for="paid{{ $o->id }}">مدفوع</label>
            </div>
            <button class="btn btn-sm btn-outline-dark">حفظ الدفع</button>
        </form>
    </div></div>
@empty
    <p class="text-muted">لا توجد طلبات.</p>
@endforelse
@endsection
