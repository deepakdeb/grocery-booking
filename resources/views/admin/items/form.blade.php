@extends('admin.layout')

@section('title', request()->route('id') ? 'Edit item' : 'Add item')

@section('content')
    <div class="toolbar">
        <h1 class="toolbar-title">{{ request()->route('id') ? 'Edit item' : 'Add item' }}</h1>
        <a href="{{ route('admin.items.index') }}" class="secondary-button">Back to inventory</a>
    </div>

    <div id="adminFormMessage" class="message" style="display:none;"></div>

    <div class="card">
        <form id="inventoryForm" method="POST">
            <div class="form-grid">
                <div class="field full-width">
                    <label for="name">{{ __('messages.name') }}</label>
                    <input id="name" name="name" type="text" required>
                </div>

                <div class="field full-width">
                    <label for="description">{{ __('messages.description') }}</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="field">
                    <label for="price">{{ __('messages.price') }}</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" required>
                </div>

                <div class="field">
                    <label for="stock_quantity">{{ __('messages.stock') }}</label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" required>
                </div>
            </div>

            <div class="toolbar" style="margin-top: 24px; margin-bottom: 0;">
                <button type="submit" class="primary-button" id="submitButton">Save item</button>
                <a href="{{ route('admin.items.index') }}" class="secondary-button">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const token = localStorage.getItem('grocery_token');
            const storedUser = JSON.parse(localStorage.getItem('grocery_user') || '{}');
            const userRole = (storedUser && storedUser.role && storedUser.role.name) || null;
            const itemId = @json(request()->route('id'));
            const isEdit = Boolean(itemId);
            const form = document.getElementById('inventoryForm');
            const message = document.getElementById('adminFormMessage');
            const submitButton = document.getElementById('submitButton');

            function showMessage(text, type) {
                message.textContent = text;
                message.className = 'message ' + type;
                message.style.display = 'block';
            }

            if (!token || userRole !== 'admin') {
                showMessage('Unauthorized. Please log in as an admin.', 'error');
                setTimeout(() => window.location.href = '{{ route('login') }}', 700);
                return;
            }

            if (isEdit) {
                fetch(`/api/admin/grocery-items/${itemId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token,
                    },
                })
                    .then(async (response) => {
                        const result = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(result.message || 'Unable to load item.');
                        }

                        const item = result.data || {};
                        document.getElementById('name').value = item.name || '';
                        document.getElementById('description').value = item.description || '';
                        document.getElementById('price').value = item.price || '';
                        document.getElementById('stock_quantity').value = item.stock_quantity || 0;
                        submitButton.textContent = 'Update item';
                    })
                    .catch((error) => {
                        showMessage(error.message || 'Unable to load item.', 'error');
                    });
            }

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const payload = {
                    name: document.getElementById('name').value,
                    description: document.getElementById('description').value,
                    price: Number(document.getElementById('price').value),
                    stock_quantity: Number(document.getElementById('stock_quantity').value),
                };

                const url = isEdit ? `/api/admin/grocery-items/${itemId}` : '/api/admin/grocery-items';
                const method = isEdit ? 'PUT' : 'POST';

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
                        throw new Error(result.message || 'Unable to save item.');
                    }

                    showMessage(isEdit ? 'Item updated successfully.' : 'Item created successfully.', 'success');
                    setTimeout(() => window.location.href = '{{ route('admin.items.index') }}', 500);
                } catch (error) {
                    showMessage(error.message || 'Unable to save item.', 'error');
                }
            });
        })();
    </script>
@endsection
