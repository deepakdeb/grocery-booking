@extends('layouts.front')

@section('title', __('messages.store'))

@section('content')
    @php
        $cart = session('cart', []);
        $cartItems = collect($cart)->map(function ($quantity, $itemId) {
            $item = \App\Models\GroceryItem::find($itemId);
            return $item ? ['id' => $item->id, 'name' => $item->name, 'price' => (float) $item->price, 'quantity' => (int) $quantity] : null;
        })->filter()->values();

        $cartTotal = $cartItems->sum(fn($entry) => $entry['price'] * $entry['quantity']);
    @endphp

    <div class="container">
        @if (session('success'))
            <div class="alert success" style="display:block;">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert error" style="display:block;">{{ session('error') }}</div>
        @endif

        <div id="storeMessage" class="alert" aria-live="polite"></div>
        <div data-ajax-add-to-order="true" style="display:none;" aria-hidden="true"></div>

        <div style="display:grid; grid-template-columns: 1.6fr 0.9fr; gap:20px; align-items:start;">
            <section class="card" style="padding:22px;">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:18px;">
                    <h2 style="margin:0; font-size:1.5rem;">{{ __('messages.items') }}</h2>
                    <span class="eyebrow" style="margin:0;">{{ __('messages.fresh_picks') }}</span>
                </div>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px;">
                    @forelse ($items as $item)
                        <article class="card" style="padding:18px; background:linear-gradient(180deg, #ffffff 0%, #f6faf7 100%);">
                            <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start; margin-bottom:12px;">
                                <h3 style="margin:0; font-size:1.12rem;">{{ $item->name }}</h3>
                                <span style="display:inline-flex; background:#edf7ef; color:#155a30; border-radius:999px; padding:6px 10px; font-size:0.75rem; font-weight:800;">{{ $item->stock_quantity }} {{ __('messages.stock') }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; color:#4b5d55;">
                                <span style="font-size:1.08rem; font-weight:800; color:#123d27;">৳{{ number_format($item->price, 2) }}</span>
                            </div>
                            <!-- data-ajax-add-to-order="true" -->
                            <button type="button" class="primary-button" data-ajax-add-to-order="true" data-item-id="{{ $item->id }}" data-item-name="{{ $item->name }}" data-item-price="{{ $item->price }}" style="width:100%;">{{ __('messages.add_to_order') }}</button>
                        </article>
                    @empty
                        <div class="card" style="padding:18px; grid-column:1/-1; color:#586b62;">{{ __('messages.no_orders') }}</div>
                    @endforelse
                </div>
            </section>

            <aside style="display:grid; gap:20px;">
                <section class="card" style="padding:22px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px;">
                        <h3 style="margin:0; font-size:1.3rem;">{{ __('messages.order_summary') }}</h3>
                    </div>

                    @if ($cartItems->isEmpty())
                        <div style="color:#586b62; min-height:110px;">{{ __('messages.cart_empty') }}</div>
                    @else
                        <table style="width:100%; border-collapse:collapse;">
                            <tbody>
                                @foreach ($cartItems as $entry)
                                    <tr>
                                        <td style="padding:8px 0; border-bottom:1px solid #edf1ee;">{{ $entry['name'] }}</td>
                                        <td style="padding:8px 0; border-bottom:1px solid #edf1ee; text-align:right;">x{{ $entry['quantity'] }}</td>
                                        <td style="padding:8px 0; border-bottom:1px solid #edf1ee; text-align:right;">৳{{ number_format($entry['price'] * $entry['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="padding-top:12px; font-weight:800;">{{ __('messages.total') }}</td>
                                    <td style="padding-top:12px; text-align:right; font-weight:800;">৳{{ number_format($cartTotal, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    @endif

                    <form method="POST" action="{{ route('orders.checkout') }}" style="margin-top:12px;">
                        @csrf
                        @foreach ($cartItems as $entry)
                            <input type="hidden" name="items[{{ $entry['id'] }}]" value="{{ $entry['quantity'] }}">
                        @endforeach
                        <button type="submit" class="primary-button" style="width:100%;">{{ __('messages.checkout') }}</button>
                    </form>
                </section>

                <section class="card" style="padding:22px;">
                    <h3 style="margin:0 0 14px; font-size:1.2rem;">{{ __('messages.order_history') }}</h3>
                    <div style="color:#586b62;">{{ __('messages.no_orders') }}</div>
                </section>
            </aside>
        </div>
    </div>

    <script>
        const addToOrderButtons = document.querySelectorAll('[data-ajax-add-to-order="true"]');
        const storeMessage = document.getElementById('storeMessage');
        const csrfToken = '{{ csrf_token() }}';

        function showStoreMessage(message, type) {
            storeMessage.textContent = message;
            storeMessage.className = 'alert ' + type;
        }

        addToOrderButtons.forEach((button) => {
            button.addEventListener('click', async () => {
                const itemId = button.dataset.itemId;
                const itemName = button.dataset.itemName;

                try {
                    const response = await fetch('{{ route('orders.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            item_id: Number(itemId),
                            quantity: 1,
                        }),
                    });

                    const result = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(result.message || 'Unable to add item to order.');
                    }

                    showStoreMessage(itemName + ' added to your order.', 'success');
                    window.location.reload();
                } catch (error) {
                    showStoreMessage(error.message || 'Unable to add item to order.', 'error');
                }
            });
        });
    </script>
@endsection
