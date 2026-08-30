<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.app_name') }}</title>
    <style>
        :root {
            --bg: #f4f7f1;
            --panel: #ffffff;
            --primary: #1f7a3d;
            --primary-dark: #155a30;
            --accent: #f9c74f;
            --muted: #5f6f7a;
            --border: #dfe8e1;
            --shadow: 0 14px 35px rgba(21, 35, 23, 0.08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            background: linear-gradient(135deg, #f8faf8 0%, #eef5ef 100%);
            color: #1d2a22;
        }

        a { text-decoration: none; }

        .topbar {
            max-width: 1200px;
            margin: 0 auto;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: #163b27;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .nav a {
            color: #23412f;
            font-weight: 600;
        }

        .hero {
            max-width: 1200px;
            margin: 0 auto;
            padding: 38px 24px 60px;
        }

        .hero-inner {
            background: linear-gradient(135deg, #ffffff 0%, #f6faf7 100%);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: var(--shadow);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
        }

        .hero-copy {
            padding: 56px 52px;
        }

        .eyebrow {
            display: inline-block;
            background: #eaf7ee;
            color: var(--primary-dark);
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        h1 {
            font-size: clamp(2.4rem, 5vw, 4rem);
            line-height: 1.05;
            margin: 18px 0 14px;
        }

        .lead {
            font-size: 1.08rem;
            color: var(--muted);
            max-width: 620px;
            margin-bottom: 28px;
        }

        .cta-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 26px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            padding: 14px 22px;
            font-weight: 700;
            transition: transform 0.2s ease;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-primary { background: var(--primary); color: white; }
        .btn-secondary { background: #ebf7ef; color: var(--primary-dark); }

        .stats {
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
            color: var(--muted);
        }

        .stat strong {
            display: block;
            color: #18241d;
            font-size: 1.5rem;
        }

        .hero-visual {
            background: linear-gradient(180deg, #dff5e4 0%, #bfdcc4 100%);
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 420px;
        }

        .basket {
            width: min(100%, 420px);
            background: rgba(255,255,255,0.65);
            border: 1px solid rgba(19,58,35,0.12);
            border-radius: 26px;
            padding: 28px;
            box-shadow: 0 10px 28px rgba(23, 58, 39, 0.12);
        }

        .basket-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .chip {
            padding: 8px 12px;
            border-radius: 999px;
            background: #fff5d1;
            color: #7e6415;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .product-list {
            display: grid;
            gap: 12px;
        }

        .product-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255,255,255,0.8);
            border: 1px solid rgba(19,58,35,0.06);
            padding: 12px 14px;
            border-radius: 12px;
        }

        .product-row strong { font-size: 0.96rem; }
        .product-row span { color: var(--muted); }

        .checkout-total {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid rgba(34,83,51,0.12);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
        }

        @media (max-width: 860px) {
            .hero-inner { grid-template-columns: 1fr; }
            .hero-copy { padding: 34px 22px; }
            .hero-visual { min-height: 260px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="brand">{{ __('messages.app_name') }}</div>
        <nav class="nav">
            <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
            <a href="{{ route('orders') }}">{{ __('messages.store') }}</a>
            <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
            <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
            <a href="{{ route('lang.switch', ['locale' => 'en']) }}">EN</a>
            <a href="{{ route('lang.switch', ['locale' => 'bn']) }}">বাংলা</a>
        </nav>
    </header>

    <main class="hero">
        <div class="hero-inner">
            <div class="hero-copy">
                <span class="eyebrow">{{ __('messages.items') }}</span>
                <h1>{{ __('messages.daily_essentials') }}</h1>
                <p class="lead">{{ __('messages.store_description') }}</p>
                <div class="cta-row">
                    <a class="btn btn-primary" href="{{ route('orders') }}">{{ __('messages.shop_now') }}</a>
                    <a class="btn btn-secondary" href="{{ route('login') }}">{{ __('messages.member_login') }}</a>
                </div>
                <div class="stats">
                    <div class="stat"><strong>200+</strong> products</div>
                    <div class="stat"><strong>24/7</strong> ordering</div>
                    <div class="stat"><strong>99.9%</strong> stock accuracy</div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="basket">
                    <div class="basket-header">
                        <strong>Today’s basket</strong>
                        <span class="chip">Fresh picks</span>
                    </div>
                    <div class="product-list">
                        <div class="product-row"><div><strong>Rice</strong><br><span>Premium grain</span></div><span>৳250</span></div>
                        <div class="product-row"><div><strong>Milk</strong><br><span>Farm fresh</span></div><span>৳90</span></div>
                        <div class="product-row"><div><strong>Eggs</strong><br><span>Free range</span></div><span>৳120</span></div>
                        <div class="product-row"><div><strong>Banana</strong><br><span>Organic</span></div><span>৳80</span></div>
                    </div>
                    <div class="checkout-total">
                        <span>Estimated total</span>
                        <span>৳540</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
