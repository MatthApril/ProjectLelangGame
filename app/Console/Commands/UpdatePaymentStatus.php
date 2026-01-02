<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePaymentStatus extends Command
{

    protected $signature = 'payment:update-payment-status';
    protected $description = 'Update the status of payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $this->info('Starting to update payment statuses...');
    }
}
