<?php

namespace App\Repository;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function getActiveProducts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->active()
            ->with('category')
            ->paginate($perPage);
    }

    public function getFeaturedProducts(int $limit = 10): Collection
    {
        return $this->query()
            ->featured()
            ->with('category')
            ->limit($limit)
            ->get();
    }

    public function getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->active()
            ->byCategory($categoryId)
            ->with('category')
            ->paginate($perPage);
    }

    public function search(string $term, int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->active()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%");
            })
            ->with('category')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->first();
    }
}

