<?php

namespace App\Http\Controllers;

use App\Imports\CrmImport;
use App\Models\AdditionalContact;
use App\Models\AdditionalInfo;
use App\Models\BillingAddress;
use App\Models\Company;
use App\Models\CompanyInfo;
use App\Models\Crm;
use App\Models\EmailTemplate;
use App\Models\JobDetail;
use App\Models\LanguageSpoken;
use App\Models\LeadPaymentInfo;
use App\Models\PaymentTerms;
use App\Models\Quotation;
use App\Models\SalesOrder;
use App\Models\ScheduleModel;
use App\Models\ServiceAddress;
use App\Models\ServiceType;
use App\Models\SourceSetting;
use App\Models\Tax;
use App\Models\ZoneSetting;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Datatables;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use phpseclib3\Crypt\RC2;

class CrmController extends Controller
{

    public function performActivity()
    {
        Auth::user()->update(['last_activity' => now()]);
    }

    // view index page

    public function index()
    {
        $heading_name  = 'CRM';
        
        return view('admin.crm.index', compact('heading_name'));
    }

    public function create()
    {
        $heading_name = 'CRM';
        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        $salutation_data = DB::table('constant_settings')->get();
        $source_data = SourceSetting::all();
        $service_type = ServiceType::all();

        return view('admin.crm.create', compact('heading_name', 'territory_list', 'spoken_language', 'payment_terms', 'salutation_data', 'source_data', 'service_type'));
    }

    public function add_branch()
    {
        return view('admin.crm.add_branch');
    }

