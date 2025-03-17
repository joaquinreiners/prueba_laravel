<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Entity;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EntityTest extends TestCase
{
    use RefreshDatabase;

    public function an_entity_can_be_created()
    {
        $category = Category::create(['name' => 'Animals']);

        $entity = Entity::create([
            'api' => 'Test API',
            'description' => 'A test API description',
            'link' => 'https://testapi.com',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('entities', ['api' => 'Test API']);
        $this->assertEquals('Test API', $entity->api);
    }

    public function an_entity_requires_a_category_id()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Entity::create([
            'api' => 'No Category API',
            'description' => 'This should fail',
            'link' => 'https://failapi.com',
        ]);
    }
}

