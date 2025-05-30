<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golf_cat = ProductCategory::create([
            'name' => 'Golf Registration',
            'description' => 'Please add this to your cart if you wish to participate in the 2024 Zeke Bondy-Villa Invitational Golf Tournament. Please add one for each player who will be participating. Registration includes lunch, golf, cart, and dinner/drinks following golf. Registration for 18-holes of golf also includes breakfast.',
        ]);

        $donation_cat = ProductCategory::create([
            'name' => 'Donation to American Cancer Society',
            'description' => 'Please add this to your cart if you wish to to make a donation to the American Cancer Society.',
        ]);

        Product::create([
            'product_category_id' => $golf_cat->id,
            'name' => '18-holes ($140)',
            'short_name' => '18-holes',
            'type' => 'golf',
            'price' => 14000,
            'is_active' => true,
            'metadata' => null,
            'description' => null,
        ]);

        Product::create([
            'product_category_id' => $golf_cat->id,
            'name' => '9-holes ($100)',
            'short_name' => '9-holes',
            'type' => 'golf',
            'price' => 10000,
            'is_active' => true,
            'metadata' => null,
            'description' => null,
        ]);

        Product::create([
            'product_category_id' => $donation_cat->id,
            'name' => '$20',
            'short_name' => '$20 donation',
            'type' => 'donation',
            'price' => 2000,
            'is_active' => true,
            'metadata' => null,
            'description' => null,
        ]);

        Product::create([
            'product_category_id' => $donation_cat->id,
            'name' => '$40',
            'short_name' => '$40 donation',

            'type' => 'donation',
            'price' => 4000,
            'is_active' => true,
            'metadata' => null,
            'description' => null,
        ]);

        Product::create([
            'product_category_id' => $donation_cat->id,
            'name' => '$100',
            'short_name' => '$100 donation',
            'type' => 'donation',
            'price' => 10000,
            'is_active' => true,
            'metadata' => null,
            'description' => null,
        ]);

        Product::create([
            'product_category_id' => $donation_cat->id,
            'name' => 'Custom Amount',
            'short_name' => 'Custom Amount',
            'type' => 'donation',
            'allow_custom_price' => true,
            'price' => 0,
            'is_active' => true,
            'metadata' => null,
            'description' => null,
        ]);

        Product::create([
            'product_category_id' => null,
            'name' => 'Hole Sponsorship',
            'short_name' => 'Hole Sponsorship',
            'type' => 'sponsorship',
            'price' => 25000,
            'is_active' => true,
            'metadata' => null,
            'description' => 'Hole Sponsorships are sold for each of the eighteen (18) holes on the golf course. The name of the Hole Sponsor is displayed on a sign at the tee box of the sponsored hole and recognition in tournament printed materials.',
        ]);

        Product::create([
            'product_category_id' => null,
            'name' => 'Dinner Only',
            'short_name' => 'Dinner',
            'type' => 'dinner',
            'price' => 1500,
            'is_active' => true,
            'metadata' => null,
            'description' => 'For those not golfing, this will help us cover the price of the wonderful catered dinner served at the course after the golfing tournament. Please add one for each person who will be attending.',
        ]);
    }
}
