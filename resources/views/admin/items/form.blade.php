@extends('admin.layout')

@section('title', isset($item) ? 'Edit item' : 'Add item')

@section('content')
    <div class="toolbar">
        <h1 class="toolbar-title">{{ isset($item) ? 'Edit item' : 'Add item' }}</h1>
        <a href="{{ route('admin.items.index') }}" class="secondary-button">Back to inventory</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ isset($item) ? route('admin.items.update', $item) : route('admin.items.store') }}">
            @csrf
            @if (isset($item))
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="field full-width">
                    <label for="name">{{ __('messages.name') }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}" required>
                </div>

                <div class="field full-width">
                    <label for="description">{{ __('messages.description') }}</label>
                    <textarea id="description" name="description">{{ old('description', $item->description ?? '') }}</textarea>
                </div>

                <div class="field">
                    <label for="price">{{ __('messages.price') }}</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $item->price ?? '') }}" required>
                </div>

                <div class="field">
                    <label for="stock_quantity">{{ __('messages.stock') }}</label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', $item->stock_quantity ?? 0) }}" required>
                </div>
            </div>

            <div class="toolbar" style="margin-top: 24px; margin-bottom: 0;">
                <button type="submit" class="primary-button">{{ isset($item) ? __('messages.update_item') : __('messages.save_item') }}</button>
                <a href="{{ route('admin.items.index') }}" class="secondary-button">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
