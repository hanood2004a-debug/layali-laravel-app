@extends('layouts.app')

@section('title', $name . ' | مطعم ومقهى')

@section('content')
@php $count = collect($menu)->flatten(1)->count(); @endphp

<header id="top" class="hero">
    <div class="container">
        <div class="eyebrow latin">FINE DINING · مطعم ومقهى</div>
        <h1>أمسية تستحق<br>أن تُذكر</h1>
        <div class="rule"></div>
        <p class="lead">حيث يلتقي فنّ الطهي بالفخامة الهادئة</p>
        <div class="actions">
            <a href="#menu" class="btn btn-brass btn-lg">اكتشف القائمة</a>
            <a href="#contact" class="btn btn-ghost btn-lg">تواصل معنا</a>
        </div>
    </div>
</header>

<section id="about" class="about">
    <div class="container">
        <h2>عن ليالي</h2>
        <div class="row g-4 mt-3">
            <div class="col-lg-7"><div class="about-card glass-hover light"><h3>نبذة عن المطعم</h3><p>{{ $about }}</p></div></div>
            <div class="col-lg-5"><div class="about-card mission glass-hover"><h3>هدفنا</h3><p>{{ $mission }}</p></div></div>
        </div>

        <div class="stats">
            <div class="stat"><b>{{ $count }}</b><span>طبقاً ومشروباً</span></div>
            <div class="stat"><b>{{ count($menu) }}</b><span>قسماً في القائمة</span></div>
            @if ($ratingCount > 0)
                <div class="stat"><b>{{ $ratingAvg }} ★</b><span>متوسط تقييم {{ $ratingCount }} رأياً</span></div>
            @endif
        </div>

        <h3 class="values-title">ما يميّزنا</h3>
        <div class="row g-4">
            <div class="col-md-4"><div class="pillar glass-hover light"><div class="num">٠١</div><h3>مكوّنات مختارة</h3><p>نختار موادنا طازجة وموسمية من مزارع وموردين نثق بهم.</p></div></div>
            <div class="col-md-4"><div class="pillar glass-hover light"><div class="num">٠٢</div><h3>طهي بشغف</h3><p>وصفات أصيلة بلمسة عصرية، تُحضَّر على الفحم وعلى نار هادئة.</p></div></div>
            <div class="col-md-4"><div class="pillar glass-hover light"><div class="num">٠٣</div><h3>خدمة راقية</h3><p>أجواء هادئة وخدمة تجعل كل زيارة أمسية لا تُنسى.</p></div></div>
        </div>
    </div>
</section>

<section id="menu" class="menu-sec">
    <div class="container">
        <div class="sec-head">
            <h2>قائمة الطعام</h2>
            <p>{{ $count }} طبقاً ومشروباً مختاراً بعناية · اضغط على أي صنف لتعرف تفاصيله وآراء الزبائن</p>
        </div>

        <div class="tabs" role="tablist">
            @foreach (array_keys($menu) as $i => $cat)
                <button type="button" class="tab-btn" data-tab="{{ $i }}">{{ $cat }}</button>
            @endforeach
        </div>

        @foreach ($menu as $category => $items)
            <div class="menu-cat" data-cat="{{ $loop->index }}">
                <div class="row g-4">
                    @foreach ($items as $item)
                        <div class="col-md-6 col-lg-4">
                            <article class="dish-card glass-hover" role="button" tabindex="0" data-item="{{ json_encode($item, JSON_UNESCAPED_UNICODE) }}">
                                <div class="dish-media">
                                    @if (!empty($item['img']))
                                        <img src="{{ asset('images/' . $item['img']) }}" alt="{{ $item['name'] }}" loading="lazy">
                                    @else
                                        <div class="ph"><img src="{{ asset('logo.svg') }}" alt=""></div>
                                    @endif
                                    <span class="dish-price">{{ $item['price'] }} ₪</span>
                                </div>
                                <div class="dish-info">
                                    <h3>{{ $item['name'] }}</h3>
                                    <div class="rate">
                                        <span class="stars" style="--r: {{ $item['rating'] ?? 0 }}">★★★★★</span>
                                        <span>@if ($item['rating']){{ $item['rating'] }} ({{ $item['reviews_count'] }})@else لا تقييمات بعد @endif</span>
                                    </div>
                                    @if (!empty($item['desc']))<p>{{ $item['desc'] }}</p>@endif
                                    <div class="dish-foot">
                                        <span class="more">التفاصيل وآراء الزبائن</span>
                                        <button type="button" class="add-btn" data-add="{{ $item['id'] }}" aria-label="أضف {{ $item['name'] }} للسلة">+</button>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

