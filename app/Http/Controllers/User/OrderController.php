<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(protected OrderService $service) {}

    public function index(Request $request)
    {
        $orders = $this->service->historyForUser($request->user()->id);

        return response()->json([
            'data' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.grocery_item_id' => 'required|integer|exists:grocery_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = $this->service->createForUser($request->user()->id, $validated['items']);

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
