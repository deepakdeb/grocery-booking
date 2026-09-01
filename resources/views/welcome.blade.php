@extends('layouts.front')

@section('title', __('messages.app_name'))

@section('content')
    <div class="container">
        <section style="display:grid; grid-template-columns: 1.1fr 0.9fr; gap:24px; align-items:center; padding:20px 0 30px;">
            <div class="card" style="padding:48px 42px;">
                <span class="eyebrow">{{ __('messages.fresh_picks') }}</span>
                <h1 class="page-heading">{{ __('messages.daily_essentials') }}</h1>
                <p class="page-subtitle">{{ __('messages.store_description') }}</p>

                <div style="display:flex; gap:14px; flex-wrap:wrap; margin-bottom:26px;">
                    <a href="{{ route('orders') }}" class="btn btn-primary">{{ __('messages.shop_now') }}</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">{{ __('messages.member_login') }}</a>
                </div>

                <div style="display:flex; gap:28px; flex-wrap:wrap; color:#53655d;">
                    <div><strong style="display:block; font-size:1.5rem; color:#142e22;">250+</strong><span>fresh items</span></div>
                    <div><strong style="display:block; font-size:1.5rem; color:#142e22;">2h</strong><span>fast delivery</span></div>
                    <div><strong style="display:block; font-size:1.5rem; color:#142e22;">4.9/5</strong><span>happy users</span></div>
                </div>
            </div>

            <div class="card" style="padding:24px; background:linear-gradient(180deg, #eaf8ee 0%, #d7f0dc 100%);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                    <strong>Today’s basket</strong>
                    <span style="background:#fff7d8; color:#7c6020; border-radius:999px; padding:8px 12px; font-size:0.74rem; font-weight:800;">Live stock</span>
                </div>

                <div style="display:grid; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.7); border-radius:12px; padding:12px 14px;"><strong>Rice</strong><span>৳220</span></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.7); border-radius:12px; padding:12px 14px;"><strong>Milk</strong><span>৳75</span></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.7); border-radius:12px; padding:12px 14px;"><strong>Eggs</strong><span>৳110</span></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.7); border-radius:12px; padding:12px 14px;"><strong>Vegetables</strong><span>৳185</span></div>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(22,75,45,0.14); margin-top:18px; padding-top:18px; font-weight:800;">
                    <span>{{ __('messages.total') }}</span>
                    <span>৳590</span>
                </div>
            </div>
        </section>
    </div>
@endsection
