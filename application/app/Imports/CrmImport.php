<?php

namespace App\Imports;

use App\Models\AdditionalContact;
use App\Models\AdditionalInfo;
use App\Models\BillingAddress;
use App\Models\CompanyInfo;
use App\Models\ConstantSetting;
use App\Models\Crm;
use App\Models\LanguageSpoken;
use App\Models\Payment;
use App\Models\PaymentTerms;
use App\Models\ServiceAddress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CrmImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // dd($rows);

        $user_id = Auth::user()->id;

        foreach ($rows as $key => $row) 
        {
            if (!empty($row['customer_type']) && !empty($row['saluation']) && !empty($row['customer_name']) && !empty($row['contact_number']) && !empty($row['language_spoken']) && !empty($row['pi_name']) && !empty($row['pi_contact_no']) && !empty($row['postal_code']) && !empty($row['address']) && !empty($row['unit_no']) && !empty($row['zone']) && !empty($row['territory']))
            {
                $ConstantSetting = ConstantSetting::where('salutation_name', trim($row['saluation']))->first();               
                $LanguageSpoken = LanguageSpoken::where('language_name', trim($row['language_spoken']))->first();
                $PaymentTerms = PaymentTerms::where('payment_terms', 'cash on delivery')->first();
                
                if(trim($row['customer_type']) == "commercial_customer_type")
                {
                    if (!empty($row['company_name']))
                    {                      
                        $customer = new Crm([
                            'customer_type' => trim($row['customer_type']) ?? '',
                            'customer_name' => trim($row['customer_name']) ?? '',
                            'saluation' => $ConstantSetting->id ?? '',
                            'individual_company_name' => trim($row['company_name']) ?? '',
                            'mobile_number' => trim($row['contact_number']),
                            'created_by' => $user_id,
                            'email' => trim($row['email']),
                            'status' => 1,
                            'language_spoken' => $LanguageSpoken->id ?? '',
                            'payment_terms' => $PaymentTerms->id ?? '',
                            'pending_invoice_limit'=>4,
                        ]);

                        $customer->save();

                        CompanyInfo::insert([
                            'customer_id' => $customer->id,
                            'mobile_no' => trim($row['contact_number']),
                            'email' => trim($row['email']),
                            'contact_name' => trim($row['customer_name']) ?? '',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        AdditionalInfo::insert([
                            'customer_id' => $customer->id,
                            'payment_terms' => $PaymentTerms->id ?? '',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        AdditionalContact::insert([
                            'customer_id' => $customer->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        BillingAddress::insert([
                            'customer_id' => $customer->id,
                            'postal_code' => trim($row['postal_code']) ?? '',
                            'person_incharge_name' => trim($row['pi_name']) ?? '',
                            'contact_no' => trim($row['pi_contact_no']) ?? '',
                            'address' => trim($row['address']) ?? '',
                            'unit_number' => trim($row['unit_no']) ?? '',
                            'zone' => trim($row['zone']) ?? '',
                            'email' => trim($row['pi_email']) ?? '',
                        ]);
    
                        ServiceAddress::insert([
                            'customer_id' => $customer->id,
                            'postal_code' => trim($row['postal_code']) ?? '',
                            'address' => trim($row['address']) ?? '',
                            'unit_number' => trim($row['unit_no']) ?? '',
                            'contact_no' => trim($row['pi_contact_no']) ?? '',
                            'email_id' => trim($row['pi_email']) ?? '',
                            'person_incharge_name' => trim($row['pi_name']) ?? '',
                            'territory' => trim($row['territory']) ?? '',
                            'zone' => trim($row['zone']) ?? '',
                            'default_address' => 1,
                        ]);
                    }
                }
                else if(trim($row['customer_type']) == "residential_customer_type")
                {
                    $customer = new Crm([
                        'customer_type' => trim($row['customer_type']) ?? '',
                        'customer_name' => trim($row['customer_name']) ?? '',
                        'saluation' => $ConstantSetting->id ?? '',
                        'mobile_number' => trim($row['contact_number']),
                        'created_by' => $user_id,
                        'email' => trim($row['email']),
                        'status' => 1,
                        'language_spoken' => $LanguageSpoken->id ?? '',
                        'payment_terms' => $PaymentTerms->id ?? '',
                    ]);

                    $customer->save();

                    AdditionalInfo::insert([
                        'customer_id' => $customer->id,
                        'payment_terms' => $PaymentTerms->id ?? '',
                    ]);

                    AdditionalContact::insert([
                        'customer_id' => $customer->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    BillingAddress::insert([
                        'customer_id' => $customer->id,
                        'postal_code' => trim($row['postal_code']) ?? '',
                        'person_incharge_name' => trim($row['pi_name']) ?? '',
                        'contact_no' => trim($row['pi_contact_no']) ?? '',
                        'address' => trim($row['address']) ?? '',
                        'unit_number' => trim($row['unit_no']) ?? '',
                        'zone' => trim($row['zone']) ?? '',
                        'email' => trim($row['pi_email']) ?? '',
                    ]);

                    ServiceAddress::insert([
                        'customer_id' => $customer->id,
                        'postal_code' => trim($row['postal_code']) ?? '',
                        'address' => trim($row['address']) ?? '',
                        'unit_number' => trim($row['unit_no']) ?? '',
                        'contact_no' => trim($row['pi_contact_no']) ?? '',
                        'email_id' => trim($row['pi_email']) ?? '',
                        'person_incharge_name' => trim($row['pi_name']) ?? '',
                        'territory' => trim($row['territory']) ?? '',
                        'zone' => trim($row['zone']) ?? '',
                        'default_address' => 1,
                    ]);
                }
            }
        }
    }
}
