@extends('layouts.app')
@section('title', __('home.home'))

@section('css')
<style>
/* ═══════════════════════════════════════════
   DASHBOARD DESIGN SYSTEM
   i-Solutions POS — Premium Dashboard
═══════════════════════════════════════════ */
:root {
    --db-green:       #16a34a;
    --db-green-light: #22c55e;
    --db-green-bg:    #f0fdf4;
    --db-blue:        #2563eb;
    --db-blue-bg:     #eff6ff;
    --db-sky:         #0ea5e9;
    --db-sky-bg:      #f0f9ff;
    --db-amber:       #d97706;
    --db-amber-bg:    #fffbeb;
    --db-red:         #dc2626;
    --db-red-bg:      #fef2f2;
    --db-purple:      #7c3aed;
    --db-purple-bg:   #f5f3ff;
    --db-orange:      #ea580c;
    --db-orange-bg:   #fff7ed;
    --db-dark:        #0f172a;
    --db-mid:         #1e293b;
    --db-card-bg:     #ffffff;
    --db-page-bg:     #f1f5f9;
    --db-border:      #e2e8f0;
    --db-text:        #0f172a;
    --db-muted:       #64748b;
    --db-radius:      16px;
    --db-shadow:      0 1px 3px rgba(0,0,0,.07), 0 4px 12px rgba(0,0,0,.05);
    --db-shadow-hover:0 4px 6px rgba(0,0,0,.06), 0 10px 30px rgba(0,0,0,.1);
}

/* ── Page background ── */
.tw-bg-gray-100 { background-color: var(--db-page-bg) !important; }

