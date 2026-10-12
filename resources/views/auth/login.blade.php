@extends('auth.layout')
@section('title', 'دخول الإدارة')
@section('content')
<form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label" for="email">البريد الإلكتروني</label>
        <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">كلمة المرور</label>
        <input class="form-control" id="password" name="password" type="password" required>
    </div>
    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="rem"><label class="form-check-label" for="rem">تذكّرني</label></div>
    <button class="btn btn-dark w-100">دخول</button>
</form>
@endsection
