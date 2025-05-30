<?php

namespace Tests\Unit\Admin;

use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_product_category()
    {
        $category = ProductCategory::factory()->create([
            'name' => 'Donations',
            'description' => 'Support our cause',
        ]);

        $this->assertDatabaseHas('product_categories', [
            'name' => 'Donations',
            'description' => 'Support our cause',
        ]);
    }

    #[Test]
    public function it_has_no_products_by_default()
    {
        $category = ProductCategory::factory()->create();
        $this->assertCount(0, $category->products);
    }

    #[Test]
    public function it_can_have_many_products()
    {
        $category = ProductCategory::factory()->create();

        $category->products()->createMany([
            ['name' => 'Donation A', 'short_name' => 'DonA', 'type' => 'donation', 'price' => 1000, 'is_active' => true],
            ['name' => 'Donation B', 'short_name' => 'DonB', 'type' => 'donation', 'price' => 2000, 'is_active' => true],
        ]);

        $this->assertCount(2, $category->fresh()->products);
    }
}
