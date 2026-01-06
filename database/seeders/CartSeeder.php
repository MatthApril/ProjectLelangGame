<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cart untuk user 4
        $cart1 = Cart::create(['user_id' => 4]);
        CartItem::create([
            'cart_id' => $cart1->cart_id,
            'product_id' => 1,
            'quantity' => 1,
        ]);
        CartItem::create([
            'cart_id' => $cart1->cart_id,
            'product_id' => 6,
            'quantity' => 2,
        ]);

        // Cart untuk user 2
        $cart2 = Cart::create(['user_id' => 2]);
        CartItem::create([
            'cart_id' => $cart2->cart_id,
            'product_id' => 10,
            'quantity' => 1,
        ]);

        // Cart untuk user 3
        $cart3 = Cart::create(['user_id' => 3]);
        CartItem::create([
            'cart_id' => $cart3->cart_id,
            'product_id' => 8,
            'quantity' => 1,
        ]);
        CartItem::create([
            'cart_id' => $cart3->cart_id,
            'product_id' => 13,
            'quantity' => 3,
        ]);
    }
}
