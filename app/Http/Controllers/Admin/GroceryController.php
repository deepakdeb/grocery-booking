<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGroceryItemRequest;
use App\Http\Requests\Admin\UpdateGroceryItemRequest;
use App\Services\GroceryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroceryController extends Controller
{
    public function __construct(protected GroceryService $service) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $this->resolvePerPage($request);
        $page = (int) $request->query('page', 1);

        $items = $this->service->all($perPage, $page);

        return $this->paginatedResponse($items);
    }

    public function store(StoreGroceryItemRequest $request): JsonResponse
    {
        $item = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Grocery item created successfully.',
            'data' => $item,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'data' => $this->service->getById($id),
        ]);
    }

    public function update(UpdateGroceryItemRequest $request, int $id): JsonResponse
    {
        $item = $this->service->update($id, $request->validated());

        return response()->json([
            'message' => 'Grocery item updated successfully.',
            'data' => $item,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Grocery item deleted successfully.',
        ]);
    }
}
