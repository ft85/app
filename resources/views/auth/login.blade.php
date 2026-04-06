@extends('layouts.auth2')
@section('title', __('lang_v1.login'))
@inject('request', 'Illuminate\Http\Request')

@section('css')
<style>
/* ── Kill auth2 chrome ── */
body { margin: 0; padding: 0; overflow: hidden; background: #0d0d1a !important; }
.container-fluid, .row.eq-height-row { padding: 0 !important; margin: 0 !important; max-width: 100%; }
.right-col { padding: 0 !important; background: none !important; }
.right-col > .row:first-child { display: none !important; }

/* ── Tokens ── */
:root {
    --g: #4CAF50;
    --gd: #2e7d32;
    --gl: #81C784;
    --y: #FDD835;
    --dark: #0d0d1a;
    --mid: #111827;
    --glass: rgba(255,255,255,0.04);
    --glass-border: rgba(255,255,255,0.09);
    --text: #f1f5f9;
    --muted: rgba(241,245,249,0.45);
}

*, *::before, *::after { box-sizing: border-box; }

/* ── Root page ── */
.lp-root {
    display: flex;
    height: 100vh;
    width: 100vw;
    font-family: 'Raleway','Segoe UI',sans-serif;
    overflow: hidden;
}

/* ═══════════════════════════════════════
   LEFT PANEL
═══════════════════════════════════════ */
.lp-left {
    flex: 1.1;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px 56px;
    overflow: hidden;
    background: linear-gradient(145deg, #0a1628 0%, #0d2137 40%, #072010 100%);
}

/* Orb blobs */
.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    animation: drift ease-in-out infinite alternate;
    pointer-events: none;
}
.orb-1 { width: 480px; height: 480px; background: radial-gradient(circle, rgba(76,175,80,0.22) 0%, transparent 70%); top: -160px; left: -120px; animation-duration: 9s; }
.orb-2 { width: 360px; height: 360px; background: radial-gradient(circle, rgba(253,216,53,0.1) 0%, transparent 70%);  bottom: -80px; right: -80px; animation-duration: 12s; animation-delay: -4s; }
.orb-3 { width: 240px; height: 240px; background: radial-gradient(circle, rgba(76,175,80,0.15) 0%, transparent 70%); bottom: 160px; left: 60px; animation-duration: 7s; animation-delay: -2s; }

@keyframes drift {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(20px, 30px) scale(1.08); }
}

