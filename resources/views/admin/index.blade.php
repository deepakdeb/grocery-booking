@extends('admin.layout')

@section('content')
    <div class="page-header">
        <div>
            <p class="eyebrow">Dashboard</p>
            <h1>{{ __('messages.admin_dashboard') }}</h1>
        </div>
        <a href="{{ route('admin.items.index') }}" class="primary-button">{{ __('messages.items') }}</a>
    </div>

    <div id="adminMessage" class="message" style="display:none;"></div>
    <div id="statsGrid" class="stats-grid"></div>

    <div class="card table-card">
        <div class="table-header">
            <h2>Latest inventory</h2>
            <a href="{{ route('admin.items.create') }}" class="secondary-button">Add item</a>
        </div>
        <div id="inventoryTable"></div>
    </div>

    <script>
        (function () {
            const token = localStorage.getItem('grocery_token');
            const storedUser = JSON.parse(localStorage.getItem('grocery_user') || '{}');
            const userRole = (storedUser && storedUser.role && storedUser.role.name) || null;
            const adminMessage = document.getElementById('adminMessage');
            const statsGrid = document.getElementById('statsGrid');
            const inventoryTable = document.getElementById('inventoryTable');

            function showMessage(text, type) {
                adminMessage.textContent = text;
                adminMessage.className = 'message ' + type;
                adminMessage.style.display = 'block';
            }

            function renderStats(items, total) {
                const lowStock = items.filter((item) => Number(item.stock_quantity) <= 5).length;
                const totalValue = items.reduce((sum, item) => sum + (Number(item.price) * Number(item.stock_quantity)), 0);

                statsGrid.innerHTML = `
                    <div class="stat-card">
                        <span class="stat-label">Inventory</span>
                        <strong>${total}</strong>
                        <small>active products</small>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Low stock</span>
                        <strong>${lowStock}</strong>
                        <small>items to review</small>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Value</span>
                        <strong>৳${totalValue.toFixed(2)}</strong>
                        <small>stock value</small>
                    </div>
                `;
            }

            function renderTable(items) {
                if (!items.length) {
                    inventoryTable.innerHTML = '<div class="empty-state">No items available yet.</div>';
                    return;
                }

                inventoryTable.innerHTML = `
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.price') }}</th>
                                <th>{{ __('messages.stock') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items.map((item) => `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>৳${Number(item.price).toFixed(2)}</td>
                                    <td><span class="stock-pill">${item.stock_quantity}</span></td>
                                    <td>
                                        <div class="inline-actions">
                                            <a href="{{ route('admin.items.edit', ['id' => '__ID__']) }}" class="secondary-button small" data-edit-item="${item.id}">Edit</a>
                                            <button type="button" class="danger-button small" data-admin-delete="${item.id}">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;

                inventoryTable.querySelectorAll('[data-edit-item]').forEach((link) => {
                    const itemId = link.getAttribute('data-edit-item');
                    link.href = '{{ route('admin.items.edit', ['id' => ':id']) }}'.replace(':id', itemId);
                });

                inventoryTable.querySelectorAll('[data-admin-delete]').forEach((button) => {
                    button.addEventListener('click', async () => {
                        const itemId = button.getAttribute('data-admin-delete');
                        if (!window.confirm('Delete this item?')) {
                            return;
                        }

                        try {
                            const response = await fetch(`/api/admin/grocery-items/${itemId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': 'Bearer ' + token,
                                },
                            });

                            const result = await response.json().catch(() => ({}));
                            if (!response.ok) {
                                throw new Error(result.message || 'Unable to delete item.');
                            }

                            button.closest('tr').remove();
                            showMessage('Item deleted successfully.', 'success');
                            window.location.reload();
                        } catch (error) {
                            showMessage(error.message || 'Unable to delete item.', 'error');
                        }
                    });
                });
            }

            if (!token || userRole !== 'admin') {
                showMessage('Unauthorized. Please log in as an admin.', 'error');
                setTimeout(() => window.location.href = '{{ route('login') }}', 700);
                return;
            }

            fetch('/api/admin/grocery-items?per_page=100', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token,
                },
            })
                .then(async (response) => {
                    const result = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(result.message || 'Unable to load inventory.');
                    }

                    const items = Array.isArray(result.data) ? result.data : [];
                    const total = (result.meta && result.meta.total) ? result.meta.total : items.length;
                    renderStats(items, total);
                    renderTable(items);
                })
                .catch((error) => {
                    showMessage(error.message || 'Unable to load inventory.', 'error');
                });
        })();
    </script>
@endsection