/* ══════════════════════════════════
   HERO HEADER BANNER
══════════════════════════════════ */
.db-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0d3321 100%);
    position: relative;
    overflow: hidden;
    padding: 28px 28px 72px;
}
.db-hero::before {
    content: '';
    position: absolute; inset: 0;
    background-image:
        radial-gradient(circle at 20% 50%, rgba(34,197,94,0.12) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(37,99,235,0.1) 0%, transparent 45%);
    pointer-events: none;
}
.db-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}
.db-hero-inner {
    position: relative; z-index: 1;
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 16px;
}
.db-hero-left {}
.db-hero-greeting {
    display: flex; align-items: center; gap: 10px;
    font-size: 0.75rem; font-weight: 700;
    color: rgba(34,197,94,0.9);
    letter-spacing: 1.8px; text-transform: uppercase;
    margin-bottom: 8px;
}
.db-hero-greeting span { display: inline-block; width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: pulse-dot 2s ease-in-out infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(0.7)} }
.db-hero-title {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 900; color: #fff; margin: 0 0 8px;
    line-height: 1.2;
}
.db-hero-title em { font-style: normal; color: #4ade80; }
.db-hero-sub { font-size: 0.88rem; color: rgba(255,255,255,0.5); margin: 0; }

.db-hero-right {
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
}
.db-hero-clock {
    text-align: right;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 10px 16px;
}
.db-hero-clock .time { font-size: 1.3rem; font-weight: 800; color: #fff; font-variant-numeric: tabular-nums; }
.db-hero-clock .date { font-size: 0.72rem; color: rgba(255,255,255,0.45); margin-top: 1px; }

.db-filter-btn {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px;
    color: #fff; font-size: 0.83rem; font-weight: 600;
    padding: 10px 16px; cursor: pointer; transition: all 0.2s;
    font-family: inherit;
}
.db-filter-btn:hover { background: rgba(255,255,255,0.14); border-color: rgba(255,255,255,0.25); }
.db-filter-btn i { font-size: 0.8rem; }

.db-location-wrap .select2-container .select2-selection--single {
    background: rgba(255,255,255,0.08) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    border-radius: 10px !important;
    height: 40px !important; color: #fff;
}
.db-location-wrap .select2-container .select2-selection--single .select2-selection__rendered {
    color: #fff !important; line-height: 38px !important; padding: 0 12px !important;
    font-size: 0.83rem; font-weight: 600;
}
.db-location-wrap .select2-container .select2-selection--single .select2-selection__arrow { height: 38px !important; }

/* ══════════════════════════════════
   STAT CARDS — ROW (floated up)
══════════════════════════════════ */
.db-stats-row {
    margin: -44px 28px 0;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
    position: relative; z-index: 10;
}

.db-stat-card {
    background: var(--db-card-bg);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    border: 1px solid var(--db-border);
    padding: 20px;
    display: flex; align-items: center; gap: 16px;
    transition: all 0.25s ease;
    position: relative; overflow: hidden;
}
.db-stat-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--card-accent, #22c55e);
    border-radius: var(--db-radius) var(--db-radius) 0 0;
}
.db-stat-card:hover { box-shadow: var(--db-shadow-hover); transform: translateY(-3px); }

.db-stat-icon {
    width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.db-stat-body {}
.db-stat-label { font-size: 0.75rem; font-weight: 600; color: var(--db-muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 4px; }
.db-stat-value { font-size: 1.35rem; font-weight: 800; color: var(--db-text); font-variant-numeric: tabular-nums; line-height: 1.1; min-height: 1.4em; }
.db-stat-sub { font-size: 0.7rem; color: var(--db-muted); margin-top: 3px; }

/* Card colour variants */
.db-stat-card.green  { --card-accent: #22c55e; }
.db-stat-card.green  .db-stat-icon  { background: var(--db-green-bg); color: var(--db-green); }
.db-stat-card.blue   { --card-accent: #3b82f6; }
.db-stat-card.blue   .db-stat-icon  { background: var(--db-blue-bg); color: var(--db-blue); }
.db-stat-card.sky    { --card-accent: #0ea5e9; }
.db-stat-card.sky    .db-stat-icon  { background: var(--db-sky-bg); color: var(--db-sky); }
.db-stat-card.amber  { --card-accent: #f59e0b; }
.db-stat-card.amber  .db-stat-icon  { background: var(--db-amber-bg); color: var(--db-amber); }
.db-stat-card.red    { --card-accent: #ef4444; }
.db-stat-card.red    .db-stat-icon  { background: var(--db-red-bg); color: var(--db-red); }
.db-stat-card.purple { --card-accent: #8b5cf6; }
.db-stat-card.purple .db-stat-icon  { background: var(--db-purple-bg); color: var(--db-purple); }
.db-stat-card.orange { --card-accent: #f97316; }
.db-stat-card.orange .db-stat-icon  { background: var(--db-orange-bg); color: var(--db-orange); }

/* ══════════════════════════════════
   SECTION HEADERS
══════════════════════════════════ */
.db-section { padding: 24px 28px; }
.db-section-title {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 16px;
}
.db-section-title h2 {
    font-size: 0.7rem; font-weight: 700;
    color: var(--db-muted); text-transform: uppercase; letter-spacing: 1.5px;
    margin: 0;
}
.db-section-title::after {
    content: ''; flex: 1; height: 1px;
    background: var(--db-border);
}

/* ══════════════════════════════════
   CONTENT CARDS (charts & tables)
══════════════════════════════════ */
.db-card {
    background: var(--db-card-bg);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    border: 1px solid var(--db-border);
    overflow: hidden;
    transition: all 0.25s ease;
}
.db-card:hover { box-shadow: var(--db-shadow-hover); }

.db-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px 0; gap: 12px; flex-wrap: wrap;
}
.db-card-title {
    display: flex; align-items: center; gap: 10px;
}
.db-card-title-icon {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem;
}
.db-card-title h3 {
    font-size: 1rem; font-weight: 700; color: var(--db-text); margin: 0;
}
.db-card-title p {
    font-size: 0.74rem; color: var(--db-muted); margin: 2px 0 0;
}
.db-card-body { padding: 16px 20px 20px; }
.db-card-body-flush { padding: 0; }

/* Chart wrapper */
.db-chart-wrap {
    background: #fafafa;
    border: 1px dashed var(--db-border);
    border-radius: 12px;
    padding: 4px;
    margin-top: 12px;
}

/* ══════════════════════════════════
   TABLE STYLING
══════════════════════════════════ */
.db-card .table { margin: 0; }
.db-card .table thead th {
    background: #f8fafc;
    border-bottom: 2px solid var(--db-border) !important;
    border-top: none !important;
    color: var(--db-muted);
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.6px;
    padding: 10px 12px;
    white-space: nowrap;
}
.db-card .table tbody td {
    border-color: var(--db-border) !important;
    font-size: 0.84rem; color: var(--db-text);
    padding: 10px 12px; vertical-align: middle;
}
.db-card .table-striped tbody tr:nth-of-type(odd) { background-color: #fafafa; }
.db-card .table tbody tr:hover { background-color: #f0fdf4 !important; }

/* ══════════════════════════════════
   LOCATION SELECT (in cards)
══════════════════════════════════ */
.db-card .select2-container { min-width: 180px; }
.db-card .select2-container .select2-selection--single {
    height: 36px !important; border-radius: 8px !important;
    border-color: var(--db-border) !important; background: #f8fafc !important;
}
.db-card .select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 34px !important; font-size: 0.82rem; color: var(--db-text) !important;
}
.db-card .select2-container .select2-selection--single .select2-selection__arrow { height: 34px !important; }

/* ══════════════════════════════════
   GRID LAYOUTS
══════════════════════════════════ */
.db-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.db-grid-full { display: grid; grid-template-columns: 1fr; gap: 16px; }

@media (max-width: 1024px) { .db-grid-2 { grid-template-columns: 1fr; } }
@media (max-width: 768px)  {
    .db-stats-row { margin: -30px 16px 0; grid-template-columns: 1fr 1fr; }
    .db-section { padding: 20px 16px; }
    .db-hero { padding: 20px 16px 60px; }
}
@media (max-width: 540px)  { .db-stats-row { grid-template-columns: 1fr; } }

/* ── Scrollbar ── */
.db-card ::-webkit-scrollbar { height: 5px; width: 5px; }
.db-card ::-webkit-scrollbar-track { background: #f1f5f9; }
.db-card ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

/* ── select2 override for hero ── */
.db-location-wrap { min-width: 200px; }
</style>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════════
     HERO BANNER
══════════════════════════════════════════════════════ --}}
<div class="db-hero">
    <div class="db-hero-inner">

        {{-- Left: greeting --}}
        <div class="db-hero-left">
            @if (auth()->user()->can('dashboard.data') && $is_admin)
            <div class="db-hero-greeting">
                <span></span> Dashboard Overview
            </div>
            @endif
            <h1 class="db-hero-title">
                {{ __('home.welcome_message', ['name' => Session::get('user.first_name')]) }} <em>👋</em>
            </h1>
            <p class="db-hero-sub">{{ config('app.name') }} &mdash; {{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
        </div>

        {{-- Right: clock + filters --}}
        <div class="db-hero-right">
            <div class="db-hero-clock">
                <div class="time">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
                <div class="date">{{ \Carbon\Carbon::now()->format('D, M j') }}</div>
            </div>

            @if (auth()->user()->can('dashboard.data') && $is_admin)
                @if (count($all_locations) > 1)
                <div class="db-location-wrap">
                    {!! Form::select('dashboard_location', $all_locations, null, [
                        'class' => 'form-control select2',
                        'placeholder' => __('lang_v1.select_location'),
                        'id' => 'dashboard_location',
                    ]) !!}
                </div>
                @endif

                <button type="button" id="dashboard_date_filter" class="db-filter-btn">
                    <i class="fa fa-calendar"></i>
                    {{ __('messages.filter_by_date') }}
                    <i class="fa fa-chevron-down" style="font-size:0.65rem; opacity:0.6"></i>
                </button>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     KPI STAT CARDS (floated up over hero)
══════════════════════════════════════════════════════ --}}
@if (auth()->user()->can('dashboard.data') && $is_admin)
<div class="db-stats-row">

    {{-- Total Sales --}}
    <div class="db-stat-card sky">
        <div class="db-stat-icon">
            <i class="fa fa-shopping-cart"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">{{ __('home.total_sell') }}</div>
            <div class="db-stat-value total_sell">—</div>
        </div>
    </div>

    {{-- Net Profit --}}
    <div class="db-stat-card green">
        <div class="db-stat-icon">
            <i class="fa fa-dollar"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">{{ __('lang_v1.net') }} @show_tooltip(__('lang_v1.net_home_tooltip'))</div>
            <div class="db-stat-value net">—</div>
        </div>
    </div>

    {{-- Invoice Due --}}
    <div class="db-stat-card amber">
        <div class="db-stat-icon">
            <i class="fa fa-file-text-o"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">{{ __('home.invoice_due') }}</div>
            <div class="db-stat-value invoice_due">—</div>
        </div>
    </div>

    {{-- Sell Return --}}
    <div class="db-stat-card red">
        <div class="db-stat-icon">
            <i class="fa fa-exchange"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">
                {{ __('lang_v1.total_sell_return') }}
                <i class="fa fa-info-circle text-info hover-q no-print" aria-hidden="true" data-container="body"
                    data-toggle="popover" data-placement="auto bottom" id="total_srp"
                    data-value="{{ __('lang_v1.total_sell_return') }}-{{ __('lang_v1.total_sell_return_paid') }}"
                    data-content="" data-html="true" data-trigger="hover"></i>
            </div>
            <div class="db-stat-value total_sell_return">—</div>
        </div>
    </div>

    {{-- Total Purchase --}}
    <div class="db-stat-card blue">
        <div class="db-stat-icon">
            <i class="fa fa-download"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">{{ __('home.total_purchase') }}</div>
            <div class="db-stat-value total_purchase">—</div>
        </div>
    </div>

    {{-- Purchase Due --}}
    <div class="db-stat-card orange">
        <div class="db-stat-icon">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">{{ __('home.purchase_due') }}</div>
            <div class="db-stat-value purchase_due">—</div>
        </div>
    </div>

    {{-- Purchase Return --}}
    <div class="db-stat-card purple">
        <div class="db-stat-icon">
            <i class="fa fa-undo"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">
                {{ __('lang_v1.total_purchase_return') }}
                <i class="fa fa-info-circle text-info hover-q no-print" aria-hidden="true" data-container="body"
                    data-toggle="popover" data-placement="auto bottom" id="total_prp"
                    data-value="{{ __('lang_v1.total_purchase_return') }}-{{ __('lang_v1.total_purchase_return_paid') }}"
                    data-content="" data-html="true" data-trigger="hover"></i>
            </div>
            <div class="db-stat-value total_purchase_return">—</div>
        </div>
    </div>

    {{-- Expenses --}}
    <div class="db-stat-card red">
        <div class="db-stat-icon">
            <i class="fa fa-minus-circle"></i>
        </div>
        <div class="db-stat-body">
            <div class="db-stat-label">{{ __('lang_v1.expense') }}</div>
            <div class="db-stat-value total_expense">—</div>
        </div>
    </div>

</div>
@endif

{{-- ══════════════════════════════════════════════════════
     MAIN DASHBOARD CONTENT
══════════════════════════════════════════════════════ --}}
@if (auth()->user()->can('dashboard.data'))

    {{-- CHARTS SECTION --}}
    @if ($is_admin && !empty($all_locations))
    <div class="db-section" style="padding-top: 36px;">
        <div class="db-section-title"><h2><i class="fa fa-bar-chart" style="margin-right:6px"></i> Sales Analytics</h2></div>

        <div class="db-grid-full" style="gap:16px">

            {{-- Sells last 30 days --}}
            @if (auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.view'))
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#eff6ff;color:#2563eb">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <div>
                            <h3>{{ __('home.sells_last_30_days') }}</h3>
                            <p>Daily sales trend for the past 30 days</p>
                        </div>
                    </div>
                </div>
                <div class="db-card-body">
                    <div class="db-chart-wrap">
                        {!! $sells_chart_1->container() !!}
                    </div>
                </div>
            </div>

            {{-- Sells current FY --}}
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#f0fdf4;color:#16a34a">
                            <i class="fa fa-area-chart"></i>
                        </div>
                        <div>
                            <h3>{{ __('home.sells_current_fy') }}</h3>
                            <p>Monthly sales for the current financial year</p>
                        </div>
                    </div>
                </div>
                <div class="db-card-body">
                    <div class="db-chart-wrap">
                        {!! $sells_chart_2->container() !!}
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
    @endif

    {{-- DUES & ALERTS SECTION --}}
    <div class="db-section">
        <div class="db-section-title"><h2><i class="fa fa-bell-o" style="margin-right:6px"></i> Dues &amp; Alerts</h2></div>

        <div class="db-grid-2">

            {{-- Sales Payment Dues --}}
            @if (auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.view'))
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#fffbeb;color:#d97706">
                            <i class="fa fa-credit-card"></i>
                        </div>
                        <div>
                            <h3>{{ __('lang_v1.sales_payment_dues') }} @show_tooltip(__('lang_v1.tooltip_sales_payment_dues'))</h3>
                        </div>
                    </div>
                    <div style="min-width:160px">
                        {!! Form::select('sales_payment_dues_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'sales_payment_dues_location',
                        ]) !!}
                    </div>
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="sales_payment_dues_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>@lang('contact.customer')</th>
                                    <th>@lang('sale.invoice_no')</th>
                                    <th>@lang('home.due_amount')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Purchase Payment Dues --}}
            @can('purchase.view')
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#fffbeb;color:#d97706">
                            <i class="fa fa-truck"></i>
                        </div>
                        <div>
                            <h3>{{ __('lang_v1.purchase_payment_dues') }} @show_tooltip(__('tooltip.payment_dues'))</h3>
                        </div>
                    </div>
                    @if (count($all_locations) > 1)
                    <div style="min-width:160px">
                        {!! Form::select('purchase_payment_dues_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'purchase_payment_dues_location',
                        ]) !!}
                    </div>
                    @endif
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="purchase_payment_dues_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>@lang('purchase.supplier')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('home.due_amount')</th>
                                    <th>@lang('messages.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endcan

            {{-- Stock Alert --}}
            @can('stock_report.view')
            <div class="db-card" style="grid-column: 1 / -1">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#fef2f2;color:#dc2626">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <h3>{{ __('home.product_stock_alert') }} @show_tooltip(__('tooltip.product_stock_alert'))</h3>
                        </div>
                    </div>
                    @if (count($all_locations) > 1)
                    <div style="min-width:160px">
                        {!! Form::select('stock_alert_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'stock_alert_location',
                        ]) !!}
                    </div>
                    @endif
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="stock_alert_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>@lang('sale.product')</th>
                                    <th>@lang('business.location')</th>
                                    <th>@lang('report.current_stock')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Stock Expiry Alert --}}
            @if (session('business.enable_product_expiry') == 1)
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#fffbeb;color:#d97706">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div>
                            <h3>{{ __('home.stock_expiry_alert') }} @show_tooltip(__('tooltip.stock_expiry_alert', ['days' => session('business.stock_expiry_alert_days', 30)]))</h3>
                        </div>
                    </div>
                </div>
                <div class="db-card-body">
                    <input type="hidden" id="stock_expiry_alert_days"
                        value="{{ \Carbon::now()->addDays(session('business.stock_expiry_alert_days', 30))->format('Y-m-d') }}">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="stock_expiry_alert_table">
                            <thead>
                                <tr>
                                    <th>@lang('business.product')</th>
                                    <th>@lang('business.location')</th>
                                    <th>@lang('report.stock_left')</th>
                                    <th>@lang('product.expires_in')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            @endcan

        </div>
    </div>

    {{-- ORDERS & SHIPMENTS SECTION --}}
    @if (auth()->user()->can('so.view_all') || auth()->user()->can('so.view_own') ||
        (!empty($common_settings['enable_purchase_requisition']) && (auth()->user()->can('purchase_requisition.view_all') || auth()->user()->can('purchase_requisition.view_own'))) ||
        (!empty($common_settings['enable_purchase_order']) && (auth()->user()->can('purchase_order.view_all') || auth()->user()->can('purchase_order.view_own'))) ||
        auth()->user()->can('access_pending_shipments_only') || auth()->user()->can('access_shipping') || auth()->user()->can('access_own_shipping') ||
        (auth()->user()->can('account.access') && config('constants.show_payments_recovered_today') == true))
    <div class="db-section">
        <div class="db-section-title"><h2><i class="fa fa-list-alt" style="margin-right:6px"></i> Orders &amp; Operations</h2></div>

        <div class="db-grid-full">

            {{-- Sales Orders --}}
            @if (auth()->user()->can('so.view_all') || auth()->user()->can('so.view_own'))
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#eff6ff;color:#2563eb">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                        <div>
                            <h3>{{ __('lang_v1.sales_order') }}</h3>
                        </div>
                    </div>
                    @if (count($all_locations) > 1)
                    <div style="min-width:160px">
                        {!! Form::select('so_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'so_location',
                        ]) !!}
                    </div>
                    @endif
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="sales_order_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('restaurant.order_no')</th>
                                    <th>@lang('sale.customer_name')</th>
                                    <th>@lang('lang_v1.contact_no')</th>
                                    <th>@lang('sale.location')</th>
                                    <th>@lang('sale.status')</th>
                                    <th>@lang('lang_v1.shipping_status')</th>
                                    <th>@lang('lang_v1.quantity_remaining')</th>
                                    <th>@lang('lang_v1.added_by')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Purchase Requisition --}}
            @if (!empty($common_settings['enable_purchase_requisition']) &&
                (auth()->user()->can('purchase_requisition.view_all') || auth()->user()->can('purchase_requisition.view_own')))
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#f5f3ff;color:#7c3aed">
                            <i class="fa fa-clipboard"></i>
                        </div>
                        <div>
                            <h3>@lang('lang_v1.purchase_requisition')</h3>
                        </div>
                    </div>
                    @if (count($all_locations) > 1)
                    <div style="min-width:160px">
                        {!! Form::select('pr_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'pr_location',
                        ]) !!}
                    </div>
                    @endif
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="purchase_requisition_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('purchase.location')</th>
                                    <th>@lang('sale.status')</th>
                                    <th>@lang('lang_v1.required_by_date')</th>
                                    <th>@lang('lang_v1.added_by')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Purchase Order --}}
            @if (!empty($common_settings['enable_purchase_order']) &&
                (auth()->user()->can('purchase_order.view_all') || auth()->user()->can('purchase_order.view_own')))
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#fff7ed;color:#ea580c">
                            <i class="fa fa-cart-arrow-down"></i>
                        </div>
                        <div>
                            <h3>@lang('lang_v1.purchase_order')</h3>
                        </div>
                    </div>
                    @if (count($all_locations) > 1)
                    <div style="min-width:160px">
                        {!! Form::select('po_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'po_location',
                        ]) !!}
                    </div>
                    @endif
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="purchase_order_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('purchase.location')</th>
                                    <th>@lang('purchase.supplier')</th>
                                    <th>@lang('sale.status')</th>
                                    <th>@lang('lang_v1.quantity_remaining')</th>
                                    <th>@lang('lang_v1.added_by')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Pending Shipments --}}
            @if (auth()->user()->can('access_pending_shipments_only') ||
                auth()->user()->can('access_shipping') ||
                auth()->user()->can('access_own_shipping'))
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#f0f9ff;color:#0ea5e9">
                            <i class="fa fa-truck"></i>
                        </div>
                        <div>
                            <h3>@lang('lang_v1.pending_shipments')</h3>
                        </div>
                    </div>
                    @if (count($all_locations) > 1)
                    <div style="min-width:160px">
                        {!! Form::select('pending_shipments_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'pending_shipments_location',
                        ]) !!}
                    </div>
                    @endif
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="shipments_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('sale.invoice_no')</th>
                                    <th>@lang('sale.customer_name')</th>
                                    <th>@lang('lang_v1.contact_no')</th>
                                    <th>@lang('sale.location')</th>
                                    <th>@lang('lang_v1.shipping_status')</th>
                                    @if (!empty($custom_labels['shipping']['custom_field_1']))<th>{{ $custom_labels['shipping']['custom_field_1'] }}</th>@endif
                                    @if (!empty($custom_labels['shipping']['custom_field_2']))<th>{{ $custom_labels['shipping']['custom_field_2'] }}</th>@endif
                                    @if (!empty($custom_labels['shipping']['custom_field_3']))<th>{{ $custom_labels['shipping']['custom_field_3'] }}</th>@endif
                                    @if (!empty($custom_labels['shipping']['custom_field_4']))<th>{{ $custom_labels['shipping']['custom_field_4'] }}</th>@endif
                                    @if (!empty($custom_labels['shipping']['custom_field_5']))<th>{{ $custom_labels['shipping']['custom_field_5'] }}</th>@endif
                                    <th>@lang('sale.payment_status')</th>
                                    <th>@lang('restaurant.service_staff')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Payments Recovered Today --}}
            @if (auth()->user()->can('account.access') && config('constants.show_payments_recovered_today') == true)
            <div class="db-card">
                <div class="db-card-header">
                    <div class="db-card-title">
                        <div class="db-card-title-icon" style="background:#f0fdf4;color:#16a34a">
                            <i class="fa fa-money"></i>
                        </div>
                        <div>
                            <h3>@lang('lang_v1.payment_recovered_today')</h3>
                        </div>
                    </div>
                </div>
                <div class="db-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="cash_flow_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('account.account')</th>
                                    <th>@lang('lang_v1.description')</th>
                                    <th>@lang('lang_v1.payment_method')</th>
                                    <th>@lang('lang_v1.payment_details')</th>
                                    <th>@lang('account.credit')</th>
                                    <th>@lang('lang_v1.account_balance') @show_tooltip(__('lang_v1.account_balance_tooltip'))</th>
                                    <th>@lang('lang_v1.total_balance') @show_tooltip(__('lang_v1.total_balance_tooltip'))</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 footer-total text-center">
                                    <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                    <td class="footer_total_credit"></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
    @endif