    public function getAddress(Request $request)
    {
        $postalcode = $request->postal_code;
        $url = "https://developers.onemap.sg/commonapi/search?searchVal=$postalcode&returnGeom=Y&getAddrDetails=Y";
        // dd( $url ) ;
        //$url = "https://www.onemap.gov.sg/api/common/elastic/search?searchVal=$postalcode&returnGeom=Y&getAddrDetails=Y&pageNum=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }

    public function getZoneByPostalCode($postalCode)
    {
        $zone = ZoneSetting::whereRaw('FIND_IN_SET(?, REPLACE(postal_code, " ", ""))', [$postalCode])
            ->first();
        // dd($zone);

        return response()->json(['zone_name' => $zone ? $zone->zone_name : '']);
    }


    // public function store_residential_detail( Request $request ) {

    //     $validator = Validator::make( $request->all(), [
    //         'customer_name' => 'required|string|max:255',
    //         'contact_no' => 'required',

    //     ],
    //     [
    //         'contact_no.required' => 'The mobile number field is required.',
    //         // 'contact_no.regex' => 'Please enter a valid 10-digit mobile number.',
    //         // 'email.required' => 'The Email field is required.',
    //         // 'email.email' => 'Please enter a valid email address.',
    //         'customer_name.required' => 'Please Enter customer name'
    //     ] );

    //     if ( $validator->fails() ) {
    //         return response()->json( [ 'errors' => $validator->errors() ] );
    //     }
    //     $cleaningTypes = $request->input( 'cleaning_type' );
    //     $customer = new Crm( [
    //         'customer_type' => $request->input( 'customer_type' ),
    //         'customer_name' => $request->input( 'customer_name' ),
    //         'saluation' => $request->input( 'saluation' ),
    //         'mobile_number' => $request->input( 'contact_no' ),
    //         'created_by' => $request->input( 'created_by' ),
    //         'email' => $request->input( 'email' ),
    //         'payment_terms' => $request->input( 'payment_terms' ),
    //         'status' => $request->input( 'status' ),
    //         // 'territory' => $request->input( 'territory' ),
    //         'language_spoken' => $request->input( 'spoken_language' ),
    //         'customer_remark' => $request->input( 'customer_remark' ),
    //         'language_spoken' => $request->input( 'language_spoken' ),
    //         'default_address' => $request->input( 'default_address' ),

    //         'cleaning_type'  => implode( ', ', $cleaningTypes ),
    //     ] );

    //     $customer->save();

    //     // Save the Additinal Info data
    //     $additionalInfo = new AdditionalInfo( [
    //         'customer_id' => $customer->id,
    //         'credit_limit' => $request->input( 'credit_limit' ),
    //         'remark' => $request->input( 'remark' ),
    //         'payment_terms' => $request->input( 'info_payment_terms' ),
    //         'status' => $request->input( 'info_status' ),
    //     ] );

    //     $additionalInfo->save();

    //     // Save Additional Info data
    //     $contact_name =  $request->input( 'contact_name' );
    //     $mobile_no = $request->input( 'additional_mobile_number' );
    //     if ( !empty( $contact_name ) && !empty( $mobile_no ) ) {
    //         $additional_contact = [];
    //         foreach ( $contact_name as $key => $name ) {
    //             $additional_contact[] = [
    //                 'customer_id' => $customer->id,
    //                 'contact_name' => $name,
    //                 'mobile_no' => $mobile_no[ $key ],
    //             ];
    //         }
    //         AdditionalContact::insert( $additional_contact );
    //     }
    //     // Save the billing addresses
    //     $postalCodes = $request->input( 'postal_code_bill' );
    //     $personIncharge = $request->input( 'person_incharge_name_bill' );
    //    $contactNumbers = $request->input( 'phone_no_bill' );
    //     $addresses = $request->input( 'address_bill' );
    //     $unitNumbers = $request->input( 'unit_number_bill' );

    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) ) {
    //         $billingAddresses = [];

    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             $billingAddresses[] = [
    //                 'customer_id' => $customer->id,
    //                 'postal_code' => $postalCode,
    //                 'person_incharge_name' => $personIncharge[$key],
    //                 'contact_no' => $contactNumbers[$key],
    //                 'address' => $addresses[ $key ],
    //                 'unit_number' => $unitNumbers[ $key ],
    //             ];
    //         }
    //         // dd($billingAddresses);
    //         BillingAddress::insert( $billingAddresses );
    //     }
    //     // Save the Service address
    //     $postalCodes = $request->input( 'postal_code_service' );
    //     $addresses = $request->input( 'address_service' );
    //     $unitNumbers = $request->input( 'unit_number_service' );
    //     $contactNumbers = $request->input( 'phone_no' );
    //     $emailIds  = $request->input( 'email_id' );
    //     $territory =  $request->input( 'territory' );
    //     $zone =  $request->input( 'zone_service' );

    //     // dd( $email_id );
    //     $personInchargeNames  = $request->input( 'person_incharge_name' );
    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
    //         $serviceAddresses = [];
    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             if(!empty($addresses[ $key ]) && !empty($unitNumbers[ $key ]) && !empty($contactNumbers[ $key ]) && !empty($emailIds[ $key ]) && !empty($personInchargeNames[ $key ]) && !empty($territory[ $key ]) && !empty($zone[ $key]) ){
    //                 $serviceAddresses[] = [
    //                     'customer_id' => $customer->id,
    //                     'postal_code' => $postalCode,
    //                     'address' => isset($addresses[ $key ])? $addresses[ $key ] : '',
    //                     'unit_number' => isset($unitNumbers[ $key ])? $unitNumbers[ $key ] : '',
    //                     'contact_no' => isset($contactNumbers[ $key ])? $contactNumbers[ $key ] : '',
    //                     'email_id' => isset($emailIds[ $key ])? $emailIds[ $key ] : '',
    //                     'person_incharge_name' => isset($personInchargeNames[ $key ])? $personInchargeNames[ $key ] : '',
    //                     'territory' => isset($territory[ $key ])? $territory[ $key ] : '',
    //                     'zone' => isset($zone[ $key ])? $zone[ $key ] : '',
    //                 ];
    //             }
    //         }
    //         // dd($billingAddresses);
    //         ServiceAddress::insert( $serviceAddresses );
    //     }
    //     return response()->json( [ 'success' => 'Customer registered successfully!' ] );

    // }

    public function store_residential_detail(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_name' => 'required|string|max:255',
                'contact_no' => 'required|digits:8|unique:customers,mobile_number',
                'email' => 'nullable|email|unique:customers',
                'created_by' => 'required',
                'cleaning_type.*' => 'required',
                'status' => 'required',
                'person_incharge_name.*' => 'required',
                'phone_no.*' => 'required|digits:8',
                'postal_code_service.*' => 'required',
                'zone_service.*' => 'required',
                'address_service.*' => 'required',
                'unit_number_service.*' => 'nullable',
                'territory.*' => 'required',
                'person_incharge_name_bill.*' => 'required',
                'phone_no_bill.*' => 'required|digits:8',
                'postal_code_bill.*' => 'required',
                'address_bill.*' => 'required',
                'unit_number_bill.*' => 'nullable',
                'pending_invoice_limit' => 'required',
            ],
            [],
            [
                'customer_name' => 'Customer Name',
                'contact_no' => 'Contact No',
                'email' => 'Email',
                'created_by' => 'Created By',
                'cleaning_type.*' => 'Type of Services',
                'status' => 'Status',
                'person_incharge_name.*' => 'Service address Person Incharge Name',
                'phone_no.*' => 'Service address Phone No',
                'postal_code_service.*' => 'Service address Postal Code',
                'zone_service.*' => 'Service address Zone',
                'address_service.*' => 'Service Address',
                'unit_number_service.*' => 'Service address Unit Number',
                'territory.*' => 'Service address Territory',
                'person_incharge_name_bill.*' => 'Billing address Person Incharge Name',
                'phone_no_bill.*' => 'Billing address Phone No',
                'postal_code_bill.*' => 'Billing address Postal Code',
                'address_bill.*' => 'Billing Address',
                'unit_number_bill.*' => 'Billing address Unit Number',
                'pending_invoice_limit' => 'Pending Invoice Limit',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (!$request->filled('cleaning_type')) {
            return response()->json(['status' => 'failed', 'message' => 'Type of Services is required']);
        }

        $cleaningTypes = $request->input('cleaning_type');

        $cleaningTypes_arr = ServiceType::whereIn('id', $cleaningTypes)->pluck('service_type')->toarray();

        $customer = new Crm([
            'customer_type' => $request->input('customer_type'),
            'customer_name' => $request->input('customer_name'),
            'saluation' => $request->input('saluation'),
            'mobile_number' => $request->input('contact_no'),
            'created_by' => $request->input('created_by'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'customer_remark' => $request->input('customer_remark'),
            'lead_source' => $request->input('lead_source'),
            'language_spoken' => $request->input('language_spoken'),
            // 'default_address' => $request->input( 'default_address' ),
            'cleaning_type'  => implode(',', $cleaningTypes_arr),
            'payment_terms' => $request->input('info_payment_terms'),
            'credit_limit' => $request->input('credit_limit'),
            'pending_invoice_limit' => $request->pending_invoice_limit,
            'renewal' => $request->filled('renewal') ? 1 : 0,
        ]);

        $customer->save();

        // store service type

        $ServiceType_insert_arr = [];
        
        foreach($cleaningTypes as $item)
        {
            $ServiceType = ServiceType::find($item);

            $ServiceType_insert_arr[] = [
                'customer_id' => $customer->id,
                'service_type_id' => $item
            ];
        }

        DB::table('customer_service_types')->insert($ServiceType_insert_arr);

        // Save the Additinal Info data
        $additionalInfo = new AdditionalInfo([
            'customer_id' => $customer->id,
            'credit_limit' => $request->input('credit_limit'),
            'payment_terms' => $request->input('info_payment_terms'),
        ]);

        $additionalInfo->save();

        // Save Additional contact data
        $res_additional_contact_name =  $request->input('res_additional_contact_name');
        $res_additional_mobile_number = $request->input('res_additional_mobile_number');
        $res_additional_email = $request->input('res_additional_email');
      
        $additional_contact = [];
        foreach ($res_additional_contact_name as $key => $name) {
            $additional_contact[] = [
                'customer_id' => $customer->id,
                'contact_name' => $name,
                'mobile_no' => $res_additional_mobile_number[$key],
                'email' => $res_additional_email[$key],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        AdditionalContact::insert($additional_contact);
        
        // Save the billing addresses
        $postalCodes = $request->input('postal_code_bill');
        $personIncharge = $request->input('person_incharge_name_bill');
        $contactNumbers = $request->input('phone_no_bill');
        $addresses = $request->input('address_bill');
        $unitNumbers = $request->input('unit_number_bill');
        $zone = $request->input('zone_bill');
        $email_bill = $request->email_bill;

        if (!empty($postalCodes) && !empty($addresses)) {
            $billingAddresses = [];

            foreach ($postalCodes as $key => $postalCode) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'person_incharge_name' => $personIncharge[$key],
                    'contact_no' => $contactNumbers[$key],
                    'address' => $addresses[$key],
                    'unit_number' => $unitNumbers[$key],
                    'zone' => $zone[$key],
                    'email' => $email_bill[$key],
                ];
            }
            // dd($billingAddresses);
            BillingAddress::insert($billingAddresses);
        }

        // Save the Service address
        $postalCodes = $request->input('postal_code_service');
        $addresses = $request->input('address_service');
        $unitNumbers = $request->input('unit_number_service');
        $contactNumbers = $request->input('phone_no');
        $emailIds  = $request->input('email_id');
        $territory =  $request->input('territory');
        $zone =  $request->input('zone_service');
        $personInchargeNames  = $request->input('person_incharge_name');

        if (!empty($postalCodes) && !empty($addresses) && !empty($contactNumbers) && !empty($personInchargeNames)) {
            $serviceAddresses = [];
            foreach ($postalCodes as $key => $postalCode) {
                if (!empty($addresses[$key]) && !empty($contactNumbers[$key]) && !empty($personInchargeNames[$key]) && !empty($territory[$key]) && !empty($zone[$key])) {

                    if ($key == 0) {
                        $default_addr = 1;
                    } else {
                        $default_addr = 0;
                    }

                    $serviceAddresses[] = [
                        'customer_id' => $customer->id,
                        'postal_code' => $postalCode,
                        'address' => isset($addresses[$key]) ? $addresses[$key] : '',
                        'unit_number' => isset($unitNumbers[$key]) ? $unitNumbers[$key] : '',
                        'contact_no' => isset($contactNumbers[$key]) ? $contactNumbers[$key] : '',
                        'email_id' => isset($emailIds[$key]) ? $emailIds[$key] : '',
                        'person_incharge_name' => isset($personInchargeNames[$key]) ? $personInchargeNames[$key] : '',
                        'territory' => isset($territory[$key]) ? $territory[$key] : '',
                        'zone' => isset($zone[$key]) ? $zone[$key] : '',
                        'default_address' => $default_addr
                    ];
                }
            }
            // dd($billingAddresses);
            ServiceAddress::insert($serviceAddresses);
        }

        // log data store start

        LogController::store('crm', 'Residential Customer Created', $customer->id);

        // log data store end

        return response()->json(['status' => 'success', 'message' => 'Customer registered successfully!']);
    }

    // public function store_commercial_details( Request $request ) {

    //     $validator = Validator::make( $request->all(), [

    //         'mobile_number' => 'required',
    //         'uen' => 'required',
    //     ],
    //     [
    //         'mobile_number.required' => 'The mobile number field is required.',
    //         // 'contact_no.regex' => 'Please enter a valid 10-digit mobile number.',
    //         // 'email.required' => 'The Email field is required.',
    //         // 'email.email' => 'Please enter a valid email address.',
    //         'uen.required' => 'Please Enter uen '
    //     ] );

    //     if ( $validator->fails() ) {
    //         return response()->json( [ 'errors' => $validator->errors() ] );
    //     }
    //     $cleaningTypes = $request->input( 'cleaning_type' );

    //     $customer = new Crm( [
    //         'customer_name' => $request->input( 'customer_name' ),
    //         'saluation' => $request->input( 'saluation' ),
    //         'customer_type' => $request->input( 'customer_type' ),
    //         'mobile_number' => $request->input( 'mobile_number' ),
    //         'created_by' => $request->input( 'created_by' ),
    //         'email' => $request->input( 'email' ),
    //         'payment_terms' => $request->input( 'payment_terms' ),
    //         'status' => $request->input( 'status' ),
    //         'territory' => $request->input( 'territory' ),
    //         'language_spoken' => $request->input( 'spoken_language' ),
    //         'customer_remark' => $request->input( 'customer_remark' ),
    //         // 'language_spoken' => $request->input( 'language_spoken' ),
    //         'uen' => $request->input( 'uen' ),
    //         'cleaning_type'  => implode( ', ', $cleaningTypes ),
    //         'default_address' => $request->input( 'default_address' ),
    //         'group_company_name' => $request->input( 'group_company_name' ),
    //         'individual_company_name' => $request->input( 'individual_company_name' ),
    //         'branch_name' => $request->input( 'branch_name' ),


    //     ] );

    //     $customer->save();
    //     $companyInfo = new CompanyInfo( [
    //         'customer_id' => $customer->id,
    //         'mobile_no' => $request->input( 'company_mobile_no' ),
    //         'fax_no' => $request->input( 'fax_number' ),
    //         'email' => $request->input( 'company_email' ),
    //         'contact_name' => $request->input( 'contact_name' ),
    //     ] );
    //     $companyInfo->save();
    //     $additionalInfo = new AdditionalInfo( [
    //         'customer_id' => $customer->id,
    //         'credit_limit' => $request->input( 'credit_limit' ),
    //         'remark' => $request->input( 'remark' ),
    //         'payment_terms' => $request->input( 'info_payment_terms' ),
    //         'status' => $request->input( 'info_status' ),
    //     ] );

    //     $additionalInfo->save();
    //     // Save the billing addresses
    //     $postalCodes = $request->input( 'c_postal_code_bil' );
    //     $personIncharge = $request->input( 'c_person_incharge_name_bil' );
    //     $contactNumbers = $request->input( 'c_contact_no_bil' );
    //     $addresses = $request->input( 'c_address_bil' );
    //     $unitNumbers = $request->input( 'c_unit_no_bil' );

    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers && !empty($personIncharge) && !empty($contactNumbers)) ) {
    //         $billingAddresses = [];
    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             $billingAddresses[] = [
    //                 'customer_id' => $customer->id,
    //                 'postal_code' => $postalCode,
    //                 'person_incharge_name' => $personIncharge[$key],
    //                 'contact_no' => $contactNumbers[ $key ],
    //                 'address' => $addresses[ $key ],
    //                 'unit_number' => $unitNumbers[ $key ],
    //             ];
    //         }

    //         BillingAddress::insert( $billingAddresses );
    //     }
    //     $postalCodes = $request->input( 'c_postal_code' );
    //     $addresses = $request->input( 'c_address' );
    //     $unitNumbers = $request->input( 'c_unit_no' );
    //     $contactNumbers = $request->input( 'c_contact_no' );
    //     $emailIds  = $request->input( 'c_email_id' );
    //     $territory =  $request->input( 'c_territory' );
    //     $zone =  $request->input( 'c_zone' );

    //     // dd( $email_id );
    //     $personInchargeNames  = $request->input( 'c_person_incharge_name' );
    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
    //         $serviceAddresses = [];
    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             if(!empty($addresses[ $key ]) && !empty($unitNumbers[ $key ]) && !empty($contactNumbers[ $key ]) && !empty($emailIds[ $key ]) && !empty($personInchargeNames[ $key ]) && !empty($territory[ $key ]) ){
    //                 $serviceAddresses[] = [
    //                     'customer_id' => $customer->id,
    //                     'postal_code' => $postalCode,
    //                     'address' => $addresses[ $key ],
    //                     'unit_number' => $unitNumbers[ $key ],
    //                     'contact_no' => $contactNumbers[ $key ],
    //                     'email_id' => $emailIds[ $key ],
    //                     'person_incharge_name' => $personInchargeNames[ $key ],
    //                     'territory' => $territory[ $key ],
    //                     'zone' => $zone[ $key ],
    //                 ];
    //             }
    //         }
    //         ServiceAddress::insert( $serviceAddresses );
    //     }
    //     $contact_name =  $request->input( 'c_contact_name' );
    //     $mobile_no = $request->input( 'c_mobile_no' );
    //     if ( !empty( $contact_name ) && !empty( $mobile_no ) ) {
    //         $additional_contact = [];
    //         foreach ( $contact_name as $key => $name ) {
    //             $additional_contact[] = [
    //                 'customer_id' => $customer->id,
    //                 'contact_name' => $name,
    //                 'mobile_no' => $mobile_no[ $key ],

    //             ];
    //         }
    //         AdditionalContact::insert( $additional_contact );
    //     }
    //     return response()->json( [ 'success' => 'Customer registered successfully!' ] );
    // }

    public function store_commercial_details(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_name' => 'required|string|max:255',
                'uen' => 'nullable',
                'individual_company_name' => 'required',
                'mobile_number' => 'required|digits:8|unique:customers',
                'email' => 'nullable|email|unique:customers',
                'created_by' => 'required',
                'cleaning_type.*' => 'required',
                'status' => 'required',
                'c_person_incharge_name.*' => 'required',
                'c_contact_no.*' => 'required|digits:8',
                'c_postal_code.*' => 'required',
                'c_zone.*' => 'required',
                'c_address.*' => 'required',
                'c_unit_no.*' => 'nullable',
                'c_territory.*' => 'required',
                'c_person_incharge_name_bil.*' => 'required',
                'c_contact_no_bil.*' => 'required|digits:8',
                'c_postal_code_bil.*' => 'required',
                'c_address_bil.*' => 'required',
                'c_unit_no_bil.*' => 'nullable',
                'pending_invoice_limit' => 'required'
            ],
            [],
            [
                'customer_name' => 'Customer Name',
                'uen' => 'UEN',
                'individual_company_name' => 'Individual Company Name',
                'mobile_number' => 'Mobile No',
                'email' => 'Email',
                'created_by' => 'Created By',
                'cleaning_type.*' => 'Type of Services',
                'status' => 'Status',
                'c_person_incharge_name.*' => 'Service Address Person Incharge Name',
                'c_contact_no.*' => 'Service Address Phone No',
                'c_postal_code.*' => 'Service Address Postal Code',
                'c_zone.*' => 'Service Address Zone',
                'c_address.*' => 'Service Address',
                'c_unit_no.*' => 'Service Address Unit Number',
                'c_territory.*' => 'Service Address Territory',
                'c_person_incharge_name_bil.*' => 'Billing address Person Incharge Name',
                'c_contact_no_bil.*' => 'Billing address Phone No',
                'c_postal_code_bil.*' => 'Billing address Postal Code',
                'c_address_bil.*' => 'Billing Address',
                'c_unit_no_bil.*' => 'Billing address Unit Number',
                'pending_invoice_limit' => 'Pending Invoice Limit'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (!$request->filled('cleaning_type')) {
            return response()->json(['status' => 'failed', 'message' => 'Type of Services is required']);
        }

        $cleaningTypes = $request->input('cleaning_type');
        $cleaningTypes_arr = ServiceType::whereIn('id', $cleaningTypes)->pluck('service_type')->toarray();

        $customer = new Crm([
            'customer_name' => $request->input('customer_name'),
            'saluation' => $request->input('saluation'),
            'customer_type' => $request->input('customer_type'),
            'mobile_number' => $request->input('mobile_number'),
            'created_by' => $request->input('created_by'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'language_spoken' => $request->input('spoken_language'),
            'customer_remark' => $request->input('customer_remark'),
            'lead_source' => $request->input('lead_source'),
            'uen' => $request->input('uen'),
            'cleaning_type'  => implode(',', $cleaningTypes_arr),
            // 'default_address' => $request->input( 'default_address' ),
            // 'group_company_name' => $request->input('group_company_name'),
            'individual_company_name' => $request->input('individual_company_name'),
            'branch_name' => $request->input('branch_name'),
            'payment_terms' => $request->input('info_payment_terms'),
            'credit_limit' => $request->input('credit_limit'),
            'pending_invoice_limit' => $request->pending_invoice_limit,
            'renewal' => $request->filled('renewal') ? 1 : 0,
        ]);

        $customer->save();

        // store service type

        $ServiceType_insert_arr = [];
        
        foreach($cleaningTypes as $item)
        {
            $ServiceType = ServiceType::find($item);

            $ServiceType_insert_arr[] = [
                'customer_id' => $customer->id,
                'service_type_id' => $item
            ];
        }

        DB::table('customer_service_types')->insert($ServiceType_insert_arr);

        // store company info

        $companyInfo = new CompanyInfo([
            'customer_id' => $customer->id,
            'mobile_no' => $request->input('company_mobile_no'),
            'fax_no' => $request->input('fax_number'),
            'email' => $request->input('company_email'),
            'contact_name' => $request->input('contact_name'),
        ]);
        $companyInfo->save();

        $additionalInfo = new AdditionalInfo([
            'customer_id' => $customer->id,
            'credit_limit' => $request->input('credit_limit'),
            'payment_terms' => $request->input('info_payment_terms'),
        ]);

        $additionalInfo->save();

        // Save the billing addresses
        $postalCodes = $request->input('c_postal_code_bil');
        $personIncharge = $request->input('c_person_incharge_name_bil');
        $contactNumbers = $request->input('c_contact_no_bil');
        $addresses = $request->input('c_address_bil');
        $unitNumbers = $request->input('c_unit_no_bil');
        $zone = $request->input('c_zone_bill');
        $c_email_bil = $request->c_email_bil;

        if (!empty($postalCodes) && !empty($addresses) && !empty($personIncharge) && !empty($contactNumbers)) {
            $billingAddresses = [];
            foreach ($postalCodes as $key => $postalCode) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'person_incharge_name' => $personIncharge[$key],
                    'contact_no' => $contactNumbers[$key],
                    'address' => $addresses[$key],
                    'unit_number' => $unitNumbers[$key],
                    'zone' => $zone[$key],
                    'email' => $c_email_bil[$key],
                ];
            }

            BillingAddress::insert($billingAddresses);
        }

        // service address
        $postalCodes = $request->input('c_postal_code');
        $addresses = $request->input('c_address');
        $unitNumbers = $request->input('c_unit_no');
        $contactNumbers = $request->input('c_contact_no');
        $emailIds  = $request->input('c_email_id');
        $territory =  $request->input('c_territory');
        $zone =  $request->input('c_zone');
        $personInchargeNames  = $request->input('c_person_incharge_name');

        if (!empty($postalCodes) && !empty($addresses) && !empty($contactNumbers) && !empty($personInchargeNames)) {
            $serviceAddresses = [];
            foreach ($postalCodes as $key => $postalCode) {
                if (!empty($addresses[$key]) && !empty($contactNumbers[$key]) && !empty($personInchargeNames[$key]) && !empty($territory[$key])) {

                    if ($key == 0) {
                        $default_addr = 1;
                    } else {
                        $default_addr = 0;
                    }

                    $serviceAddresses[] = [
                        'customer_id' => $customer->id,
                        'postal_code' => $postalCode,
                        'address' => $addresses[$key],
                        'unit_number' => $unitNumbers[$key],
                        'contact_no' => $contactNumbers[$key],
                        'email_id' => $emailIds[$key],
                        'person_incharge_name' => $personInchargeNames[$key],
                        'territory' => $territory[$key],
                        'zone' => $zone[$key],
                        'default_address' => $default_addr
                    ];
                }
            }
            ServiceAddress::insert($serviceAddresses);
        }

        // additional contact
        $com_additional_contact_name =  $request->input('com_additional_contact_name');
        $com_additional_mobile_no = $request->input('com_additional_mobile_no');
        $com_additional_email = $request->input('com_additional_email');

        $additional_contact = [];
        foreach ($com_additional_contact_name as $key => $name) {
            $additional_contact[] = [
                'customer_id' => $customer->id,
                'contact_name' => $name,
                'mobile_no' => $com_additional_mobile_no[$key],
                'email' => $com_additional_email[$key],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
        AdditionalContact::insert($additional_contact);

        // log data store start

        LogController::store('crm', 'Commercial Customer Created', $customer->id);

        // log data store end

        return response()->json(['status' => 'success', 'message' => 'Customer registered successfully!']);
    }

    public function searchResidential(Request $request)
    {
        if (!empty($request->search_value)) {

            $customer = Crm::where('customer_type', 'residential_customer_type')->where('customer_name', 'like', '%' . $request->search_value . '%')->orderByRaw('CASE
            WHEN status = 1 THEN 1
            WHEN status = 2 THEN 2
            WHEN status = 0 THEN 3
            ELSE 4
         END')->get();
        } else {
            $customer = Crm::where('customer_type', 'residential_customer_type')
                ->orderByRaw('CASE
                            WHEN status = 1 THEN 1
                            WHEN status = 2 THEN 2
                            WHEN status = 0 THEN 3
                            ELSE 4
                         END')->get();
        }
        // print_r($customer); exit;
        if ($customer) {
            $tbody = '';

            foreach ($customer as $key => $value) {
                if ($value->status == 0) {
                    $status = '<span class="badge bg-red">Block</span>';
                } elseif ($value->status == 1) {
                    $status = '<span class="badge bg-green">Active</span>';
                } else {
                    $status = '<span class="badge bg-gray">Inactive</span>';
                }

                // outstanding amount start

                $total_amount = Quotation::where('payment_status', '=', 'partial_paid')
                    ->where('customer_id', $value->id)
                    ->WhereNotNull('invoice_no')
                    ->sum('grand_total');

                $lead_payment_details = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
                    ->where('lead_payment_detail.customer_id', $value->id)
                    ->where('quotations.payment_status', '=', 'partial_paid')
                    ->WhereNotNull('quotations.invoice_no')
                    ->select('lead_payment_detail.*')
                    ->get();

                $payment_amount = 0;
                foreach ($lead_payment_details as $list) {
                    $payment_amount += $list->payment_amount;
                }

                if ($total_amount == $payment_amount) {
                    $outstanding_amount = 0;
                } else if ($total_amount > $payment_amount) {
                    $outstanding_amount = $total_amount - $payment_amount;
                } else {
                    $outstanding_amount = 0;
                }

                // outstanding amount end

                $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $value->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
                $action .= '<a href="#" class="btn btn_delete_crm"  data-customer-id="' . $value->id . '">
                                    <i class="fa-solid fa-trash me-2 text-red"></i>
                                </a>';
                $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $value->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';
                $action .= '<a href="' . route('crm.transaction-history', $value->id) . '" class="btn" title="Transaction History"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';

                $tbody .= '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $status . '</td>
                                <td>' . $value->id . '</td>
                                <td>' . $value->customer_name . '</td>
                                <td>' . $value->mobile_number . '</td>
                                <td>' . $value->email . '</td>
                                <td>$' . $outstanding_amount . '</td>
                                <td>' . $action . '</td>
                            </tr>';
            }
            return $tbody;
        } else {
            return 'no result found.';
        }
    }
    public function searchCommercial(Request $request)
    {
        if (!empty($request->search_value)) {
            $customer = Crm::where('customer_type', 'commercial_customer_type')->where('individual_company_name', 'like', '%' . $request->search_value . '%')->orderByRaw('CASE
                        WHEN status = 1 THEN 1
                        WHEN status = 2 THEN 2
                        WHEN status = 0 THEN 3
                        ELSE 4
                    END')->get();
        } else {
            $customer = Crm::where('customer_type', 'commercial_customer_type')
                ->orderByRaw('CASE
                            WHEN status = 1 THEN 1
                            WHEN status = 2 THEN 2
                            WHEN status = 0 THEN 3
                            ELSE 4
                         END')->get();
        }
        // print_r($customer); exit;
        if ($customer) {
            $tbody = '';

            foreach ($customer as $key => $value) {
                if ($value->status == 0) {
                    $status = '<span class="badge bg-red">Block</span>';
                } elseif ($value->status == 1) {
                    $status = '<span class="badge bg-green">Active</span>';
                } else {
                    $status = '<span class="badge bg-gray">Inactive</span>';
                }

                // outstanding amount start

                $total_amount = Quotation::where('payment_status', '=', 'partial_paid')
                    ->where('customer_id', $value->id)
                    ->WhereNotNull('invoice_no')
                    ->sum('grand_total');

                $lead_payment_details = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
                    ->where('lead_payment_detail.customer_id', $value->id)
                    ->where('quotations.payment_status', '=', 'partial_paid')
                    ->WhereNotNull('quotations.invoice_no')
                    ->select('lead_payment_detail.*')
                    ->get();

                $payment_amount = 0;
                foreach ($lead_payment_details as $list) {
                    $payment_amount += $list->payment_amount;
                }

                if ($total_amount == $payment_amount) {
                    $outstanding_amount = 0;
                } else if ($total_amount > $payment_amount) {
                    $outstanding_amount = $total_amount - $payment_amount;
                } else {
                    $outstanding_amount = 0;
                }

                // outstanding amount end

                $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $value->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
                $action .= '<a href="#" class="btn btn_delete_crm"  data-customer-id="' . $value->id . '">
                                <i class="fa-solid fa-trash me-2 text-red"></i>
                            </a>';
                $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $value->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';
                $action .= '<a href="' . route('crm.transaction-history', $value->id) . '" class="btn" title="Transaction History"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';

                $tbody .= '<tr>
                                <td>' . ($key + 1) . '</td>
                                <td>' . $status . '</td>
                                <td>' . $value->id . '</td>
                                <td>' . $value->individual_company_name . '</td>
                                <td>' . $value->mobile_number . '</td>
                                <td>' . $value->email . '</td>
                                <td>$' . $outstanding_amount . '</td>
                                <td>' . $action . '</td>
                            </tr>';
            }
            return $tbody;
        } else {
            return 'no result found.';
        }
    }

    // get residential data

    // public function residential()
    // {
    //     $residentialCustomers = CRM::where('customer_type', 'residential_customer_type')
    //         ->orderByRaw('CASE
    //         WHEN status = 1 THEN 1
    //         WHEN status = 2 THEN 2
    //         WHEN status = 0 THEN 3
    //         ELSE 4
    //     END')->get();

    //     $new_data = [];

    //     foreach ($residentialCustomers as $key => $item) {
    //         $statusBadge = match ($item->status) {
    //             '0' => '<span class="badge bg-red">Block</span>',
    //             '1' => '<span class="badge bg-green">Active</span>',
    //             '2' => '<span class="badge bg-gray">Inactive</span>',
    //             default => ''
    //         };

    //         // $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')">Edit</a>';
    //         $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
    //         $action .= '<a href="#" class="btn btn_delete_crm"  data-customer-id="' . $item->id . '">
    //                         <i class="fa-solid fa-trash me-2 text-red"></i>
    //                     </a>
    //                     ';
    //         $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $item->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';
    //         $action .= '<a href="' . route('crm.transaction-history', $item->id) . '" class="btn" title="Transaction History"><i class="fa fa-history me-2 text-blue"></i></a>';

    //         // outstanding amount start

    //         $total_amount = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
    //             ->where('customer_id', $item->id)
    //             ->WhereNotNull('invoice_no')
    //             ->sum('grand_total');

    //         $lead_payment_details = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
    //             ->where('lead_payment_detail.customer_id', $item->id)
    //             ->whereIn('quotations.payment_status', ['partial_paid', 'unpaid'])
    //             ->WhereNotNull('quotations.invoice_no')
    //             ->select('lead_payment_detail.*')
    //             ->get();

    //         $payment_amount = 0;
    //         foreach ($lead_payment_details as $list) {
    //             if($list->payment_status == 1)
    //             {
    //                 $payment_amount += $list->payment_amount;
    //             }
    //         }

    //         if ($total_amount == $payment_amount) {
    //             $outstanding_amount = 0;
    //         } else if ($total_amount > $payment_amount) {
    //             $outstanding_amount = $total_amount - $payment_amount;
    //         } else {
    //             $outstanding_amount = 0;
    //         }

    //         // outstanding amount end

    //         // postal code start

    //         $service_address = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

    //         // postal code end

    //         $new_data[] = array(
    //             'sno' => $key + 1,
    //             'id' => $item->id,
    //             'mobile_number' => $item->mobile_number,
    //             'email' => $item->email,
    //             'status' => $statusBadge,
    //             'customer_name' => $item->customer_name,
    //             'postal_code' => $service_address->postal_code ?? '',
    //             'outstanding_Amount' => "$" . number_format($outstanding_amount, 2),
    //             'action' => $action
    //         );
    //     }

    //     $output = array(
    //         "draw" => request()->draw,
    //         'recordsTotal' => count($residentialCustomers),
    //         'recordsFiltered' => count($residentialCustomers),
    //         'data' => $new_data,
    //     );

    //     echo json_encode($output);
    // }

    public function residential(Request $request)
    {
        $residentialCustomers = CRM::where('customer_type', 'residential_customer_type')
                                    ->orderByRaw('CASE
                                                    WHEN status = 1 THEN 1
                                                    WHEN status = 2 THEN 2
                                                    WHEN status = 0 THEN 3
                                                    ELSE 4
                                                END');
                                    
        if($request->filled('status'))
        {
            if($request->status == "active")
            {
                $filter_status = 1;
            }
            else
            {
                $filter_status = "";
            }

            $residentialCustomers = $residentialCustomers->where('status', $filter_status);        
        }

        $residentialCustomers = $residentialCustomers->get();                  

        $new_data = [];

        foreach ($residentialCustomers as $key => $item) {
            $statusBadge = match ($item->status) {
                '0' => '<span class="badge bg-red">Block</span>',
                '1' => '<span class="badge bg-green">Active</span>',
                '2' => '<span class="badge bg-gray">Inactive</span>',
                default => ''
            };

            // $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')">Edit</a>';
            $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
            $action .= '<a href="#" class="btn btn_delete_crm"  data-customer-id="' . $item->id . '">
                            <i class="fa-solid fa-trash me-2 text-red"></i>
                        </a>
                        ';
            $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $item->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';
            $action .= '<a href="' . route('crm.transaction-history', $item->id) . '" class="btn" title="Transaction History"><i class="fa fa-history me-2 text-blue"></i></a>';
            $action .= '<a href="' . route('crm.log-report', $item->id) . '" class="btn" title="Log Report"><i class="fa fa-file me-2 text-blue"></i></a>';

            // outstanding amount start

            $total_amount = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
                ->where('customer_id', $item->id)
                ->WhereNotNull('invoice_no')
                ->sum('grand_total');

            $lead_payment_details = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
                ->where('lead_payment_detail.customer_id', $item->id)
                ->whereIn('quotations.payment_status', ['partial_paid', 'unpaid'])
                ->WhereNotNull('quotations.invoice_no')
                ->select('lead_payment_detail.*')
                ->get();

            $payment_amount = 0;
            foreach ($lead_payment_details as $list) {
                if($list->payment_status == 1)
                {
                    $payment_amount += $list->payment_amount;
                }
            }

            if ($total_amount == $payment_amount) {
                $outstanding_amount = 0;
            } else if ($total_amount > $payment_amount) {
                $outstanding_amount = $total_amount - $payment_amount;
            } else {
                $outstanding_amount = 0;
            }

            // outstanding amount end

            // postal code start

            $service_address = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

            // postal code end

            $new_data[] = array(
                $key + 1,
                $statusBadge,
                $item->id,
                $item->customer_name,
                $item->mobile_number,
                $item->email,
                $service_address->postal_code ?? '',
                "$" . number_format($outstanding_amount, 2),
                $action
            );
            
        }

        $output = array(
            "draw" => request()->draw,
            'recordsTotal' => count($residentialCustomers),
            'recordsFiltered' => count($residentialCustomers),
            'data' => $new_data,
        );

        echo json_encode($output);
    }

    // get commercial data

    // public function commercial()
    // {
    //     $residentialCustomers = CRM::where('customer_type', 'commercial_customer_type')
    //         ->orderByRaw('CASE
    //         WHEN status = 1 THEN 1
    //         WHEN status = 2 THEN 2
    //         WHEN status = 0 THEN 3
    //         ELSE 4
    //     END')->get();
    //     $data = [];
    //     $new_data = [];
    //     foreach ($residentialCustomers as $key => $item) {
    //         $statusBadge = match ($item->status) {
    //             '0' => '<span class="badge bg-red">Block</span>',
    //             '1' => '<span class="badge bg-green">Active</span>',
    //             '2' => '<span class="badge bg-gray">Inactive</span>',
    //             default => '',
    //         };
    //         // $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')">Edit</a>';
    //         $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
    //         $action .= '<a href="#" class="btn btn_delete_crm_commercial" data-customer-id="' . $item->id . '">
    //                         <i class="fa-solid fa-trash me-2 text-red"></i>
    //                     </a>
    //                     ';

    //         $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $item->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';
    //         $action .= '<a href="' . route('crm.transaction-history', $item->id) . '" class="btn" title="Transaction History"><i class="fa fa-history me-2 text-blue"></i></a>';

    //         // outstanding amount start

    //         $total_amount = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
    //             ->where('customer_id', $item->id)
    //             ->WhereNotNull('invoice_no')
    //             ->sum('grand_total');

    //         $lead_payment_details = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
    //             ->where('lead_payment_detail.customer_id', $item->id)
    //             ->whereIn('quotations.payment_status', ['partial_paid', 'unpaid'])
    //             ->WhereNotNull('quotations.invoice_no')
    //             ->select('lead_payment_detail.*')
    //             ->get();

    //         $payment_amount = 0;
    //         foreach ($lead_payment_details as $list) {
    //             if($list->payment_status == 1)
    //             {
    //                 $payment_amount += $list->payment_amount;
    //             }
    //         }

    //         if ($total_amount == $payment_amount) {
    //             $outstanding_amount = 0;
    //         } else if ($total_amount > $payment_amount) {
    //             $outstanding_amount = $total_amount - $payment_amount;
    //         } else {
    //             $outstanding_amount = 0;
    //         }

    //         // outstanding amount end

    //         // postal code start

    //         $service_address = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

    //         // postal code end

    //         $new_data[] = array(
    //             'sno' => $key + 1,
    //             'id' => $item->id,
    //             'mobile_number' => $item->mobile_number,
    //             'email' => $item->email,
    //             'status' => $statusBadge,
    //             'individual_company_name' => $item->individual_company_name,
    //             'postal_code' => $service_address->postal_code ?? '',
    //             'outstanding_Amount' => '$' . number_format($outstanding_amount, 2),
    //             'action' => $action

    //         );
    //     }

    //     $output = array(
    //         // 'draw' => intval( $_GET[ 'draw' ] ),
    //         "draw" => request()->draw,
    //         'recordsTotal' => count($data),
    //         'recordsFiltered' => count($data),
    //         'data' => $new_data,
    //     );

    //     // dd( $output );
    //     echo json_encode($output);
    // }

    public function commercial(Request $request)
    {
        $residentialCustomers = CRM::where('customer_type', 'commercial_customer_type')
                                    ->orderByRaw('CASE
                                                    WHEN status = 1 THEN 1
                                                    WHEN status = 2 THEN 2
                                                    WHEN status = 0 THEN 3
                                                    ELSE 4
                                                END');

        if($request->filled('status'))
        {
            if($request->status == "active")
            {
                $filter_status = 1;
            }
            else
            {
                $filter_status = "";
            }

            $residentialCustomers = $residentialCustomers->where('status', $filter_status);        
        }

        $residentialCustomers = $residentialCustomers->get();               

        $new_data = [];
        foreach ($residentialCustomers as $key => $item) {
            $statusBadge = match ($item->status) {
                '0' => '<span class="badge bg-red">Block</span>',
                '1' => '<span class="badge bg-green">Active</span>',
                '2' => '<span class="badge bg-gray">Inactive</span>',
                default => '',
            };
            // $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')">Edit</a>';
            $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
            $action .= '<a href="#" class="btn btn_delete_crm_commercial" data-customer-id="' . $item->id . '">
                            <i class="fa-solid fa-trash me-2 text-red"></i>
                        </a>
                        ';

            $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $item->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';
            $action .= '<a href="' . route('crm.transaction-history', $item->id) . '" class="btn" title="Transaction History"><i class="fa fa-history me-2 text-blue"></i></a>';

            // outstanding amount start

            $total_amount = Quotation::whereIn('payment_status', ['partial_paid', 'unpaid'])
                ->where('customer_id', $item->id)
                ->WhereNotNull('invoice_no')
                ->sum('grand_total');

            $lead_payment_details = LeadPaymentInfo::join('quotations', 'quotations.id', '=', 'lead_payment_detail.quotation_id')
                ->where('lead_payment_detail.customer_id', $item->id)
                ->whereIn('quotations.payment_status', ['partial_paid', 'unpaid'])
                ->WhereNotNull('quotations.invoice_no')
                ->select('lead_payment_detail.*')
                ->get();

            $payment_amount = 0;
            foreach ($lead_payment_details as $list) {
                if($list->payment_status == 1)
                {
                    $payment_amount += $list->payment_amount;
                }
            }

            if ($total_amount == $payment_amount) {
                $outstanding_amount = 0;
            } else if ($total_amount > $payment_amount) {
                $outstanding_amount = $total_amount - $payment_amount;
            } else {
                $outstanding_amount = 0;
            }

            // outstanding amount end

            // postal code start

            $service_address = ServiceAddress::where('customer_id', $item->id)->where('default_address', 1)->first();

            // postal code end

            $new_data[] = array(
                $key + 1,
                $statusBadge,
                $item->id,
                $item->individual_company_name,
                $item->mobile_number,
                $item->email,
                $service_address->postal_code ?? '',
                '$' . number_format($outstanding_amount, 2),
                $action
            );
        }

        $output = array(
            "draw" => request()->draw,
            'recordsTotal' => count($residentialCustomers),
            'recordsFiltered' => count($residentialCustomers),
            'data' => $new_data,
        );

        echo json_encode($output);
    }

    public function crm_active($status)
    {
        // return $status;

        $data['status'] = $status;

        return view('admin.crm.index', $data);
    }

    // edit

    public function edit()
    {
        $data = Crm::find(request()->id);

        $data['cleaningTypes'] = DB::table('customer_service_types')->where('customer_id', request()->id)->pluck('service_type_id')->toarray();

        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        $additional_contact =  AdditionalContact::where('customer_id', request()->id)->get();
        $additional_info = AdditionalInfo::where('customer_id', request()->id)->first();
        $service_address = ServiceAddress::where('customer_id', request()->id)->get();
        $billing_address = BillingAddress::where('customer_id', request()->id)->get();
        $company_info = CompanyInfo::where('customer_id', request()->id)->first();
        $source_data = SourceSetting::all();
        $salutation_data = DB::table('constant_settings')->get();
        $service_type = ServiceType::all();

        return view('admin.crm.edit', compact('source_data', 'data', 'territory_list', 'spoken_language', 'payment_terms', 'additional_contact', 'additional_info', 'service_address', 'billing_address', 'company_info', 'salutation_data', 'service_type'));
    }

    // public function update_residential_detail( Request $request ) {
    //     $validator = Validator::make( $request->all(), [
    //         'customer_name' => 'required|string|max:255',
    //         'contact_no' => 'required',
    //     ], [
    //         'contact_no.required' => 'The mobile number field is required.',
    //         'customer_name.required' => 'Please enter the customer name.',
    //     ] );

    //     if ( $validator->fails() ) {
    //         return response()->json( [ 'errors' => $validator->errors() ] );
    //     }

    //     $cleaningTypes = $request->input( 'cleaning_type' );

    //     $customer = Crm::find( $request->id );
    //     // dd( $customer->customer_type );

    //     $customer->customer_type = $request->input( 'customer_type' );
    //     $customer->customer_name = $request->input( 'customer_name' );
    //     $customer->saluation = $request->input( 'saluation' );
    //     $customer->nick_name = $request->input( 'nick_name' );
    //     $customer->mobile_number = $request->input( 'contact_no' );
    //     $customer->created_by = $request->input( 'created_by' );
    //     $customer->email = $request->input( 'email' );
    //     $customer->payment_terms = $request->input( 'payment_terms' );
    //     $customer->status = $request->input( 'status' );
    //     $customer->territory = $request->input( 'territory' );
    //     $customer->language_spoken = $request->input( 'language_spoken' );
    //     $customer->customer_remark = $request->input( 'customer_remark' );
    //     $customer->language_spoken = $request->input( 'language_spoken' );
    //     $customer->default_address = $request->input( 'default_address' );

    //     $customer->cleaning_type = implode( ',', $cleaningTypes );
    //     $customer->save();

    //     // Update the Additional Info data
    //     $additionalInfo = AdditionalInfo::where( 'customer_id', $customer->id )->first();
    //     $additionalInfo->credit_limit = $request->input( 'credit_limit' );
    //     $additionalInfo->remark = $request->input( 'remark' );
    //     $additionalInfo->payment_terms = $request->input( 'info_payment_terms' );
    //     $additionalInfo->status = $request->input( 'info_status' );
    //     $additionalInfo->save();

    //     // Update Additional Contact data
    //     $contactNames = $request->input( 'contact_name' );
    //     $mobileNumbers = $request->input( 'additional_mobile_number' );

    //     if ( !empty( $contactNames ) && !empty( $mobileNumbers ) ) {
    //         $additionalContacts = [];

    //         foreach ( $contactNames as $key => $name ) {
    //             $additionalContacts[] = [
    //                 'customer_id' => $customer->id,
    //                 'contact_name' => $name,
    //                 'mobile_no' => $mobileNumbers[ $key ],
    //             ];
    //         }

    //         AdditionalContact::where( 'customer_id', $customer->id )->delete();
    //         AdditionalContact::insert( $additionalContacts );
    //     }

    //     // Update the billing addresses
    //     $postalCodes = $request->input( 'postal_code_bill' );
    //     $personIncharge = $request->input( 'person_incharge_name_bill' );
    //    $contactNumbers = $request->input( 'phone_no_bill' );
    //     $addresses = $request->input( 'address_bill' );
    //     $unitNumbers = $request->input( 'unit_number_bill' );

    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) ) {
    //         $billingAddresses = [];

    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             $billingAddresses[] = [
    //                 'customer_id' => $customer->id,
    //                 'postal_code' => $postalCode,
    //                 'person_incharge_name' => $personIncharge[$key],
    //                 'contact_no' => $contactNumbers[$key],
    //                 'address' => $addresses[ $key ],
    //                 'unit_number' => $unitNumbers[ $key ],
    //             ];
    //         }

    //         BillingAddress::where( 'customer_id', $customer->id )->delete();
    //         BillingAddress::insert( $billingAddresses );
    //     }

    //     // Update the service addresses
    //     $postalCodes = $request->input( 'postal_code_service' );
    //     $addresses = $request->input( 'address_service' );
    //     $unitNumbers = $request->input( 'unit_number_service' );
    //     $contactNumbers = $request->input( 'phone_no' );
    //     $emailIds = $request->input( 'email_id' );
    //     $personInchargeNames = $request->input( 'person_incharge_name' );
    //     $territory =  $request->input( 'territory' );
    //     $zone =  $request->input( 'zone_service' );
    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
    //         $serviceAddresses = [];

    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             if(!empty($addresses[ $key ]) && !empty($unitNumbers[ $key ]) && !empty($contactNumbers[ $key ]) && !empty($emailIds[ $key ]) && !empty($personInchargeNames[ $key ]) && !empty($territory[ $key ]) ){
    //                 $serviceAddresses[] = [
    //                     'customer_id' => $customer->id,
    //                     'postal_code' => $postalCode,
    //                     'address' => $addresses[ $key ],
    //                     'unit_number' => $unitNumbers[ $key ],
    //                     'contact_no' => $contactNumbers[ $key ],
    //                     'email_id' => $emailIds[ $key ],
    //                     'person_incharge_name' => $personInchargeNames[ $key ],
    //                     'territory' => $territory[ $key ],
    //                     'territory' => $zone[ $key ],
    //                 ];
    //             }
    //         }
    //         // print_r($serviceAddresses); exit;

    //         ServiceAddress::where( 'customer_id', $customer->id )->delete();
    //         ServiceAddress::insert( $serviceAddresses );
    //     }

    //     return response()->json( [ 'success' => 'Residential Customer updated successfully!' ] );
    // }

    public function update_residential_detail(Request $request)
    {
        $customer_id = $request->id;

        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'customer_name' => 'required|string|max:255',
                'contact_no' => 'required|digits:8|unique:customers,mobile_number,' . $customer_id,
                'email' => 'nullable|unique:customers,email,' . $customer_id,
                'created_by' => 'required',
                'cleaning_type' => 'required',
                'status' => 'required',

                'edit_resd_serv_person_incharge_name.*' => 'required',
                'edit_resd_serv_phone_no.*' => 'required|digits:8',
                'edit_resd_serv_postal_code.*' => 'required',
                'edit_resd_serv_zone.*' => 'required',
                'edit_resd_serv_address.*' => 'required',
                'edit_resd_serv_unit_number.*' => 'nullable',
                'edit_resd_serv_territory.*' => 'required',

                'edit_resd_bill_person_incharge_name.*' => 'required',
                'edit_resd_bill_phone_no.*' => 'required|digits:8',
                'edit_resd_bill_postal_code.*' => 'required',
                'edit_resd_bill_address.*' => 'required',
                'edit_resd_bill_unit_number.*' => 'nullable',

                'person_incharge_name.*' => 'required',
                'phone_no.*' => 'required|digits:8',
                'postal_code_service.*' => 'required',
                'zone_service.*' => 'required',
                'address_service.*' => 'required',
                'unit_number_service.*' => 'nullable',
                'territory.*' => 'required',

                'person_incharge_name_bill.*' => 'required',
                'phone_no_bill.*' => 'required|digits:8',
                'postal_code_bill.*' => 'required',
                'address_bill.*' => 'required',
                'unit_number_bill.*' => 'nullable',
                'pending_invoice_limit' => 'required'
            ],
            [],
            [
                'customer_name' => 'Customer Name',
                'contact_no' => 'Contact No',
                'email' => 'Email',
                'created_by' => 'Created By',
                'cleaning_type' => 'Type of Services',
                'status' => 'Status',

                'edit_resd_serv_person_incharge_name.*' => 'Service address Person Incharge Name',
                'edit_resd_serv_phone_no.*' => 'Service address Phone No',
                'edit_resd_serv_postal_code.*' => 'Service address Postal Code',
                'edit_resd_serv_zone.*' => 'Service address Zone',
                'edit_resd_serv_address.*' => 'Service Address',
                'edit_resd_serv_unit_number.*' => 'Service address Unit Number',
                'edit_resd_serv_territory.*' => 'Service address Territory',

                'edit_resd_bill_person_incharge_name.*' => 'Billing address Person Incharge Name',
                'edit_resd_bill_phone_no.*' => 'Billing address Phone No',
                'edit_resd_bill_postal_code.*' => 'Billing address Postal Code',
                'edit_resd_bill_address.*' => 'Billing Address',
                'edit_resd_bill_unit_number.*' => 'Billing address Unit Number',

                'person_incharge_name.*' => 'Service address Person Incharge Name',
                'phone_no.*' => 'Service address Phone No',
                'postal_code_service.*' => 'Service address Postal Code',
                'zone_service.*' => 'Service address Zone',
                'address_service.*' => 'Service Address',
                'unit_number_service.*' => 'Service address Unit Number',
                'territory.*' => 'Service address Territory',

                'person_incharge_name_bill.*' => 'Billing address Person Incharge Name',
                'phone_no_bill.*' => 'Billing address Phone No',
                'postal_code_bill.*' => 'Billing address Postal Code',
                'address_bill.*' => 'Billing Address',
                'unit_number_bill.*' => 'Billing address Unit Number',
                'pending_invoice_limit' => 'Pending Invoice Limit'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (!$request->filled('cleaning_type')) {
            return response()->json(['status' => 'failed', 'message' => 'Type of Services is required']);
        }

        $cleaningTypes = $request->input('cleaning_type');
        $cleaningTypes_arr = ServiceType::whereIn('id', $cleaningTypes)->pluck('service_type')->toarray();

        $customer = Crm::find($request->id);

        $customer->customer_type = $request->input('customer_type');
        $customer->customer_name = $request->input('customer_name');
        $customer->saluation = $request->input('saluation');
        $customer->mobile_number = $request->input('contact_no');
        $customer->created_by = $request->input('created_by');
        $customer->email = $request->input('email');
        $customer->status = $request->input('status');
        $customer->customer_remark = $request->input('customer_remark');
        $customer->lead_source = $request->input('lead_source');
        $customer->language_spoken = $request->input('language_spoken');
        // $customer->default_address = $request->input( 'default_address' );
        $customer->cleaning_type = implode(',', $cleaningTypes_arr);
        $customer->payment_terms = $request->input('info_payment_terms');
        $customer->credit_limit = $request->input('credit_limit');
        $customer->pending_invoice_limit = $request->pending_invoice_limit;
        $customer->renewal = $request->filled('renewal') ? 1 : 0;
        $customer->save();

        // delete service type
        DB::table('customer_service_types')->where('customer_id', $customer_id)->delete();

        // store service type
        $ServiceType_insert_arr = [];
        
        foreach($cleaningTypes as $item)
        {
            $ServiceType = ServiceType::find($item);

            $ServiceType_insert_arr[] = [
                'customer_id' => $customer_id,
                'service_type_id' => $item
            ];
        }

        DB::table('customer_service_types')->insert($ServiceType_insert_arr);

        if(AdditionalInfo::where('customer_id', $customer->id)->exists())
        {
            $additionalInfo = AdditionalInfo::where('customer_id', $customer->id)->first();
        }
        else
        {
            $additionalInfo = new AdditionalInfo();
        }
        
        $additionalInfo->credit_limit = $request->input('credit_limit');
        $additionalInfo->payment_terms = $request->input('info_payment_terms');
        $additionalInfo->save();

        // Update Additional Contact data
        $edit_res_additional_contact_name = $request->input('edit_res_additional_contact_name');
        $edit_res_additional_mobile_number = $request->input('edit_res_additional_mobile_number');
        $edit_res_additional_email = $request->input('edit_res_additional_email');

        $additionalContacts = [];

        foreach ($edit_res_additional_contact_name as $key => $name) {
            $additionalContacts[] = [
                'customer_id' => $customer->id,
                'contact_name' => $name,
                'mobile_no' => $edit_res_additional_mobile_number[$key],
                'email' => $edit_res_additional_email[$key],
                'updated_at' => Carbon::now()
            ];
        }

        AdditionalContact::where('customer_id', $customer->id)->delete();
        AdditionalContact::insert($additionalContacts);

        // Update the billing addresses
        $postalCodes = $request->input('postal_code_bill');
        $personIncharge = $request->input('person_incharge_name_bill');
        $contactNumbers = $request->input('phone_no_bill');
        $addresses = $request->input('address_bill');
        $unitNumbers = $request->input('unit_number_bill');
        $zone = $request->input('zone_bill');
        $email_bill = $request->input('email_bill');

        if (!empty($postalCodes) && !empty($addresses)) {
            $billingAddresses = [];

            foreach ($postalCodes as $key => $postalCode) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'person_incharge_name' => $personIncharge[$key],
                    'contact_no' => $contactNumbers[$key],
                    'address' => $addresses[$key],
                    'unit_number' => $unitNumbers[$key],
                    'zone' => $zone[$key],
                    'email' => $email_bill[$key],
                ];
            }

            // BillingAddress::where( 'customer_id', $customer->id )->delete();
            BillingAddress::insert($billingAddresses);
        }

        // Update the service addresses
        $postalCodes = $request->input('postal_code_service');
        $addresses = $request->input('address_service');
        $unitNumbers = $request->input('unit_number_service');
        $contactNumbers = $request->input('phone_no');
        $emailIds = $request->input('email_id');
        $personInchargeNames = $request->input('person_incharge_name');
        $territory =  $request->input('territory');
        $zone =  $request->input('zone_service');
        if (!empty($postalCodes) && !empty($addresses) && !empty($contactNumbers) && !empty($personInchargeNames)) {
            $serviceAddresses = [];

            foreach ($postalCodes as $key => $postalCode) {
                if (!empty($addresses[$key]) && !empty($contactNumbers[$key]) && !empty($personInchargeNames[$key]) && !empty($territory[$key])) {
                    $serviceAddresses[] = [
                        'customer_id' => $customer->id,
                        'postal_code' => $postalCode,
                        'address' => $addresses[$key],
                        'unit_number' => $unitNumbers[$key],
                        'contact_no' => $contactNumbers[$key],
                        'email_id' => $emailIds[$key],
                        'person_incharge_name' => $personInchargeNames[$key],
                        'territory' => $territory[$key],
                        'zone' => $zone[$key],
                    ];
                }
            }
            // print_r($serviceAddresses); exit;

            // ServiceAddress::where( 'customer_id', $customer->id )->delete();
            ServiceAddress::insert($serviceAddresses);
        }


        // start

        // service address
        if($request->filled('edit_resd_serv_address_id'))
        {
            for ($i = 0; $i < count($request->edit_resd_serv_address_id); $i++) {
            $addr_data = [
                'customer_id' => $customer->id,
                "person_incharge_name" => $request->edit_resd_serv_person_incharge_name[$i],
                "email_id" => $request->edit_resd_serv_email_id[$i],
                "contact_no" => $request->edit_resd_serv_phone_no[$i],
                "address" => $request->edit_resd_serv_address[$i],
                "postal_code" => $request->edit_resd_serv_postal_code[$i],
                "unit_number" => $request->edit_resd_serv_unit_number[$i],
                "territory" => $request->edit_resd_serv_territory[$i],
                "zone" => $request->edit_resd_serv_zone[$i],
            ];

            ServiceAddress::where('customer_id', $customer->id)
                ->where('id', $request->edit_resd_serv_address_id[$i])
                ->update($addr_data);
            }
        }

        // billing address
        if($request->filled('edit_resd_bill_address_id'))
        {
            for ($i = 0; $i < count($request->edit_resd_bill_address_id); $i++) {
                $addr_data = [
                    'customer_id' => $customer->id,
                    "person_incharge_name" => $request->edit_resd_bill_person_incharge_name[$i],
                    "contact_no" => $request->edit_resd_bill_phone_no[$i],
                    "address" => $request->edit_resd_bill_address[$i],
                    "postal_code" => $request->edit_resd_bill_postal_code[$i],
                    "unit_number" => $request->edit_resd_bill_unit_number[$i],
                    "zone" => $request->edit_resd_bill_zone[$i],
                    "email" => $request->edit_resd_bill_email[$i]
                ];

                BillingAddress::where('customer_id', $customer->id)
                    ->where('id', $request->edit_resd_bill_address_id[$i])
                    ->update($addr_data);
            }
        }

        // end

        // log data store start

        LogController::store('crm', 'Residential Customer Updated', $customer_id);

        // log data store end

        return response()->json(['success' => 'Residential Customer updated successfully!']);
    }

    // public function update_commercial_detail( Request $request ) {
    //     $validator = Validator::make( $request->all(), [

    //         'mobile_number' => 'required',
    //         'uen' => 'required',
    //     ],
    //     [
    //         'mobile_number.required' => 'The mobile number field is required.',
    //         // 'contact_no.regex' => 'Please enter a valid 10-digit mobile number.',
    //         'uen.required' => 'Please Enter uen '
    //     ] );

    //     if ( $validator->fails() ) {
    //         return response()->json( [ 'errors' => $validator->errors() ] );
    //     }

    //     $cleaningTypes = $request->input( 'cleaning_type' );

    //     $customer = Crm::find( $request->commercial_id );
    //     // dd( $customer->customer_type );
    //     $customer->customer_type = $request->input( 'customer_type' );
    //     $customer->customer_name = $request->input( 'customer_name' );
    //     $customer->saluation = $request->input( 'saluation' );
    //     $customer->mobile_number = $request->input( 'mobile_number' );
    //     $customer->created_by = $request->input( 'created_by' );
    //     $customer->email = $request->input( 'email' );
    //     $customer->payment_terms = $request->input( 'payment_terms' );
    //     $customer->status = $request->input( 'status' );
    //     $customer->territory = $request->input( 'territory' );
    //     $customer->language_spoken = $request->input( 'spoken_language' );
    //     $customer->customer_remark = $request->input( 'customer_remark' );
    //     $customer->language_spoken = $request->input( 'language_spoken' );
    //     $customer->uen = $request->input( 'uen' );
    //     $customer->default_address = $request->input( 'default_address' );
    //     $customer->group_company_name = $request->input( 'group_company_name' );
    //     $customer->individual_company_name = $request->input( 'individual_company_name' );

    //     $customer->cleaning_type = implode( ',', $cleaningTypes );
    //     $customer->save();
    //     //  UPDATE COMPANY INFO
    //     $companyInfo = CompanyINfo::where( 'customer_id', $customer->id )->first();
    //     $companyInfo->customer_id = $customer->id;
    //     $companyInfo->mobile_no = $request->input( 'company_mobile_no' );
    //     $companyInfo->fax_no = $request->input( 'fax_number' );
    //     $companyInfo->email = $request->input( 'company_email' );
    //     $companyInfo->contact_name = $request->input( 'company_contact_name' );
    //     $companyInfo->save();
    //     // Update the Additional Info data
    //     $additionalInfo = AdditionalInfo::where( 'customer_id', $customer->id )->first();
    //     $additionalInfo->credit_limit = $request->input( 'credit_limit' );
    //     $additionalInfo->remark = $request->input( 'remark' );
    //     $additionalInfo->payment_terms = $request->input( 'info_payment_terms' );
    //     $additionalInfo->status = $request->input( 'info_status' );
    //     $additionalInfo->save();

    //     // Update Additional Contact data
    //     $contactNames = $request->input( 'c_contact_name' );
    //     $mobileNumbers = $request->input( 'c_mobile_number' );

    //     if ( !empty( $contactNames ) && !empty( $mobileNumbers ) ) {
    //         $additionalContacts = [];

    //         foreach ( $contactNames as $key => $name ) {
    //             $additionalContacts[] = [
    //                 'customer_id' => $customer->id,
    //                 'contact_name' => $name,
    //                 'mobile_no' => $mobileNumbers[ $key ],
    //             ];
    //         }

    //         AdditionalContact::where( 'customer_id', $customer->id )->delete();
    //         AdditionalContact::insert( $additionalContacts );
    //     }

    //     // Update the billing addresses
    //     $postalCodes = $request->input( 'c_postal_code_bil' );
    //     $personIncharge = $request->input( 'c_person_incharge_name_bil' );
    //     $contactNumbers = $request->input( 'c_contact_no_bil' );
    //     $addresses = $request->input( 'c_address_bil' );
    //     $unitNumbers = $request->input( 'c_unit_no_bil' );

    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) ) {
    //         $billingAddresses = [];

    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             $billingAddresses[] = [
    //                 'customer_id' => $customer->id,
    //                 'postal_code' => $postalCode,
    //                 'person_incharge_name' => $personIncharge[$key],
    //                 'contact_no' => $contactNumbers[ $key ],
    //                 'address' => $addresses[ $key ],
    //                 'unit_number' => $unitNumbers[ $key ],
    //             ];
    //         }

    //         BillingAddress::where( 'customer_id', $customer->id )->delete();
    //         BillingAddress::insert( $billingAddresses );
    //     }

    //     // Update the service addresses
    //     $postalCodes = $request->input( 'c_postal_code' );
    //     $addresses = $request->input( 'c_address' );
    //     $unitNumbers = $request->input( 'c_unit_no' );
    //     $contactNumbers = $request->input( 'c_contact_no' );
    //     $emailIds = $request->input( 'c_email_id' );
    //     $personInchargeNames = $request->input( 'c_person_incharge_name' );
    //     $zone = $request->input( 'c_zone' );
    //     $territory = $request->input( 'c_territory' );

    //     if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
    //         $serviceAddresses = [];

    //         foreach ( $postalCodes as $key => $postalCode ) {
    //             if(!empty($addresses[ $key ]) && !empty($unitNumbers[ $key ]) && !empty($contactNumbers[ $key ]) && !empty($emailIds[ $key ]) && !empty($personInchargeNames[ $key ]) && !empty($territory[ $key ]) ){
    //                 $serviceAddresses[] = [
    //                     'customer_id' => $customer->id,
    //                     'postal_code' => $postalCode,
    //                     'address' => $addresses[ $key ],
    //                     'unit_number' => $unitNumbers[ $key ],
    //                     'contact_no' => $contactNumbers[ $key ],
    //                     'email_id' => $emailIds[ $key ],
    //                     'person_incharge_name' => $personInchargeNames[ $key ],
    //                     'zone' => $zone[ $key ],
    //                     'territory' => $territory[ $key ],
    //                 ];
    //             }
    //         }

    //         ServiceAddress::where( 'customer_id', $customer->id )->delete();
    //         ServiceAddress::insert( $serviceAddresses );
    //     }

    //     return response()->json( [ 'success' => 'Commercial Customer updated successfully!' ] );
    // }

    public function update_commercial_detail(Request $request)
    {
        $customer_id = $request->commercial_id;

        $validator = Validator::make(
            $request->all(),
            [
                'customer_name' => 'required|string|max:255',
                'uen' => 'nullable',
                'individual_company_name' => 'required',
                'mobile_number' => 'required|digits:8|unique:customers,mobile_number,' . $customer_id,
                'email' => 'nullable|unique:customers,email,' . $customer_id,
                'created_by' => 'required',
                'cleaning_type' => 'required',
                'status' => 'required',

                'edit_com_serv_person_incharge_name.*' => 'required',
                'edit_com_serv_contact_no.*' => 'required|digits:8',
                'edit_com_serv_postal_code.*' => 'required',
                'edit_com_serv_zone.*' => 'required',
                'edit_com_serv_address.*' => 'required',
                'edit_com_serv_unit_no.*' => 'nullable',
                'edit_com_serv_territory.*' => 'required',

                'edit_com_bill_person_incharge_name.*' => 'required',
                'edit_com_bill_contact_no.*' => 'required|digits:8',
                'edit_com_bill_postal_code.*' => 'required',
                'edit_com_bill_address.*' => 'required',
                'edit_com_bill_unit_no.*' => 'nullable',

                'c_person_incharge_name.*' => 'required',
                'c_contact_no.*' => 'required|digits:8',
                'c_postal_code.*' => 'required',
                'c_zone.*' => 'required',
                'c_address.*' => 'required',
                'c_unit_no.*' => 'nullable',
                'c_territory.*' => 'required',

                'c_person_incharge_name_bil.*' => 'required',
                'c_contact_no_bil.*' => 'required|digits:8',
                'c_postal_code_bil.*' => 'required',
                'c_address_bil.*' => 'required',
                'c_unit_no_bil.*' => 'nullable',
                'pending_invoice_limit' => 'required'
            ],
            [],
            [
                'customer_name' => 'Customer Name',
                'uen' => 'UEN',
                'individual_company_name' => 'Individual Company Name',
                'mobile_number' => 'Mobile No',
                'email' => 'Email',
                'created_by' => 'Created By',
                'cleaning_type' => 'Type of Services',
                'status' => 'Status',

                'edit_com_serv_person_incharge_name.*' => 'Service Address Person Incharge Name',
                'edit_com_serv_contact_no.*' => 'Service Address Phone No',
                'edit_com_serv_postal_code.*' => 'Service Address Postal Code',
                'edit_com_serv_zone.*' => 'Service Address Zone',
                'edit_com_serv_address.*' => 'Service Address',
                'edit_com_serv_unit_no.*' => 'Service Address Unit Number',
                'edit_com_serv_territory.*' => 'Service Address Territory',

                'edit_com_bill_person_incharge_name.*' => 'Billing address Person Incharge Name',
                'edit_com_bill_contact_no.*' => 'Billing address Phone No',
                'edit_com_bill_postal_code.*' => 'Billing address Postal Code',
                'edit_com_bill_address.*' => 'Billing Address',
                'edit_com_bill_unit_no.*' => 'Billing address Unit Number',

                'c_person_incharge_name.*' => 'Service Address Person Incharge Name',
                'c_contact_no.*' => 'Service Address Phone No',
                'c_postal_code.*' => 'Service Address Postal Code',
                'c_zone.*' => 'Service Address Zone',
                'c_address.*' => 'Service Address',
                'c_unit_no.*' => 'Service Address Unit Number',
                'c_territory.*' => 'Service Address Territory',

                'c_person_incharge_name_bil.*' => 'Billing address Person Incharge Name',
                'c_contact_no_bil.*' => 'Billing address Phone No',
                'c_postal_code_bil.*' => 'Billing address Postal Code',
                'c_address_bil.*' => 'Billing Address',
                'c_unit_no_bil.*' => 'Billing address Unit Number',
                'pending_invoice_limit' => 'Pending Invoice Limit'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if (!$request->filled('cleaning_type')) {
            return response()->json(['status' => 'failed', 'message' => 'Type of Services is required']);
        }

        $cleaningTypes = $request->input('cleaning_type');
        $cleaningTypes_arr = ServiceType::whereIn('id', $cleaningTypes)->pluck('service_type')->toarray();

        $customer = Crm::find($request->commercial_id);

        $customer->customer_type = $request->input('customer_type');
        $customer->customer_name = $request->input('customer_name');
        $customer->saluation = $request->input('saluation');
        $customer->mobile_number = $request->input('mobile_number');
        $customer->created_by = $request->input('created_by');
        $customer->email = $request->input('email');
        $customer->status = $request->input('status');
        $customer->language_spoken = $request->input('language_spoken');
        $customer->customer_remark = $request->input('customer_remark');
        $customer->lead_source = $request->input('lead_source');
        $customer->uen = $request->input('uen');
        // $customer->default_address = $request->input( 'default_address' );
        // $customer->group_company_name = $request->input('group_company_name');
        $customer->individual_company_name = $request->input('individual_company_name');
        $customer->cleaning_type = implode(',', $cleaningTypes_arr);
        $customer->payment_terms = $request->input('info_payment_terms');
        $customer->credit_limit = $request->input('credit_limit');
        $customer->pending_invoice_limit = $request->pending_invoice_limit;
        $customer->renewal = $request->filled('renewal') ? 1 : 0;
        $customer->save();

        // delete service type
        DB::table('customer_service_types')->where('customer_id', $customer_id)->delete();

        // store service type
        $ServiceType_insert_arr = [];
        
        foreach($cleaningTypes as $item)
        {
            $ServiceType = ServiceType::find($item);

            $ServiceType_insert_arr[] = [
                'customer_id' => $customer_id,
                'service_type_id' => $item
            ];
        }

        DB::table('customer_service_types')->insert($ServiceType_insert_arr);

        //  UPDATE COMPANY INFO
        if(CompanyINfo::where('customer_id', $customer->id)->exists())
        {
            $companyInfo = CompanyINfo::where('customer_id', $customer->id)->first();
        }
        else
        {
            $companyInfo = new CompanyInfo();
        }
        
        $companyInfo->customer_id = $customer->id;
        $companyInfo->mobile_no = $request->input('company_mobile_no');
        $companyInfo->fax_no = $request->input('fax_number');
        $companyInfo->email = $request->input('company_email');
        $companyInfo->contact_name = $request->input('company_contact_name');
        $companyInfo->save();

        // Update the Additional Info data
        if(AdditionalInfo::where('customer_id', $customer->id)->exists())
        {
            $additionalInfo = AdditionalInfo::where('customer_id', $customer->id)->first();
        }
        else
        {
            $additionalInfo = new AdditionalInfo();
        }
        
        $additionalInfo->credit_limit = $request->input('credit_limit');
        $additionalInfo->payment_terms = $request->input('info_payment_terms');
        $additionalInfo->save();

        // Update Additional Contact data
        $edit_com_additional_contact_name = $request->input('edit_com_additional_contact_name');
        $edit_com_additional_mobile_no = $request->input('edit_com_additional_mobile_no');
        $edit_com_additional_email = $request->input('edit_com_additional_email');

        $additionalContacts = [];

        foreach ($edit_com_additional_contact_name as $key => $name) {
            $additionalContacts[] = [
                'customer_id' => $customer->id,
                'contact_name' => $name,
                'mobile_no' => $edit_com_additional_mobile_no[$key],
                'email' => $edit_com_additional_email[$key],
                'updated_at' => Carbon::now()
            ];
        }

        AdditionalContact::where('customer_id', $customer->id)->delete();
        AdditionalContact::insert($additionalContacts);
        

        // add the billing addresses
        $postalCodes = $request->input('c_postal_code_bil');
        $personIncharge = $request->input('c_person_incharge_name_bil');
        $contactNumbers = $request->input('c_contact_no_bil');
        $addresses = $request->input('c_address_bil');
        $unitNumbers = $request->input('c_unit_no_bil');
        $zone = $request->input('c_zone_bill');
        $c_email_bil = $request->c_email_bil;

        if (!empty($postalCodes) && !empty($addresses)) {
            $billingAddresses = [];

            foreach ($postalCodes as $key => $postalCode) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'person_incharge_name' => $personIncharge[$key],
                    'contact_no' => $contactNumbers[$key],
                    'address' => $addresses[$key],
                    'unit_number' => $unitNumbers[$key],
                    'zone' => $zone[$key],
                    'email' => $c_email_bil[$key]
                ];
            }

            // BillingAddress::where( 'customer_id', $customer->id )->delete();
            BillingAddress::insert($billingAddresses);
        }

        // add the service addresses
        $postalCodes = $request->input('c_postal_code');
        $addresses = $request->input('c_address');
        $unitNumbers = $request->input('c_unit_no');
        $contactNumbers = $request->input('c_contact_no');
        $emailIds = $request->input('c_email_id');
        $personInchargeNames = $request->input('c_person_incharge_name');
        $zone = $request->input('c_zone');
        $territory = $request->input('c_territory');

        if (!empty($postalCodes) && !empty($addresses) && !empty($contactNumbers) && !empty($personInchargeNames)) {
            $serviceAddresses = [];

            foreach ($postalCodes as $key => $postalCode) {
                if (!empty($addresses[$key]) && !empty($contactNumbers[$key]) && !empty($personInchargeNames[$key]) && !empty($territory[$key])) {
                    $serviceAddresses[] = [
                        'customer_id' => $customer->id,
                        'postal_code' => $postalCode,
                        'address' => $addresses[$key],
                        'unit_number' => $unitNumbers[$key],
                        'contact_no' => $contactNumbers[$key],
                        'email_id' => $emailIds[$key],
                        'person_incharge_name' => $personInchargeNames[$key],
                        'zone' => $zone[$key],
                        'territory' => $territory[$key],
                    ];
                }
            }

            // ServiceAddress::where( 'customer_id', $customer->id )->delete();
            ServiceAddress::insert($serviceAddresses);
        }

        // start

        // update service address
        if($request->filled('edit_com_serv_address_id'))
        {
            for ($i = 0; $i < count($request->edit_com_serv_address_id); $i++) {
                $addr_data = [
                    'customer_id' => $customer->id,
                    "person_incharge_name" => $request->edit_com_serv_person_incharge_name[$i],
                    "email_id" => $request->edit_com_serv_email_id[$i],
                    "contact_no" => $request->edit_com_serv_contact_no[$i],
                    "address" => $request->edit_com_serv_address[$i],
                    "postal_code" => $request->edit_com_serv_postal_code[$i],
                    "unit_number" => $request->edit_com_serv_unit_no[$i],
                    "territory" => $request->edit_com_serv_territory[$i],
                    "zone" => $request->edit_com_serv_zone[$i],          
                ];

                ServiceAddress::where('customer_id', $customer->id)
                    ->where('id', $request->edit_com_serv_address_id[$i])
                    ->update($addr_data);
            }
        }

        // update billing address
        if($request->filled('edit_com_bill_address_id'))
        {
            for ($i = 0; $i < count($request->edit_com_bill_address_id); $i++) {
                $addr_data = [
                    'customer_id' => $customer->id,
                    "person_incharge_name" => $request->edit_com_bill_person_incharge_name[$i],
                    "contact_no" => $request->edit_com_bill_contact_no[$i],
                    "address" => $request->edit_com_bill_address[$i],
                    "postal_code" => $request->edit_com_bill_postal_code[$i],
                    "unit_number" => $request->edit_com_bill_unit_no[$i],
                    "zone" => $request->edit_com_bill_zone[$i],
                    'email' => $request->edit_com_bill_email[$i],
                ];

                BillingAddress::where('customer_id', $customer->id)
                    ->where('id', $request->edit_com_bill_address_id[$i])
                    ->update($addr_data);
            }
        }

        // end

        // log data store start

        LogController::store('crm', 'Commercial Customer Updated', $customer_id);

        // log data store end

        return response()->json(['success' => 'Commercial Customer updated successfully!']);
    }

    public function view()
    {
        $data = Crm::find(request()->id);
        $service_type_arr = DB::table('customer_service_types')
                                ->where('customer_service_types.customer_id', request()->id)
                                ->leftJoin('service_types', 'service_types.id', '=', 'customer_service_types.service_type_id')
                                ->pluck('service_types.service_type')
                                ->toarray();

        $data->service_type = implode(',', $service_type_arr);

        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        $additional_contact =  AdditionalContact::where('customer_id', request()->id)->get();
        $additional_info = AdditionalInfo::where('customer_id', request()->id)->first();
        $service_address = ServiceAddress::where('customer_id', request()->id)->get();
        $billing_address = BillingAddress::where('customer_id', request()->id)->get();
        $company_info = CompanyInfo::where('customer_id', request()->id)->first();
        $salutation_data = DB::table('constant_settings')->get();
        $source_data = SourceSetting::all();

        return view('admin.crm.view', compact('source_data', 'data', 'territory_list', 'spoken_language', 'payment_terms', 'additional_contact', 'additional_info', 'service_address', 'billing_address', 'company_info', 'salutation_data'));
    }

    public function deleteResidentialData(Request $request)
    {
        $crm = Crm::find($request->id);

        if ($crm) {
            $crm->delete();

            DB::table('customer_service_types')->where('customer_id', $crm->id)->delete();
            AdditionalInfo::where('customer_id', $crm->id)->delete();
            AdditionalContact::where('customer_id', $crm->id)->delete();
            BillingAddress::where('customer_id', $crm->id)->delete();
            ServiceAddress::where('customer_id', $crm->id)->delete();

            // log data store start

            LogController::store('crm', 'Residential Customer Deleted', $request->id);

            // log data store end

            return response()->json(['message' => 'Customer deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Customer not found'], 404);
        }
    }

    public function deleteCommercialData(Request $request)
    {
        $crm = Crm::find($request->id);
        if ($crm) {
            $crm->delete();

            DB::table('customer_service_types')->where('customer_id', $crm->id)->delete();
            CompanyInfo::where('customer_id', $crm->id)->delete();
            AdditionalInfo::where('customer_id', $crm->id)->delete();
            AdditionalContact::where('customer_id', $crm->id)->delete();
            BillingAddress::where('customer_id', $crm->id)->delete();
            ServiceAddress::where('customer_id', $crm->id)->delete();

            // log data store start

            LogController::store('crm', 'Commercial Customer Deleted', $request->id);

            // log data store end

            return response()->json(['message' => 'Customer deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // return 1;
    }

    // transaction-history start

    public function transaction_history($id)
    {
        // return $id;

        $data['customer'] = Crm::find($id);

        // return $data;

        return view('admin.crm.transaction-history', $data);
    }

    public function transaction_history_quotation_data(Request $request)
    {
        $customer_id = $request->customer_id;

        $quotation = Quotation::where('customer_id', $customer_id)
                                ->where('quotations.status', '!=', 0)
                                ->where('quotations.quotation_type', '=', 1)
                                ->orderBy('quotations.created_at', 'desc')
                                ->get();

        $data['quotation'] = $quotation;

        $new_data = [];

        foreach ($data['quotation'] as $key => $item) {
            $customer = Crm::find($item->customer_id);

            if ($customer) {
                $item->customer_name = $customer->customer_name;
            } else {
                $item->customer_name = "";
            }

            $company = Company::find($item->company_id);

            if ($company) {
                $item->company_name = $company->company_name;
            } else {
                $item->company_name = "";
            }

            if(isset($item))
            {
                if($item->status == 1)
                {
                    $status = '<span class="badge bg-yellow">Pending</span>';
                }                  
                elseif($item->status == 2)
                {
                    $status = '<span class="badge bg-red">Pending Customer Approval</span>';
                }
                elseif($item->status == 3)
                {
                    $status = '<span class="badge bg-red">Pending Payment</span>';
                }
                elseif($item->status == 4)                                          
                {
                    $status = '<span class="badge bg-green">Approved</span>';
                }
                elseif($item->status == 5)
                {
                    $status = '<span class="badge bg-red">Rejected</span>';
                }
            }
            else
            {                  
                $status = '<span class="badge bg-gray">No Data</span>';
            }

            // $action = '<a href="#" onclick="viewQuotation('.$item->id.')"><span class="badge bg-info">View</span></a>';

            $action = '<div class="dropdown">
                            <button
                                class="btn dropdown-toggle align-text-top t-btn"
                                data-bs-toggle="dropdown"
                                aria-expanded="true">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-end d-menu"
                                style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 39px);"
                                data-popper-placement="bottom-end">
                                <a class="dropdown-item" href="#" onclick="viewQuotation(' . $item->id . ')">
                                    <i class="fa-solid fa-eye me-2 text-blue"></i>
                                    View
                                </a>';           

            if ($item->status == 1 || $item->status == 2) {
                $action .= '<a class="dropdown-item" href="#" onclick="editQuotation(' . $item->id . ')">
                                <i class="fa-solid fa-pencil me-2 text-yellow"></i>
                                Edit
                            </a>';
            }

            if($item->status != 5)
            {
                $action .= '<a class="dropdown-item" href="'.route('quotation.download', $item->id).'">
                                <i class="fa-solid fa-download me-2 text-blue"></i>
                                Download
                            </a>';
            }
                    
            $action .= '</div>
                    </div>';

            $new_data[] = [
                $key + 1,
                $item->quotation_no,
                $item->company_name,
                date('d-m-Y', strtotime($item->created_at. ' + 14 days')),
                "$" . number_format($item->grand_total, 2),
                $item->created_by_name,
                $status,
                $action
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['quotation']->count(),
            "recordsFiltered" => $data['quotation']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    public function transaction_history_invoice_data(Request $request)
    {
        $customer_id = $request->customer_id;

        $quotation = Quotation::where('customer_id', $customer_id)->WhereNotNull('invoice_no')->orderBy('quotations.created_at', 'desc')->get();

        $data['quotation'] = $quotation;

        $new_data = [];

        foreach ($data['quotation'] as $key => $item) {
            if ($item->invoice_no != "" && $item->invoice_no != null) {
                $customer = Crm::find($item->customer_id);

                if ($customer) {
                    $item->customer_type = $customer->customer_type;
                    $item->customer_name = $customer->customer_name;
                    $item->individual_company_name = $customer->individual_company_name;
                } else {
                    $item->customer_type = "";
                    $item->customer_name = "";
                    $item->individual_company_name = "";
                }

                // lead payment detail start

                $lead_payment_detail = LeadPaymentInfo::where('quotation_id', $item->id)->get();

                $deposit = 0;
                $balance = 0;
                foreach($lead_payment_detail as $list)
                {
                    if($list->payment_status == 1)
                    {
                        $deposit += $list->payment_amount;
                    }
                }
                $balance = $item->grand_total - $deposit;

                // lead payment detail end

                $action = '<a href="'.route('finance.view-invoice', $item->id).'"><span class="badge bg-info">View</span></a>';

                $new_data[] = [
                    $key + 1,
                    $item->invoice_no,
                    $item->invoice_date ? date('d-m-Y', strtotime($item->invoice_date)) : '',
                    $item->schedule_date ? date('d-m-Y', strtotime($item->schedule_date)) : '',
                    ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->individual_company_name,
                    // $item->individual_company_name,
                    "$".number_format($item->amount, 2),
                    "$" . number_format($item->tax, 2) . " (". $item->tax_percent . "%)",
                    "$".number_format($item->grand_total, 2),
                    "$".number_format($balance, 2),
                    $item->created_by_name,
                    ucfirst(str_replace("_", " ", $item->payment_status)),
                    $action
                ];
            }
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['quotation']->count(),
            "recordsFiltered" => $data['quotation']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    public function transaction_history_sales_order_data(Request $request)
    {
        $customer_id = $request->customer_id;

        $sales_order = SalesOrder::where('customer_id', $customer_id)->orderBy('created_at', 'desc')->get();

        $data['sales_order'] = $sales_order;

        $new_data = [];

        foreach ($data['sales_order'] as $key => $item) 
        {
            // customer name

            $customer = Crm::find($item->customer_id);

            if ($customer) {
                $item->customer_type = $customer->customer_type;
                $item->customer_name = $customer->customer_name;
                $item->individual_company_name = $customer->individual_company_name;
            } else {
                $item->customer_type = "";
                $item->customer_name = "";
                $item->individual_company_name = "";
            }

            // status

            if ($item->cleaner_assigned_status == 1) {
                $status = '<span class="badge bg-success">Assigned</span>';
            }
            else if ($item->cleaner_assigned_status == 2)
            {
                $status = '<span class="badge bg-yellow">Partial</span>';
            }
            else if ($item->cleaner_assigned_status == 0)
            {
                $status = '<span class="badge bg-red">Unassigned</span>';
            }
            else {
                $status = '';
            }
            
            $quotation = Quotation::find($item->quotation_id);

            // total amount
            $item->total_amount = $quotation->grand_total ?? 0;

            // service address
            $service_address = ServiceAddress::find($quotation->service_address ?? '');  
            
            // remarks
            $schedule = ScheduleModel::where('sales_order_id', $item->id)->first();

            $new_data[] = [
                $key + 1,
                $item->invoice_no,
                $item->id,
                ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->individual_company_name,
                $service_address->address??'' .", ". $service_address->unit_number??'',
                $quotation->remarks ?? '',
                "$" . number_format($item->total_amount, 2),
                $item->created_by_name,
                $status,
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['sales_order']->count(),
            "recordsFiltered" => $data['sales_order']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    public function transaction_history_payment_data(Request $request)
    {
        $customer_id = $request->customer_id;

        $payment_details = LeadPaymentInfo::where('customer_id', $customer_id)->orderBy('created_at', 'desc')->get();

        $data['payment_details'] = $payment_details;

        $new_data = [];

        foreach ($data['payment_details'] as $key => $item) 
        {
            $quotation = Quotation::find($item->quotation_id);

            $action = '<a href="#" class="view_payment_proof_btn" data-id="'.$item->id.'" style="margin-right: 10px;"><span class="badge bg-info">View payment Proof</span></a>';

            if($item->payment_status == "2")
            {
                $action .= '<span class="badge bg-danger">Rejected</span>';
            }
            else if($item->payment_status == "0")
            {
                $action .= '<span class="badge bg-warning">Pending</span>';
            }

            $new_data[] = [
                $key + 1,
                $quotation->invoice_no ?? "",
                $item->payment_remarks ? ucfirst($item->payment_method) . " (" . $item->payment_remarks . ")" : ucfirst($item->payment_method),
                $item->created_at->format('d-m-Y'),
                "$".number_format($item->payment_amount, 2),
                $item->created_by_name,
                $action
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $data['payment_details']->count(),
            "recordsFiltered" => $data['payment_details']->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    public function transaction_history_session_details_data(Request $request)
    {
        $customer_id = $request->customer_id;

        $Schedule_details = ScheduleModel::leftJoin('customers', 'customers.id', '=', 'tble_schedule.customer_id')         
                        ->leftJoin('tble_schedule_details', 'tble_schedule_details.tble_schedule_id', '=', 'tble_schedule.id')    
                        ->leftJoin('sales_order', 'sales_order.id', '=', 'tble_schedule.sales_order_id')                                     
                        ->select('tble_schedule_details.*', 
                                'tble_schedule.customer_id', 
                                'sales_order.invoice_no',                          
                                'customers.customer_type', 
                                'customers.customer_name', 
                                'customers.individual_company_name')
                        // ->where('tble_schedule_details.job_status', 2)
                        ->where('tble_schedule.customer_id', $customer_id)
                        ->orderBy('tble_schedule_details.created_at', 'desc')
                        ->get();

        $new_data = [];

        foreach($Schedule_details as $key => $item)
        {
            // cleaner name

            if($item->cleaner_type == "team")
            {
                // $tble_schedule_employee = DB::table('tble_schedule_employee')
                //                                 ->where('tble_schedule_details_id', $item->id)
                //                                 ->first();
              
                // $team = DB::table('xin_team')->where('team_id', $tble_schedule_employee->employee_id)->first();

                $team = DB::table('xin_team')->where('team_id', $item->employee_id)->first();

                $emp_name = "";

                if($team)
                {
                    $temp_emp = explode(",", $team->employee_id);

                    $name_arr = [];

                    foreach($temp_emp as $list)
                    {
                        $xin_employees = DB::table('xin_employees')->where('user_id', $list)->first();

                        if($xin_employees)
                        {
                            $name = $xin_employees->first_name . " " . $xin_employees->last_name;
                        }
                        else
                        {
                            $name = "";
                        }

                        $name_arr[] = $name;
                    }

                    if(isset($name_arr))
                    {
                        $emp_name = implode(", ", $name_arr);
                    }
                }

                $item->employee_name = $emp_name;
            }
            elseif($item->cleaner_type == "individual")
            {        
                $emp_arr = explode(',', $item->employee_id);

                // $emp_arr = DB::table('tble_schedule_employee')
                //                 ->where('tble_schedule_details_id', $item->id)
                //                 ->pluck('employee_id')
                //                 ->toarray();

                $xin_employees = DB::table('xin_employees')
                                    ->whereIn('xin_employees.user_id', $emp_arr)
                                    ->get();

                $emp_name_arr = [];

                foreach($xin_employees as $loop_emp)
                {                  
                    $name = $loop_emp->first_name . " " . $loop_emp->last_name;

                    $emp_name_arr[] = $name;                                    
                }
                
                $item->employee_name = implode(',', $emp_name_arr);
            }

            // status

            if ($item->job_status == 2)
            {
                $status = '<span class="badge bg-success">Completed</span>';
            }
            else if ($item->job_status == 0)
            {
                $status = '<span class="badge bg-blue">Pending</span>';
            }
            else if ($item->job_status == 1)
            {
                $status = '<span class="badge bg-warning">Work In progress</span>';
            }
            else if ($item->job_status == 3)
            {
                $status = '<span class="badge bg-red">Cancelled</span>';
            }
            else {
                $status = '';
            }
            
            $action = '<a href="#" class="view_session_details_btn" data-sales_order_id="'.$item->sales_order_id.'" data-schedule_date="'.$item->schedule_date.'" style="margin-right: 10px;"><span class="badge bg-info">View</span></a>';

            $new_data[] = [
                $key + 1,
                $item->invoice_no ?? "",
                $item->sales_order_no,
                ($item->customer_type == "residential_customer_type") ? $item->customer_name : $item->individual_company_name,
                date('d-m-Y', strtotime($item->schedule_date)),
                date('h:i A', strtotime(($item->startTime))) . " - " . date('h:i A', strtotime(($item->endTime))),
                ucfirst($item->cleaner_type),
                $item->employee_name,
                $status,
                $action
            ];
        }

        $output = [
            "draw" => request()->draw,
            "recordsTotal" => $Schedule_details->count(),
            "recordsFiltered" => $Schedule_details->count(),
            "data" => $new_data
        ];

        echo json_encode($output);
    }

    public function transaction_history_view_session_details(Request $request)
    {
        $sales_order_id = $request->sales_order_id;
        $schedule_date = $request->schedule_date;

        $job_details = JobDetail::where('sales_order_id', $sales_order_id)
                                ->whereDate('schedule_date', $schedule_date)
                                ->first();

        if($job_details)
        {
            $before_cleaning_photos = DB::table('before_cleaning_photos')
                                        ->where('sales_order_id', $sales_order_id)
                                        ->whereDate('schedule_date', $schedule_date)
                                        ->get();

            foreach($before_cleaning_photos as $item)
            {
                $item->before_photos_url = asset('application/public/uploads/before_photos/'.$item->before_photos);
            }

            $job_details->before_cleaning_photos = $before_cleaning_photos;
                            
            $after_cleaning_photos = DB::table('after_cleaning_photos')
                                        ->where('sales_order_id', $sales_order_id)
                                        ->whereDate('schedule_date', $schedule_date)
                                        ->get();

            foreach($after_cleaning_photos as $item)
            {
                $item->after_photos_url = asset('application/public/uploads/after_photos/'.$item->after_photos);
            }

            $job_details->after_cleaning_photos = $after_cleaning_photos;
        }

        $data['job_details'] = $job_details;

        return response()->json($data);
    }

    // transaction-history end

    // crm_bulk_upload

    public function crm_bulk_upload(Request $request)
    {
        // return $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'crm_file' => 'required|mimes:xls,xlsx',
            ],
            [],
            [
                'crm_file' => 'CRM File',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'errors', 'errors' => $validator->errors()]);
        }
        else
        {
            if($request->hasFile('crm_file'))
            {
                $crm_file = $request->file('crm_file');

                Excel::import(new CrmImport, $crm_file);
            }

            // log data store start

            LogController::store('crm', 'Bulk Customer Created');

            // log data store end
            
            return response()->json( [ 'status' => 'success', 'message' => 'Bulk uploaded successfully.' ] );
        }
    }

    // quotation_create

    public function quotation_create($customer_id)
    {
        // customer
        $data['customer'] = Crm::leftJoin('language_spoken', 'customers.language_spoken', '=', 'language_spoken.id')
                                    ->select('customers.*', 'language_spoken.language_name as language_name')
                                    ->where('customers.id', $customer_id)
                                    ->first();

        // company list
        $data['companyList'] = Company::get();

        // service address
        $data['serviceAddress'] = ServiceAddress::where('customer_id', $customer_id)->get();

        // tax
        $today_date = date('Y-m-d');
        $data['tax'] = Tax::whereDate('from_date', '<=', $today_date)
                            ->whereDate('to_date', '>=', $today_date)
                            ->first();

        $data['emailTemplates'] = EmailTemplate::get();

        return view('admin.crm.create_quotation', $data);
    }

    // log report

    public function log_report($customer_id)
    {
        $data['customer'] = Crm::find($customer_id);

        $log_details = DB::table('log_details')
                        ->where('module', 'crm')
                        ->where('ref_no', $customer_id)
                        ->paginate(30);

        $data['log_details'] = $log_details;

        return view('admin.crm.log-report', $data);
    }
}
