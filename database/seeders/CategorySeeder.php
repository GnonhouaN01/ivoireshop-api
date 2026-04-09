<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            ['name' => 'Pagnes & Tissus',       'sort_order' => 1],
            ['name'=> 'Robes & Tenue',          'sort_order' => 2],
            ['name' => 'Bijoux & Accessoires',  'sort_order' => 3],
            ['name' => 'Sacs & Maroquinerie',   'sort_order' => 4],
            ['name' => 'Mode Homme',            'sort_order' => 5],
            ['name' => 'Mode Enfant',           'sort_order' => 6],
            ['name' => 'Chaussures',            'sort_order' => 7],
            ['name' => 'Art & Décoration',      'sort_order' => 8],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'       => $category['name'],
                'slug'       => Str::slug($category['name']),
                'is_active'  => true,
                'sort_order' => $category['sort_order'],
            ]);
        }
    }
}
