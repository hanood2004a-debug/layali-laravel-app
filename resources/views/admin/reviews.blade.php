@extends('admin.layout')
@section('title', 'التقييمات')
@section('content')
<h1 class="h4 mb-3">آراء الزبائن وتقييماتهم</h1>
<p class="text-muted">التقييمات الجديدة لا تظهر في الموقع إلا بعد اعتمادك لها.</p>
@forelse ($reviews as $r)
    <div class="card stat mb-3"><div class="card-body">
        <div class="d-flex flex-wrap justify-content-between gap-2">
            <div>
                <strong>{{ $r->name }}</strong>
                <span class="text-muted">على</span> <strong>{{ $r->item->name ?? 'صنف محذوف' }}</strong>
                <span style="color:#d6b478;letter-spacing:2px">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}</span>
            </div>
            <div>
                @if ($r->approved)<span class="badge text-bg-success">معتمد</span>@else<span class="badge text-bg-warning">بانتظار الاعتماد</span>@endif
                <span class="text-muted small">{{ $r->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>
        @if ($r->comment)<p class="my-2">{{ $r->comment }}</p>@endif
        <div class="d-flex gap-2 mt-2">
            @unless ($r->approved)
                <form method="POST" action="{{ route('admin.reviews.approve', $r) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-dark">اعتماد</button></form>
            @endunless
            <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" onsubmit="return confirm('حذف هذا التقييم؟')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">حذف</button></form>
        </div>
    </div></div>
@empty
    <p class="text-muted">لا توجد تقييمات بعد.</p>
@endforelse
@endsection
