<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(
        UserRepository $userRepository,
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ) {
        return response()->json([
            'success' => true,
            'stats' => [
                'customers' => $userRepository->query()->where('role', 'client')->count(),
                'orders' => $orderRepository->query()->count(),
                'products' => $productRepository->query()->count(),
                'categories' => $categoryRepository->query()->count(),
            ],
        ]);
    }

    public function customers(UserRepository $userRepository)
    {
        $customers = $userRepository->query()->where('role', 'client')->get();

        return response()->json([
            'success' => true,
            'customers' => $customers,
        ]);
    }
}
