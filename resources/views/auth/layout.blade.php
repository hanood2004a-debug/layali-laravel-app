<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - ليالي</title>
    <link rel="icon" href="{{ asset('logo.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; display:flex; align-items:center; justify-content:center; background:#150f0c; font-family:'Segoe UI',Tahoma,Arial,sans-serif; padding:1rem; }
        .box { width:100%; max-width:420px; background:#fff; border-radius:18px; padding:2rem; border-top:5px solid #d6b478; }
        .otp { letter-spacing:.5rem; text-align:center; font-size:1.5rem; direction:ltr; }
    </style>
</head>
<body>
<div class="box">
    <div class="text-center mb-3">
        <a href="{{ route('home') }}"><img src="{{ asset('logo.svg') }}" width="76" alt="ليالي"></a>
        <h1 class="h4 mt-2">@yield('title')</h1>
    </div>
    @if (session('ok'))<div class="alert alert-success py-2">{{ session('ok') }}</div>@endif
    @if (session('warn'))<div class="alert alert-warning py-2">{{ session('warn') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger py-2 mb-3"><ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    @yield('content')
</div>
</body>
</html>
