<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(protected OrderService $service) {}

    public function index(Request $request): JsonResponse
    {
        $orders = $this->service->historyForUser((int) $request->user()->id);

        return response()->json([
            'data' => $orders,
        ]);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->service->createForUser((int) $request->user()->id, $request->validated()['items']);

            return response()->json([
                'message' => 'Order placed successfully.',
                'data' => [
                    'id' => $order->id,
                    'user_id' => $order->user_id,
                    'total_amount' => (float) $order->total_amount,
                    'status' => $order->status,
                    'items' => $order->items->map(fn ($item) => [
                        'id' => $item->id,
                        'grocery_item_id' => $item->grocery_item_id,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                    ])->values()->all(),
                ],
            ], 201);
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'items' => [$exception->getMessage()],
            ])->status(422);
        }
    }
}
