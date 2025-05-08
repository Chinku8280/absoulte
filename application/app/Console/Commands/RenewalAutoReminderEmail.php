<?php

namespace App\Console\Commands;

use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\User;
use Illuminate\Console\Command;

class RenewalAutoReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:renewal-auto-reminder-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email reminder to the administrator on the same day as the last session in each sales order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $sales_order = SalesOrder::join('tble_schedule', 'tble_schedule.sales_order_id', '=', 'sales_order.id')
        //                                     ->leftjoin('customers', 'sales_order.customer_id', '=', 'customers.id')
        //                                     ->select('sales_order.*', 'customers.customer_type', 'customers.customer_name', 'customers.individual_company_name')
        //                                     ->where('sales_order.job_status', '!=', 2)
        //                                     ->get();

        // foreach($sales_order as $item)
        // {
        //     $ScheduleDetails = ScheduleDetails::where('sales_order_id', $item->id)->where('job_status', 0)->get();

        //     $item->balance_job = count($ScheduleDetails);


        //     if($item->balance_job == 1)
        //     {
        //         $item->balance_schedule_date = $ScheduleDetails[0]->schedule_date ?? '';
        //     }      
        //     else
        //     {
        //         $item->balance_schedule_date = "";
        //     }

        //     $today = date('Y-m-d');

        //     if($today == $item->balance_schedule_date)
        //     {
        //         $flag = 0;
        //     }

        //     if($flag == 0)
        //     {
        //         $user = User::find(1);

        //         $to = $user->email ?? '';

        //         if(!empty($to))
        //         {
                    
        //         }
        //     }
        // }
    }
}
