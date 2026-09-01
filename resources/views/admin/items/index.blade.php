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
                                                <a href="{{ route('admin.items.create') }}" class="secondary-button small">Edit</a>
                                                <button type="button" class="danger-button small" data-delete-item="${item.id}">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                })
                .catch((error) => {
                    showMessage(error.message || 'Unable to load inventory.', 'error');
                });
        })();
    </script>
@endsection
