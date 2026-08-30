<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GroceryService;
use Illuminate\Http\Request;

class GroceryController extends Controller
{
    public function __construct(protected GroceryService $service) {}

    public function index()
    {
        return response()->json([
            'data' => $this->service->all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $item = $this->service->create($validated);

        return response()->json([
            'message' => 'Grocery item created successfully.',
            'data' => $item,
        ], 201);
    }

    public function show(int $id)
    {
        return response()->json([
            'data' => $this->service->getById($id),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
        ]);

        $item = $this->service->update($id, $validated);

        return response()->json([
            'message' => 'Grocery item updated successfully.',
            'data' => $item,
        ]);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Grocery item deleted successfully.',
        ]);
    }
}
