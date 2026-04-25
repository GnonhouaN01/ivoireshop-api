<?php

namespace App\Repository;

use App\Models\Cart;

class CartRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Cart());
    }

    public function getOrCreateForUser(int $userId): Cart
    {
        return $this->model->firstOrCreate(['user_id' => $userId]);
    }

    public function getCartWithItems(int $userId, array $relations = ['items.product']): ?Cart
    {
        return $this->query()
            ->where('user_id', $userId)
            ->with($relations)
            ->first();
    }

    public function clear(Cart $cart): bool
    {
        return $cart->items()->delete();
    }
}
