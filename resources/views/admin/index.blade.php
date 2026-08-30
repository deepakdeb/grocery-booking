<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.admin_dashboard') }}</title>
    <style>
        :root {
            --bg: #f4f7f1;
            --panel: #ffffff;
            --primary: #1f7a3d;
            --primary-dark: #155a30;
            --muted: #5f6f7a;
            --border: #e1eadf;
            --shadow: 0 14px 35px rgba(21, 35, 23, 0.08);
            --danger: #d93025;
            --danger-soft: #fdecea;
            --success: #188038;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            background: #f3f7f3;
            color: #1d2a22;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 18px 24px;
        }
        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
        }
        .brand { font-weight: 800; font-size: 1.25rem; }
        .nav { display: flex; gap: 12px; flex-wrap: wrap; }
        .nav a, .nav button {
            border: none; background: transparent; color: #1d2a22; font: inherit; font-weight: 600; cursor: pointer;
        }
        .container {
            max-width: 1200px; margin: 28px auto; padding: 0 24px 40px;
        }
        .grid {
            display: grid; grid-template-columns: 1fr 1.2fr; gap: 22px;
        }
        .panel {
            background: var(--panel); border: 1px solid var(--border); border-radius: 20px; box-shadow: var(--shadow); padding: 22px;
        }
        .title { margin: 0 0 18px; font-size: 1.4rem; }
        .field { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
        label { font-size: 0.95rem; font-weight: 600; }
        input, textarea {
            width: 100%; padding: 11px 12px; font: inherit; border-radius: 10px; border: 1px solid #d5ddd5; background: white;
        }
        textarea { min-height: 90px; resize: vertical; }
        .row { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 12px; }
        button.primary, button.secondary, button.danger {
            border: none; border-radius: 10px; padding: 10px 14px; font: inherit; font-weight: 700; cursor: pointer;
        }
        button.primary { background: var(--primary); color: white; }
        button.secondary { background: #edfaf0; color: var(--primary-dark); }
        button.danger { background: var(--danger-soft); color: var(--danger); }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; text-align: left; border-bottom: 1px solid #edf1ee; }
        th { color: var(--muted); font-size: 0.78rem; text-transform: uppercase; }
        .pill { display: inline-block; background: #edf7ef; color: var(--primary-dark); padding: 6px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; }
        .alert { display: none; margin-bottom: 18px; padding: 12px 14px; font-weight: 700; border-radius: 10px; }
        .alert.success { display: block; background: #dcfce7; color: #166534; }
        .alert.error { display: block; background: #fee2e2; color: #991b1b; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">{{ __('messages.admin_dashboard') }}</div>
            <nav class="nav">
                <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
                <a href="{{ route('lang.switch', ['locale' => 'en']) }}">EN</a>
                <a href="{{ route('lang.switch', ['locale' => 'bn']) }}">বাংলা</a>
                <button type="button" id="logoutBtn">{{ __('messages.logout') }}</button>
            </nav>
        </div>
    </header>

    <main class="container">
        <div id="message" class="alert"></div>

        <div class="grid">
            <section class="panel">
                <h2 class="title">{{ __('messages.inventory') }}</h2>
                <form id="itemForm">
                    <input type="hidden" id="itemId">
                    <div class="field">
                        <label for="name">{{ __('messages.name') }}</label>
                        <input id="name" name="name" required>
                    </div>

                    <div class="field">
                        <label for="description">{{ __('messages.description') }}</label>
                        <textarea id="description" name="description"></textarea>
                    </div>

                    <div class="row">
                        <div class="field">
                            <label for="price">{{ __('messages.price') }}</label>
                            <input id="price" name="price" type="number" step="0.01" min="0" required>
                        </div>
                        <div class="field">
                            <label for="stock_quantity">{{ __('messages.stock') }}</label>
                            <input id="stock_quantity" name="stock_quantity" type="number" min="0" required>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="primary" type="submit" id="submitBtn">{{ __('messages.save_item') }}</button>
                        <button class="secondary" type="button" id="resetBtn">{{ __('messages.cancel') }}</button>
                    </div>
                </form>
            </section>

            <section class="panel">
                <h2 class="title">{{ __('messages.items') }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.price') }}</th>
                            <th>{{ __('messages.stock') }}</th>
                            <th>{{ __('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
            </section>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('grocery_token');
        const itemForm = document.getElementById('itemForm');
        const messageEl = document.getElementById('message');
        const itemsBody = document.getElementById('itemsBody');
        const submitBtn = document.getElementById('submitBtn');

        function showMessage(text, type) {
            messageEl.textContent = text;
            messageEl.className = 'alert ' + type;
        }

        function resetForm() {
            itemForm.reset();
            document.getElementById('itemId').value = '';
            submitBtn.textContent = '{{ __('messages.save_item') }}';
        }

        async function loadItems() {
            if (!token) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            try {
                const response = await fetch('/api/admin/grocery-items', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });

                const result = await response.json().catch(() => ({ data: [] }));
                if (!response.ok) {
                    throw new Error(result.message || 'Unable to load items.');
                }

                itemsBody.innerHTML = (result.data || []).map((item) => `
                    <tr>
                        <td>${item.name}</td>
                        <td>৳${Number(item.price).toFixed(2)}</td>
                        <td><span class="pill">${item.stock_quantity}</span></td>
                        <td class="actions">
                            <button class="secondary" type="button" data-edit="${item.id}">Edit</button>
                            <button class="danger" type="button" data-delete="${item.id}">Delete</button>
                        </td>
                    </tr>
                `).join('');

                itemsBody.querySelectorAll('[data-edit]').forEach((button) => {
                    button.addEventListener('click', () => fillForm(Number(button.dataset.edit)));
                });

                itemsBody.querySelectorAll('[data-delete]').forEach((button) => {
                    button.addEventListener('click', () => deleteItem(Number(button.dataset.delete)));
                });
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        async function fillForm(itemId) {
            const response = await fetch('/api/admin/grocery-items/' + itemId, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token,
                },
            });
            const result = await response.json().catch(() => ({}));
            if (!response.ok) {
                showMessage(result.message || 'Unable to load item.', 'error');
                return;
            }

            const item = result.data;
            document.getElementById('itemId').value = item.id;
            document.getElementById('name').value = item.name;
            document.getElementById('description').value = item.description || '';
            document.getElementById('price').value = item.price;
            document.getElementById('stock_quantity').value = item.stock_quantity;
            submitBtn.textContent = '{{ __('messages.update_item') }}';
        }

        async function deleteItem(itemId) {
            try {
                const response = await fetch('/api/admin/grocery-items/' + itemId, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(result.message || 'Delete failed.');
                }
                showMessage('Item removed successfully.', 'success');
                await loadItems();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        }

        itemForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            const itemId = document.getElementById('itemId').value;
            const payload = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                stock_quantity: document.getElementById('stock_quantity').value,
            };

            const method = itemId ? 'PUT' : 'POST';
            const url = itemId ? '/api/admin/grocery-items/' + itemId : '/api/admin/grocery-items';

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                    body: JSON.stringify(payload),
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(result.message || 'Operation failed.');
                }
                showMessage(itemId ? 'Item updated successfully.' : 'Item created successfully.', 'success');
                resetForm();
                await loadItems();
            } catch (error) {
                showMessage(error.message, 'error');
            }
        });

        document.getElementById('resetBtn').addEventListener('click', resetForm);
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });
            } finally {
                localStorage.removeItem('grocery_token');
                window.location.href = '{{ route('home') }}';
            }
        });

        if (!token) {
            window.location.href = '{{ route('login') }}';
        } else {
            loadItems();
        }
    </script>
</body>
</html>
