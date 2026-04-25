<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends Controller
{
    protected ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $products = $this->repository->getActiveProducts();

        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products->items()),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
            ],
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $product = $this->repository->create(array_merge($data, [
            'images' => $data['images'] ?? [],
            'attributes' => $data['attributes'] ?? [],
        ]));

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product),
        ], 201);
    }

    public function show(string $slug)
    {
        $product = $this->repository->findBySlug($slug);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product),
        ]);
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        $product = $this->repository->findOrFail($id);

        $data = $request->validated();

        $this->repository->update($product, array_merge($data, [
            'images' => $data['images'] ?? $product->images,
            'attributes' => $data['attributes'] ?? $product->attributes,
        ]));

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product->fresh()),
        ]);
    }

    public function destroy(string $id)
    {
        $product = $this->repository->findOrFail($id);
        $this->repository->delete($product);

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé.',
        ]);
    }
}
