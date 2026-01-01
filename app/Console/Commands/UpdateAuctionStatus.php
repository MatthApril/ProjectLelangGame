<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use Carbon\Carbon;


class UpdateAuctionStatus extends Command
{
    protected $signature = 'auction:update-status';
    protected $description = 'Update auction status based on time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Pending → Running
        Auction::where('status', 'pending')
            ->where('start_time', '<=', $now)
            ->update(['status' => 'running']);

        // Running → Ended
        Auction::where('status', 'running')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'ended']);

        $this->info('Auction status updated successfully.');
    }
}
