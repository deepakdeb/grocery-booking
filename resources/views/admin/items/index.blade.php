@extends('admin.layout')

@section('title', 'Inventory')

@section('content')
    <div class="toolbar">
        <h1 class="toolbar-title">{{ __('messages.inventory') }}</h1>
        <a href="{{ route('admin.items.create') }}" class="primary-button">Add item</a>
    </div>

    <div id="adminItemMessage" class="message" style="display:none;"></div>
    <div class="card table-card">
        <div id="inventoryList">Loading inventory...</div>
    </div>

    <script>
        (function () {
            const token = localStorage.getItem('grocery_token');
            const storedUser = JSON.parse(localStorage.getItem('grocery_user') || '{}');
            const userRole = (storedUser && storedUser.role && storedUser.role.name) || null;
            const adminItemMessage = document.getElementById('adminItemMessage');
            const inventoryList = document.getElementById('inventoryList');

            function showMessage(text, type) {
                adminItemMessage.textContent = text;
                adminItemMessage.className = 'message ' + type;
                adminItemMessage.style.display = 'block';
            }

            function renderRows(items) {
                if (!items.length) {
                    inventoryList.innerHTML = '<div class="empty-state">No grocery items have been added yet.</div>';
                    return;
                }

                inventoryList.innerHTML = `
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.description') }}</th>
                                <th>{{ __('messages.price') }}</th>
                                <th>{{ __('messages.stock') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${items.map((item) => `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.description || '—'}</td>
                                    <td>৳${Number(item.price).toFixed(2)}</td>
                                    <td><span class="stock-pill">${item.stock_quantity}</span></td>
                                    <td>
                                        <div class="inline-actions">
                                            <a href="{{ route('admin.items.edit', ['id' => '__ID__']) }}" class="secondary-button small" data-edit-item="${item.id}">Edit</a>
                                            <button type="button" class="danger-button small" data-delete-item="${item.id}">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;

                inventoryList.querySelectorAll('[data-edit-item]').forEach((link) => {
                    const itemId = link.getAttribute('data-edit-item');
                    link.href = '{{ route('admin.items.edit', ['id' => ':id']) }}'.replace(':id', itemId);
                });

                inventoryList.querySelectorAll('[data-delete-item]').forEach((button) => {
                    button.addEventListener('click', async () => {
                        const itemId = button.getAttribute('data-delete-item');
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

            fetch('/api/admin/grocery-items', {
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
                    renderRows(items);
                })
                .catch((error) => {
                    showMessage(error.message || 'Unable to load inventory.', 'error');
                });
        })();
    </script>
@endsection
