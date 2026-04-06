@extends('layouts.auth2')
@section('title', config('app.name', 'i-Solutions'))
@inject('request', 'Illuminate\Http\Request')

@section('css')
<style>
    /* ── Reset layout injected by auth2 ── */
    body { background: #1a1a2e !important; overflow-x: hidden; }
    .container-fluid, .row.eq-height-row { padding: 0 !important; margin: 0 !important; }
    .right-col { padding: 0 !important; background: none !important; }

    /* Hide auth2's built-in logo circle + header nav row (we have our own nav) */
    .right-col > .row:first-child { display: none !important; }

    /* ── Colour tokens ── */
    :root {
        --brand-green: #4CAF50;
        --brand-green-dark: #388E3C;
        --brand-green-light: #81C784;
        --brand-yellow: #FDD835;
        --brand-dark: #1a1a2e;
        --brand-mid: #16213e;
        --brand-accent: #0f3460;
        --text-light: #f0f4f8;
    }

    /* ── Base ── */
    .welcome-wrapper {
        font-family: 'Raleway', 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, var(--brand-dark) 0%, var(--brand-mid) 50%, var(--brand-accent) 100%);
        min-height: 100vh;
        color: var(--text-light);
        overflow-x: hidden;
    }

    /* ── Animated background particles ── */
    .particles {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        overflow: hidden;
        z-index: 0;
        pointer-events: none;
    }
    .particle {
        position: absolute;
        width: 4px; height: 4px;
        background: var(--brand-green-light);
        border-radius: 50%;
        opacity: 0.3;
        animation: float linear infinite;
    }
    @keyframes float {
        0%   { transform: translateY(100vh) rotate(0deg); opacity: 0; }
        10%  { opacity: 0.3; }
        90%  { opacity: 0.3; }
        100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
    }

    /* ── Navigation bar ── */
    .welcome-nav {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 100;
        background: rgba(26, 26, 46, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(76, 175, 80, 0.2);
        padding: 12px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .nav-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }
    .nav-brand img {
        width: 44px; height: 44px;
        border-radius: 50%;
        border: 2px solid var(--brand-green);
        object-fit: cover;
    }
    .nav-brand-text {
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.5px;
    }
    .nav-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .btn-signin {
        background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
        color: #fff !important;
        border: none;
        padding: 9px 24px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .btn-signin:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.5);
        color: #fff !important;
        text-decoration: none;
    }

    /* ── Hero ── */
    .hero {
        position: relative;
        z-index: 1;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 120px 20px 80px;
    }
    .hero-inner {
        max-width: 860px;
        margin: 0 auto;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(76,175,80,0.15);
        border: 1px solid rgba(76,175,80,0.4);
        color: var(--brand-green-light);
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 6px 18px;
        border-radius: 30px;
        margin-bottom: 24px;
    }
    .hero-badge i { font-size: 0.75rem; }
    .hero-title {
        font-size: clamp(2.4rem, 5vw, 4rem);
        font-weight: 900;
        line-height: 1.15;
        margin-bottom: 16px;
        color: #fff;
    }
    .hero-title .highlight {
        background: linear-gradient(90deg, var(--brand-green), var(--brand-yellow));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.65);
        max-width: 580px;
        margin: 0 auto 40px;
        line-height: 1.7;
    }
    .hero-cta-group {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-primary-hero {
        background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
        color: #fff !important;
        padding: 14px 36px;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(76,175,80,0.4);
        display: inline-flex;
        align-items: center;
        gap: 9px;
    }
    .btn-primary-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(76,175,80,0.55);
        color: #fff !important;
        text-decoration: none;
    }
    .btn-outline-hero {
        background: transparent;
        color: #fff !important;
        padding: 13px 34px;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        border: 2px solid rgba(255,255,255,0.35);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 9px;
    }
    .btn-outline-hero:hover {
        border-color: var(--brand-green);
        color: var(--brand-green-light) !important;
        text-decoration: none;
        transform: translateY(-3px);
    }

    /* ── Stats bar ── */
    .stats-bar {
        position: relative;
        z-index: 1;
        background: rgba(255,255,255,0.04);
        border-top: 1px solid rgba(255,255,255,0.07);
        border-bottom: 1px solid rgba(255,255,255,0.07);
        padding: 32px 20px;
    }
    .stats-inner {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 24px;
    }
    .stat-item { text-align: center; }
    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        color: var(--brand-green);
        line-height: 1;
    }
    .stat-label {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.5);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }

    /* ── Services section ── */
    .services-section {
        position: relative;
        z-index: 1;
        padding: 80px 20px 100px;
    }
    .section-header {
        text-align: center;
        margin-bottom: 56px;
    }
    .section-tag {
        display: inline-block;
        color: var(--brand-green-light);
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }
    .section-title {
        font-size: clamp(1.8rem, 3.5vw, 2.6rem);
        font-weight: 800;
        color: #fff;
        margin-bottom: 14px;
    }
    .section-desc {
        font-size: 1rem;
        color: rgba(255,255,255,0.5);
        max-width: 520px;
        margin: 0 auto;
        line-height: 1.7;
    }
    .section-divider {
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--brand-green), var(--brand-yellow));
        border-radius: 2px;
        margin: 20px auto 0;
    }

    /* ── Service cards ── */
    .services-grid {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }
    .service-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 32px 28px;
        transition: all 0.35s ease;
        position: relative;
        overflow: hidden;
        cursor: default;
    }
    .service-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        border: 1px solid transparent;
        background: linear-gradient(135deg, rgba(76,175,80,0.3), rgba(253,216,53,0.1)) border-box;
        -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: destination-out;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.35s ease;
    }
    .service-card:hover::before { opacity: 1; }
    .service-card:hover {
        background: rgba(76,175,80,0.07);
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.3), 0 0 30px rgba(76,175,80,0.1);
    }
    .service-card.featured {
        border-color: rgba(76,175,80,0.35);
        background: rgba(76,175,80,0.06);
    }
    .card-icon-wrap {
        width: 60px; height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.5rem;
        background: linear-gradient(135deg, rgba(76,175,80,0.2), rgba(76,175,80,0.05));
        border: 1px solid rgba(76,175,80,0.25);
        color: var(--brand-green);
        transition: all 0.35s ease;
    }
    .service-card:hover .card-icon-wrap {
        background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
        border-color: var(--brand-green);
        color: #fff;
        transform: scale(1.1) rotate(-5deg);
    }
    .card-badge {
        position: absolute;
        top: 18px; right: 18px;
        background: linear-gradient(135deg, var(--brand-green), var(--brand-green-dark));
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    .card-desc {
        font-size: 0.875rem;
        color: rgba(255,255,255,0.5);
        line-height: 1.65;
    }
    .card-arrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 18px;
        color: var(--brand-green-light);
        font-size: 0.82rem;
        font-weight: 600;
        opacity: 0;
        transform: translateX(-8px);
        transition: all 0.3s ease;
    }
    .service-card:hover .card-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    /* ── Footer strip ── */
    .welcome-footer {
        position: relative;
        z-index: 1;
        background: rgba(0,0,0,0.3);
        border-top: 1px solid rgba(255,255,255,0.06);
        padding: 28px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .footer-brand {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.4);
    }
    .footer-brand span { color: var(--brand-green-light); font-weight: 600; }
    .footer-link {
        color: rgba(255,255,255,0.4);
        text-decoration: none;
        font-size: 0.85rem;
        transition: color 0.2s;
    }
    .footer-link:hover { color: var(--brand-green-light); text-decoration: none; }

    /* ── Scroll reveal animation ── */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Responsive ── */
    @media (max-width: 767px) {
        .welcome-nav { padding: 10px 20px; }
        .nav-brand-text { display: none; }
        .services-grid { grid-template-columns: 1fr; }
        .welcome-footer { justify-content: center; text-align: center; }
        .stats-inner { gap: 16px; }
    }
