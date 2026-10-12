@extends('admin.layout')
@section('title', 'الرئيسية')
@section('content')
<div class="row g-3 mb-4">
    @foreach ([['الطلبات الجديدة', $stats['new'], 'text-warning'], ['طلبات اليوم', $stats['today'], ''], ['إجمالي الطلبات', $stats['orders'], ''], ['قيمة الطلبات (₪)', number_format($stats['revenue'], 0), 'text-success'], ['عدد الأصناف', $stats['items'], '']] as $s)
        <div class="col-6 col-lg">
            <div class="card stat"><div class="card-body text-center">
                <div class="text-muted small">{{ $s[0] }}</div>
                <div class="fs-2 fw-bold {{ $s[2] }}">{{ $s[1] }}</div>
            </div></div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card stat h-100"><div class="card-body">
            <h2 class="h6 mb-3">الطلبات خلال آخر 7 أيام</h2>
            @php $max = max(1, collect($days)->max('count')); @endphp
            <div class="d-flex align-items-end gap-2" style="height:160px">
                @foreach ($days as $d)
                    <div class="flex-fill text-center d-flex flex-column justify-content-end" style="height:100%">
                        <small class="fw-bold">{{ $d['count'] }}</small>
                        <div class="bar" style="height: {{ round($d['count'] / $max * 120) }}px"></div>
                        <small class="text-muted">{{ $d['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </div></div>
    </div>
    <div class="col-lg-5">
        <div class="card stat h-100"><div class="card-body">
            <h2 class="h6 mb-3">الأكثر طلباً</h2>
            @forelse ($top as $name => $qty)
                <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $name }}</span><strong>{{ $qty }}</strong></div>
            @empty
                <p class="text-muted mb-0">لا توجد بيانات بعد.</p>
            @endforelse
        </div></div>
    </div>
</div>

<div class="card stat"><div class="card-body">
    <div class="d-flex justify-content-between mb-2"><h2 class="h6 mb-0">آخر الطلبات</h2><a href="{{ route('admin.orders') }}">عرض الكل</a></div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>#</th><th>الاسم</th><th>الهاتف</th><th>المجموع</th><th>الحالة</th><th>الوقت</th></tr></thead>
            <tbody>
            @forelse ($recent as $o)
                <tr>
                    <td>{{ $o->id }}</td><td>{{ $o->name }}</td><td>{{ $o->phone }}</td><td>{{ $o->total + 0 }} ₪</td>
                    <td><span class="badge text-bg-{{ \App\Models\Order::STATUSES[$o->status][1] ?? 'secondary' }}">{{ \App\Models\Order::STATUSES[$o->status][0] ?? $o->status }}</span></td>
                    <td class="text-muted small">{{ $o->created_at->format('m/d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">لا توجد طلبات بعد.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div></div>
@endsection
