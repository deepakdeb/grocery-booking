<?php

namespace App\Repositories\Eloquent;

use App\Models\GroceryItem;
use App\Repositories\Contracts\GroceryRepositoryInterface;

class GroceryRepository implements GroceryRepositoryInterface
{
    public function all(?int $perPage = null, ?int $page = null)
    {
        return GroceryItem::orderBy('id', 'asc')->paginate($perPage, ['*'], 'page', $page);
    }

    public function find(int $id)
    {
        return GroceryItem::findOrFail($id);
    }

    public function create(array $data)
    {
        return GroceryItem::create($data);
    }

    public function update(int $id, array $data)
    {
        $item = GroceryItem::findOrFail($id);
        $item->update($data);

        return $item->fresh();
    }

    public function delete(int $id): bool
    {
        $item = GroceryItem::findOrFail($id);

        return (bool) $item->delete();
    }
}
