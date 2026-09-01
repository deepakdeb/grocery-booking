<?php

namespace App\Repositories\Contracts;

interface GroceryRepositoryInterface
{
    public function all(?int $perPage = null, ?int $page = null);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
}
