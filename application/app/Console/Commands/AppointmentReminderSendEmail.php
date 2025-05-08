<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Crm;
use App\Models\PaymentMethod;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\ScheduleDetails;
use App\Models\ScheduleModel;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentReminderSendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendEmail:appointmentReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To have email notification 1 day before job (reminder)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appointment_reminder_email = DB::table('appointment_reminder_email')
                                            ->orderBy('created_at', 'desc')
                                            ->get();  

        $schedule_details = ScheduleDetails::whereNotNull('employee_id')
                                            ->whereDate('schedule_date', '>', date('Y-m-d'))
                                            ->get();

        foreach($schedule_details as $item)
        {
            $Schedule = ScheduleModel::find($item->tble_schedule_id);
            $SalesOrder = SalesOrder::find($item->sales_order_id);
            // $customer = Crm::find($SalesOrder->customer_id ?? '');
            $customer = Crm::where('customers.id', $SalesOrder->customer_id ?? '')
                        ->leftJoin('constant_settings','constant_settings.id', '=', 'customers.saluation')
                        ->select('customers.*', 'constant_settings.salutation_name')
                        ->first();

            $item->customer_id = $SalesOrder->customer_id ?? '';
            $item->customer_email = $customer->email ?? '';

            $start = Carbon::parse(date('Y-m-d'));
            $end = Carbon::parse($item->schedule_date);

            $item->diff_days = $end->diffInDays($start);

            if($item->diff_days == 1)
            {
                $flag = 0;

                // check appointment reminder email exists or not start

                if(!$appointment_reminder_email->isEmpty())
                {
                    foreach($appointment_reminder_email as $list)
                    {                       
                        $email_created_at = date('Y-m-d', strtotime($list->created_at));

                        if($list->sales_order_id == $item->sales_order_id && $list->schedule_date == $item->schedule_date && $email_created_at == date('Y-m-d'))
                        {
                            $flag = 1;                                                                   
                        }
                    }
                }   

                // check appointment reminder email exists or not end

                if($flag == 0)
                {
                    $to = $item->customer_email;

                    if(!empty($to))
                    {
                        if($item->cleaner_type == "team")
                        {
                            $xin_team = DB::table('xin_team')->where('team_id', $item->employee_id)->first();
                            $team_name = $xin_team->team_name;
                            $temp_emp_arr = explode(',', $xin_team->employee_id);

                            $temp_cleaner_name_arr = [];
                            $xin_employees = DB::table('xin_employees')->whereIn('user_id', $temp_emp_arr)->get();
                            foreach($xin_employees as $emp)
                            {
                                $temp_cleaner_name_arr[] = $emp->first_name . " " . $emp->last_name;
                            }

                            $cleaner_name = implode(', ', $temp_cleaner_name_arr);

                            $technician_name = $team_name . " (" . $cleaner_name . ")";
                        }
                        else if($item->cleaner_type == "individual")
                        {
                            $emp_arr = explode(',', $item->employee_id);

                            $xin_employees = DB::table('xin_employees')
                                                ->whereIn('xin_employees.user_id', $emp_arr)
                                                ->get();

                            $emp_name_arr = [];

                            foreach($xin_employees as $loop_emp)
                            {                  
                                $cleaner_name = $loop_emp->first_name . " " . $loop_emp->last_name;

                                $emp_name_arr[] = $cleaner_name;                                    
                            }

                            $technician_name = implode(',', $emp_name_arr);
                        }
                        else
                        {
                            $technician_name = "";
                        }

                        $company = Company::find($SalesOrder->company_id ?? '');
                        $quotation = Quotation::find($SalesOrder->quotation_id ?? '');
                        $payment_method = PaymentMethod::where('payment_method', 'Offline')->get();
                        $payment_method_arr = [];

                        foreach($payment_method as $list)
                        {
                            $payment_method_arr[] = $list->payment_option;
                        }                      

                        $data['schedule_date'] = date('F d, Y', strtotime($item->schedule_date));
                        $data['schedule_day'] = $item->schedule_day ?? '';
                        $data['schedule_time'] = date('h:i a', strtotime($item->startTime)) ." - ". date('h:i a', strtotime($item->endTime));
                        $data['arrival_time'] = date('h:i a', strtotime($item->startTime)) ." - ". date('h:i a', strtotime($item->startTime.'+1 hour'));
                        $data['service_address'] = $Schedule->address;
                        $data['technician_name'] = $technician_name;
                        $data['company'] = $company;
                        $data['customer'] = $customer;
                        $data['quotation'] = $quotation;
                        $data['payment_method'] = implode(' / ', $payment_method_arr);
                        $data['amount_payable'] = $item->pay_amount;

                        // $data['subject'] = "Appointment Reminder";
                        $data['subject'] = "Reminder: Upcoming Cleaning Service on " . date('d-m-Y', strtotime($item->schedule_date));
                        $data["to"] = $to;

                        Mail::send('emails.appointment_reminder_email', $data, function ($message) use ($data) {
                            $message->to($data['to'])
                                    ->subject($data['subject']);
                        });

                        DB::table('appointment_reminder_email')->insert([
                            'customer_id' => $item->customer_id,
                            'customer_email' => $item->customer_email,
                            'sales_order_id' => $item->sales_order_id,
                            'schedule_date' => $item->schedule_date,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }
            }
        }
    }
}
