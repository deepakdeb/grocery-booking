<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function createWithItems(int $userId, array $items): mixed;

    public function history(int $userId);
}
