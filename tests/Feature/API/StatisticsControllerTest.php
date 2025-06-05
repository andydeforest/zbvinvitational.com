<?php

namespace Tests\Feature;

use App\Http\Controllers\API\StatisticsController;
use App\Models\Event\Golfer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Products\Types\DonationProduct;
use App\Products\Types\SponsorshipProduct;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StatisticsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_computes_and_caches_statistics()
    {
        // use the array cache driver so everything is in‐memory and isolated
        config(['cache.default' => 'array']);
        Cache::flush();

        $now = Carbon::create(2025, 6, 1, 0, 0, 0);
        Carbon::setTestNow($now);

        $yearStart = $now->copy()->startOfYear();
        $yearEnd = $now->copy()->endOfYear();
        $weekStart = $now->copy()->subDays(7);

        $recentOrder = Order::factory()->create([
            'status' => 'paid',
            'created_at' => $now->copy()->subDays(2),
        ]);

        $olderOrder = Order::factory()->create([
            'status' => 'paid',
            'created_at' => $now->copy()->subDays(30),
        ]);

        $sponsorship = Product::factory()->create([
            'type' => SponsorshipProduct::identifier(),
        ]);
        $donation = Product::factory()->create([
            'type' => DonationProduct::identifier(),
        ]);

        $recentSponsorshipItem = OrderItem::factory()->create([
            'order_id' => $recentOrder->id,
            'product_id' => $sponsorship->id,
            'quantity' => 2,
            'unit_price_cents' => 5000,
            'created_at' => $now->copy()->subDays(2),
        ]);

        $olderSponsorshipItem = OrderItem::factory()->create([
            'order_id' => $olderOrder->id,
            'product_id' => $sponsorship->id,
            'quantity' => 1,
            'unit_price_cents' => 5000,
            'created_at' => $now->copy()->subDays(10),
        ]);

        $recentDonationItem = OrderItem::factory()->create([
            'order_id' => $recentOrder->id,
            'product_id' => $donation->id,
            'quantity' => 3,
            'unit_price_cents' => 1000,
            'created_at' => $now->copy()->subDays(2),
        ]);

        $olderDonationItem = OrderItem::factory()->create([
            'order_id' => $olderOrder->id,
            'product_id' => $donation->id,
            'quantity' => 1,
            'unit_price_cents' => 2000,
            'created_at' => $now->copy()->subDays(20),
        ]);

        Golfer::factory()->create([
            'order_item_id' => $recentSponsorshipItem->id,
            'created_at' => $now->copy()->subDays(3),
        ]);

        Golfer::factory()->create([
            'order_item_id' => $olderSponsorshipItem->id,
            'created_at' => $now->copy()->subDays(30),
        ]);

        $expectedYearlyOrders = Order::whereBetween('created_at', [$yearStart, $yearEnd])
            ->where('status', 'paid')
            ->count(); // should be 2
        $expectedWeeklyOrders = Order::whereBetween('created_at', [$weekStart, $now])
            ->where('status', 'paid')
            ->count(); // should be 1

        $expectedYearlyGolfers = 2;
        $expectedWeeklyGolfers = 1;

        $expectedYearlySponsorshipQty = 3;
        $expectedWeeklySponsorshipQty = 2;

        // yearly revenue = (3 × 1000) + (1 × 2000) = 5000
        // weekly revenue = (3 × 1000) = 3000
        $expectedYearlyDonationRevenue = 5000;
        $expectedWeeklyDonationRevenue = 3000;

        $this->assertFalse(Cache::has('statistics.overview'));

        $controller = new StatisticsController;
        $firstResult = $controller->index();

        $this->assertTrue(Cache::has('statistics.overview'));

        $this->assertEquals(
            $expectedYearlyOrders,
            $firstResult['yearly']['orders'],
            'Yearly order count did not match.'
        );
        $this->assertEquals(
            $expectedYearlyGolfers,
            $firstResult['yearly']['golfers'],
            'Yearly golfer count did not match.'
        );
        $this->assertEquals(
            $expectedYearlySponsorshipQty,
            $firstResult['yearly']['sponsorships'],
            'Yearly sponsorship quantity did not match.'
        );
        $this->assertEquals(
            $expectedYearlyDonationRevenue,
            $firstResult['yearly']['donationRevenue'],
            'Yearly donation revenue did not match.'
        );

        $this->assertEquals(
            $expectedWeeklyOrders,
            $firstResult['weekly']['orders'],
            'Weekly order count did not match.'
        );
        $this->assertEquals(
            $expectedWeeklyGolfers,
            $firstResult['weekly']['golfers'],
            'Weekly golfer count did not match.'
        );
        $this->assertEquals(
            $expectedWeeklySponsorshipQty,
            $firstResult['weekly']['sponsorships'],
            'Weekly sponsorship quantity did not match.'
        );
        $this->assertEquals(
            $expectedWeeklyDonationRevenue,
            $firstResult['weekly']['donationRevenue'],
            'Weekly donation revenue did not match.'
        );

        // add one more golfer in the current minute, tied to a brand-new paid order/item
        $newOrder = Order::factory()->create([
            'status' => 'paid',
            'created_at' => $now, // right now → inside year & week
        ]);

        $newItem = OrderItem::factory()->create([
            'order_id' => $newOrder->id,
            'product_id' => $sponsorship->id,
            'quantity' => 5,
            'unit_price_cents' => 0, // does not affect donation revenue
            'created_at' => $now,
        ]);

        Golfer::factory()->create([
            'order_item_id' => $newItem->id,
            'created_at' => $now,
        ]);

        // becase the result is cached, index should return exactly the same firstResult
        $secondResult = $controller->index();
        $this->assertEquals($firstResult, $secondResult);

        Cache::forget('statistics.overview');
        $this->assertFalse(Cache::has('statistics.overview'));

        $thirdResult = $controller->index();

        // expect 3 golfers (the original 2 + 1 new)
        $this->assertEquals(3, $thirdResult['yearly']['golfers']);
        $this->assertEquals(2, $thirdResult['weekly']['golfers']);

        // new order should also bump the order count
        $newExpectedYearlyOrders = Order::whereBetween('created_at', [$yearStart, $yearEnd])
            ->where('status', 'paid')
            ->count();
        $newExpectedWeeklyOrders = Order::whereBetween('created_at', [$weekStart, $now])
            ->where('status', 'paid')
            ->count();

        $this->assertEquals($newExpectedYearlyOrders, $thirdResult['yearly']['orders']);
        $this->assertEquals($newExpectedWeeklyOrders, $thirdResult['weekly']['orders']);

        // yearly sponsors = 3 original (2 + 1) + 5 new = 8
        $this->assertEquals(8, $thirdResult['yearly']['sponsorships']);

        // weekly sponsors = 2 original + 5 new = 7
        $this->assertEquals(7, $thirdResult['weekly']['sponsorships']);

        // donation revenue did not change (because our new item was unit_price_cents = 0)
        $this->assertEquals(5000, $thirdResult['yearly']['donationRevenue']);
        $this->assertEquals(3000, $thirdResult['weekly']['donationRevenue']);
    }
}
