<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class UpdateExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-expired-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status Pending, Payment in Cashier Menjadi Expired Setelah 15 Menit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = Order::where('status', 'Pending, Payment in Cashier')
        ->where('created_at', '<', Carbon::now()->subMinutes(15))
        ->get();

        foreach ($expiredOrders as $order) {
            $order->update(['order_status' => 'Expired']);
        }

        $this->info('Expired orders updated successfully.');
    }
}
