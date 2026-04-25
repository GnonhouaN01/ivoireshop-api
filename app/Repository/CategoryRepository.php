<?php

namespace App\Repository;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Category());
    }

    public function getActiveCategories(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->active()->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->query()->where('slug', $slug)->first();
    }

    public function getProductsBySlug(string $slug): Collection
    {
        $category = $this->findBySlug($slug);

        if (!$category) {
            return collect();
        }

        return $category->activeProducts()->with('category')->get();
    }
}
