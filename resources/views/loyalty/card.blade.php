@extends('auth.layout')
@section('title', 'بطاقة الولاء')
@section('content')
@if (!$enabled)
    <p class="text-center text-muted mb-3">برنامج الولاء متوقف حالياً.</p>
@endif

@if ($result)
    <div class="text-center mb-3">
        <div class="text-muted">أهلاً {{ $result['name'] }}</div>
        <div class="display-4 fw-bold" style="color:#a67c3d">{{ $result['points'] }}</div>
        <div class="text-muted">نقطة — {{ $result['visits'] }} زيارة</div>
    </div>
    <div class="mb-1 d-flex justify-content-between small"><span>التقدم نحو المكافأة القادمة</span><span>{{ $result['progress'] }}%</span></div>
    <div class="progress mb-2" role="progressbar" aria-valuenow="{{ $result['progress'] }}" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar" style="width: {{ $result['progress'] }}%; background:#d6b478"></div>
    </div>
    <p class="small text-muted">تبقّى لك {{ $result['remaining'] }} نقطة للمكافأة التالية.
        @if ($result['rewards'] > 0) لديك الآن {{ $result['rewards'] }} مكافأة بقيمة {{ $result['worth'] + 0 }} ₪ — اطلب الاستبدال عند طلبك التالي. @endif</p>

    @if ($result['tx']->count())
        <h2 class="h6 mt-3">آخر الحركات</h2>
        <ul class="list-group mb-3">
            @foreach ($result['tx'] as $t)
                <li class="list-group-item d-flex justify-content-between small">
                    <span>{{ \App\Models\LoyaltyTransaction::LABELS[$t->type] ?? $t->type }} <span class="text-muted">· {{ $t->created_at->format('Y-m-d') }}</span></span>
                    <strong dir="ltr" class="{{ $t->points >= 0 ? 'text-success' : 'text-danger' }}">{{ $t->points > 0 ? '+' : '' }}{{ $t->points }}</strong>
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('loyalty.card') }}" class="btn btn-outline-dark w-100 mb-2">بحث جديد</a>
@else
    <form method="POST" action="{{ route('loyalty.lookup') }}">
        @csrf
        <p class="text-muted text-center">اكتب رقم جوالك ورمز بطاقتك لمعرفة نقاطك.</p>
        <div class="mb-3"><label class="form-label" for="phone">رقم الجوال</label>
            <input class="form-control" id="phone" name="phone" inputmode="tel" value="{{ old('phone') }}" required autofocus></div>
        <div class="mb-3"><label class="form-label" for="code">رمز البطاقة</label>
            <input class="form-control" id="code" name="code" placeholder="LY-XXXXXX" maxlength="12" style="direction:ltr;text-transform:uppercase" required></div>
        <button class="btn btn-dark w-100">عرض نقاطي</button>
    </form>
@endif
<p class="text-center small mt-3 mb-0"><a href="{{ route('home') }}">العودة للموقع</a></p>
@endsection
