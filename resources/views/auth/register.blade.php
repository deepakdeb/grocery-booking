@extends('layouts.front')

@section('title', __('messages.register'))

@section('content')
    <div class="container">
        <div class="card auth-card">
            <span class="eyebrow">{{ __('messages.create_account') }}</span>
            <h1 class="page-heading">{{ __('messages.create_account') }}</h1>
            <p class="page-subtitle">{{ __('messages.weekly_essentials') }}</p>

            <form id="registerForm" method="POST" action="{{ route('register') }}">
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

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">{{ __('messages.register') }}</button>
            </form>

            <div id="message" class="alert"></div>

            <div class="meta">
                <span>{{ __('messages.login') }}? </span>
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

            const password = document.getElementById('password').value;
            const payload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password,
                password_confirmation: document.getElementById('password_confirmation').value,
            };

            if (payload.password !== payload.password_confirmation) {
                showMessage('Passwords do not match.', 'error');
                return;
            }

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

                const role = (result.user && result.user.role && result.user.role.name) || 'user';
                setTimeout(() => {
                    window.location.href = role === 'admin' ? '{{ route('admin.index') }}' : ordersUrl;
                }, 550);
            } catch (error) {
                showMessage(error.message || 'Registration failed.', 'error');
            }
        });
    </script>
@endsection