</style>
@endsection

@section('content')
<div class="welcome-wrapper">

    <!-- Animated particles -->
    <div class="particles" id="particles"></div>

    <!-- ── Navigation ── -->
    <nav class="welcome-nav">
        <a class="nav-brand" href="#">
            <img src="{{ asset('img/logo-small.png') }}" alt="{{ config('app.name') }}">
            <span class="nav-brand-text">{{ config('app.name', 'i-Solutions') }}</span>
        </a>
        <div class="nav-actions">
            @include('layouts.partials.language_btn')
            <a class="btn-signin"
               href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login'])}}@if(!empty(request()->lang)){{'?lang='.request()->lang}}@endif">
                <i class="fa fa-sign-in"></i> {{ __('business.sign_in') }}
            </a>
        </div>
    </nav>

    <!-- ── Hero ── -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-badge">
                <i class="fa fa-circle"></i>
                Trusted Technology Partner in Burundi
            </div>
            <h1 class="hero-title">
                Optimize Your Business<br>
                with Our Advanced<br>
                <span class="highlight">{{ config('app.name', 'i-Solutions') }} Services</span>
            </h1>
            <p class="hero-subtitle">
                Comprehensive digital solutions designed to accelerate growth, streamline operations, and secure your business in the modern era.
            </p>
            <div class="hero-cta-group">
                <a class="btn-primary-hero"
                   href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login'])}}@if(!empty(request()->lang)){{'?lang='.request()->lang}}@endif">
                    <i class="fa fa-rocket"></i> Get Started
                </a>
                <a class="btn-outline-hero" href="#services">
                    <i class="fa fa-th-large"></i> Our Services
                </a>
            </div>
        </div>
    </section>

    <!-- ── Stats bar ── -->
    <div class="stats-bar">
        <div class="stats-inner">
            <div class="stat-item">
                <div class="stat-number">9+</div>
                <div class="stat-label">Services Offered</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Businesses Served</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">OBR</div>
                <div class="stat-label">Certified Integration</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support Available</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Client Satisfaction</div>
            </div>
        </div>
    </div>

    <!-- ── Services ── -->
    <section class="services-section" id="services">
        <div class="section-header reveal">
            <span class="section-tag">What We Offer</span>
            <h2 class="section-title">Services We Provide to Drive<br>Your Success</h2>
            <p class="section-desc">From point of sale to cloud backup, we deliver end-to-end solutions tailored to your business needs.</p>
            <div class="section-divider"></div>
        </div>

        <div class="services-grid">

            <!-- 1. POS -->
            <div class="service-card featured reveal">
                <div class="card-badge">Core Product</div>
                <div class="card-icon-wrap">
                    <i class="fa fa-desktop"></i>
                </div>
                <h3 class="card-title">Point of Sale (POS) Solutions</h3>
                <p class="card-desc">Complete POS system to manage sales, inventory, receipts, and reporting — all in real time from any device.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 2. OBR -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-file-text-o"></i>
                </div>
                <h3 class="card-title">Invoicing System Integration with OBR</h3>
                <p class="card-desc">Seamlessly integrate your invoicing with the Office Burundais des Recettes for full tax compliance and electronic billing.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 3. Web Dev -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-globe"></i>
                </div>
                <h3 class="card-title">Web Development</h3>
                <p class="card-desc">Modern, responsive websites and web applications built with the latest technologies to represent your brand online.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 4. Software Dev -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-code"></i>
                </div>
                <h3 class="card-title">Software Development</h3>
                <p class="card-desc">Custom software solutions tailored to your workflows — from ERP and CRM to specialised industry applications.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 5. Cybersecurity -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-shield"></i>
                </div>
                <h3 class="card-title">Cybersecurity &amp; Training</h3>
                <p class="card-desc">Protect your digital assets with audits, penetration testing, and staff training to build a security-first culture.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 6. SEO -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-line-chart"></i>
                </div>
                <h3 class="card-title">SEO &amp; Marketing</h3>
                <p class="card-desc">Drive organic traffic and grow your audience with data-driven SEO strategies and targeted digital marketing campaigns.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 7. Cloud Backup -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-cloud"></i>
                </div>
                <h3 class="card-title">Cloud Backup Solutions</h3>
                <p class="card-desc">Automated, encrypted cloud backups so your critical data is always safe, recoverable, and accessible anywhere.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 8. Banking -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-university"></i>
                </div>
                <h3 class="card-title">Core Banking &amp; Agency Banking Solution</h3>
                <p class="card-desc">Robust banking platform covering core operations, agent networks, and mobile money integration for financial institutions.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

            <!-- 9. Car Tracking -->
            <div class="service-card reveal">
                <div class="card-icon-wrap">
                    <i class="fa fa-map-marker"></i>
                </div>
                <h3 class="card-title">Car Tracking System</h3>
                <p class="card-desc">Real-time GPS fleet tracking with live maps, route history, alerts, and driver behaviour analytics for full visibility.</p>
                <div class="card-arrow"><i class="fa fa-arrow-right"></i> Learn more</div>
            </div>

        </div>
    </section>

    <!-- ── Footer ── -->
    <footer class="welcome-footer">
        <div class="footer-brand">
            &copy; {{ date('Y') }} <span>{{ config('app.name', 'i-Solutions') }}</span> &mdash; www.i-solutions.bi
        </div>
        <div style="display:flex; gap:24px; flex-wrap:wrap; justify-content:center;">
            <a class="footer-link" href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login']) }}">
                <i class="fa fa-sign-in"></i> Sign In
            </a>
            @if (config('constants.allow_registration'))
            <a class="footer-link" href="{{ route('business.getRegister') }}">
                <i class="fa fa-user-plus"></i> Register
            </a>
            @endif
        </div>
    </footer>

</div>
@endsection

@section('javascript')
<script>
(function () {
    /* ── Generate floating particles ── */
    var container = document.getElementById('particles');
    for (var i = 0; i < 35; i++) {
        var p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.width = p.style.height = (Math.random() * 4 + 2) + 'px';
        p.style.animationDuration = (Math.random() * 18 + 12) + 's';
        p.style.animationDelay = (Math.random() * 20) + 's';
        p.style.opacity = Math.random() * 0.25 + 0.05;
        container.appendChild(p);
    }

    /* ── Scroll-reveal ── */
    function checkReveal() {
        var els = document.querySelectorAll('.reveal');
        var wh = window.innerHeight;
        els.forEach(function (el, i) {
            var rect = el.getBoundingClientRect();
            if (rect.top < wh - 60) {
                setTimeout(function () { el.classList.add('visible'); }, i * 80);
            }
        });
    }
    window.addEventListener('scroll', checkReveal, { passive: true });
    checkReveal();

    /* ── Smooth scroll for anchor links ── */
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
})();
</script>
@endsection
