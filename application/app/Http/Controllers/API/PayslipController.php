<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayslipController extends Controller
{
    // monthly payslip list

    // public function monthly_payslip_list()
    // {
    //     $user_id = Auth::user()->id;

    //     $result = DB::table('xin_salary_payslips')
    //                     ->where('employee_id', $user_id)
    //                     ->select('payslip_id', 'employee_id', 'salary_month', 'net_salary', 'is_payment', 'year_to_date')
    //                     ->get();

    //     foreach($result as $item)
    //     {
    //         $item->month = date('m', strtotime('01-'.$item->salary_month));
    //         $item->month_name = date('F', strtotime('01-'.$item->salary_month));
    //         // $item->date_range = date('d-m-Y', strtotime('01-'.$item->salary_month)) .' - '. date('t-m-Y', strtotime('01-'.$item->salary_month));
    //     }

    //     if (count($result) > 0) 
    //     {
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Payslip List',
    //             'data' => $result
    //         ]);
    //     } 
    //     else 
    //     {
    //         return response()->json([
    //             'status' => false,
    //             'message' => "No Payslip Found",
    //         ]);
    //     }
    // }

    public function monthly_payslip_list()
    {
        $user_id = Auth::user()->id;

        $result = DB::table('xin_salary_payslips')
            ->where('employee_id', $user_id)
            ->whereYear(DB::raw("STR_TO_DATE(salary_month, '%m-%Y')"), date('Y'))
            // ->select('payslip_id', 'employee_id', 'salary_month', 'net_salary', 'is_payment', 'year_to_date')
            ->get();

        foreach ($result as $item) {
            $item->month = date('m', strtotime('01-' . $item->salary_month));
            $item->month_name = date('M', strtotime('01-' . $item->salary_month));
            // $item->date_range = date('d-m-Y', strtotime('01-'.$item->salary_month)) .' - '. date('t-m-Y', strtotime('01-'.$item->salary_month));
        }

        if (count($result) > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Payslip List',
                'data' => $result
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "No Payslip Found",
            ]);
        }
    }

    public function download_payslip_pdf(Request $request)
    {
        $user_id = Auth::user()->id;

        $payslip_id = $request->payslip_id;
        $payslip_key = $request->payslip_key;

		$system =  DB::table('xin_system_setting')->where('setting_id', 1)->get();

        $payment = DB::table('xin_salary_payslips')->where('payslip_key', $payslip_key)->get();     

        if ($payment->isEmpty()) 
        {
			return response()->json([
                'status' => false,
                'message' => 'Payment not found'
            ]);
		}

        $user = DB::table('xin_employees')->where('user_id', $user_id)->get();

        // if password generate option enable
		if ($system[0]->is_payslip_password_generate == 1) 
        {
            /**
			 * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
			 * to provide password as selected format in settings module.
			 */
			if ($system[0]->payslip_password_format == 'dateofbirth') {
				$password_val = date("dmY", strtotime($user[0]->date_of_birth));
			} else if ($system[0]->payslip_password_format == 'contact_no') {
				$password_val = $user[0]->contact_no;
			} else if ($system[0]->payslip_password_format == 'full_name') {
				$password_val = $user[0]->first_name . $user[0]->last_name;
			} else if ($system[0]->payslip_password_format == 'email') {
				$password_val = $user[0]->email;
			} else if ($system[0]->payslip_password_format == 'password') {
				$password_val = $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'user_password') {
				$password_val = $user[0]->username . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'employee_id') {
				$password_val = $user[0]->employee_id;
			} else if ($system[0]->payslip_password_format == 'employee_id_password') {
				$password_val = $user[0]->employee_id . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'dateofbirth_name') {
				$dob = date("dmY", strtotime($user[0]->date_of_birth));
				$fname = $user[0]->first_name;
				$lname = $user[0]->last_name;
				$password_val = $dob . $fname[0] . $lname[0];
			}
        }

        $_des_name = DB::table('xin_designations')->where('designation_id', $user[0]->designation_id)->get();
        if (!$_des_name->isEmpty()) 
        {
			$_designation_name = $_des_name[0]->designation_name;
		} 
        else 
        {
			$_designation_name = '';
		}

        $department = DB::table('xin_departments')->where('department_id', $user[0]->department_id)->get();
		if (!$department->isEmpty()) 
        {
			$_department_name = $department[0]->department_name;
		} 
        else 
        {
			$_department_name = '';
		}

        // company info
        $company = DB::table('xin_companies')->where('company_id', $user[0]->company_id)->get();

        $p_method = '';

		if (!$company->isEmpty()) 
        {
			$company_name = $company[0]->name;
			$company_logo = $company[0]->logo;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;

			$country = DB::table('xin_countries')->where('country_id', $company[0]->country)->get();
			if (!$country->isEmpty()) 
            {
				$country_name = $country[0]->country_name;
			} 
            else 
            {
				$country_name = '--';
			}

			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
		} 
        else 
        {
			$company_name = '--';
			$company_logo = '--';
			$address_1 = '--';
			$address_2 = '--';
			$city = '--';
			$state = '--';
			$zipcode = '--';
			$country_name = '--';
			$c_info_email = '--';
			$c_info_phone = '--';
		}

        $fname = $user[0]->first_name . ' ' . $user[0]->last_name;
		$created_at = $this->set_date_format($payment[0]->created_at);
		$date_of_joining = $this->set_date_format($user[0]->date_of_joining);
		$salary_month = $this->set_date_format($payment[0]->salary_month);

        // check
		$half_title = '';

		if ($system[0]->is_half_monthly == 1) 
        {
			$payment_check1 = DB::table('xin_salary_payslips')
                                    ->where('is_half_monthly_payroll', 1)
                                    ->where('employee_id', $payment[0]->employee_id)
                                    ->where('salary_month', $payment[0]->salary_month)
                                    ->orderBy('payslip_id', 'asc')
                                    ->get();

			$payment_check2 = DB::table('xin_salary_payslips')
                                    ->where('is_half_monthly_payroll', 1)
                                    ->where('employee_id', $payment[0]->employee_id)
                                    ->where('salary_month', $payment[0]->salary_month)
                                    ->orderBy('payslip_id', 'desc')
                                    ->get();
			
            $payment_check = DB::table('xin_salary_payslips')
                                    ->where('is_half_monthly_payroll', 1)
                                    ->where('employee_id', $payment[0]->employee_id)
                                    ->where('salary_month', $payment[0]->salary_month)
                                    ->get();
			
            if (count($payment_check) > 1) 
            {
				if ($payment_check2[0]->payslip_key == $payslip_key) 
                {
					$half_title = '(half)';
				} 
                else if ($payment_check1[0]->payslip_key == $payslip_key) 
                {
					$half_title = '(half)';
				} 
                else 
                {
					$half_title = '';
				}
			} 
            else 
            {
				$half_title = '(half)';
			}

			$half_title = $half_title;
		} 
        else 
        {
			$half_title = '';
		}

        // basic salary
		$bs = 0;
		$basic_salary = $payment[0]->basic_salary;

        // allowances
		$count_allowances = count(DB::table('xin_salary_payslip_allowances')->where('payslip_id', $payment[0]->payslip_id)->get());
		$allowances = DB::table('xin_salary_payslip_allowances')->where('payslip_id', $payment[0]->payslip_id)->get();
	
        // commissions
		$general_allowance_amount = 0;
		$motivation_allowance_amount = 0;

        $count_commissions = count(DB::table('xin_salary_payslip_commissions')->where('payslip_id', $payment[0]->payslip_id)->get());
		$commissions = DB::table('xin_salary_payslip_commissions')->where('payslip_id', $payment[0]->payslip_id)->get();
    
        // otherpayments
		$count_other_payments = count(DB::table('xin_salary_payslip_other_payments')->where('payslip_id', $payment[0]->payslip_id)->get());
		$other_payments = DB::table('xin_salary_payslip_other_payments')->where('payslip_id', $payment[0]->payslip_id)->get();
    
        // statutory_deductions
		$count_statutory_deductions = count(DB::table('xin_salary_payslip_statutory_deductions')->where('payslip_id', $payment[0]->payslip_id)->get());
		$statutory_deductions = DB::table('xin_salary_payslip_statutory_deductions')->where('payslip_id', $payment[0]->payslip_id)->get();
    
        // overtime
		$count_overtime = count(DB::table('xin_salary_payslip_overtime')->where('payslip_id', $payment[0]->payslip_id)->get());
		$overtime = DB::table('xin_salary_payslip_overtime')->where('payslip_id', $payment[0]->payslip_id)->get();

        // loan
		$count_loan = count(DB::table('xin_salary_payslip_loan')->where('payslip_id', $payment[0]->payslip_id)->get());
		$loan = DB::table('xin_salary_payslip_loan')->where('payslip_id', $payment[0]->payslip_id)->get();

        $statutory_deduction_amount = 0;
		$loan_de_amount = 0;
		$allowances_amount = 0;
		$commissions_amount = 0;
		$other_payments_amount = 0;
		$overtime_amount = 0;

        // laon
		if ($count_loan > 0)
        {
			foreach ($loan->result() as $r_loan) 
            {
				$loan_de_amount += $r_loan->loan_amount;
			}

			$loan_de_amount = $loan_de_amount;
        }
		else 
		{
            $loan_de_amount = 0;
        }

        // allowances
		$loan_de_amount = number_format(floatval($loan_de_amount), 2);
		$allowances_amount = 0;
		foreach ($allowances as $sl_allowances) 
        {
			$allowances_amount += $sl_allowances->allowance_amount;
		}

        // commission
		$commissions_amount = 0;
		foreach ($commissions as $sl_commissions) 
        {
			$commissions_amount += $sl_commissions->commission_amount;
		}

        // statutory deduction
		$statutory_deduction_amount = 0;
		foreach ($statutory_deductions as $sl_statutory_deductions) 
        {
			if ($system[0]->statutory_fixed != 'yes')
            {
				$sta_salary = $basic_salary;
				$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
				//$statutory_deductions_amount += $st_amount;
				if ($system[0]->is_half_monthly == 1) {
					if ($system[0]->half_deduct_month == 2) {
						$single_sd = $st_amount / 2;
					} else {
						$single_sd = $st_amount;
					}
				} else {
					$single_sd = $st_amount;
				}
				$statutory_deduction_amount += $single_sd;
            }
            else
            {
				if ($system[0]->is_half_monthly == 1) {
					if ($system[0]->half_deduct_month == 2) {
						$single_sd = $sl_statutory_deductions->deduction_amount / 2;
					} else {
						$single_sd = $sl_statutory_deductions->deduction_amount;
					}
				} else {
					$single_sd = $sl_statutory_deductions->deduction_amount;
				}
				$statutory_deduction_amount += $single_sd;
			}
		}

        // other amount
		$other_payments_amount = 0;
		foreach ($other_payments as $sl_other_payments) 
        {
			$other_payments_amount += $sl_other_payments->payments_amount;
		}

		// overtime
		$overtime_amount = 0;
		foreach ($overtime as $r_overtime) 
        {
			$overtime_total = (float)$r_overtime->overtime_hours * (float)$r_overtime->overtime_rate;
			$overtime_amount += $overtime_total;
		}

        //cpf
        $cpf_result = DB::table('xin_cpf_payslip')
                        ->select('id', 'ow_paid', 'ow_cpf', 'ow_cpf_employer', 'ow_cpf_employee', 'aw_paid', 'aw_cpf', 'aw_cpf_employer', 'aw_cpf_employee')
                        ->where('payslip_id', $payment[0]->payslip_id)
                        ->get();

		if ($payment[0]->cpf_employee_amount || $payment[0]->cpf_employer_amount) 
        {
			$cpf_employer = $payment[0]->cpf_employer_amount;
			$cpf_employee = $payment[0]->cpf_employee_amount;
		}
        else 
        {
			$cpf_employer = 0;
			$cpf_employee = 0;
		}

        //share options
		$share_option_amount = 0;
		$share_option = DB::table('xin_salary_payslip_share_options')
                            ->select('id', 'payslip_id', 'amount')
                            ->where('payslip_id', $payment[0]->payslip_id)
                            ->first();

		if ($share_option) 
        {
			$share_option_amount = round($share_option->amount, 2);
		}

        //Leave Deductions
		$total_leave_deduction = 0;
		$leave_deductions = DB::table('xin_salary_payslip_leave_deductions')
                                ->select('id', 'payslip_id', 'leave_date', 'leave_amount', 'is_half', 'total_leave_amount')
                                ->where('payslip_id', $payment[0]->payslip_id)
                                ->get();

		if (!$leave_deductions->isEmpty()) 
        {
			$total_leave_deduction = round($leave_deductions[0]->total_leave_amount, 2);
		}

		$system = DB::table('xin_system_setting')->where('setting_id', 1)->get();
		$work_day = 1;

        $basic_s = $basic_salary;
		if (count($allowances) > 0) 
        {
			// $alt = array_sum(array_column($allowances, 'allowance_amount'));
            $alt = $allowances->sum('allowance_amount');

			// $pay_data['allowance'] = $allowances;
            $pay_data['allowance'] = $allowances;
            $pay_data['allowance_total'] = $alt;
		}

        $restday_pay = 0;
		if ($payment[0]->payslip_type == 'hourly') 
        {
			$all_overtime_P = 0;
			$total_earning = $allowances_amount + $all_overtime_P + $restday_pay + $general_allowance_amount + $motivation_allowance_amount + $basic_s + $commissions_amount + $other_payments_amount;
			$total_deduction = floatval($loan_de_amount) + $statutory_deduction_amount + $payment[0]->deduction_amount;
			$total_net_salary = $total_earning - $total_deduction;
			//cpf
			$total_net_salary = $total_net_salary - $cpf_employee;
			$etotal_earning = $total_net_salary;

			// $etotal_count = $hcount * $bs;
			// $fsalary = $etotal_count + $total_net_salary;
			// $etotal_earning = $total_earning + $etotal_count;
			// $total_deductions = '';


		} 
        else 
        {
			$all_overtime_P = 0;
			$total_earning = ($allowances_amount + $all_overtime_P + $basic_s + $general_allowance_amount + $motivation_allowance_amount + $restday_pay + $commissions_amount + $other_payments_amount + $share_option_amount) - $total_leave_deduction;
			$total_deduction = floatval($loan_de_amount) + $statutory_deduction_amount + $payment[0]->deduction_amount;
			$total_net_salary = $total_earning - $total_deduction;
			//cpf

			if ($cpf_employee) {
				// $total_net_salary = $total_net_salary - $cpf_employee;
			}
			// $etotal_earning = $total_net_salary - $cpf_employer;
			//contribution
			$contribution = 0;
			if ($contribution) {
				$contribution_amount = 0;
				// foreach ($contribution as $c) {
				// 	if ($c->contribution_id != 5) {
				// 		$contribution_amount += $c->contribution_amount;
				// 	}
				// }
				$total_net_salary = $total_net_salary - $contribution_amount;
			}
			$total_net_salary = $total_net_salary - $cpf_employee;
		}

        $encamoun = 0;


		$pay_data['name'] = $fname;
		$pay_data['basic'] = $basic_s;
		$pay_data['restd'] = $restday_pay;
		$pay_data['rest_daya'] = 0;
		$pay_data['overtime'] = $all_overtime_P;
		$pay_data['total_pay'] = $total_net_salary + $encamoun;
		$pay_data['overtime_t'] = 0;
		$pay_data['claims'] = $encamoun;
		$pay_data['other_payments_amount'] = $other_payments_amount;
		$pay_data['total_deduction'] = $total_deduction;
		$pay_data['company_name'] = $company_name;
		$pay_data['company_logo'] = $company_logo;
		$pay_data['company_detals'] = $company;
		$pay_data['date_of_payment'] = $payment[0]->salary_month;
		$pay_data['balance_leave'] = $getBalanceLeave->balance_leaves ?? 0;
		$pay_data['allowance_array'] = $allowances;
		$pay_data['cpf_employer'] = $cpf_employer;
		$pay_data['cpf_employee'] = $cpf_employee;
		$pay_data['employee_deduction'] = 0;
		$pay_data['employee_id'] = $user[0]->employee_id;
		$pay_data['statutory_deduction_amount'] = $statutory_deduction_amount;

		$pay_data['general_amount'] = $general_allowance_amount;
		$pay_data['motivation_amount'] = $motivation_allowance_amount;
		$pay_data['loan'] = $loan_de_amount;

        // return $pay_data;

        $pdf = Pdf::loadView('pdf.payslip-pdf', $pay_data);

        // Set paper size (Optional)
        $customPaper = array(0, 0, 1000, 1500); // You can adjust this as needed
        $pdf->setPaper($customPaper);

        // Return PDF file for download or display
        // return $pdf->stream("payslip.pdf", ["Attachment" => false]);

		// return $pdf->download('Payslip.pdf');    

        // Save the PDF to a file
        $pdf->save(public_path('download/payslip/Payslip-'.$payslip_id.'.pdf'));

        $pdf_url = asset('application/public/download/payslip/Payslip-'.$payslip_id.'.pdf');
        
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'pdf_url' => $pdf_url
        ]);
    }

    public static function set_date_format($date)
    {
		// get details
		$system_setting = DB::table('xin_system_setting')->where('setting_id', 1)->get();

		// date formate
		if($system_setting[0]->date_format_xi=='d-m-Y' && !empty($date)){
			$d_format = date("d-m-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='m-d-Y' && !empty($date)){
			$d_format = date("m-d-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='d-M-Y' && !empty($date)){
			$d_format = date("d-M-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='M-d-Y' && !empty($date)){
			$d_format = date("M-d-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='F-j-Y' && !empty($date)){
			$d_format = date("F-j-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='j-F-Y' && !empty($date)){
			$d_format = date("j-F-Y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='m.d.y' && !empty($date)){
			$d_format = date("m.d.y", strtotime($date));
		} else if($system_setting[0]->date_format_xi=='d.m.y' && !empty($date)){
			$d_format = date("d.m.y", strtotime($date));
		} else {
			$d_format = !empty($date) ? $system_setting[0]->date_format_xi : $date;
		}
		
		return $d_format;
	}


    // payslip details

    // public function payslip_details(Request $request)
    // {
    //     $user_id = Auth::user()->id;

    //     $payslip_id = $request->payslip_id;

    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'payslip_id' => 'required|exists:xin_salary_payslips,payslip_id|integer|gt:0',
    //         ],
    //         [],
    //         [
    //             'payslip_id' => 'Payslip Id',
    //         ]
    //     );

    //     if($validator->fails())
    //     {
    //         // $error = $validator->errors();
    //         // return response()->json(['status'=> false, 'message' => 'error', 'error'=>$error]);

    //         $error = $validator->errors()->all();

    //         foreach($error as $item)
    //         {
    //             return response()->json(['status' => false, 'message' => $item]);
    //         }
    //     }
    //     else
    //     {
    //         $result = DB::table('xin_salary_payslips')->where('payslip_id', $payslip_id)->first();

    //         if($result)
    //         {
    //             // total working hours start

    //             $xin_attendance_time = DB::table('xin_attendance_time')
    //                                         ->where('employee_id', $user_id)
    //                                         ->where('attendance_date', 'like', '%' . $result->salary_month . '%')
    //                                         ->get();

    //             $hrs_old_int1 = 0;

    //             foreach ($xin_attendance_time as $item)
    //             {
    //                 // total work          
    //                 $clock_in =  new DateTime($item->clock_in);
    //                 $clock_out =  new DateTime($item->clock_out);
    //                 $interval_late = $clock_in->diff($clock_out);
    //                 $hours_r  = $interval_late->format('%h');
    //                 $minutes_r = $interval_late->format('%i');          
    //                 $total_time = $hours_r .":".$minutes_r.":".'00';

    //                 $str_time = $total_time;

    //                 $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

    //                 sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

    //                 $hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

    //                 $hrs_old_int1 += $hrs_old_seconds;

    //                 $total_working_hours = gmdate("H", $hrs_old_int1);          
    //             }

    //             // total working hours end

    //             // total earning and total deuction start

    //             $total_earning = (double) $result->basic_salary + (double) $result->total_allowances + (double) $result->total_commissions + (double) $result->total_other_payments + (double) $result->total_overtime + (double) $result->other_payment + (double) $result->total_other_payments ;
    //             $total_deduction = (double) $result->total_statutory_deductions + (double) $result->statutory_deductions + (double) $result->total_loan + (double) $result->cpf_employee_amount;

    //             $result->total_earning = $total_earning;
    //             $result->total_deduction = $total_deduction;
    //             $result->total_working_hours = $total_working_hours;

    //             // total earning and total deuction end

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Payslip Details',
    //                 'data' => $result
    //             ]);    
    //         }
    //         else 
    //         {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => "No Payslip Found",
    //             ]);
    //         }
    //     }
    // }
}
