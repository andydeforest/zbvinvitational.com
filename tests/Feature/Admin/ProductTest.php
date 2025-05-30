<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure we're authenticated for all admin routes
        $this->actingAs(User::factory()->create());
    }

    /**
     * Generate a minimal valid payload for product creation.
     */
    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Donation',
            'short_name' => 'Test',
            'type' => 'donation',
            'description' => 'A short description of the product.',
            'price' => 2000,
            'allow_custom_price' => false,
            'is_active' => true,
            'metadata' => [],
        ], $overrides);
    }

    #[Test]
    public function it_creates_a_product()
    {
        $payload = $this->validPayload();

        $response = $this->post(route('admin.products.store'), $payload);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => $payload['name'],
            'short_name' => $payload['short_name'],
            'type' => $payload['type'],
            'description' => $payload['description'],
            'price' => $payload['price'],
            'allow_custom_price' => (int) $payload['allow_custom_price'],
            'is_active' => (int) $payload['is_active'],
            'metadata' => json_encode($payload['metadata']),
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['name', 'type', 'price']);
    }

    #[Test]
    public function it_creates_a_golf_product_with_valid_metadata()
    {
        $validMeta = [
            'name' => 'John Doe',
            'instructions' => 'Tee off at first light',
        ];

        $payload = $this->validPayload([
            'type' => 'golf',
            'metadata' => $validMeta,
        ]);

        $response = $this->post(route('admin.products.store'), $payload);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'type' => 'golf',
            'metadata' => json_encode($validMeta),
        ]);
    }
}