@if ($loyalty['enabled'])
<section id="loyalty" class="loyalty-sec">
    <div class="container">
        <h2>برنامج الولاء</h2>
        <p class="sub">كل طلب يقرّبك من مكافأتك</p>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4"><div class="loy-card glass-hover"><div class="n">{{ $loyalty['rate'] + 0 }}</div><p>نقطة مقابل كل 1 ₪ تدفعه</p></div></div>
            <div class="col-md-4"><div class="loy-card glass-hover"><div class="n">{{ $loyalty['block'] }}</div><p>نقطة = خصم {{ $loyalty['value'] + 0 }} ₪ على طلبك</p></div></div>
            <div class="col-md-4"><div class="loy-card glass-hover"><div class="n">تلقائياً</div><p>تُضاف النقاط لرقم جوالك بعد تسليم طلبك</p></div></div>
        </div>
        <a href="{{ route('loyalty.card') }}" class="btn btn-brass btn-lg mt-4">تحقق من نقاطك</a>
    </div>
</section>
@endif

<section id="contact" class="contact-sec">
    <div class="container">
        <h2>تواصل معنا</h2>
        <p class="text-muted mb-4">يسعدنا استقبالكم وخدمتكم</p>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4"><div class="info-card glass-hover light">
                <svg viewBox="0 0 24 24"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                <h3>العنوان</h3><p>{{ $address }}</p></div></div>
            <div class="col-md-4"><div class="info-card glass-hover light">
                <svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
                <h3>الهاتف</h3><a href="tel:{{ $phone }}" dir="ltr">{{ $phone }}</a></div></div>
            <div class="col-md-4"><div class="info-card glass-hover light">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                <h3>ساعات العمل</h3><p>{{ $hours }}</p></div></div>
        </div>
        <div class="actions">
            <a href="tel:{{ $phone }}" class="btn btn-brass btn-lg">اتصل الآن</a>
            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="btn btn-ghost btn-lg">واتساب</a>
        </div>
    </div>
</section>