/* Grid overlay */
.lp-left::after {
    content: '';
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(76,175,80,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(76,175,80,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
}

/* Left content */
.lp-brand { position: relative; z-index: 2; display: flex; align-items: center; gap: 14px; margin-bottom: 56px; }
.lp-brand img { width: 52px; height: 52px; border-radius: 14px; border: 2px solid rgba(76,175,80,0.5); object-fit: cover; box-shadow: 0 0 20px rgba(76,175,80,0.3); }
.lp-brand-name { font-size: 1.15rem; font-weight: 800; color: #fff; letter-spacing: 0.3px; }
.lp-brand-sub { font-size: 0.72rem; color: var(--gl); letter-spacing: 1.5px; text-transform: uppercase; }

.lp-headline { position: relative; z-index: 2; margin-bottom: 40px; }
.lp-headline h2 { font-size: clamp(1.9rem, 2.8vw, 2.7rem); font-weight: 900; color: #fff; line-height: 1.18; margin: 0 0 14px; }
.lp-headline h2 em { font-style: normal; background: linear-gradient(90deg, var(--g), var(--y)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.lp-headline p { font-size: 0.95rem; color: var(--muted); line-height: 1.7; max-width: 380px; margin: 0; }

/* Service pills */
.lp-services { position: relative; z-index: 2; display: flex; flex-direction: column; gap: 11px; }
.lp-svc {
    display: flex; align-items: center; gap: 13px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 12px 16px;
    opacity: 0;
    transform: translateX(-20px);
    animation: slideIn 0.5s forwards;
}
.lp-svc:nth-child(1) { animation-delay: 0.1s; }
.lp-svc:nth-child(2) { animation-delay: 0.2s; }
.lp-svc:nth-child(3) { animation-delay: 0.3s; }
.lp-svc:nth-child(4) { animation-delay: 0.4s; }
.lp-svc:nth-child(5) { animation-delay: 0.5s; }

@keyframes slideIn {
    to { opacity: 1; transform: translateX(0); }
}

.lp-svc-icon {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, rgba(76,175,80,0.25), rgba(76,175,80,0.08));
    border: 1px solid rgba(76,175,80,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--gl); font-size: 0.9rem;
}
.lp-svc-text { font-size: 0.82rem; color: rgba(255,255,255,0.7); font-weight: 500; line-height: 1.35; }
.lp-svc-text strong { color: #fff; font-size: 0.87rem; display: block; }

/* Bottom link */
.lp-back { position: relative; z-index: 2; margin-top: 40px; }
.lp-back a {
    display: inline-flex; align-items: center; gap: 7px;
    color: var(--muted); font-size: 0.82rem; text-decoration: none;
    transition: color 0.2s;
}
.lp-back a:hover { color: var(--gl); text-decoration: none; }
.lp-back a i { font-size: 0.75rem; }

/* ═══════════════════════════════════════
   RIGHT PANEL
═══════════════════════════════════════ */
.lp-right {
    width: 460px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--mid);
    position: relative;
    overflow: hidden;
}

/* Subtle top-right glow */
.lp-right::before {
    content: '';
    position: absolute;
    top: -120px; right: -120px;
    width: 320px; height: 320px;
    background: radial-gradient(circle, rgba(76,175,80,0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.lp-form-wrap {
    width: 100%;
    max-width: 380px;
    padding: 0 24px;
    position: relative; z-index: 1;
}

/* Form header */
.lp-form-header { text-align: center; margin-bottom: 36px; }
.lp-form-logo {
    width: 68px; height: 68px; border-radius: 18px;
    border: 2px solid rgba(76,175,80,0.4);
    background: rgba(76,175,80,0.08);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    overflow: hidden;
    box-shadow: 0 0 30px rgba(76,175,80,0.15);
}
.lp-form-logo img { width: 100%; height: 100%; object-fit: cover; border-radius: 16px; }
.lp-form-header h1 { font-size: 1.55rem; font-weight: 800; color: var(--text); margin: 0 0 6px; }
.lp-form-header p { font-size: 0.85rem; color: var(--muted); margin: 0; }
.lp-form-header p span { color: var(--gl); font-weight: 600; }

/* Alert / error */
.lp-alert {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.25);
    border-radius: 12px;
    color: #fca5a5;
    font-size: 0.82rem;
    padding: 11px 14px;
    margin-bottom: 20px;
    display: flex; align-items: flex-start; gap: 9px;
}
.lp-alert i { color: #f87171; margin-top: 1px; flex-shrink: 0; }

/* Input groups */
.lp-field { margin-bottom: 18px; }
.lp-label {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 7px;
}
.lp-label span { font-size: 0.8rem; font-weight: 600; color: rgba(241,245,249,0.75); }
.lp-label a { font-size: 0.78rem; color: var(--gl); text-decoration: none; font-weight: 500; }
.lp-label a:hover { color: #fff; text-decoration: none; }

.lp-input-wrap { position: relative; }
.lp-input-wrap i.ico-left {
    position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
    color: rgba(255,255,255,0.25); font-size: 0.9rem; pointer-events: none;
    transition: color 0.25s;
}
.lp-input-wrap input:focus ~ i.ico-left,
.lp-input-wrap:focus-within i.ico-left { color: var(--gl); }

.lp-input {
    width: 100%;
    height: 50px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    color: var(--text);
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0 44px 0 42px;
    outline: none;
    transition: all 0.25s ease;
    font-family: inherit;
}
.lp-input::placeholder { color: rgba(255,255,255,0.22); }
.lp-input:focus {
    border-color: rgba(76,175,80,0.55);
    background: rgba(76,175,80,0.06);
    box-shadow: 0 0 0 3px rgba(76,175,80,0.12);
    color: #fff;
}
.lp-input.is-invalid { border-color: rgba(239,68,68,0.5) !important; }

/* Password toggle */
.lp-eye {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: rgba(255,255,255,0.25); font-size: 0.9rem;
    padding: 0; transition: color 0.2s;
}
.lp-eye:hover { color: var(--gl); }

/* Error text */
.lp-error { font-size: 0.75rem; color: #f87171; margin-top: 5px; display: flex; align-items: center; gap: 5px; }

/* Remember row */
.lp-remember {
    display: flex; align-items: center; gap: 9px;
    margin-bottom: 24px; cursor: pointer;
}
.lp-remember input[type="checkbox"] { display: none; }
.lp-check-box {
    width: 18px; height: 18px; border-radius: 5px; flex-shrink: 0;
    border: 1.5px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.05);
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.lp-check-box i { color: #fff; font-size: 0.6rem; opacity: 0; transition: opacity 0.2s; }
.lp-remember.checked .lp-check-box { background: var(--g); border-color: var(--g); }
.lp-remember.checked .lp-check-box i { opacity: 1; }
.lp-remember span { font-size: 0.82rem; color: rgba(241,245,249,0.6); font-weight: 500; }

/* Submit button */
.lp-btn {
    width: 100%;
    height: 52px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--g) 0%, var(--gd) 100%);
    color: #fff;
    font-size: 0.97rem;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(76,175,80,0.35);
    display: flex; align-items: center; justify-content: center; gap: 9px;
    letter-spacing: 0.2px;
}
.lp-btn::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
    opacity: 0; transition: opacity 0.3s;
}
.lp-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(76,175,80,0.5); }
.lp-btn:hover::before { opacity: 1; }
.lp-btn:active { transform: translateY(0); box-shadow: 0 4px 14px rgba(76,175,80,0.35); }
.lp-btn.loading { pointer-events: none; opacity: 0.8; }

/* Spinner */
.lp-spinner {
    display: none;
    width: 18px; height: 18px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}
.lp-btn.loading .lp-spinner { display: block; }
.lp-btn.loading .lp-btn-text { opacity: 0.6; }
@keyframes spin { to { transform: rotate(360deg); } }

/* Divider */
.lp-divider { display: flex; align-items: center; gap: 12px; margin: 22px 0 0; }
.lp-divider hr { flex: 1; border: none; border-top: 1px solid rgba(255,255,255,0.08); }
.lp-divider span { font-size: 0.74rem; color: var(--muted); white-space: nowrap; }

/* Footer */
.lp-form-footer { text-align: center; margin-top: 26px; }
.lp-form-footer p { font-size: 0.78rem; color: var(--muted); margin: 0; }
.lp-form-footer a { color: var(--gl); font-weight: 600; text-decoration: none; }
.lp-form-footer a:hover { color: #fff; text-decoration: none; }

/* Security badge */
.lp-secure {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin-top: 28px;
    font-size: 0.74rem; color: rgba(255,255,255,0.2);
}
.lp-secure i { font-size: 0.72rem; color: var(--gl); opacity: 0.6; }

/* ── Responsive ── */
@media (max-width: 900px) {
    .lp-left { display: none; }
    .lp-right { width: 100%; }
}
@media (max-width: 480px) {
    .lp-form-wrap { padding: 0 16px; }
}
</style>
@endsection

@section('content')
@php
    $username = old('username');
    $password = null;
    if (config('app.env') == 'demo') {
        $username = 'admin';
        $password = '123456';
    }
@endphp

<div class="lp-root">

    <!-- ═══════════ LEFT PANEL ═══════════ -->
    <div class="lp-left">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <!-- Brand -->
        <div class="lp-brand">
            <img src="{{ asset('img/logo-small.png') }}" alt="{{ config('app.name') }}">
            <div>
                <div class="lp-brand-name">{{ config('app.name', 'I-Solutions POS') }}</div>
                <div class="lp-brand-sub">www.i-solutions.bi</div>
            </div>
        </div>

        <!-- Headline -->
        <div class="lp-headline">
            <h2>Your Complete<br><em>Business Suite</em><br>in One Platform</h2>
            <p>From point of sale to core banking — manage everything with speed, security, and confidence.</p>
        </div>

        <!-- Services preview -->
        <div class="lp-services">
            <div class="lp-svc">
                <div class="lp-svc-icon"><i class="fa fa-desktop"></i></div>
                <div class="lp-svc-text"><strong>Point of Sale (POS)</strong>Real-time sales, inventory & receipts</div>
            </div>
            <div class="lp-svc">
                <div class="lp-svc-icon"><i class="fa fa-file-text-o"></i></div>
                <div class="lp-svc-text"><strong>OBR Invoicing Integration</strong>Full tax compliance & e-billing</div>
            </div>
            <div class="lp-svc">
                <div class="lp-svc-icon"><i class="fa fa-shield"></i></div>
                <div class="lp-svc-text"><strong>Cybersecurity & Training</strong>Protect your digital assets</div>
            </div>
            <div class="lp-svc">
                <div class="lp-svc-icon"><i class="fa fa-university"></i></div>
                <div class="lp-svc-text"><strong>Core & Agency Banking</strong>End-to-end financial platform</div>
            </div>
            <div class="lp-svc">
                <div class="lp-svc-icon"><i class="fa fa-map-marker"></i></div>
                <div class="lp-svc-text"><strong>Car Tracking System</strong>Live GPS fleet management</div>
            </div>
        </div>

        <!-- Back link -->
        <div class="lp-back">
            <a href="{{ url('/') }}">
                <i class="fa fa-arrow-left"></i> Back to home
            </a>
        </div>
    </div>

    <!-- ═══════════ RIGHT PANEL ═══════════ -->
    <div class="lp-right">
        <div class="lp-form-wrap">

            <!-- Header -->
            <div class="lp-form-header">
                <div class="lp-form-logo">
                    <img src="{{ asset('img/logo-small.png') }}" alt="{{ config('app.name') }}">
                </div>
                <h1>Welcome back</h1>
                <p>Sign in to <span>{{ config('app.name', 'I-Solutions POS') }}</span></p>
            </div>

            <!-- Errors -->
            @if ($errors->any())
            <div class="lp-alert">
                <i class="fa fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" id="login-form">
                {{ csrf_field() }}

                <!-- Username -->
                <div class="lp-field">
                    <div class="lp-label">
                        <span>@lang('Username')</span>
                    </div>
                    <div class="lp-input-wrap">
                        <input
                            class="lp-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            type="text"
                            name="username"
                            id="username"
                            value="{{ $username }}"
                            placeholder="Enter your username"
                            required
                            autofocus
                            data-last-active-input=""
                            autocomplete="username"
                        />
                        <i class="fa fa-user ico-left"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="lp-field">
                    <div class="lp-label">
                        <span>@lang('Password')</span>
                        @if (config('app.env') != 'demo')
                        <a href="{{ route('password.request') }}" tabindex="-1">@lang('lang_v1.forgot_your_password')</a>
                        @endif
                    </div>
                    <div class="lp-input-wrap">
                        <input
                            class="lp-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            type="password"
                            name="password"
                            id="password"
                            value="{{ $password }}"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        />
                        <i class="fa fa-lock ico-left"></i>
                        <button type="button" class="lp-eye" id="togglePwd" aria-label="Toggle password">
                            <i class="fa fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember me -->
                <label class="lp-remember" id="rememberLabel">
                    <input type="checkbox" name="remember" id="rememberCheck" {{ old('remember') ? 'checked' : '' }}>
                    <div class="lp-check-box {{ old('remember') ? 'checked' : '' }}">
                        <i class="fa fa-check"></i>
                    </div>
                    <span>@lang('lang_v1.remember_me')</span>
                </label>

                <!-- Submit -->
                <button type="submit" class="lp-btn" id="submitBtn">
                    <div class="lp-spinner"></div>
                    <span class="lp-btn-text">
                        <i class="fa fa-sign-in"></i>
                        @lang('lang_v1.login')
                    </span>
                </button>

                @if (config('constants.allow_registration'))
                <div class="lp-divider">
                    <hr><span>or</span><hr>
                </div>
                <div class="lp-form-footer">
                    <p>{{ __('business.not_yet_registered') }}
                        <a href="{{ route('business.getRegister') }}@if(!empty(request()->lang)){{'?lang='.request()->lang}}@endif">
                            {{ __('business.register_now') }}
                        </a>
                    </p>
                </div>
                @endif

            </form>

            <!-- Security badge -->
            <div class="lp-secure">
                <i class="fa fa-lock"></i>
                Secured with 256-bit SSL encryption
            </div>

        </div>
    </div><!-- /.lp-right -->

</div><!-- /.lp-root -->
@stop

@section('javascript')
<script>
$(document).ready(function () {

    /* ── Language change ── */
    $('.change_lang').click(function () {
        window.location = "{{ route('login') }}?lang=" + $(this).attr('value');
    });

    /* ── Password toggle ── */
    $('#togglePwd').on('click', function () {
        var inp = $('#password');
        var ico = $('#eyeIcon');
        if (inp.attr('type') === 'password') {
            inp.attr('type', 'text');
            ico.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            inp.attr('type', 'password');
            ico.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    /* ── Custom checkbox ── */
    $('#rememberLabel').on('click', function () {
        var chk = $('#rememberCheck');
        chk.prop('checked', !chk.prop('checked'));
        $(this).toggleClass('checked', chk.prop('checked'));
        $(this).find('.lp-check-box').toggleClass('checked', chk.prop('checked'));
        return false; // prevent double-toggle from native label
    });

    /* ── Submit loading state ── */
    $('#login-form').on('submit', function () {
        $('#submitBtn').addClass('loading');
    });

    /* ── Input focus glow sync ── */
    $('.lp-input').on('focus', function () {
        $(this).closest('.lp-input-wrap').find('.ico-left').css('color', 'var(--gl)');
    }).on('blur', function () {
        $(this).closest('.lp-input-wrap').find('.ico-left').css('color', 'rgba(255,255,255,0.25)');
    });

    /* ── Demo login ── */
    $('a.demo-login').click(function (e) {
        e.preventDefault();
        $('#username').val($(this).data('admin'));
        $('#password').val("{{ $password }}");
        $('form#login-form').submit();
    });
});
</script>
@endsection
