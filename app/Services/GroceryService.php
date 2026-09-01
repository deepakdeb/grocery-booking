<?php

namespace App\Services;

use App\Repositories\Contracts\GroceryRepositoryInterface;

class GroceryService
{
    public function __construct(
        protected GroceryRepositoryInterface $repository
    ) {}

    public function all(?int $perPage = null, ?int $page = null)
    {
        return $this->repository->all($perPage, $page);
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
