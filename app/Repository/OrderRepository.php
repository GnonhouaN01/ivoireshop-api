<?php

namespace App\Repository;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Order());
    }

    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return $this->query()->where('order_number', $orderNumber)->first();
    }

    public function getForUser(int $userId): Collection
    {
        return $this->query()
            ->forUser($userId)
            ->with(['items.product', 'user'])
            ->latest()
            ->get();
    }

    public function findForUserByNumber(int $userId, string $orderNumber): ?Order
    {
        return $this->query()
            ->forUser($userId)
            ->where('order_number', $orderNumber)
            ->with(['items.product', 'user'])
            ->first();
    }

    public function getAllWithRelations(): Collection
    {
        return $this->query()->with(['user', 'items'])->latest()->get();
    }
}
