@extends('admin.layout')
@section('title', 'برنامج الولاء')
@section('content')
<h1 class="h4 mb-3">إعدادات برنامج الولاء</h1>
<div class="row g-3">
    <div class="col-lg-6">
        <form method="POST" action="{{ route('admin.loyalty.save') }}" class="card stat"><div class="card-body">
            @csrf
            <div class="form-check form-switch mb-3">
                <input type="hidden" name="enabled" value="0">
                <input class="form-check-input" type="checkbox" role="switch" id="enabled" name="enabled" value="1" @checked(old('enabled', $enabled))>
                <label class="form-check-label" for="enabled">تفعيل برنامج الولاء</label>
            </div>
            <div class="mb-3">
                <label class="form-label" for="rate">عدد النقاط مقابل كل 1 ₪</label>
                <input class="form-control" id="rate" name="rate" type="number" step="0.1" min="0.1" max="100" value="{{ old('rate', $rate + 0) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="block">عدد النقاط اللازمة للمكافأة الواحدة</label>
                <input class="form-control" id="block" name="block" type="number" min="10" value="{{ old('block', $block) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="value">قيمة الخصم لكل مكافأة (₪)</label>
                <input class="form-control" id="value" name="value" type="number" step="0.5" min="0.5" value="{{ old('value', $value + 0) }}" required>
            </div>
            <button class="btn btn-dark">حفظ</button>
        </div></form>
    </div>
    <div class="col-lg-6">
        <div class="card stat"><div class="card-body">
            <h2 class="h6">كيف يعمل؟</h2>
            <ul class="mb-3">
                <li>تُضاف النقاط تلقائياً <strong>بعد تسليم الطلب</strong> على رقم جوال الزبون.</li>
                <li>الاستبدال يتم من صفحة <strong>الطلبات</strong> بزر "استبدال النقاط بخصم" وبموافقة الزبون.</li>
                <li>يطّلع الزبون على نقاطه من صفحة <strong>بطاقة الولاء</strong> برقم جواله ورمز بطاقته السري.</li>
            </ul>
            <div class="alert alert-info mb-0">
                العائد الفعلي للزبون حسب الإعدادات الحالية ≈ <strong>{{ $effective }}%</strong> من قيمة مشترياته.
                <div class="small mt-1">(الأرقام الشائعة في المطاعم بين 3% و 7%.)</div>
            </div>
        </div></div>
    </div>
</div>
@endsection