{{-- نافذة تفاصيل الصنف + آراء الزبائن --}}
<div class="modal fade" id="dishModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content dish-modal">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            <div class="row g-0">
                <div class="col-md-5 dm-media" id="dmMedia"></div>
                <div class="col-md-7 dm-body">
                    <small class="dm-cat" id="dmCat"></small>
                    <h3 id="dmName"></h3>
                    <p id="dmDetails"></p>
                    <h4 class="dm-sub" id="dmIngTitle">المكوّنات الأساسية</h4>
                    <div class="chips" id="dmIng"></div>
                    <div class="dm-foot">
                        <span class="dm-price" id="dmPrice"></span>
                        <button type="button" class="btn btn-brass" id="dmAdd">أضف للسلة</button>
                    </div>
                </div>
            </div>

            <div class="rv-box">
                <h4 class="dm-sub" style="margin-top:0">آراء العملاء</h4>
                <div class="rv-sum">
                    <b id="rvAvg">–</b>
                    <div><span class="stars" id="rvStars">★★★★★</span><div class="small" style="color:var(--muted)" id="rvCount"></div></div>
                </div>
                <div id="rvList"></div>

                <form class="rv-form" id="rvForm" novalidate>
                    <h4 class="dm-sub" style="margin-top:0">شاركنا رأيك</h4>
                    <div class="mb-2"><input class="form-control" name="name" placeholder="اسمك" maxlength="60"></div>
                    <div class="mb-2">
                        <div class="star-input" id="starInput" role="radiogroup" aria-label="التقييم">
                            <button type="button" data-v="1" aria-label="نجمة">★</button><button type="button" data-v="2" aria-label="نجمتان">★</button><button type="button" data-v="3" aria-label="3 نجوم">★</button><button type="button" data-v="4" aria-label="4 نجوم">★</button><button type="button" data-v="5" aria-label="5 نجوم">★</button>
                        </div>
                    </div>
                    <div class="mb-2"><textarea class="form-control" name="comment" rows="2" maxlength="500" placeholder="اكتب رأيك بالطبق (اختياري)"></textarea></div>
                    <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div id="rvMsg" class="small mb-2"></div>
                    <button class="btn btn-brass" type="submit" id="rvSubmit">إرسال التقييم</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    // تبويبات الأقسام
    var tabs = document.querySelectorAll('[data-tab]'), cats = document.querySelectorAll('.menu-cat');
    function show(i) {
        tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.tab == i); });
        cats.forEach(function (c) { c.classList.toggle('d-none', c.dataset.cat != i); });
    }
    tabs.forEach(function (t) { t.addEventListener('click', function () { show(t.dataset.tab); }); });
    show(0);

    var IMG = '{{ asset('images') }}/', LOGO = '{{ asset('logo.svg') }}';
    var el = document.getElementById('dishModal'), currentId = null, rating = 0;
    function g(id) { return document.getElementById(id); }
    function setStars(v) {
        rating = v;
        g('starInput').querySelectorAll('button').forEach(function (b) { b.classList.toggle('on', +b.dataset.v <= v); });
    }

    function openDish(card) {
        var d = JSON.parse(card.dataset.item);
        currentId = d.id;
        g('dmCat').textContent = d.cat || '';
        g('dmName').textContent = d.name;
        g('dmDetails').textContent = d.details || d.desc || '';
        g('dmPrice').textContent = d.price + ' ₪';

        var media = g('dmMedia'); media.innerHTML = '';
        var im = document.createElement('img');
        if (d.img) { im.src = IMG + d.img; im.alt = d.name; media.appendChild(im); }
        else { var ph = document.createElement('div'); ph.className = 'ph'; ph.style.minHeight = '260px'; im.src = LOGO; im.alt = ''; im.style.cssText = 'width:90px;height:auto;opacity:.4'; ph.appendChild(im); media.appendChild(ph); }

        var ing = g('dmIng'); ing.innerHTML = '';
        (d.ingredients || []).forEach(function (x) { var s = document.createElement('span'); s.textContent = x; ing.appendChild(s); });
        var has = (d.ingredients || []).length > 0;
        g('dmIngTitle').style.display = has ? '' : 'none'; ing.style.display = has ? '' : 'none';
        g('dmAdd').setAttribute('data-add', d.id);

        // التقييمات
        g('rvAvg').textContent = d.rating ? d.rating : '–';
        g('rvStars').style.setProperty('--r', d.rating || 0);
        g('rvCount').textContent = d.reviews_count ? d.reviews_count + ' تقييم' : 'لا توجد تقييمات بعد، كن أول من يقيّم هذا الطبق';
        var list = g('rvList'); list.innerHTML = '';
        (d.reviews || []).forEach(function (r) {
            var row = document.createElement('div'); row.className = 'rv-item';
            var who = document.createElement('div'); who.className = 'who';
            var n = document.createElement('strong'); n.textContent = r.name;
            var st = document.createElement('span'); st.className = 'stars'; st.textContent = '★★★★★'; st.style.setProperty('--r', r.rating);
            var dt = document.createElement('small'); dt.textContent = r.date;
            who.appendChild(n); who.appendChild(st); who.appendChild(dt); row.appendChild(who);
            if (r.comment) { var p = document.createElement('p'); p.textContent = r.comment; row.appendChild(p); }
            list.appendChild(row);
        });
        g('rvForm').reset(); setStars(0); g('rvMsg').textContent = '';
        bootstrap.Modal.getOrCreateInstance(el).show();
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('.add-btn')) { return; }
        var c = e.target.closest('.dish-card'); if (c) { openDish(c); }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && e.target.classList && e.target.classList.contains('dish-card')) { openDish(e.target); }
    });
    g('dmAdd').addEventListener('click', function () {
        setTimeout(function () { bootstrap.Modal.getOrCreateInstance(el).hide(); }, 450);
    });

    // نموذج التقييم
    g('starInput').addEventListener('click', function (e) { var b = e.target.closest('button'); if (b) { setStars(+b.dataset.v); } });
    g('rvForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var f = e.target, msg = g('rvMsg'), btn = g('rvSubmit');
        msg.style.color = '#e8a0a0';
        if (!f.name.value.trim()) { msg.textContent = 'اكتب اسمك'; return; }
        if (!rating) { msg.textContent = 'اختر عدد النجوم'; return; }
        btn.disabled = true;
        fetch('{{ route('reviews.store') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ menu_item_id: currentId, name: f.name.value, rating: rating, comment: f.comment.value, website: f.website.value })
        })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
        .then(function (res) {
            if (res.ok) { msg.style.color = '#9fd6a8'; msg.textContent = res.j.message; f.reset(); setStars(0); }
            else { var first = res.j.errors ? Object.values(res.j.errors)[0][0] : res.j.message; msg.textContent = first || 'تعذر الإرسال، حاول مرة أخرى'; }
        })
        .catch(function () { msg.textContent = 'تعذر الإرسال، تحقق من الاتصال'; })
        .finally(function () { btn.disabled = false; });
    });
})();
</script>

