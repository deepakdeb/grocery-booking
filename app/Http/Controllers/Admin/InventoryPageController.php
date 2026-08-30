<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGroceryItemRequest;
use App\Http\Requests\Admin\UpdateGroceryItemRequest;
use App\Services\GroceryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventoryPageController extends Controller
{
    public function __construct(protected GroceryService $service) {}

    public function dashboard(): View
    {
        return view('admin.index', [
            'items' => $this->service->all(),
        ]);
    }

    public function index(): View
    {
        return view('admin.items.index', [
            'items' => $this->service->all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.items.form', ['item' => null]);
    }

    public function store(StoreGroceryItemRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    public function edit(int $id): View
    {
        return view('admin.items.form', [
            'item' => $this->service->getById($id),
        ]);
    }

    public function update(UpdateGroceryItemRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}
