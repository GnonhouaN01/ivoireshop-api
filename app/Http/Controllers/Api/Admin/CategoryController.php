<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repository\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Category\CategoryRequest;

class CategoryController extends Controller
{
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $categories = $this->repository->getActiveCategories();

        return response()->json([
            'success' => true,
            'categories' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
            ],
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->repository->create($request->validated());

        return response()->json([
            'success' => true,
            'category' => $category,
        ], 201);
    }

    public function show(string $id)
    {
        $category = $this->repository->findOrFail($id);

        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }

    public function update(CategoryRequest $request, string $id)
    {
        $category = $this->repository->findOrFail($id);

        $this->repository->update($category, $request->validated());

        return response()->json([
            'success' => true,
            'category' => $category->fresh(),
        ]);
    }

    public function destroy(string $id)
    {
        $category = $this->repository->findOrFail($id);
        $this->repository->delete($category);

        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée.',
        ]);
    }

    public function products(string $slug)
    {
        $products = $this->repository->getProductsBySlug($slug);
        $category = $this->repository->findBySlug($slug);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'category' => $category,
            'products' => $products,
        ]);
    }
}
