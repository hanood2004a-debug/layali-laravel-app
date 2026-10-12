@extends('admin.layout')
@section('title', 'المبيعات والحسابات')
@section('content')
@php $m = fn ($v) => rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.'); @endphp
<style>@media print { .adm-nav, .no-print, #soundBtn { display:none !important; } body { background:#fff; } .stat { box-shadow:none !important; border:1px solid #ddd !important; } }</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">المبيعات والحسابات</h1>
    <button type="button" class="btn btn-outline-dark btn-sm no-print" onclick="window.print()">طباعة التقرير</button>
</div>

<form method="GET" class="card stat mb-3 no-print"><div class="card-body d-flex flex-wrap gap-2 align-items-end">
    <div><label class="form-label small mb-0" for="from">من</label><input type="date" id="from" name="from" class="form-control form-control-sm" value="{{ $from->toDateString() }}"></div>
    <div><label class="form-label small mb-0" for="to">إلى</label><input type="date" id="to" name="to" class="form-control form-control-sm" value="{{ $to->toDateString() }}"></div>
    <button class="btn btn-dark btn-sm">عرض</button>
    <div class="me-auto d-flex gap-1 flex-wrap">
        @foreach ($presets as $p)
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.accounting', ['from' => $p[1], 'to' => $p[2]]) }}">{{ $p[0] }}</a>
        @endforeach
    </div>
</div></form>

<p class="text-muted small">الفترة: {{ $from->toDateString() }} إلى {{ $to->toDateString() }} — المبيعات تُحتسب من الطلبات بحالة «تم التسليم» فقط.</p>

<div class="row g-3 mb-4">
    @foreach ([
        ['المبيعات', $m($sales) . ' ₪', ''],
        ['عدد الطلبات', $count, ''],
        ['متوسط الطلب', $m($avg) . ' ₪', ''],
        ['المقبوض', $m($paid) . ' ₪', 'text-success'],
        ['غير المقبوض', $m($unpaid) . ' ₪', $unpaid > 0 ? 'text-danger' : ''],
        ['المصاريف', $m($expTotal) . ' ₪', 'text-danger'],
        ['الربح (المبيعات − المصاريف)', $m($profit) . ' ₪', $profit >= 0 ? 'text-success' : 'text-danger'],
    ] as $c)
        <div class="col-6 col-lg-3"><div class="card stat h-100"><div class="card-body text-center">
            <div class="text-muted small">{{ $c[0] }}</div><div class="fs-4 fw-bold {{ $c[2] }}">{{ $c[1] }}</div>
        </div></div></div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6"><div class="card stat h-100"><div class="card-body">
        <h2 class="h6 mb-3">المبيعات حسب اليوم</h2>
        <div class="table-responsive"><table class="table table-sm align-middle mb-0">
            <thead><tr><th>التاريخ</th><th>الطلبات</th><th>المبيعات</th></tr></thead>
            <tbody>
            @forelse ($byDay as $day => $d)
                <tr><td>{{ $day }}</td><td>{{ $d['count'] }}</td><td>{{ $m($d['sum']) }} ₪</td></tr>
            @empty
                <tr><td colspan="3" class="text-muted">لا توجد مبيعات في هذه الفترة.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div></div></div>

    <div class="col-lg-6"><div class="card stat h-100"><div class="card-body">
        <h2 class="h6 mb-3">حسب طريقة الدفع</h2>
        <table class="table table-sm mb-4"><thead><tr><th>الطريقة</th><th>الطلبات</th><th>المبلغ</th></tr></thead><tbody>
            @foreach ($byMethod as $row)<tr><td>{{ $row['label'] }}</td><td>{{ $row['count'] }}</td><td>{{ $m($row['sum']) }} ₪</td></tr>@endforeach
        </tbody></table>

        <h2 class="h6 mb-3">ملخص الصندوق النقدي</h2>
        <table class="table table-sm mb-0"><tbody>
            <tr><td>مقبوض نقداً</td><td>{{ $m($cashIn) }} ₪</td></tr>
            <tr><td>المصاريف</td><td>− {{ $m($expTotal) }} ₪</td></tr>
            <tr class="fw-bold"><td>صافي النقد</td><td class="{{ $cashNet >= 0 ? 'text-success' : 'text-danger' }}">{{ $m($cashNet) }} ₪</td></tr>
        </tbody></table>
    </div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-7"><div class="card stat h-100"><div class="card-body">
        <h2 class="h6 mb-3">الأصناف الأعلى مبيعاً (حسب القيمة)</h2>
        <div class="table-responsive"><table class="table table-sm align-middle mb-0">
            <thead><tr><th>الصنف</th><th>الكمية</th><th>المبيعات</th></tr></thead>
            <tbody>
            @forelse ($items as $name => $i)
                <tr><td>{{ $name }}</td><td>{{ $i['qty'] }}</td><td>{{ $m($i['sum']) }} ₪</td></tr>
            @empty
                <tr><td colspan="3" class="text-muted">لا توجد بيانات.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div></div></div>

    <div class="col-lg-5"><div class="card stat h-100"><div class="card-body">
        <div class="d-flex justify-content-between mb-3"><h2 class="h6 mb-0">المصاريف حسب النوع</h2><a href="{{ route('admin.expenses') }}" class="small no-print">إدارة المصاريف</a></div>
        <table class="table table-sm mb-0"><tbody>
            @forelse ($byCat as $cat => $sum)
                <tr><td>{{ $cat }}</td><td>{{ $m($sum) }} ₪</td></tr>
            @empty
                <tr><td class="text-muted">لا توجد مصاريف في هذه الفترة.</td></tr>
            @endforelse
        </tbody></table>
    </div></div></div>
</div>
@endsection
