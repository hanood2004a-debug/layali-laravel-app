@extends('admin.layout')
@section('title', 'النبذة والهدف')
@section('content')
<h1 class="h4 mb-3">نبذة المطعم وهدفه</h1>
<form method="POST" action="{{ route('admin.about.update') }}" class="card stat"><div class="card-body">
    @csrf
    <div class="mb-3">
        <label class="form-label" for="about">نبذة عن المطعم</label>
        <textarea class="form-control" id="about" name="about" rows="6" maxlength="2000" required>{{ old('about', $about) }}</textarea>
        <div class="form-text">كل سطر جديد يظهر كسطر جديد في الموقع.</div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="mission">هدف المطعم</label>
        <textarea class="form-control" id="mission" name="mission" rows="4" maxlength="1000" required>{{ old('mission', $mission) }}</textarea>
    </div>
    <button class="btn btn-dark">حفظ</button>
</div></form>
@endsection
