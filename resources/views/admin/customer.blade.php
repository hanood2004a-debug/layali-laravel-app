@extends('admin.layout')
@section('title', $customer->name)
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">{{ $customer->name }} <small class="text-muted" dir="ltr">{{ $customer->phone }}</small></h1>
    <div class="d-flex gap-2">
        <a class="btn btn-success btn-sm" href="{{ $wa }}" target="_blank" rel="noopener">إرسال البطاقة عبر واتساب</a>
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.customers') }}">رجوع</a>
    </div>
</div>

<div class="row g-3 mb-3">
    @foreach ([['رصيد النقاط', $customer->points], ['قيمة النقاط', (intdiv($customer->points, $block) * $value) . ' ₪'], ['الزيارات', $customer->visits], ['رمز البطاقة', $customer->card_code]] as $s)
        <div class="col-6 col-lg-3"><div class="card stat"><div class="card-body text-center">
            <div class="text-muted small">{{ $s[0] }}</div><div class="fs-4 fw-bold">{{ $s[1] }}</div>
        </div></div></div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <form method="POST" action="{{ route('admin.customers.adjust', $customer) }}" class="card stat"><div class="card-body">
            @csrf
            <h2 class="h6 mb-3">تعديل الرصيد يدوياً</h2>
            <div class="mb-2"><label class="form-label small mb-0" for="points">النقاط (موجب للإضافة، سالب للخصم)</label>
                <input class="form-control" id="points" name="points" type="number" value="{{ old('points') }}" required></div>
            <div class="mb-3"><label class="form-label small mb-0" for="note">السبب</label>
                <input class="form-control" id="note" name="note" maxlength="120" value="{{ old('note') }}" required placeholder="مثال: هدية عيد ميلاد"></div>
            <button class="btn btn-dark w-100">حفظ</button>
        </div></form>
    </div>
    <div class="col-lg-8">
        <div class="card stat"><div class="card-body">
            <h2 class="h6 mb-3">سجل النقاط</h2>
            <div class="table-responsive"><table class="table table-sm align-middle mb-0">
                <thead><tr><th>التاريخ</th><th>النوع</th><th>النقاط</th><th>ملاحظة</th></tr></thead>
                <tbody>
                @forelse ($tx as $t)
                    <tr>
                        <td class="text-nowrap">{{ $t->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ \App\Models\LoyaltyTransaction::LABELS[$t->type] ?? $t->type }}</td>
                        <td class="fw-bold {{ $t->points >= 0 ? 'text-success' : 'text-danger' }}" dir="ltr">{{ $t->points > 0 ? '+' : '' }}{{ $t->points }}</td>
                        <td>{{ $t->note }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">لا توجد حركات بعد.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div></div>
    </div>
</div>
@endsection
