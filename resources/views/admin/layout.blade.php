<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.admin_dashboard'))</title>
    <style>
        :root {
            --bg: #f3f7f3;
            --card: #ffffff;
            --primary: #1f7a3d;
            --primary-dark: #155a30;
            --muted: #5f6f7a;
            --border: #dfe9e1;
            --shadow: 0 16px 36px rgba(22, 39, 28, 0.08);
            --danger: #d93025;
            --danger-soft: #fdecea;
            --success: #1f8f4d;
            --success-soft: #e8f8ee;
            --warning: #f59e0b;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            background: linear-gradient(180deg, #f6faf7 0%, #edf4ef 100%);
            color: #1d2b22;
        }
        a { text-decoration: none; }
        button, input, textarea { font: inherit; }

        .topbar {
            background: rgba(255,255,255,0.9);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .topbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand {
            font-size: 1.3rem;
            font-weight: 800;
            color: #173d2b;
        }
        .nav {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .nav a, .nav button {
            border: none;
            background: transparent;
            color: #244332;
            font-weight: 700;
            cursor: pointer;
        }
        .nav a:hover { color: var(--primary-dark); }

        .shell {
            max-width: 1280px;
            margin: 0 auto;
            padding: 28px 24px 48px;
        }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-size: clamp(2rem, 4vw, 2.8rem);
            margin: 6px 0 0;
        }
        .eyebrow {
            margin: 0;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.11em;
            color: var(--primary-dark);
            font-weight: 800;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
            padding: 22px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }
        .stat-card {
            padding: 20px 18px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, #ffffff 0%, #f6faf7 100%);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .stat-label {
            color: var(--muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }
        .stat-card strong {
            font-size: 2rem;
            line-height: 1.1;
        }
        .stat-card small {
            color: var(--muted);
        }

        .table-card { overflow: hidden; }
        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }
        .table-header h2 {
            margin: 0;
            font-size: 1.3rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #edf1ee;
        }
        th {
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--muted);
            font-size: 0.75rem;
        }
        .stock-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 54px;
            background: #edf7ef;
            color: var(--primary-dark);
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 800;
        }
        .inline-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .primary-button, .secondary-button, .danger-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.18s ease;
        }
        .primary-button:hover, .secondary-button:hover, .danger-button:hover { transform: translateY(-1px); }
        .primary-button { background: var(--primary); color: #fff; }
        .secondary-button { background: #ebf7ee; color: var(--primary-dark); }
        .danger-button { background: var(--danger-soft); color: var(--danger); }
        .small { padding: 8px 12px; font-size: 0.82rem; }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 20px;
        }
        .toolbar-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }
        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 18px;
        }
        .field label {
            font-weight: 700;
            color: #2a3d34;
        }
        .field input, .field textarea {
            width: 100%;
            border: 1px solid #d8e0d9;
            background: #fff;
            border-radius: 12px;
            padding: 11px 12px;
            color: #1a2c24;
        }
        .field textarea { min-height: 110px; resize: vertical; }
        .full-width { grid-column: 1 / -1; }
        .empty-state {
            text-align: center;
            color: var(--muted);
            padding: 24px;
        }
        .message {
            margin-bottom: 18px;
            border-radius: 12px;
            padding: 12px 14px;
            font-weight: 700;
        }
        .message.success {
            background: var(--success-soft);
            color: var(--success);
        }
        .message.error {
            background: var(--danger-soft);
            color: var(--danger);
        }

        @media (max-width: 900px) {
            .stats-grid, .form-grid { grid-template-columns: 1fr; }
            .page-header, .table-header, .toolbar { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">{{ __('messages.admin_dashboard') }}</div>
            <nav class="nav">
                <a href="{{ route('admin.index') }}">Dashboard</a>
                <a href="{{ route('admin.items.index') }}">Inventory</a>
                <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
                @if (app()->getLocale() === 'bn')
                    <a href="{{ route('lang.switch', ['locale' => 'en']) }}">EN</a>
                @else
                    <a href="{{ route('lang.switch', ['locale' => 'bn']) }}">বাংলা</a>
                @endif
                <button type="button" onclick="window.location.href='{{ route('login') }}'">{{ __('messages.logout') }}</button>
            </nav>
        </div>
    </header>

    <main class="shell">
        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="message error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
