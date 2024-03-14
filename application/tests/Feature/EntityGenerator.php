<?php

namespace Tests\Feature;

use App\Models\RecommendedProduct;
use App\Models\User;

trait EntityGenerator
{
    private function createUser(array $attributes = []): User
    {
        $attributes = array_merge([
            'email' => fake()->email(),
            'password' => 'Ab@888',
        ], $attributes);

        $user = new User($attributes);

        $user->save();

        return $user;
    }

    private function createRecommendedProduct(array $attributes = []): RecommendedProduct
    {
        $attributes = array_merge([
            'name' => 'Product',
            'price' => 10,
        ], $attributes);

        $recommendedProduct = new RecommendedProduct($attributes);

        $recommendedProduct->save();

        return $recommendedProduct;
    }
}
