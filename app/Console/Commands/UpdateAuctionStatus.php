<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\AuctionWinner;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class UpdateAuctionStatus extends Command
{
    protected $signature = 'auction:update-status';
    protected $description = 'Update auction status based on time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // Pending → Running
        Auction::where('status', 'pending')
            ->where('start_time', '<=', $now)
            ->update(['status' => 'running']);

        DB::beginTransaction();

        try {
            $auctions = Auction::with(['highestBid.user', 'product'])
                ->where('status', 'running')
                ->where('end_time', '<=', $now)
                ->whereHas('highestBid')
                ->whereHas('product', function($q) {
                    $q->whereHas('shop', function($query) {
                        $query->where('status', 'open')->whereHas('owner');
                    });
                })
                ->lockForUpdate()
                ->get();

            foreach ($auctions as $auction) {

                $auction->update(['status' => 'ended']);

                $highestBid = $auction->highestBid;
                $user = $highestBid?->user;

                // TIDAK ADA BID
                if (!$highestBid) {
                    continue; // Lanjut ke auction berikutnya
                }

                if ($highestBid) {
                    AuctionWinner::create([
                        'auction_id'    => $auction->auction_id,
                        'user_id'       => $highestBid->user_id,
                        'winning_price' => $highestBid->bid_price,
                    ]);

                    $order = Order::create([
                        'order_id' => 'AUC-' . strtoupper(uniqid()),
                        'user_id'  => $highestBid->user_id,
                        'status'   => 'unpaid',
                        'expire_payment_at' => now()->addHours(1),
                        'total_prices' => $highestBid->bid_price,
                    ]);

                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $auction->product->product_id,
                        'shop_id' => $auction->product->shop_id,
                        'product_price' => $auction->product->price,
                        'quantity' => 1,
                        'subtotal' => $highestBid->bid_price,
                        'status' => 'shipped',
                    ]);

                    // Midtrans
                    \Midtrans\Config::$serverKey = config('midtrans.server_key');
                    \Midtrans\Config::$isProduction = false;
                    \Midtrans\Config::$isSanitized = true;
                    \Midtrans\Config::$is3ds = true;

                    $params = [
                        'transaction_details' => [
                            'order_id' => $order->order_id,
                            'gross_amount' => $order->total_prices,
                        ],
                        'customer_details' => [
                            'first_name' => $user->username,
                            'email' => $user->email,
                        ],
                        'callbacks' => [
                            'finish' => config('app.url') . route('payment.midtrans.callback', [], false),
                        ],
                    ];

                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    $order->update(['snap_token' => $snapToken]);
                }
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Error updating auction status: ' . $e->getMessage());
        }

        $this->info('Auction status updated successfully.');
    }
}
