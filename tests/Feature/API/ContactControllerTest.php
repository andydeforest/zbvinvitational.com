<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // mocks sanctum user
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
    }

    #[Test]
    public function index_returns_all_contacts_sorted_by_created_at_desc()
    {
        $oldest = Contact::factory()->create(['created_at' => Carbon::now()->subDays(2)]);
        $middle = Contact::factory()->create(['created_at' => Carbon::now()->subDay()]);
        $newest = Contact::factory()->create(['created_at' => Carbon::now()]);

        $response = $this->getJson('/api/contact');

        $response->assertOk()
            ->assertJsonCount(3);

        $ids = array_column($response->json(), 'id');

        $this->assertEquals(
            [$newest->id, $middle->id, $oldest->id],
            $ids
        );
    }

    #[Test]
    public function index_limits_results_when_latest_parameter_is_provided()
    {
        // Given three contacts at different times
        $first = Contact::factory()->create(['created_at' => Carbon::now()->subDays(2)]);
        $second = Contact::factory()->create(['created_at' => Carbon::now()->subDay()]);
        $third = Contact::factory()->create(['created_at' => Carbon::now()]);

        $response = $this->getJson('/api/contact?latest=2');

        $response->assertOk()
            ->assertJsonCount(2);

        $ids = array_column($response->json(), 'id');

        $this->assertEquals(
            [$third->id, $second->id],
            $ids
        );
    }
}
