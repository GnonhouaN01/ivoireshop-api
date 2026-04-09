<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $products = [
            [
                'name' => 'Robe Kente Traditionnelle',
                'category' => 'Robes & Tenue',
                'price' => 12500,
                'compare_price' => 18000,
                'stock_quantity' => 25,
                'is_featured' => true,
                'short_description' => 'Robe confectionnée en tissu Kente 100% coton par des artisans ivoiriens.',
            ],
            [
                'name' => 'Sac Wax Premium',
                'category' => 'Sacs & Maroquinerie',
                'price' => 8000,
                'compare_price' => null,
                'stock_quantity' => 40,
                'is_featured' => true,
                'short_description' => 'Sac en tissu wax de qualité supérieure, coutures renforcées.',
            ],
            [
                'name' => 'Collier Perles Baoulé',
                'category' => 'Bijoux & Accessoires',
                'price' => 5500,
                'compare_price' => null,
                'stock_quantity' => 3,  // Stock bas exprès
                'is_featured' => false,
                'short_description' => 'Collier en perles naturelles fabriqué à la main par les artisans Baoulé.',
            ],
            [
                'name' => 'Boubou Homme Grand Boubou',
                'category' => 'Mode Homme',
                'price' => 22000,
                'compare_price' => 28000,
                'stock_quantity' => 15,
                'is_featured' => true,
                'short_description' => 'Grand Boubou brodé à la main, tissu bazin riche.',
            ],
            [
                'name' => 'Foulard Tie-Dye',
                'category' => 'Pagnes & Tissus',
                'price' => 3200,
                'compare_price' => null,
                'stock_quantity' => 60,
                'is_featured' => false,
                'short_description' => 'Foulard tie-dye 100% coton, couleurs naturelles.',
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();
            
            if (!$category) {
                continue; // Skip if category not found
            }

            Product::create([
                'category_id' => $category->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'short_description' => $data['short_description'],
                'price' => $data['price'],
                'compare_price' => $data['compare_price'],
                'stock_quantity' => $data['stock_quantity'],
                'is_active' => true,
                'is_featured' => $data['is_featured'],
                'sku' => 'IVS-'.strtoupper(Str::random(6)),
            ]);
        }
    }
}
