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
        // force cache reset between tests
        config(['cache.default' => 'array']);
        Cache::flush();

        $now = Carbon::create(2025, 06, 01, 00, 00, 00);
        Carbon::setTestNow($now);

        $yearStart = $now->copy()->startOfYear();
        $yearEnd = $now->copy()->endOfYear();
        $weekStart = $now->copy()->subDays(7);

        Golfer::factory()->create([
            'created_at' => $now->copy()->subDays(3),
        ]);

        Golfer::factory()->create([
            'created_at' => $now->copy()->subDays(30),
        ]);

        Order::factory()->create([
            'created_at' => $now->copy()->subDays(2),
        ]);
        Order::factory()->create([
            'created_at' => $now->copy()->subDays(40),
        ]);

        $sponsorship = Product::factory()->create([
            'type' => SponsorshipProduct::identifier(),
        ]);
        $donation = Product::factory()->create([
            'type' => DonationProduct::identifier(),
        ]);

        OrderItem::factory()->create([
            'product_id' => $sponsorship->id,
            'quantity' => 2,
            'unit_price_cents' => 5000,
            'created_at' => $now->copy()->subDays(3),
        ]);

        OrderItem::factory()->create([
            'product_id' => $sponsorship->id,
            'quantity' => 1,
            'unit_price_cents' => 5000,
            'created_at' => $now->copy()->subDays(10),
        ]);

        OrderItem::factory()->create([
            'product_id' => $donation->id,
            'quantity' => 3,
            'unit_price_cents' => 1000,
            'created_at' => $now->copy()->subDays(2),
        ]);

        OrderItem::factory()->create([
            'product_id' => $donation->id,
            'quantity' => 1,
            'unit_price_cents' => 2000,
            'created_at' => $now->copy()->subDays(20),
        ]);

        $expectedYearlyOrders = Order::whereBetween('created_at', [$yearStart, $yearEnd])->count();
        $expectedWeeklyOrders = Order::whereBetween('created_at', [$weekStart, $now])->count();

        $expectedYearlyGolfers = 2;
        $expectedWeeklyGolfers = 1;

        $expectedYearlySponsorshipQty = 3;
        $expectedWeeklySponsorshipQty = 2;

        // yearly: (3 × 1000) + (1 × 2000) = 5000
        // weekly: 3 × 1000 = 3000
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

        Golfer::factory()->create([
            'created_at' => $now,
        ]);

        OrderItem::factory()->create([
            'product_id' => $sponsorship->id,
            'quantity' => 5,
            'unit_price_cents' => 0, // no effect on donationRevenue
            'created_at' => $now,
        ]);

        $secondResult = $controller->index();

        $this->assertEquals($firstResult, $secondResult);

        Cache::forget('statistics.overview');
        $this->assertFalse(Cache::has('statistics.overview'));

        $thirdResult = $controller->index();

        $this->assertEquals(3, $thirdResult['yearly']['golfers']);
        $this->assertEquals(2, $thirdResult['weekly']['golfers']);

        $newExpectedYearlyOrders = Order::whereBetween('created_at', [$yearStart, $yearEnd])->count();
        $newExpectedWeeklyOrders = Order::whereBetween('created_at', [$weekStart, $now])->count();
        $this->assertEquals($newExpectedYearlyOrders, $thirdResult['yearly']['orders']);
        $this->assertEquals($newExpectedWeeklyOrders, $thirdResult['weekly']['orders']);

        $this->assertEquals(8, $thirdResult['yearly']['sponsorships']);
        $this->assertEquals(7, $thirdResult['weekly']['sponsorships']);

        $this->assertEquals(5000, $thirdResult['yearly']['donationRevenue']);
        $this->assertEquals(3000, $thirdResult['weekly']['donationRevenue']);
    }
}
