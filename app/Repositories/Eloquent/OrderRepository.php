<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function createWithItems(int $userId, array $items): mixed
    {
        return DB::transaction(function () use ($userId, $items) {
            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => 0,
                'status' => 'completed',
            ]);

            $total = 0;

            foreach ($items as $entry) {
                $groceryItem = \App\Models\GroceryItem::lockForUpdate()->findOrFail($entry['grocery_item_id']);

                if ($groceryItem->stock_quantity < $entry['quantity']) {
                    throw new \InvalidArgumentException('Requested quantity exceeds available stock.');
                }

                $unitPrice = $groceryItem->price;
                $lineTotal = $unitPrice * $entry['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'grocery_item_id' => $groceryItem->id,
                    'quantity' => $entry['quantity'],
                    'price' => $unitPrice,
                ]);

                $groceryItem->decrement('stock_quantity', $entry['quantity']);
                $total += $lineTotal;
            }

            $order->update(['total_amount' => $total]);

            return $order->load('items.groceryItem');
        });
    }

    public function history(int $userId)
    {
        return Order::with('items.groceryItem')->where('user_id', $userId)->orderByDesc('created_at')->get();
    }
}
