<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        :root {
            --bg: #f1f6f2;
            --panel: #ffffff;
            --primary: #1f7a3d;
            --primary-dark: #155a30;
            --muted: #5f6f7a;
            --border: #dfe8e1;
            --shadow: 0 18px 40px rgba(17, 51, 31, 0.08);
            --danger-bg: #fee2e2;
            --danger-text: #991b1b;
            --success-bg: #dcfce7;
            --success-text: #166534;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: Inter, Arial, sans-serif;
            background: linear-gradient(135deg, #f7faf7 0%, #edf6ee 100%);
            color: #1d2a22;
        }

        .auth-shell {
            width: min(100%, 500px);
            padding: 24px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
            padding: 28px;
        }

        .card h2 {
            margin: 0 0 12px;
            font-size: 2rem;
        }

        .sub {
            color: var(--muted);
            margin-bottom: 20px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        label {
            font-weight: 600;
            color: #26352d;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d3d9d3;
            border-radius: 10px;
            outline: none;
            font: inherit;
            background: #fff;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(31, 122, 61, 0.12);
        }

        button {
            width: 100%;
            border: none;
            background: var(--primary);
            color: white;
            border-radius: 10px;
            padding: 13px 16px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        button:hover { opacity: 0.96; }

        .meta {
            margin-top: 16px;
            text-align: center;
            color: var(--muted);
        }

        .meta a {
            color: var(--primary-dark);
            font-weight: 700;
            text-decoration: none;
        }

        .alert {
            display: none;
            margin-top: 16px;
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 600;
        }

        .alert.error {
            display: block;
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        .alert.success {
            display: block;
            background: var(--success-bg);
            color: var(--success-text);
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="card">
            <h2>{{ __('messages.create_account') }}</h2>
            <div class="sub">{{ __('messages.weekly_essentials') }}</div>

            <form id="registerForm">
                <div class="field">
                    <label for="name">{{ __('messages.name') }}</label>
                    <input id="name" name="name" type="text" placeholder="Your name" required>
                </div>

                <div class="field">
                    <label for="email">{{ __('messages.email') }}</label>
                    <input id="email" name="email" type="email" placeholder="name@example.com" required>
                </div>

                <div class="field">
                    <label for="password">{{ __('messages.password') }}</label>
                    <input id="password" name="password" type="password" placeholder="Create password" required>
                </div>

                <button type="submit">{{ __('messages.register') }}</button>
            </form>

            <div id="message" class="alert"></div>

            <div class="meta">
                <span>Already have an account? </span>
                <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
            </div>
        </div>
    </div>

    <script>
        const ordersUrl = @json(route('orders'));
        const registerForm = document.getElementById('registerForm');
        const messageEl = document.getElementById('message');

        function showMessage(text, type) {
            messageEl.textContent = text;
            messageEl.className = 'alert ' + type;
        }

        registerForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const payload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password').value,
            };

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(result.message || 'Registration failed.');
                }

                localStorage.setItem('grocery_token', result.token);
                localStorage.setItem('grocery_user', JSON.stringify(result.user || {}));
                showMessage('Registration successful.', 'success');

                setTimeout(() => {
                    window.location.href = ordersUrl;
                }, 550);
            } catch (error) {
                showMessage(error.message || 'Registration failed.', 'error');
            }
        });
    </script>
</body>
</html>
