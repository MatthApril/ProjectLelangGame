<?php

namespace App\Console\Commands;

use App\Models\Shop;
use Illuminate\Console\Command;
use Carbon\Carbon;
class AutoOpenCloseShops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shops:auto-toggle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically open/close shops based on their operating hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentTime = Carbon::now()->format('H:i:s');

        $shopsToOpen = Shop::where('status', 'closed')
            ->where('open_hour', '<=', $currentTime)
            ->where('close_hour', '>', $currentTime)
            ->get();

        foreach ($shopsToOpen as $shop) {
            $shop->update(['status' => 'open']);
            $this->info("Toko '{$shop->shop_name}' dibuka otomatis pada {$currentTime}");
        }

        $shopsToClose = Shop::where('status', 'open')
            ->where('close_hour', '<=', $currentTime)
            ->get();

        foreach ($shopsToClose as $shop) {
            $shop->update(['status' => 'closed']);
            $this->info("Toko '{$shop->shop_name}' ditutup otomatis pada {$currentTime}");
        }

        $this->info('Auto open/close shops completed!');
    }
}
