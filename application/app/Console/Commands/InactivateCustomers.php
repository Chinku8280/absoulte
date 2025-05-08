<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Crm;
use Carbon\Carbon;

class InactivateCustomers extends Command
{
    protected $signature = 'customers:inactivate';
    protected $description = 'Inactivate customers after 3 months of inactivity';

    public function handle()
    {

        $activeCustomers = Crm::where('status', 1)->get();

        foreach ($activeCustomers as $customer) {

            if ($customer->updated_at->diffInMonths(Carbon::now()) >= 3) {
                $customer->update(['status' => 2]);
            }
        }
        $this->info('Customers inactivated successfully.');
    }
}
