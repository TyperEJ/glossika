<?php

namespace Database\Seeders;

use App\Models\RecommendedProduct;
use Illuminate\Database\Seeder;

class RecommendedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (RecommendedProduct::query()->count() === 0) {
            $this->createProducts();
        }
    }

    private function createProducts(): void
    {
        RecommendedProduct::query()->create([
            'name' => 'Product A',
            'price' => 10,
        ]);

        RecommendedProduct::query()->create([
            'name' => 'Product B',
            'price' => 20,
        ]);

        RecommendedProduct::query()->create([
            'name' => 'Product C',
            'price' => 30,
        ]);

        RecommendedProduct::query()->create([
            'name' => 'Product D',
            'price' => 40,
        ]);

        RecommendedProduct::query()->create([
            'name' => 'Product E',
            'price' => 50,
        ]);
    }
}