@endif

{{-- Bottom spacing --}}
<div style="height: 32px"></div>

@endsection

{{-- Modals (unchanged) --}}
<div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<div class="modal fade edit_pso_status_modal" tabindex="-1" role="dialog"></div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@section('javascript')
    <script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    @includeIf('sales_order.common_js')
    @includeIf('purchase_order.common_js')
    @if (!empty($all_locations))
        {!! $sells_chart_1->script() !!}
        {!! $sells_chart_2->script() !!}
    @endif
    <script type="text/javascript">
    $(document).ready(function () {

        /* Sales Orders DataTable — identical to original home behaviour */
        sales_order_table = $('#sales_order_table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            aaSorting: [[1, 'desc']],
            ajax: {
                url: '{{ action([\App\Http\Controllers\SellController::class, 'index']) }}?sale_type=sales_order',
                data: function (d) {
                    d.for_dashboard_sales_order = true;
                    if ($('#so_location').length) d.location_id = $('#so_location').val();
                }
            },
            columnDefs: [{ targets: 7, orderable: false, searchable: false }],
            columns: [
                { data: 'action',            name: 'action' },
                { data: 'transaction_date',  name: 'transaction_date' },
                { data: 'invoice_no',        name: 'invoice_no' },
                { data: 'conatct_name',      name: 'conatct_name' },
                { data: 'mobile',            name: 'contacts.mobile' },
                { data: 'business_location', name: 'bl.name' },
                { data: 'status',            name: 'status' },
                { data: 'shipping_status',   name: 'shipping_status' },
                { data: 'so_qty_remaining',  name: 'so_qty_remaining', searchable: false },
                { data: 'added_by',          name: 'u.first_name' },
            ]
        });

    });
    </script>
@endsection
