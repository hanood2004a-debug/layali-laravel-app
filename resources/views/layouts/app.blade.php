<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ليالي')</title>
    <link rel="icon" href="{{ asset('logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@400;700&family=Amiri:wght@400;700&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600&family=Cormorant+Garamond:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script>try{if(sessionStorage.getItem('layaliIntro'))document.documentElement.classList.add('seen')}catch(e){}</script>
    <noscript><style>#intro{display:none}</style></noscript>
    <style>
        :root { --ink:#150f0c; --ink2:#1f1712; --ink3:#2b2018; --cream:#f3ead9; --paper:#faf6ee; --brass:#d6b478; --brass2:#a67c3d; --wine:#7a2433; --text:#2b2119; --muted:#a49883; }
        html { scroll-behavior:smooth; }
        body { font-family:'IBM Plex Sans Arabic','Tajawal',Tahoma,sans-serif; font-weight:400; background:var(--paper); color:var(--text); }
        body.locked { overflow:hidden; }
        h1,h2,.display { font-family:'Aref Ruqaa','Amiri',serif; font-weight:700; letter-spacing:0; line-height:1.5; }
        h3,h4,.serif { font-family:'Amiri','Aref Ruqaa',serif; font-weight:700; letter-spacing:0; }
        .latin { font-family:'Cormorant Garamond',serif; letter-spacing:.3em; font-weight:600; }

        /* buttons */
        .btn-brass { background:linear-gradient(135deg,#c79c5a,#e6c98e 55%,#c79c5a); background-size:200% 100%; color:#1d1409; font-weight:600; border:0; border-radius:8px; padding:.7rem 1.8rem; transition:background-position .4s, transform .2s, box-shadow .25s; }
        .btn-brass:hover, .btn-brass:focus { background-position:100% 0; color:#1d1409; transform:translateY(-2px); box-shadow:0 10px 24px rgba(214,180,120,.35); }
        .btn-ghost { background:transparent; color:var(--brass); font-weight:600; border:1px solid var(--brass); border-radius:8px; padding:.7rem 1.8rem; transition:background .25s, color .25s; }
        .btn-ghost:hover { background:var(--brass); color:#1d1409; }

        /* glass hover on every card */
        .glass-hover { position:relative; overflow:hidden; transition:transform .4s, background .4s, box-shadow .4s, border-color .4s, backdrop-filter .4s; }
        .glass-hover::after { content:""; position:absolute; inset:0; pointer-events:none; background:linear-gradient(115deg, transparent 32%, rgba(255,255,255,.2) 48%, transparent 64%); transform:translateX(-130%); transition:transform .9s; }
        .glass-hover:hover::after { transform:translateX(130%); }
        .glass-hover:hover { transform:translateY(-6px); background:rgba(255,255,255,.08) !important; -webkit-backdrop-filter:blur(14px) saturate(160%); backdrop-filter:blur(14px) saturate(160%); border-color:rgba(255,255,255,.3) !important; box-shadow:0 18px 44px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,255,255,.28); }
        .glass-hover.light:hover { background:rgba(255,255,255,.55) !important; border-color:rgba(166,124,61,.55) !important; box-shadow:0 20px 44px rgba(43,33,25,.14), inset 0 1px 0 #fff; }

        /* header */
        .nav-lux { position:fixed; top:0; inset-inline:0; z-index:1030; display:grid; grid-template-columns:1fr auto 1fr; align-items:center; padding:1rem 2rem; transition:background .3s, padding .3s, border-color .3s; border-bottom:1px solid transparent; }
        .nav-lux.scrolled { background:rgba(21,15,12,.7); -webkit-backdrop-filter:blur(14px); backdrop-filter:blur(14px); padding:.6rem 2rem; border-bottom-color:rgba(214,180,120,.25); }
        .nav-links { display:flex; gap:1.7rem; }
        .nav-links a { color:#ddd3bf; text-decoration:none; font-size:.95rem; transition:color .2s; }
        .nav-links a:hover { color:var(--brass); }
        .nav-brand { display:flex; flex-direction:column; align-items:center; text-decoration:none; color:var(--cream); line-height:1.1; }
        .nav-brand img { width:38px; margin-bottom:.1rem; }
        .nav-brand strong { font-family:'Aref Ruqaa',serif; font-size:1.6rem; }
        .nav-brand small { font-size:.62rem; color:var(--brass); }
        .nav-cta { justify-self:end; } .nav-cta .btn-brass { padding:.45rem 1.2rem; font-size:.9rem; }

        /* hero */
        .hero { position:relative; min-height:100vh; display:flex; align-items:center; justify-content:center; text-align:center; color:var(--cream); padding:7rem 1.5rem 5rem;
            background:
              radial-gradient(ellipse at 50% 22%, rgba(214,180,120,.22), transparent 55%),
              radial-gradient(ellipse at 50% 105%, rgba(122,36,51,.45), transparent 60%),
              repeating-linear-gradient(90deg, rgba(255,255,255,.02) 0 2px, transparent 2px 96px),
              linear-gradient(180deg, #211811 0%, #150f0c 70%, #0d0907 100%); }
        .hero::after { content:""; position:absolute; inset:1.25rem; border:1px solid rgba(214,180,120,.3); pointer-events:none; }
        .hero .eyebrow { color:var(--brass); font-size:.85rem; margin-bottom:1rem; }
        .hero h1 { font-size:clamp(3rem,9vw,6.2rem); margin-bottom:.6rem; text-shadow:0 4px 30px rgba(0,0,0,.5); }
        .hero .rule { width:80px; height:1px; background:var(--brass); margin:1.2rem auto; }
        .hero p.lead { color:#d6ccb6; font-size:1.2rem; font-weight:300; margin-bottom:2.2rem; }
        .hero .actions { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }

        /* about */
        .about { padding:5rem 0; text-align:center; background:radial-gradient(circle at 10% 20%, rgba(214,180,120,.28), transparent 38%), radial-gradient(circle at 92% 80%, rgba(122,36,51,.14), transparent 40%), #f1e7d3; }
        .about h2 { font-size:clamp(2rem,4.5vw,3rem); margin-bottom:3rem; }
        .pillar { height:100%; padding:2.2rem 1.5rem; border-radius:16px; background:rgba(255,255,255,.4); border:1px solid rgba(166,124,61,.25); }
        .pillar .num { font-family:'Amiri',serif; font-size:2.6rem; color:var(--brass2); line-height:1; }
        .pillar h3 { font-size:1.45rem; margin:.5rem 0; }
        .pillar p { color:#6c6254; margin:0 auto; max-width:300px; font-weight:300; }

        /* menu */
        .menu-sec { padding:5rem 0; color:var(--cream); background:radial-gradient(circle at 12% 12%, rgba(214,180,120,.14), transparent 38%), radial-gradient(circle at 90% 65%, rgba(122,36,51,.3), transparent 42%), var(--ink2); }
        .sec-head { text-align:center; margin-bottom:2.2rem; }
        .sec-head h2 { font-size:clamp(2.2rem,5vw,3.3rem); }
        .sec-head p { color:var(--muted); margin:0; font-weight:300; }
        .tabs { display:flex; gap:.4rem; overflow-x:auto; padding-bottom:.5rem; margin-bottom:2rem; scrollbar-width:thin; }
        @media (min-width:992px) { .tabs { justify-content:center; flex-wrap:wrap; } }
        .tab-btn { white-space:nowrap; background:transparent; color:#c4b9a1; border:0; border-bottom:2px solid transparent; padding:.5rem 1rem; font-weight:500; transition:color .2s, border-color .2s; }
        .tab-btn:hover { color:var(--cream); }
        .tab-btn.active { color:var(--brass); border-bottom-color:var(--brass); }
        .dish-card { height:100%; display:flex; flex-direction:column; cursor:pointer; background:rgba(43,32,24,.96); border:1px solid rgba(214,180,120,.18); border-radius:14px; }
        .dish-media { position:relative; aspect-ratio:4/3; overflow:hidden; background:var(--ink3); }
        .dish-media img { width:100%; height:100%; object-fit:cover; object-position:50% 68%; transition:transform .7s; }
        .dish-card:hover .dish-media img { transform:scale(1.07); }
        .ph { width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:radial-gradient(circle at 50% 40%, #44332a, #1f1712 75%); }
        .ph img { width:74px !important; height:auto !important; opacity:.4; }
        .dish-price { position:absolute; top:.7rem; inset-inline-start:.7rem; background:rgba(21,15,12,.78); -webkit-backdrop-filter:blur(6px); backdrop-filter:blur(6px); color:var(--brass); border:1px solid rgba(214,180,120,.4); border-radius:50rem; padding:.15rem .8rem; font-weight:600; z-index:2; }
        .dish-info { padding:1.1rem 1.2rem 1.2rem; display:flex; flex-direction:column; flex:1; }
        .dish-info h3 { font-size:1.35rem; margin:0 0 .3rem; color:var(--cream); }
        .dish-info p { color:var(--muted); font-size:.92rem; font-weight:300; margin:0 0 .9rem; }
        .dish-foot { margin-top:auto; display:flex; align-items:center; justify-content:space-between; }
        .dish-foot .more { color:var(--brass); font-size:.88rem; }
        .add-btn { width:38px; height:38px; border-radius:50%; border:1px solid var(--brass); background:transparent; color:var(--brass); font-size:1.5rem; line-height:1; transition:background .2s, color .2s, transform .2s; position:relative; z-index:3; }
        .add-btn:hover { background:var(--brass); color:#1d1409; }
        .add-btn.added { background:var(--brass); color:#1d1409; transform:scale(1.15); }

        /* dish modal */
        .dish-modal { background:var(--ink2); color:var(--cream); border:1px solid rgba(214,180,120,.35); border-radius:18px; overflow:hidden; }
        .dish-modal .btn-close { position:absolute; top:.9rem; inset-inline-end:.9rem; z-index:5; filter:invert(1); opacity:.85; }
        .dm-media { min-height:260px; background:var(--ink3); } .dm-media img { width:100%; height:100%; object-fit:cover; object-position:50% 68%; display:block; }
        .dm-body { padding:2rem 1.8rem; }
        .dm-cat { color:var(--brass); letter-spacing:.05em; }
        .dm-body h3 { font-family:'Aref Ruqaa',serif; font-size:2rem; margin:.2rem 0 .6rem; }
        .dm-body p { color:#d6ccb6; font-weight:300; line-height:1.9; }
        .dm-sub { font-size:1.1rem; color:var(--brass); margin:1.2rem 0 .6rem; }
        .chips { display:flex; flex-wrap:wrap; gap:.45rem; }
        .chips span { background:rgba(214,180,120,.1); border:1px solid rgba(214,180,120,.35); color:var(--cream); border-radius:50rem; padding:.2rem .85rem; font-size:.9rem; }
        .dm-foot { display:flex; align-items:center; justify-content:space-between; margin-top:1.6rem; gap:1rem; }
        .dm-price { font-family:'Amiri',serif; font-size:1.7rem; color:var(--brass); }

        /* contact */
        .contact-sec { padding:5rem 0; text-align:center; background:radial-gradient(circle at 88% 12%, rgba(214,180,120,.3), transparent 38%), radial-gradient(circle at 8% 90%, rgba(122,36,51,.13), transparent 40%), var(--paper); }
        .contact-sec h2 { font-size:clamp(2rem,4.5vw,3rem); margin-bottom:.3rem; }
        .info-card { height:100%; background:rgba(255,255,255,.55); border:1px solid rgba(166,124,61,.3); border-radius:16px; padding:2.2rem 1rem; }
        .info-card svg { width:30px; height:30px; fill:none; stroke:var(--brass2); stroke-width:1.5; stroke-linecap:round; stroke-linejoin:round; margin-bottom:.8rem; }
        .info-card h3 { font-size:1.2rem; color:var(--brass2); margin-bottom:.3rem; }
        .info-card p, .info-card a { margin:0; color:var(--text); text-decoration:none; font-size:1.05rem; }
        .contact-sec .actions { margin-top:2.2rem; display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }
        .contact-sec .btn-ghost { color:var(--brass2); border-color:var(--brass2); } .contact-sec .btn-ghost:hover { background:var(--brass2); color:#fff; }

        .site-footer { background:#0d0907; color:#8d8678; text-align:center; padding:3rem 0 5.5rem; border-top:1px solid rgba(214,180,120,.25); }
        .site-footer img { width:56px; margin-bottom:.5rem; }
        .site-footer strong { display:block; color:var(--cream); font-family:'Aref Ruqaa',serif; font-size:1.7rem; }
        .site-footer a { color:#b6ab91; text-decoration:none; margin:0 .8rem; } .site-footer a:hover { color:var(--brass); }
        .site-footer hr { border-color:rgba(214,180,120,.2); opacity:1; margin:1.6rem 0 1rem; }

        /* cart */
        .cart-fab { position:fixed; bottom:1rem; inset-inline-start:1rem; z-index:1040; box-shadow:0 8px 24px rgba(0,0,0,.4); }
        .cart-line { display:flex; align-items:center; gap:.6rem; padding:.55rem 0; border-bottom:1px solid #e6dfcf; } .cart-line .nm { flex:1; }
        .qty-btn { width:30px; height:30px; padding:0; line-height:1; border-radius:50%; }
        .offcanvas { background:var(--paper); }

        /* stars + ratings */
        .stars { --r:0; direction:ltr; display:inline-block; font-size:1rem; letter-spacing:2px; line-height:1; background:linear-gradient(90deg, var(--brass) calc(var(--r) / 5 * 100%), rgba(255,255,255,.22) 0); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .rate { display:flex; align-items:center; gap:.5rem; margin:0 0 .45rem; font-size:.85rem; color:var(--muted); }
        .about-card { height:100%; padding:2.2rem; border-radius:18px; background:rgba(255,255,255,.5); border:1px solid rgba(166,124,61,.3); text-align:start; }
        .about-card h3 { color:var(--brass2); font-size:1.6rem; margin-bottom:.9rem; }
        .about-card p { white-space:pre-line; line-height:2; color:#4b4034; font-weight:300; margin:0; }
        .about-card.mission { background:linear-gradient(160deg, rgba(122,36,51,.94), rgba(66,18,28,.97)); border-color:rgba(214,180,120,.5); }
        .about-card.mission h3 { color:var(--brass); } .about-card.mission p { color:#eadfc9; font-size:1.1rem; }
        .stats { display:flex; justify-content:center; gap:3.5rem; flex-wrap:wrap; margin:2.5rem 0 3rem; }
        .stat b { display:block; font-family:'Amiri',serif; font-size:2.6rem; color:var(--brass2); line-height:1.1; }
        .stat span { color:#6c6254; font-size:.95rem; }
        .values-title { font-size:1.6rem; margin:0 0 1.6rem; }
        .rv-box { padding:1.4rem 1.8rem 1.8rem; border-top:1px solid rgba(214,180,120,.25); }
        .rv-sum { display:flex; align-items:center; gap:1rem; margin-bottom:.8rem; }
        .rv-sum b { font-family:'Amiri',serif; font-size:2.3rem; color:var(--brass); line-height:1; }
        .rv-item { padding:.9rem 0; border-bottom:1px solid rgba(255,255,255,.08); }
        .rv-item .who { display:flex; gap:.7rem; align-items:center; flex-wrap:wrap; } .rv-item strong { color:var(--cream); } .rv-item small { color:var(--muted); }
        .rv-item p { margin:.35rem 0 0; color:#d6ccb6; font-weight:300; }
        .rv-form { background:rgba(255,255,255,.04); border:1px solid rgba(214,180,120,.25); border-radius:12px; padding:1.2rem; margin-top:1.2rem; }
        .rv-form .form-control { background:rgba(0,0,0,.28); border:1px solid rgba(214,180,120,.3); color:var(--cream); }
        .rv-form .form-control::placeholder { color:#8f846f; } .rv-form .form-control:focus { background:rgba(0,0,0,.38); border-color:var(--brass); box-shadow:none; color:var(--cream); }
        .star-input { direction:ltr; display:inline-flex; gap:.15rem; }
        .star-input button { background:none; border:0; padding:0 .1rem; font-size:1.9rem; line-height:1; color:rgba(255,255,255,.25); transition:color .15s, transform .15s; }
        .star-input button.on { color:var(--brass); } .star-input button:hover { transform:scale(1.15); }
        .hp { position:absolute; left:-9999px; width:0; height:0; opacity:0; }

        /* loyalty */
        .loyalty-sec { padding:5rem 0; text-align:center; color:var(--cream); background:radial-gradient(circle at 50% 0%, rgba(214,180,120,.2), transparent 55%), linear-gradient(180deg,#2a1218,#1a0c10); }
        .loyalty-sec h2 { font-size:clamp(2rem,4.5vw,3rem); } .loyalty-sec .sub { color:#cdbf9f; margin-bottom:2.2rem; font-weight:300; }
        .loy-card { height:100%; padding:2rem 1rem; border-radius:16px; background:rgba(255,255,255,.05); border:1px solid rgba(214,180,120,.3); }
        .loy-card .n { font-family:'Amiri',serif; font-size:2.7rem; color:var(--brass); line-height:1.1; }
        .loy-card p { margin:.4rem 0 0; color:#e7dcc3; }

        /* curtain intro */
        #intro { position:fixed; inset:0; z-index:3000; overflow:hidden; direction:ltr; }
        #intro::before { content:""; position:absolute; top:0; left:0; right:0; height:12px; z-index:4; background:linear-gradient(180deg,#f0d9a0,#a67c3d); box-shadow:0 4px 12px rgba(0,0,0,.5); }
        #intro .curtain { position:absolute; top:0; width:50.4%; height:100%; transition:transform 2.4s cubic-bezier(.7,0,.2,1);
            background:
              linear-gradient(90deg, rgba(0,0,0,.5), rgba(255,255,255,.07) 22%, rgba(0,0,0,.45) 50%, rgba(255,255,255,.06) 76%, rgba(0,0,0,.5)) 0 0/110px 100% repeat-x,
              #6a1c2a; box-shadow:0 0 40px rgba(0,0,0,.6); border-bottom:8px solid #c79c5a; }
        #intro .curtain.l { left:0; } #intro .curtain.r { right:0; }
        #intro.open .curtain.l { transform:translateX(-102%); } #intro.open .curtain.r { transform:translateX(102%); }
        #intro .sign { position:absolute; inset:0; z-index:3; display:flex; align-items:center; justify-content:center; text-align:center; direction:rtl; transition:opacity .6s; padding:1rem; }
        #intro .plate { background:rgba(21,15,12,.9); border:1px solid var(--brass); border-radius:18px; padding:1.8rem 2.4rem; color:var(--cream); box-shadow:0 20px 60px rgba(0,0,0,.6); max-width:360px; }
        #intro .plate img { width:78px; } #intro .plate h2 { font-size:2.4rem; margin:.4rem 0 0; } #intro .plate p { color:#cdbf9f; margin:.3rem 0 1.2rem; font-weight:300; }
        #intro.open .sign { opacity:0; pointer-events:none; }
        #intro.done { opacity:0; visibility:hidden; transition:opacity .6s, visibility 0s .6s; pointer-events:none; }
        html.seen #intro { display:none; }

        @media (max-width:767px) { .nav-links { display:none; } .nav-lux { grid-template-columns:auto 1fr; padding:.8rem 1rem; } .nav-brand { align-items:flex-start; } }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior:auto; } *, *::after { transition-duration:.01s !important; } }
    </style>
</head>
<body class="locked">

<div id="intro" role="dialog" aria-label="مرحباً بك">
    <div class="curtain l"></div><div class="curtain r"></div>
    <div class="sign"><div class="plate">
        <img src="{{ asset('logo.svg') }}" alt="">
        <h2>{{ $name ?? 'ليالي' }}</h2>
        <p>أهلاً بك في أمسية استثنائية</p>
        <button type="button" id="openCurtain" class="btn btn-brass">ادخل</button>
    </div></div>
</div>

<header class="nav-lux">
    <nav class="nav-links"><a href="#about">عن ليالي</a><a href="#menu">القائمة</a><a href="#contact">تواصل معنا</a></nav>
    <a class="nav-brand" href="#top"><img src="{{ asset('logo.svg') }}" alt=""><strong>{{ $name ?? 'ليالي' }}</strong><small class="latin">FINE DINING</small></a>
    <div class="nav-cta"><a href="#menu" class="btn btn-brass">اطلب الآن</a></div>
</header>

@yield('content')

<footer class="site-footer">
    <div class="container">
        <img src="{{ asset('logo.svg') }}" alt="">
        <strong>{{ $name ?? 'ليالي' }}</strong>
        <span style="color:var(--brass)">أمسية تستحق أن تُذكر</span>
        <div class="mt-3"><a href="#menu">القائمة</a><a href="#contact">تواصل معنا</a><a href="{{ route('loyalty.card') }}">بطاقة الولاء</a><a href="{{ route('login') }}">الإدارة</a></div>
        <hr>
        <small>© {{ date('Y') }} {{ $name ?? 'ليالي' }} — جميع الحقوق محفوظة</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var n = document.querySelector('.nav-lux');
    function f() { n.classList.toggle('scrolled', window.scrollY > 40); } f();
    addEventListener('scroll', f, { passive: true });

    var intro = document.getElementById('intro'), btn = document.getElementById('openCurtain');
    function unlock() { document.body.classList.remove('locked'); }
    if (document.documentElement.classList.contains('seen')) { unlock(); return; }
    btn.addEventListener('click', function () {
        try { sessionStorage.setItem('layaliIntro', '1'); } catch (e) {}
        intro.classList.add('open');
        setTimeout(function () { intro.classList.add('done'); unlock(); }, 2300);
    });
    btn.focus();
})();
</script>
</body>
</html>
