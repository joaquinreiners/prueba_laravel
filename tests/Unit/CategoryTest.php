<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function a_category_can_be_created()
    {
        $category = Category::create(['name' => 'Security']);

        $this->assertDatabaseHas('categories', ['name' => 'Security']);
        $this->assertEquals('Security', $category->name);
    }

    public function a_category_requires_a_name()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Category::create([]);
    }
}
 