<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\GroceryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function __construct(protected GroceryService $service) {}

    public function index(): View
    {
        $cart = session('cart', []);

        return view('orders.index', [
            'items' => $this->service->all(),
            'cart' => $cart,
        ]);
    }

    public function addToOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:grocery_items,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);
        $itemId = (int) $validated['item_id'];
        $quantity = (int) ($validated['quantity'] ?? 1);

        $cart[$itemId] = ($cart[$itemId] ?? 0) + $quantity;
        $request->session()->put('cart', $cart);

        $item = $this->service->getById($itemId);

        return response()->json([
            'message' => 'Item added to your order.',
            'quantity' => $cart[$itemId],
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (float) $item->price,
            ],
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('orders')->with('error', 'Your cart is empty.');
        }

        return redirect()->route('orders')->with('success', 'Order prepared successfully. Please continue to checkout from your account.');
    }
}
