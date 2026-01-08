<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Order 1: User 4 membeli dari shop 1
        $order1 = Order::create([
            'order_id' => 'ORD-' . strtoupper(Str::random(12)),
            'user_id' => 4,
            'status' => 'paid',
            'total_prices' => 267500,
            'admin_fee' => 17500,
            'snap_token' => Str::random(40),
            'order_date' => now()->subDays(5),
            'expire_payment_at' => now()->subDays(5)->addHours(2),
        ]);
        OrderItem::create([
            'order_id' => $order1->order_id,
            'product_id' => 1,
            'shop_id' => 1,
            'product_price' => 250000,
            'quantity' => 1,
            'subtotal' => 250000,
            'admin_fee' => 17500,
            'status' => 'completed',
            'paid_at' => now()->subDays(5),
            'shipped_at' => now()->subDays(4),
        ]);

        // Order 2: User 4 membeli dari shop 2
        $order2 = Order::create([
            'order_id' => 'ORD-' . strtoupper(Str::random(12)),
            'user_id' => 4,
            'status' => 'paid',
            'total_prices' => 749000,
            'admin_fee' => 49000,
            'snap_token' => Str::random(40),
            'order_date' => now()->subDays(3),
            'expire_payment_at' => now()->subDays(3)->addHours(2),
        ]);
        OrderItem::create([
            'order_id' => $order2->order_id,
            'product_id' => 6,
            'shop_id' => 4,
            'product_price' => 350000,
            'quantity' => 2,
            'subtotal' => 700000,
            'admin_fee' => 49000,
            'status' => 'shipped',
            'paid_at' => now()->subDays(3),
            'shipped_at' => now()->subDays(2),
        ]);

        // Order 3: User 2 membeli dari shop 2
        $order3 = Order::create([
            'order_id' => 'ORD-' . strtoupper(Str::random(12)),
            'user_id' => 2,
            'status' => 'paid',
            'total_prices' => 321000,
            'admin_fee' => 21000,
            'snap_token' => Str::random(40),
            'order_date' => now()->subDays(2),
            'expire_payment_at' => now()->subDays(2)->addHours(2),
        ]);
        OrderItem::create([
            'order_id' => $order3->order_id,
            'product_id' => 8,
            'shop_id' => 1,
            'product_price' => 300000,
            'quantity' => 1,
            'subtotal' => 300000,
            'admin_fee' => 21000,
            'status' => 'completed',
            'paid_at' => now()->subDays(2),
            'shipped_at' => now()->subDays(1),
        ]);

        // Order 4: User 3 membeli dari shop 4
        $order4 = Order::create([
            'order_id' => 'ORD-' . strtoupper(Str::random(12)),
            'user_id' => 3,
            'status' => 'paid',
            'total_prices' => 481500,
            'admin_fee' => 31500,
            'snap_token' => Str::random(40),
            'order_date' => now()->subDays(1),
            'expire_payment_at' => now()->subDays(1)->addHours(2),
        ]);
        OrderItem::create([
            'order_id' => $order4->order_id,
            'product_id' => 7,
            'shop_id' => 4,
            'product_price' => 450000,
            'quantity' => 1,
            'subtotal' => 450000,
            'admin_fee' => 31500,
            'status' => 'pending',
            'paid_at' => now()->subDays(1),
        ]);

        // Order 5: User 2 membeli dari shop 5
        $order5 = Order::create([
            'order_id' => 'ORD-' . strtoupper(Str::random(12)),
            'user_id' => 2,
            'status' => 'unpaid',
            'total_prices' => 192600,
            'admin_fee' => 12600,
            'snap_token' => Str::random(40),
            'order_date' => now(),
            'expire_payment_at' => now()->addHours(2),
        ]);
        OrderItem::create([
            'order_id' => $order5->order_id,
            'product_id' => 10,
            'shop_id' => 5,
            'product_price' => 180000,
            'quantity' => 1,
            'subtotal' => 180000,
            'admin_fee' => 12600,
            'status' => 'pending',
        ]);
    }
}
