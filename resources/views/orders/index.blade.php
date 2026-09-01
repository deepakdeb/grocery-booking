@extends('layouts.front')

@section('title', __('messages.store'))

@section('content')
    <div class="container">
        <div id="storeMessage" class="alert" aria-live="polite"></div>
        <div data-ajax-add-to-order="true" style="display:none;" aria-hidden="true"></div>

        <div style="display:grid; grid-template-columns: 1.6fr 0.9fr; gap:20px; align-items:start;">
            <section class="card" style="padding:22px;">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:18px;">
                    <h2 style="margin:0; font-size:1.5rem;">{{ __('messages.items') }}</h2>
                    <span class="eyebrow" style="margin:0;">{{ __('messages.fresh_picks') }}</span>
                </div>

                <div id="itemsGrid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px;">
                    <div class="card" style="padding:18px; grid-column:1/-1; color:#586b62;">Loading items...</div>
                </div>
            </section>

            <aside style="display:grid; gap:20px;">
                <section class="card" style="padding:22px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px;">
                        <h3 style="margin:0; font-size:1.3rem;">{{ __('messages.order_summary') }}</h3>
                    </div>

                    <div id="cartContent" style="color:#586b62; min-height:110px;">{{ __('messages.cart_empty') }}</div>
                    <button id="checkoutButton" type="button" class="primary-button" style="width:100%; margin-top:12px;">{{ __('messages.checkout') }}</button>
                </section>

                <section class="card" style="padding:22px;">
                    <h3 style="margin:0 0 14px; font-size:1.2rem;">{{ __('messages.order_history') }}</h3>
                    <div id="orderHistory" style="color:#586b62;">Loading orders...</div>
                </section>
            </aside>
        </div>
    </div>

    <script>
        (function () {
            const itemsGrid = document.getElementById('itemsGrid');
            const cartContent = document.getElementById('cartContent');
            const orderHistory = document.getElementById('orderHistory');
            const checkoutButton = document.getElementById('checkoutButton');
            const storeMessage = document.getElementById('storeMessage');
            const token = localStorage.getItem('grocery_token');
            const state = { items: [], cart: [] };

            function showMessage(message, type = 'success') {
                storeMessage.textContent = message;
                storeMessage.className = 'alert ' + type;
            }

            function ensureAuthenticated() {
                if (!token) {
                    showMessage('Please log in to continue shopping.', 'error');
                    setTimeout(() => window.location.href = '{{ route('login') }}', 700);
                    return false;
                }

                return true;
            }

            function renderItems(items) {
                if (!items.length) {
                    itemsGrid.innerHTML = '<div class="card" style="padding:18px; grid-column:1/-1; color:#586b62;">No items available yet.</div>';
                    return;
                }

                itemsGrid.innerHTML = items.map((item) => `
                    <article class="card" style="padding:18px; background:linear-gradient(180deg, #ffffff 0%, #f6faf7 100%);">
                        <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; margin-bottom:12px;">
                            <h3 style="margin:0; font-size:1.12rem;">${item.name}</h3>
                            <span style="display:inline-flex; background:#edf7ef; color:#155a30; border-radius:999px; padding:6px 10px; font-size:0.75rem; font-weight:800;">${item.stock_quantity} {{ __('messages.stock') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; color:#4b5d55;">
                            <span style="font-size:1.08rem; font-weight:800; color:#123d27;">৳${Number(item.price).toFixed(2)}</span>
                        </div>
                        <button type="button" class="primary-button" data-ajax-add-to-order="true" data-item-id="${item.id}" data-item-name="${item.name}" data-item-price="${item.price}" style="width:100%;">{{ __('messages.add_to_order') }}</button>
                    </article>
                `).join('');
            }

            function renderCart() {
                if (!state.cart.length) {
                    cartContent.innerHTML = '{{ __('messages.cart_empty') }}';
                    return;
                }

                const total = state.cart.reduce((sum, item) => sum + Number(item.price) * Number(item.quantity), 0);

                cartContent.innerHTML = `
                    <table style="width:100%; border-collapse:collapse;">
                        <tbody>
                            ${state.cart.map((item) => `
                                <tr>
                                    <td style="padding:8px 0; border-bottom:1px solid #edf1ee;">${item.name}</td>
                                    <td style="padding:8px 0; border-bottom:1px solid #edf1ee; text-align:right;">x${item.quantity}</td>
                                    <td style="padding:8px 0; border-bottom:1px solid #edf1ee; text-align:right;">৳${(Number(item.price) * Number(item.quantity)).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:12px; font-weight:800;">{{ __('messages.total') }}</td>
                                <td style="padding-top:12px; text-align:right; font-weight:800;">৳${total.toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                `;
            }

            function addToCart(itemId) {
                const item = state.items.find((entry) => Number(entry.id) === Number(itemId));
                if (!item) {
                    return;
                }

                const existing = state.cart.find((entry) => Number(entry.id) === Number(itemId));
                if (existing) {
                    existing.quantity += 1;
                } else {
                    state.cart.push({
                        id: Number(item.id),
                        name: item.name,
                        price: Number(item.price),
                        quantity: 1,
                    });
                }

                renderCart();
                showMessage(`${item.name} added to your order.`, 'success');
            }

            function renderOrders(orders) {
                if (!orders.length) {
                    orderHistory.innerHTML = '{{ __('messages.no_orders') }}';
                    return;
                }

                orderHistory.innerHTML = orders.map((order) => `
                    <div class="card" style="padding:12px; margin-bottom:10px; background:#f8fbf9;">
                        <div style="display:flex; justify-content:space-between; gap:8px; align-items:center; margin-bottom:8px;">
                            <strong>Order #${order.id}</strong>
                            <span class="eyebrow" style="margin:0;">${order.status}</span>
                        </div>
                        <div style="margin:8px 0; color:#4b5d55;">
                            ${order.items.map((item) => `
                                <div style="display:flex; justify-content:space-between; gap:8px; padding:4px 0; border-bottom:1px solid #edf1ee;">
                                    <span>${item.grocery_item?.name || 'Item'}</span>
                                    <span>x${item.quantity}</span>
                                    <span>৳${Number(item.price).toFixed(2)}</span>
                                </div>
                            `).join('')}
                        </div>
                        <div style="text-align:right; font-weight:800; margin-top:6px;">Total: ৳${Number(order.total_amount).toFixed(2)}</div>
                    </div>
                `).join('');
            }

            async function fetchItems() {
                if (!ensureAuthenticated()) {
                    return;
                }

                const response = await fetch('/api/items', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });

                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(result.message || 'Unable to load items.');
                }

                state.items = Array.isArray(result.data) ? result.data : [];
                renderItems(state.items);
            }

            async function fetchOrders() {
                if (!ensureAuthenticated()) {
                    return;
                }

                const response = await fetch('/api/orders', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                });

                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(result.message || 'Unable to load orders.');
                }

                renderOrders(Array.isArray(result.data) ? result.data : []);
            }

            async function placeOrder() {
                if (!ensureAuthenticated()) {
                    return;
                }

                if (!state.cart.length) {
                    showMessage('Your cart is empty.', 'error');
                    return;
                }

                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                    body: JSON.stringify({
                        items: state.cart.map((item) => ({
                            grocery_item_id: Number(item.id),
                            quantity: Number(item.quantity),
                        })),
                    }),
                });

                const result = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(result.message || 'Unable to place order.');
                }

                showMessage('Order placed successfully.', 'success');
                state.cart = [];
                renderCart();
                fetchOrders();
            }

            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-ajax-add-to-order="true"]');
                if (!button) {
                    return;
                }

                if (!ensureAuthenticated()) {
                    return;
                }

                addToCart(button.dataset.itemId);
            });

            checkoutButton.addEventListener('click', async () => {
                try {
                    await placeOrder();
                } catch (error) {
                    showMessage(error.message || 'Unable to place order.', 'error');
                }
            });

            renderCart();
            fetchItems().catch((error) => showMessage(error.message || 'Unable to load items.', 'error'));
            fetchOrders().catch((error) => showMessage(error.message || 'Unable to load orders.', 'error'));
        })();
    </script>
@endsection
