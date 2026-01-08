<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\AuctionWinner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Auction 1: Akun ML Collector (Ongoing)
        $auction1 = Auction::create([
            'product_id' => 14,
            'seller_id' => 3,
            'start_price' => 1500000,
            'current_price' => 1750000,
            'start_time' => now()->subHours(2),
            'end_time' => now()->addHours(4),
            'status' => 'running',
        ]);

        AuctionBid::create([
            'auction_id' => $auction1->auction_id,
            'user_id' => 2,
            'bid_price' => 1550000,
            'created_at' => now()->subHours(2)->addMinutes(5),
        ]);
        AuctionBid::create([
            'auction_id' => $auction1->auction_id,
            'user_id' => 3,
            'bid_price' => 1650000,
            'created_at' => now()->subHours(1)->addMinutes(30),
        ]);
        AuctionBid::create([
            'auction_id' => $auction1->auction_id,
            'user_id' => 4,
            'bid_price' => 1750000,
            'created_at' => now()->subMinutes(15),
        ]);

        // Auction 2: Akun Genshin Full 5 Star (Upcoming)
        $auction2 = Auction::create([
            'product_id' => 15,
            'seller_id' => 2,
            'start_price' => 2000000,
            'current_price' => 2000000,
            'start_time' => now()->addHours(24),
            'end_time' => now()->addHours(48),
            'status' => 'pending',
        ]);

        // Auction 3: Akun Dota 2 Immortal (Ended - User 2 Won)
        $auction3 = Auction::create([
            'product_id' => 5,
            'seller_id' => 3,
            'start_price' => 500000,
            'current_price' => 725000,
            'start_time' => now()->subDays(3),
            'end_time' => now()->subDays(2),
            'status' => 'ended',
        ]);

        AuctionBid::create([
            'auction_id' => $auction3->auction_id,
            'user_id' => 4,
            'bid_price' => 525000,
            'created_at' => now()->subDays(3)->addHours(1),
        ]);
        AuctionBid::create([
            'auction_id' => $auction3->auction_id,
            'user_id' => 3,
            'bid_price' => 625000,
            'created_at' => now()->subDays(2)->addHours(18),
        ]);
        AuctionBid::create([
            'auction_id' => $auction3->auction_id,
            'user_id' => 2,
            'bid_price' => 725000,
            'created_at' => now()->subDays(2)->addMinutes(30),
        ]);

        AuctionWinner::create([
            'auction_id' => $auction3->auction_id,
            'user_id' => 2,
            'winning_price' => 725000,
            'created_at' => now()->subDays(2),
        ]);

        // Auction 4: Item Skin Valorant (Running)
        $auction4 = Auction::create([
            'product_id' => 11,
            'seller_id' => 2,
            'start_price' => 350000,
            'current_price' => 425000,
            'start_time' => now()->subHours(8),
            'end_time' => now()->addHours(16),
            'status' => 'running',
        ]);

        AuctionBid::create([
            'auction_id' => $auction4->auction_id,
            'user_id' => 4,
            'bid_price' => 375000,
            'created_at' => now()->subHours(7),
        ]);
        AuctionBid::create([
            'auction_id' => $auction4->auction_id,
            'user_id' => 2,
            'bid_price' => 425000,
            'created_at' => now()->subHours(2),
        ]);

        // Auction 5: Item Mount CoC (Ended - User 3 Won)
        $auction5 = Auction::create([
            'product_id' => 13,
            'seller_id' => 2,
            'start_price' => 280000,
            'current_price' => 320000,
            'start_time' => now()->subDays(1)->subHours(4),
            'end_time' => now()->subHours(2),
            'status' => 'ended',
        ]);

        AuctionBid::create([
            'auction_id' => $auction5->auction_id,
            'user_id' => 2,
            'bid_price' => 290000,
            'created_at' => now()->subDays(1)->subHours(2),
        ]);
        AuctionBid::create([
            'auction_id' => $auction5->auction_id,
            'user_id' => 3,
            'bid_price' => 320000,
            'created_at' => now()->subHours(6),
        ]);

        AuctionWinner::create([
            'auction_id' => $auction5->auction_id,
            'user_id' => 3,
            'winning_price' => 320000,
            'created_at' => now()->subHours(2),
        ]);
    }
}
