<?php

namespace App\Repository;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function findWithDefaultAddress(int $userId): User
    {
        return $this->query()
            ->with(['addresses' => function ($query) {
                $query->where('is_default', true);
            }])
            ->findOrFail($userId);
    }
}
