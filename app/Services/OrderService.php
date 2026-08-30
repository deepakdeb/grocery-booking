<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {}

    public function createForUser(int $userId, array $items)
    {
        if (empty($items)) {
            throw ValidationException::withMessages([
                'items' => ['At least one grocery item is required.'],
            ]);
        }

        return $this->repository->createWithItems($userId, $items);
    }

    public function historyForUser(int $userId)
    {
        return $this->repository->history($userId);
    }
}
