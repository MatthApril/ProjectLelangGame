<?php

namespace App\Console\Commands;

use App\Models\Complaint;
use Illuminate\Console\Command;

class AutoResolveComplaints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'complaints:auto-resolve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto resolve complaints if seller does not respond within 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $complaints = Complaint::where('status', 'waiting_seller')
            ->whereDoesntHave('response')
            ->where('created_at', '<=', now()->subDay())
            ->get();

        if ($complaints->isEmpty()) {
            $this->info('No complaints to auto-resolve');
            return;
        }

        foreach ($complaints as $complaint) {
            $orderItem = $complaint->orderItem;
            $shop = $orderItem->shop;
            $complaint->buyer->increment('balance', $orderItem->subtotal);
            $shop->decrement('running_transactions', $orderItem->subtotal);
            $orderItem->update(['status' => 'cancelled']);
            $complaint->update([
                'status' => 'resolved',
                'decision' => 'refund',
                'is_auto_resolved' => true,
                'resolved_at' => now()
            ]);
            $this->info("Complaint #{$complaint->complaint_id} auto-resolved (seller tidak respons > 24 jam)");
        }
        $this->info('Auto-resolve complaints finished! Total: ' . $complaints->count());
    }
}
