<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Basket Store</title>
    <style>
        :root {
            --bg: #f4f7f1;
            --panel: #ffffff;
            --primary: #1f7a3d;
            --primary-dark: #155a30;
            --muted: #5f6f7a;
            --border: #e1eadf;
            --shadow: 0 14px 35px rgba(21, 35, 23, 0.08);
            --danger-bg: #fee2e2;
            --danger-text: #991b1b;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --accent: #f9c74f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            background: linear-gradient(180deg, #f5faf5 0%, #edf4ee 100%);
            color: #1d2a22;
        }

        a { text-decoration: none; }

        .topbar {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .brand {
            font-weight: 800;
            font-size: 1.3rem;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 24px 52px;
        }

        .layout {
            display: grid;
            grid-template-columns: 1.55fr 0.95fr;
            gap: 20px;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
            padding: 22px;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .title {
            margin: 0;
            font-size: 1.45rem;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
        }

        .product-card {
            background: linear-gradient(180deg, #ffffff 0%, #f5faf6 100%);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
        }

        .product-card h3 {
            margin: 0 0 8px;
            font-size: 1.08rem;
        }

        .product-meta {
            color: var(--muted);
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            align-items: center;
        }

        .price {
            font-size: 1.1rem;
            font-weight: 800;
            color: #123d27;
        }

        .stock-pill {
            display: inline-block;
            background: #edf7ef;
            color: var(--primary-dark);
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .cta-btn, .checkout-btn, .logout-btn {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .cta-btn {
            background: var(--primary);
            color: white;
            width: 100%;
        }

        .checkout-btn {
            background: var(--primary);
            color: white;
            width: 100%;
            margin-top: 12px;
        }

        .logout-btn {
            background: #eef2ef;
            color: #1f2d26;
        }

        .sidebar {
            display: grid;
            gap: 20px;
        }

        .summary-box {
            min-height: 120px;
            color: var(--muted);
        }

        .summary-box table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .summary-box th, .summary-box td {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #edf1ee;
        }

        .summary-box tfoot td {
            border-bottom: none;
            font-weight: 800;
            color: #153a2a;
        }

        .history table {
            width: 100%;
            border-collapse: collapse;
        }

        .history th, .history td {
            text-align: left;
            padding: 10px 0;
            border-bottom: 1px solid #edf1ee;
        }

        .alert {
            display: none;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-weight: 700;
        }

        .alert.success {
            display: block;
            background: var(--success-bg);
            color: var(--success-text);
        }

        .alert.error {
            display: block;
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        @media (max-width: 900px) {
            .layout { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="topbar">
            <div class="brand">{{ __('messages.app_name') }}</div>
        <nav class="nav">
            <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
            <a href="{{ route('orders') }}">{{ __('messages.store') }}</a>
            <a href="{{ route('lang.switch', ['locale' => 'en']) }}">EN</a>
            <a href="{{ route('lang.switch', ['locale' => 'bn']) }}">বাংলা</a>
            <button class="logout-btn" type="button" id="logoutBtn">{{ __('messages.logout') }}</button>
        </nav>
    </header>

    <main class="container">
        <div id="message" class="alert"></div>

        <div class="layout">
            <section class="panel">
                <div class="panel-header">
                    <h2 class="title">{{ __('messages.items') }}</h2>
                </div>
                <div id="itemsGrid" class="product-grid"></div>
            </section>

            <aside class="sidebar">
                <section class="panel">
                    <div class="panel-header">
                        <h2 class="title">{{ __('messages.order_summary') }}</h2>
                    </div>
                    <div id="summaryBox" class="summary-box">{{ __('messages.cart_empty') }}</div>
                    <button class="checkout-btn" type="button" id="checkoutBtn">{{ __('messages.checkout') }}</button>
                </section>

                <section class="panel history">
                    <div class="panel-header">
                        <h2 class="title">{{ __('messages.order_history') }}</h2>
                    </div>
                    <div id="historyBox">{{ __('messages.no_orders') }}</div>
                </section>
            </aside>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('grocery_token');
        const cart = {};
        const messageEl = document.getElementById('message');
        const summaryBox = document.getElementById('summaryBox');
        const itemsGrid = document.getElementById('itemsGrid');
        const historyBox = document.getElementById('historyBox');

        function showMessage(text, type) {
            messageEl.textContent = text;
            messageEl.className = 'alert ' + type;
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderSummary() {
            const entries = Object.values(cart);

            if (!entries.length) {
                summaryBox.innerHTML = 'Your cart is empty.';
                return;
            }

            let total = 0;
            let rows = '<table><tbody>';

            entries.forEach((item) => {
                const lineTotal = Number(item.price) * Number(item.quantity);
                total += lineTotal;
                rows += '<tr><td>' + escapeHtml(item.name) + '</td><td>x' + item.quantity + '</td><td>৳' + lineTotal.toFixed(2) + '</td></tr>';
            });

            rows += '</tbody><tfoot><tr><td colspan="2">Total</td><td>৳' + total.toFixed(2) + '</td></tr></tfoot></table>';
            summaryBox.innerHTML = rows;
        }

        async function fetchItems() {
            try {
                const response = await fetch('/api/items', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });

                if (!response.ok) {
                    throw new Error('Please login first.');
                }

                const data = await response.json();
                itemsGrid.innerHTML = data.data.map((item) => `
                    <article class="product-card">
                        <h3>${escapeHtml(item.name)}</h3>
                        <div class="product-meta">
                            <span class="price">৳${Number(item.price).toFixed(2)}</span>
                            <span class="stock-pill">${item.stock_quantity} in stock</span>
                        </div>
                        <button class="cta-btn" type="button" data-id="${item.id}" data-name="${escapeHtml(item.name)}" data-price="${item.price}">Add to cart</button>
                    </article>
                `).join('');

                itemsGrid.querySelectorAll('button[data-id]').forEach((button) => {
                    button.addEventListener('click', () => {
                        const id = Number(button.dataset.id);
                        const name = button.dataset.name;
                        const price = Number(button.dataset.price);

                        if (!cart[id]) {
                            cart[id] = { id, name, price, quantity: 1 };
                        } else {
                            cart[id].quantity += 1;
                        }

                        renderSummary();
                    });
                });
            } catch (error) {
                showMessage(error.message || 'Unable to load items.', 'error');
            }
        }

        async function loadOrders() {
            if (!token) {
                historyBox.textContent = 'Please login first.';
                return;
            }

            try {
                const response = await fetch('/api/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });

                const result = await response.json().catch(() => ({ data: [] }));

                if (!response.ok || !result.data || !result.data.length) {
                    historyBox.textContent = 'No orders yet.';
                    return;
                }

                historyBox.innerHTML = '<table><tbody>' + result.data.map((order) => `
                    <tr>
                        <td>#${order.id}</td>
                        <td>${order.status}</td>
                        <td>৳${Number(order.total_amount).toFixed(2)}</td>
                    </tr>
                `).join('') + '</tbody></table>';
            } catch (error) {
                historyBox.textContent = 'Unable to load order history.';
            }
        }

        async function placeOrder() {
            if (!token) {
                showMessage('Please login first.', 'error');
                return;
            }

            const entries = Object.values(cart);
            if (!entries.length) {
                showMessage('Add at least one item to your cart.', 'error');
                return;
            }

            const payload = {
                items: entries.map((item) => ({
                    grocery_item_id: item.id,
                    quantity: item.quantity,
                })),
            };

            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                    body: JSON.stringify(payload),
                });

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(result.message || 'Order failed.');
                }

                Object.keys(cart).forEach((key) => delete cart[key]);
                renderSummary();
                showMessage('Order placed successfully.', 'success');
                await fetchItems();
                await loadOrders();
            } catch (error) {
                showMessage(error.message || 'Order failed.', 'error');
            }
        }

        async function logoutUser() {
            if (!token) {
                window.location.href = '{{ route('home') }}';
                return;
            }

            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });

                if (!response.ok) {
                    throw new Error('Logout failed.');
                }

                localStorage.removeItem('grocery_token');
                window.location.href = '{{ route('home') }}';
            } catch (error) {
                showMessage(error.message || 'Logout failed.', 'error');
            }
        }

        document.getElementById('checkoutBtn').addEventListener('click', placeOrder);
        document.getElementById('logoutBtn').addEventListener('click', logoutUser);

        if (!token) {
            showMessage('Please login to continue.', 'error');
        }

        fetchItems();
        loadOrders();
        renderSummary();
    </script>
</body>
</html>
