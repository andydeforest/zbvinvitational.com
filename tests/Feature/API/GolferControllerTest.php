<?php

namespace Tests\Feature\API;

use App\Models\Event\Golfer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GolferControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
    }

    #[Test]
    public function index_returns_flat_list_where_status_is_paid_ordered_by_created_at_ascending()
    {
        $order = Order::factory()->create(['status' => 'paid']);
        $item1 = OrderItem::factory()->for($order)->create();
        $item2 = OrderItem::factory()->for($order)->create();
        $item3 = OrderItem::factory()->for($order)->create();

        $first = Golfer::factory()->for($item1)->create(['created_at' => Carbon::now()->subDays(2)]);
        $second = Golfer::factory()->for($item2)->create(['created_at' => Carbon::now()->subDay()]);
        $third = Golfer::factory()->for($item3)->create(['created_at' => Carbon::now()]);

        $response = $this->getJson('/api/golfers');

        $response->assertOk()
            ->assertJsonCount(3);

        $ids = array_column($response->json(), 'id');

        // orderBy('created_at') defaults to ascending
        $this->assertEquals(
            [$first->id, $second->id, $third->id],
            $ids
        );
    }

    #[Test]
    public function index_groups_by_year_where_status_is_paid_when_requested()
    {
        $order = Order::factory()->create(['status' => 'paid']);
        $itemA = OrderItem::factory()->for($order)->create();
        $itemB = OrderItem::factory()->for($order)->create();
        $itemC = OrderItem::factory()->for($order)->create();

        $g2023a = Golfer::factory()->for($itemA)->create(['created_at' => '2023-05-01 10:00:00']);
        $g2024 = Golfer::factory()->for($itemB)->create(['created_at' => '2024-02-15 14:30:00']);
        $g2023b = Golfer::factory()->for($itemC)->create(['created_at' => '2023-12-10 09:20:00']);

        $response = $this->getJson('/api/golfers?group_by=year');

        $response->assertOk();

        $data = $response->json();
        $this->assertArrayHasKey('2023', $data);
        $this->assertArrayHasKey('2024', $data);

        $this->assertCount(2, $data['2023']);
        $ids2023 = array_column($data['2023'], 'id');
        $this->assertEqualsCanonicalizing(
            [$g2023a->id, $g2023b->id],
            $ids2023
        );

        $this->assertCount(1, $data['2024']);
        $this->assertEquals(
            $g2024->id,
            $data['2024'][0]['id']
        );
    }
}
