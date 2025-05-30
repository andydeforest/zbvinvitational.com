<?php

namespace Tests\Feature\Admin;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_category(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Donations',
            'description' => 'All donation-related products.',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('product_categories', ['name' => 'Donations']);
    }

    #[Test]
    public function it_updates_a_category(): void
    {
        $this->actingAs(User::factory()->create());

        $category = ProductCategory::factory()->create();

        $response = $this->put(route('admin.categories.update', $category), [
            'name' => 'Updated',
            'description' => 'Updated description.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_categories', ['name' => 'Updated']);
    }

    #[Test]
    public function it_deletes_a_category(): void
    {
        $this->actingAs(User::factory()->create());

        $category = ProductCategory::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect();
        $this->assertModelMissing($category);
    }
}
