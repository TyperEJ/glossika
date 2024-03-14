<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RecommendedProductTest extends TestCase
{
    use EntityGenerator, RefreshDatabase;

    public function test_get_recommendation(): void
    {
        $user = $this->createUser();

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(new Collection([1, 2, 3]));

        $response = $this->actingAs($user)
            ->getJson('/api/recommendation');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Success',
            'data' => [1, 2, 3],
        ]);
    }
}
