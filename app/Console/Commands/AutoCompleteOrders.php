<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class AutoCompleteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto complete orders after 3 days of shipping';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderItems = OrderItem::where('status', 'shipped')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', now()->subDays(3))
            ->get();

        foreach ($orderItems as $orderItem) {

            $orderItem->update([
                'status' => 'completed',
            ]);

            $shop = $orderItem->shop;
            $shop->decrement('running_transactions', $orderItem->subtotal);
            $shop->increment('shop_balance', $orderItem->subtotal);

            $this->info("Order #{$orderItem->order_item_id} auto-completed");

            (new NotificationService())->send($orderItem->order->user_id, 'pesanan_otomatis_selesai', [
                'order_id' => $orderItem->order->order_id,
                'username' => $orderItem->order->account->username,
            ]);
        }


        $this->info('Auto-complete orders finished!');

    }
}
