<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateOpenShopStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:update-open-shop-status';
    protected $description = 'Update the status of open shops';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $this->info('Starting to update open shop statuses...');
    }
}
