<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event\Golfer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Products\Types\DonationProduct;
use App\Products\Types\SponsorshipProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        return Cache::remember('statistics.overview', 60, function () {
            $now = Carbon::now();

            $yearStart = $now->copy()->startOfYear();
            $yearEnd = $now->copy()->endOfYear();
            $weekStart = $now->copy()->subDays(7);

            // golfers
            $yearlyGolferCount = Golfer::whereBetween('created_at', [$yearStart, $yearEnd])
                ->wherePaid()
                ->count();

            $weeklyGolferCount = Golfer::whereBetween('created_at', [$weekStart, $now])
                ->wherePaid()
                ->count();

            // orders
            $yearlyOrderCount = Order::whereBetween('created_at', [$yearStart, $yearEnd])
                ->where('status', 'paid')
                ->count();
            $weeklyOrderCount = Order::whereBetween('created_at', [$weekStart, $now])
                ->where('status', 'paid')
                ->count();

            // sponsors
            $yearlySponsorCount = OrderItem::whereHas('product', function ($q) {
                $q->where('type', SponsorshipProduct::identifier());
            })
                ->wherePaid()
                ->whereBetween('created_at', [$yearStart, $yearEnd])
                ->sum('quantity');

            $weeklySponsorCount = OrderItem::whereHas('product', function ($q) {
                $q->where('type', SponsorshipProduct::identifier());
            })
                ->wherePaid()
                ->whereBetween('created_at', [$weekStart, $now])
                ->sum('quantity');

            // donation revenue (in cents)
            $yearlyDonationRevenueCents = OrderItem::whereHas('product', function ($q) {
                $q->where('type', DonationProduct::identifier());
            })
                ->wherePaid()
                ->whereBetween('created_at', [$yearStart, $yearEnd])
                ->sum(DB::raw('quantity * unit_price_cents'));

            $weeklyDonationRevenueCents = OrderItem::whereHas('product', function ($q) {
                $q->where('type', DonationProduct::identifier());
            })
                ->wherePaid()
                ->whereBetween('created_at', [$weekStart, $now])
                ->sum(DB::raw('quantity * unit_price_cents'));

            return [
                'yearly' => [
                    'orders' => $yearlyOrderCount,
                    'golfers' => $yearlyGolferCount,
                    'sponsorships' => $yearlySponsorCount,
                    'donationRevenue' => $yearlyDonationRevenueCents,
                ],
                'weekly' => [
                    'orders' => $weeklyOrderCount,
                    'golfers' => $weeklyGolferCount,
                    'sponsorships' => $weeklySponsorCount,
                    'donationRevenue' => $weeklyDonationRevenueCents,
                ],
            ];
        });
    }
}
