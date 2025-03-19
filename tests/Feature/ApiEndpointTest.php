<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Entity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiEndpointTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_entities_for_a_category()
    {
        $category = Category::create(['name' => 'Security']);
        Entity::create([
            'api' => 'Security API',
            'description' => 'A security-related API',
            'link' => 'https://securityapi.com',
            'category_id' => $category->id,
        ]);

        $response = $this->getJson('/api/Security');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'api' => 'Security API',
                        'description' => 'A security-related API',
                        'link' => 'https://securityapi.com',
                        'category' => [
                            'id' => $category->id,
                            'category' => 'Security'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_unknown_category()
    {
        $response = $this->getJson('/api/UnknownCategory');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Category not found'
            ]);
    }
}
