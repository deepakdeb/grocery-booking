@extends('admin.layout')

@section('title', 'Inventory')

@section('content')
    <div class="toolbar">
        <h1 class="toolbar-title">{{ __('messages.inventory') }}</h1>
        <a href="{{ route('admin.items.create') }}" class="primary-button">Add item</a>
    </div>

    <div class="card table-card">
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
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description ?: '—' }}</td>
                        <td>৳{{ number_format($item->price, 2) }}</td>
                        <td><span class="stock-pill">{{ $item->stock_quantity }}</span></td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.items.edit', $item) }}" class="secondary-button small">Edit</a>
                                <form method="POST" action="{{ route('admin.items.destroy', $item) }}" onsubmit="return confirm('Delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="danger-button small">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">No grocery items have been added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
