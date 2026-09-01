<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.app_name'))</title>
    <style>
        :root {
            --bg: #f4f8f3;
            --panel: #ffffff;
            --primary: #1f7a3d;
            --primary-dark: #155a30;
            --primary-soft: #eaf6ee;
            --accent: #f7c95d;
            --text: #1f2a22;
            --muted: #5d6d66;
            --border: #dfe9e2;
            --success-bg: #eaf9ef;
            --success-text: #156437;
            --danger-bg: #fee2e2;
            --danger-text: #991b1b;
            --shadow: 0 18px 42px rgba(18, 47, 30, 0.08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, "Segoe UI", sans-serif;
            background: linear-gradient(180deg, #f8fbf8 0%, #edf4ef 100%);
            color: var(--text);
        }

        a { text-decoration: none; }
        img { max-width: 100%; }

        .container {
            width: min(1200px, calc(100% - 32px));
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
        }

        .topbar-inner {
            width: min(1200px, calc(100% - 32px));
            margin: 0 auto;
            padding: 18px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: #173d2b;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav a,
        .nav button {
            border: 0;
            background: transparent;
            color: #234131;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            padding: 8px 10px;
            border-radius: 10px;
        }

        .nav a:hover,
        .nav button:hover {
            background: #f0f7f2;
        }

        .nav .btn-link {
            background: var(--primary);
            color: #fff;
            padding: 10px 14px;
        }

        .page-shell {
            padding: 24px 0 48px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
        }

        .auth-card {
            max-width: 500px;
            margin: 48px auto;
            padding: 28px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            background: var(--primary-soft);
            color: var(--primary-dark);
            border-radius: 999px;
            font-size: 0.74rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .page-heading {
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1.08;
            margin: 0 0 14px;
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 1.04rem;
            margin-bottom: 26px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .field label {
            font-weight: 700;
            color: #21372d;
        }

        .field input,
        .field textarea {
            width: 100%;
            border: 1px solid #d0d9d2;
            border-radius: 12px;
            padding: 12px 14px;
            background: #fff;
            color: var(--text);
            font: inherit;
        }

        .field input:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(31, 122, 61, 0.12);
        }

        .btn,
        button[type="submit"],
        .primary-button,
        .secondary-button,
        .ghost-button {
            border: 0;
            border-radius: 12px;
            padding: 12px 18px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.18s ease, opacity 0.18s ease;
        }

        .btn:hover,
        button[type="submit"]:hover,
        .primary-button:hover,
        .secondary-button:hover,
        .ghost-button:hover {
            transform: translateY(-1px);
        }

        .btn-primary,
        .primary-button,
        button[type="submit"] {
            background: var(--primary);
            color: #fff;
        }

        .btn-secondary,
        .secondary-button {
            background: var(--primary-soft);
            color: var(--primary-dark);
        }

        .ghost-button {
            background: #edf3ee;
            color: #243b2f;
        }

        .alert {
            display: none;
            padding: 12px 14px;
            border-radius: 12px;
            font-weight: 700;
            margin-top: 18px;
        }

        .alert.error { display: block; background: var(--danger-bg); color: var(--danger-text); }
        .alert.success { display: block; background: var(--success-bg); color: var(--success-text); }

        .meta {
            margin-top: 18px;
            text-align: center;
            color: var(--muted);
            font-size: 0.96rem;
        }

        .meta a {
            color: var(--primary-dark);
            font-weight: 700;
        }

        @media (max-width: 760px) {
            .topbar-inner { padding: 14px 0; }
            .brand { font-size: 1.18rem; }
            .nav { justify-content: flex-start; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">{{ __('messages.app_name') }}</div>
            <nav class="nav" aria-label="Main navigation">
                <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
                <a href="{{ route('orders') }}" data-store-link="true" style="display:none;">{{ __('messages.store') }}</a>
                <a href="{{ route('admin.index') }}" data-admin-link="true" style="display:none;">Admin</a>
                <a href="{{ route('login') }}" data-auth-guest="true">{{ __('messages.login') }}</a>
                <a href="{{ route('register') }}" data-auth-guest="true">{{ __('messages.register') }}</a>
                <button type="button" data-auth-user="true" style="display:none;" id="logoutButton">Logout</button>
                @if (app()->getLocale() === 'bn')
                    <a href="{{ route('lang.switch', ['locale' => 'en']) }}" aria-label="English">EN</a>
                @else
                    <a href="{{ route('lang.switch', ['locale' => 'bn']) }}" aria-label="Bangla">বাংলা</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="page-shell">
        @yield('content')
    </main>

    <script>
        (function () {
            const token = localStorage.getItem('grocery_token');
            const user = JSON.parse(localStorage.getItem('grocery_user') || '{}');
            const role = (user && user.role && user.role.name) || null;

            const guestLinks = document.querySelectorAll('[data-auth-guest="true"]');
            const userOnlyButtons = document.querySelectorAll('[data-auth-user="true"]');
            const adminLink = document.querySelector('[data-admin-link="true"]');
            const storeLink = document.querySelector('[data-store-link="true"]');

            if (token) {
                guestLinks.forEach((el) => el.style.display = 'none');
                userOnlyButtons.forEach((el) => el.style.display = 'inline-flex');
                if (storeLink) {
                    storeLink.style.display = 'inline-flex';
                }
                if (role === 'admin' && adminLink) {
                    adminLink.style.display = 'inline-flex';
                }
            } else {
                guestLinks.forEach((el) => el.style.display = 'inline-flex');
                userOnlyButtons.forEach((el) => el.style.display = 'none');
                if (adminLink) {
                    adminLink.style.display = 'none';
                }
                if (storeLink) {
                    storeLink.style.display = 'none';
                }
            }

            const logoutButton = document.getElementById('logoutButton');
            if (logoutButton) {
                logoutButton.addEventListener('click', async function () {
                    const logoutToken = localStorage.getItem('grocery_token');

                    try {
                        await fetch('/api/logout', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + logoutToken,
                            },
                        });
                    } catch (e) {
                        // ignore and still clear + redirect
                    }

                    localStorage.removeItem('grocery_token');
                    localStorage.removeItem('grocery_user');
                    window.location.href = '{{ route('login') }}';
                });
            }
        })();
    </script>
</body>
</html>