{{-- زر السلة --}}
<button id="cartFab" class="btn btn-brass btn-lg cart-fab rounded-pill" data-bs-toggle="offcanvas" data-bs-target="#cartPanel">
    السلة <span id="cartCount" class="badge bg-dark">0</span> <span id="cartFabTotal"></span>
</button>

{{-- لوحة السلة والطلب --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartPanel" aria-labelledby="cartTitle">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartTitle">سلة الطلب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="إغلاق"></button>
    </div>
    <div class="offcanvas-body">
        <div id="orderDone" class="d-none text-center">
            <h4 class="mb-2">تم استلام طلبك</h4>
            <p>رقم الطلب: <strong id="doneNumber"></strong></p>
            @if ($loyalty['enabled'])<p class="small text-muted">تُضاف نقاط الولاء لرقم جوالك بعد تسليم الطلب.</p>@endif
            <p class="text-muted">لتأكيد الطلب أسرع، أرسله لنا عبر واتساب:</p>
            <a id="doneWa" class="btn btn-success w-100 mb-2" target="_blank" rel="noopener">أرسل الطلب عبر واتساب</a>
            <button class="btn btn-outline-secondary w-100" data-bs-dismiss="offcanvas">إغلاق</button>
        </div>

        <div id="cartBody">
            <p id="cartEmpty" class="text-muted">السلة فارغة. أضف أصنافاً من القائمة.</p>
            <div id="cartList"></div>
            <div id="cartTotalRow" class="d-flex justify-content-between fw-bold fs-5 py-3 d-none">
                <span>المجموع</span><span id="cartTotal"></span>
            </div>

            <form id="orderForm" class="d-none" novalidate>
                <div class="mb-2">
                    <label class="form-label" for="fName">الاسم</label>
                    <input class="form-control" id="fName" name="name" required maxlength="100">
                </div>
                <div class="mb-2">
                    <label class="form-label" for="fPhone">رقم الهاتف</label>
                    <input class="form-control" id="fPhone" name="phone" type="tel" required maxlength="30">
                </div>
                <div class="mb-2">
                    <label class="form-label" for="fType">طريقة الاستلام</label>
                    <select class="form-select" id="fType" name="type">
                        <option value="delivery">توصيل</option>
                        <option value="pickup">استلام من المطعم</option>
                    </select>
                </div>
                <div class="mb-2" id="addrWrap">
                    <label class="form-label" for="fAddress">العنوان</label>
                    <input class="form-control" id="fAddress" name="address" maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="fNotes">ملاحظات (اختياري)</label>
                    <textarea class="form-control" id="fNotes" name="notes" rows="2" maxlength="500"></textarea>
                </div>
                <div id="formError" class="alert alert-danger py-2 d-none"></div>
                <button id="submitBtn" class="btn btn-brass w-100" type="submit">إرسال الطلب</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var CATALOG = @json((object) $catalog);
    var KEY = 'cart-layali';
    var cart = {};
    try { cart = JSON.parse(localStorage.getItem(KEY)) || {}; } catch (e) { cart = {}; }
    Object.keys(cart).forEach(function (id) { if (!CATALOG[id]) delete cart[id]; });

    var $ = function (id) { return document.getElementById(id); };

    function save() { try { localStorage.setItem(KEY, JSON.stringify(cart)); } catch (e) {} }
    function count() { return Object.values(cart).reduce(function (a, b) { return a + b; }, 0); }
    function total() { return Object.keys(cart).reduce(function (a, id) { return a + CATALOG[id].price * cart[id]; }, 0); }

    function render() {
        var ids = Object.keys(cart), n = count(), t = total();
        $('cartCount').textContent = n;
        $('cartFabTotal').textContent = n ? t + ' ₪' : '';
        $('cartEmpty').classList.toggle('d-none', n > 0);
        $('cartTotalRow').classList.toggle('d-none', n === 0);
        $('orderForm').classList.toggle('d-none', n === 0);
        $('cartTotal').textContent = t + ' ₪';
        var list = $('cartList');
        list.innerHTML = '';
        ids.forEach(function (id) {
            var p = CATALOG[id], row = document.createElement('div');
            row.className = 'cart-line';
            row.innerHTML =
                '<span class="nm"></span>' +
                '<button type="button" class="btn btn-outline-dark qty-btn" data-dec="' + id + '" aria-label="إنقاص">−</button>' +
                '<strong>' + cart[id] + '</strong>' +
                '<button type="button" class="btn btn-outline-dark qty-btn" data-inc="' + id + '" aria-label="زيادة">+</button>' +
                '<span class="text-nowrap" style="min-width:56px;text-align:left">' + (p.price * cart[id]) + ' ₪</span>';
            row.querySelector('.nm').textContent = p.name;
            list.appendChild(row);
        });
    }

    document.addEventListener('click', function (e) {
        var add = e.target.closest('[data-add]');
        var inc = e.target.closest('[data-inc]');
        var dec = e.target.closest('[data-dec]');
        if (add) {
            var id = add.getAttribute('data-add');
            cart[id] = Math.min((cart[id] || 0) + 1, 20);
            add.classList.add('added'); setTimeout(function () { add.classList.remove('added'); }, 700);
        } else if (inc) {
            var i = inc.getAttribute('data-inc'); cart[i] = Math.min(cart[i] + 1, 20);
        } else if (dec) {
            var d = dec.getAttribute('data-dec'); cart[d] -= 1; if (cart[d] <= 0) delete cart[d];
        } else { return; }
        save(); render();
    });

    $('fType').addEventListener('change', function () {
        $('addrWrap').classList.toggle('d-none', this.value === 'pickup');
    });

    $('orderForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var f = e.target, err = $('formError');
        err.classList.add('d-none');
        var delivery = f.type.value === 'delivery';
        if (!f.name.value.trim() || !f.phone.value.trim() || (delivery && !f.address.value.trim())) {
            err.textContent = 'يرجى تعبئة الاسم والهاتف' + (delivery ? ' والعنوان' : '');
            err.classList.remove('d-none');
            return;
        }
        var items = Object.keys(cart).map(function (id) { return { id: id, qty: cart[id] }; });
        var btn = $('submitBtn'); btn.disabled = true; btn.textContent = 'جارٍ الإرسال...';

        fetch('{{ route('order.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: f.name.value, phone: f.phone.value, type: f.type.value,
                address: delivery ? f.address.value : null, notes: f.notes.value, items: items
            })
        })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
        .then(function (res) {
            if (!res.ok) { throw new Error('bad'); }
            $('doneNumber').textContent = '#' + res.j.number;
            $('doneWa').href = res.j.wa;
            $('cartBody').classList.add('d-none');
            $('orderDone').classList.remove('d-none');
            cart = {}; save(); render();
        })
        .catch(function () {
            err.textContent = 'تعذر إرسال الطلب، تأكد من البيانات وحاول مرة أخرى.';
            err.classList.remove('d-none');
        })
        .finally(function () { btn.disabled = false; btn.textContent = 'إرسال الطلب'; });
    });

    // عند فتح السلة من جديد بعد إتمام طلب، نعرض السلة العادية
    $('cartPanel').addEventListener('show.bs.offcanvas', function () {
        $('orderDone').classList.add('d-none');
        $('cartBody').classList.remove('d-none');
    });

    render();
});
</script>

@endsection
