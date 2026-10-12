<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'لوحة التحكم') - ليالي</title>
    <link rel="icon" href="{{ asset('logo.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background:#f4f5f2; font-family:'Segoe UI',Tahoma,Arial,sans-serif; }
        .adm-nav { background:#150f0c; border-bottom:3px solid #d6b478; }
        .adm-nav .nav-link { color:#e9e4d6; } .adm-nav .nav-link.active, .adm-nav .nav-link:hover { color:#d6b478; }
        .stat { border:0; border-radius:14px; box-shadow:0 2px 12px rgba(20,32,26,.08); }
        .thumb { width:56px; height:56px; object-fit:cover; border-radius:10px; background:#150f0c; }
        .bar { background:#d6b478; border-radius:6px 6px 0 0; width:100%; min-height:3px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark adm-nav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('logo.svg') }}" width="34" alt=""> لوحة التحكم
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#an"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="an">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders') }}">الطلبات <span id="navNewBadge" class="badge rounded-pill text-bg-warning d-none">0</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.items*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}">الأصناف</a></li>
                @php $pendingReviews = \App\Models\Review::where('approved', false)->count(); @endphp
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews') }}">التقييمات @if ($pendingReviews)<span class="badge rounded-pill text-bg-warning">{{ $pendingReviews }}</span>@endif</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">الأقسام</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.about*') ? 'active' : '' }}" href="{{ route('admin.about') }}">النبذة والهدف</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.accounting*') ? 'active' : '' }}" href="{{ route('admin.accounting') }}">المبيعات والحسابات</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.expenses*') ? 'active' : '' }}" href="{{ route('admin.expenses') }}">المصاريف</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}" href="{{ route('admin.customers') }}">الزبائن</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.loyalty*') ? 'active' : '' }}" href="{{ route('admin.loyalty') }}">الولاء</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}" target="_blank">الموقع</a></li>
            </ul>
            <form method="POST" action="{{ route('logout') }}" class="d-flex">
                @csrf
                <button class="btn btn-sm btn-outline-light">تسجيل الخروج</button>
            </form>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div id="newOrderAlert" class="alert alert-warning d-none">وصل طلب جديد! <a href="{{ route('admin.orders') }}" class="alert-link">عرض الطلبات</a></div>
    @if (session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</main>
<button id="soundBtn" type="button" class="btn btn-dark position-fixed" style="bottom:1rem;inset-inline-start:1rem;z-index:1030">تفعيل صوت الطلبات</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var url = '{{ route('admin.orders.poll') }}', last = null, on = false, ctx = null;
    var btn = document.getElementById('soundBtn');

    function beep() {
        try {
            ctx = ctx || new (window.AudioContext || window.webkitAudioContext)();
            [0, 0.3, 0.6].forEach(function (t) {
                var o = ctx.createOscillator(), g = ctx.createGain(), now = ctx.currentTime + t;
                o.frequency.value = 880; o.connect(g); g.connect(ctx.destination);
                g.gain.setValueAtTime(0.35, now); g.gain.exponentialRampToValueAtTime(0.001, now + 0.22);
                o.start(now); o.stop(now + 0.22);
            });
        } catch (e) {}
    }

    btn.addEventListener('click', function () {
        on = !on;
        btn.textContent = on ? 'صوت الطلبات: مفعّل' : 'تفعيل صوت الطلبات';
        btn.className = 'btn position-fixed ' + (on ? 'btn-success' : 'btn-dark');
        if (on) { beep(); }
    });

    function poll() {
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var badge = document.getElementById('navNewBadge');
                badge.textContent = d.new; badge.classList.toggle('d-none', d.new === 0);
                if (last !== null && d.latest > last) {
                    document.getElementById('newOrderAlert').classList.remove('d-none');
                    document.title = '(طلب جديد) ' + document.title.replace('(طلب جديد) ', '');
                    if (on) { beep(); }
                }
                last = d.latest;
            })
            .catch(function () {});
    }
    poll(); setInterval(poll, 15000);
})();
</script>
</body>
</html>
