@extends('layouts.front')

@section('title', __('messages.login'))

@section('content')
    <div class="container">
        <div class="card auth-card">
            <span class="eyebrow">{{ __('messages.member_login') }}</span>
            <h1 class="page-heading">{{ __('messages.welcome_back') }}</h1>
            <p class="page-subtitle">{{ __('messages.continue_shopping') }}</p>

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                <div class="field">
                    <label for="email">{{ __('messages.email') }}</label>
                    <input id="email" name="email" type="email" placeholder="name@example.com" required>
                </div>

                <div class="field">
                    <label for="password">{{ __('messages.password') }}</label>
                    <input id="password" name="password" type="password" placeholder="Your password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">{{ __('messages.login') }}</button>
            </form>

            <div id="message" class="alert"></div>

            <div class="meta">
                <span>{{ __('messages.create_account') }}? </span>
                <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
            </div>
        </div>
    </div>

    <script>
        const ordersUrl = @json(route('orders'));
        const loginForm = document.getElementById('loginForm');
        const messageEl = document.getElementById('message');

        function showMessage(text, type) {
            messageEl.textContent = text;
            messageEl.className = 'alert ' + type;
        }

        loginForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const payload = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
            };

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(result.message || 'Login failed.');
                }

                localStorage.setItem('grocery_token', result.token);
                localStorage.setItem('grocery_user', JSON.stringify(result.user || {}));
                showMessage('Login successful.', 'success');

                const role = (result.user && result.user.role && result.user.role.name) || 'user';
                setTimeout(() => {
                    window.location.href = role === 'admin' ? '{{ route('admin.index') }}' : ordersUrl;
                }, 550);
            } catch (error) {
                showMessage(error.message || 'Login failed.', 'error');
            }
        });
    </script>
@endsection
