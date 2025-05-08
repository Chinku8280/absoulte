<?php

namespace App\Http\Controllers;

use App\Models\AdditionalContact;
use App\Models\AdditionalInfo;
use App\Models\BillingAddress;
use App\Models\CompanyInfo;
use App\Models\Crm;
use App\Models\LanguageSpoken;
use App\Models\PaymentTerms;
use App\Models\ServiceAddress;
use App\Models\Territory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Datatables;
use Illuminate\Support\HtmlString;

class CrmController extends Controller {
    public function index() {
        $heading_name  = 'CRM';
        return view( 'admin.crm.index', compact( 'heading_name' ) );
    }

    public function create() {

        $heading_name = 'CRM';
        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        return view( 'admin.crm.create', compact( 'heading_name', 'territory_list', 'spoken_language', 'payment_terms' ) );
    }

    public function add_branch() {

        return view( 'admin.crm.add_branch' );
    }

    public function getAddress( Request $request ) {
        $postalCode = $request->input( 'postal_code' );
        // dd( $postalCode );
        $url = 'https://developers.onemap.sg/commonapi/search?searchVal=' . $postalCode . '&returnGeom=Y&getAddrDetails=Y';
        $response = file_get_contents( $url );
        $data = json_decode( $response, true );
        if ( isset( $data[ 'results' ] ) && !empty( $data[ 'results' ] ) ) {
            $address = $data[ 'results' ][ 0 ][ 'ADDRESS' ];
            return response()->json( [ 'address' => $address ] );
        } else {
            return response()->json( [ 'error' => 'Postal code not found' ], 404 );
        }
    }

    public function store_residential_detail( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'customer_name' => 'required|string|max:255',
            'contact_no' => 'required',
            'email' => 'required|email' ,

        ],
        [
            'contact_no.required' => 'The mobile number field is required.',
            // 'contact_no.regex' => 'Please enter a valid 10-digit mobile number.',
            'email.required' => 'The Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'customer_name.required' => 'Please Enter customer name'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
        }
        $cleaningTypes = $request->input( 'cleaning_type' );
// dd($request->input( 'spoken_language' ));
        $customer = new Crm( [
            'customer_type' => $request->input( 'customer_type' ),
            'customer_name' => $request->input( 'customer_name' ),
            'saluation' => $request->input( 'saluation' ),
            'mobile_number' => $request->input( 'contact_no' ),
            'created_by' => $request->input( 'created_by' ),
            'email' => $request->input( 'email' ),
            'payment_terms' => $request->input( 'payment_terms' ),
            'status' => $request->input( 'status' ),
            // 'territory' => $request->input( 'territory' ),
            'language_spoken' => $request->input( 'spoken_language' ),
            'customer_remark' => $request->input( 'customer_remark' ),
            'language_spoken' => $request->input( 'language_spoken' ),
            'default_address' => $request->input( 'default_address' ),

            'cleaning_type'  => implode( ', ', $cleaningTypes ),
        ] );

        $customer->save();

        // Save the Additinal Info data
        $additionalInfo = new AdditionalInfo( [
            'customer_id' => $customer->id,
            'credit_limit' => $request->input( 'credit_limit' ),
            'remark' => $request->input( 'remark' ),
            'payment_terms' => $request->input( 'info_payment_terms' ),
            'status' => $request->input( 'info_status' ),
        ] );

        $additionalInfo->save();

        // Save Additional Info data
        $contact_name =  $request->input( 'contact_name' );
        $mobile_no = $request->input( 'additional_mobile_number' );
        if ( !empty( $contact_name ) && !empty( $mobile_no ) ) {
            $additional_contact = [];
            foreach ( $contact_name as $key => $name ) {
                $additional_contact[] = [
                    'customer_id' => $customer->id,
                    'contact_name' => $name,
                    'mobile_no' => $mobile_no[ $key ],

                ];
            }
            AdditionalContact::insert( $additional_contact );
        }
        // Save the billing addresses
        $postalCodes = $request->input( 'postal_code' );
        $addresses = $request->input( 'address' );
        $unitNumbers = $request->input( 'unit_number' );

        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) ) {
            $billingAddresses = [];
            foreach ( $postalCodes as $key => $postalCode ) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    'unit_number' => $unitNumbers[ $key ],
                ];
            }
            BillingAddress::insert( $billingAddresses );
        }
        // Save the Service address
        $postalCodes = $request->input( 'postal_code_service' );
        $addresses = $request->input( 'address_service' );
        $unitNumbers = $request->input( 'unit_number_service' );
        $contactNumbers = $request->input( 'phone_no' );
        $emailIds  = $request->input( 'email_id' );
        $territory =  $request->input( 'territory' );
        $zone =  $request->input( 'zone' );

        // dd( $email_id );
        $personInchargeNames  = $request->input( 'person_incharge_name' );
        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
            $serviceAddresses = [];
            foreach ( $postalCodes as $key => $postalCode ) {
                $serviceAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    'unit_number' => $unitNumbers[ $key ],
                    'contact_no' => $contactNumbers[ $key ],
                    'email_id' => $emailIds[ $key ],
                    'person_incharge_name' => $personInchargeNames[ $key ],
                    'territory' => $territory[ $key ],
                    'zone' => $zone[ $key ],

                ];
            }
            ServiceAddress::insert( $serviceAddresses );
        }
        return response()->json( [ 'success' => 'Customer registered successfully!' ] );

    }

    public function store_commercial_details( Request $request ) {

        $validator = Validator::make( $request->all(), [

            'mobile_number' => 'required',
            'email' => 'required|email' ,
            'uen' => 'required',
        ],
        [
            'mobile_number.required' => 'The mobile number field is required.',
            // 'contact_no.regex' => 'Please enter a valid 10-digit mobile number.',
            'email.required' => 'The Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'uen.required' => 'Please Enter uen '
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
        }
        $cleaningTypes = $request->input( 'cleaning_type' );

        $customer = new Crm( [
            'customer_name' => $request->input( 'customer_name' ),
            'saluation' => $request->input( 'saluation' ),
            'customer_type' => $request->input( 'customer_type' ),
            'mobile_number' => $request->input( 'mobile_number' ),
            'created_by' => $request->input( 'created_by' ),
            'email' => $request->input( 'email' ),
            'payment_terms' => $request->input( 'payment_terms' ),
            'status' => $request->input( 'status' ),
            'territory' => $request->input( 'territory' ),
            'language_spoken' => $request->input( 'spoken_language' ),
            'customer_remark' => $request->input( 'customer_remark' ),
            // 'language_spoken' => $request->input( 'language_spoken' ),
            'uen' => $request->input( 'uen' ),
            'cleaning_type'  => implode( ', ', $cleaningTypes ),
            'default_address' => $request->input( 'default_address' ),
            'group_company_name' => $request->input( 'group_company_name' ),
            'individual_company_name' => $request->input( 'individual_company_name' ),
            'branch_name' => $request->input( 'branch_name' ),


        ] );

        $customer->save();
        $companyInfo = new CompanyInfo( [
            'customer_id' => $customer->id,
            'mobile_no' => $request->input( 'company_mobile_no' ),
            'fax_no' => $request->input( 'fax_number' ),
            'email' => $request->input( 'company_email' ),
            'contact_name' => $request->input( 'contact_name' ),
        ] );
        $companyInfo->save();
        $additionalInfo = new AdditionalInfo( [
            'customer_id' => $customer->id,
            'credit_limit' => $request->input( 'credit_limit' ),
            'remark' => $request->input( 'remark' ),
            'payment_terms' => $request->input( 'info_payment_terms' ),
            'status' => $request->input( 'info_status' ),
        ] );

        $additionalInfo->save();
        // Save the billing addresses
        $postalCodes = $request->input( 'postal_code' );
        $addresses = $request->input( 'address' );
        $unitNumbers = $request->input( 'billing_unit_number' );

        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) ) {
            $billingAddresses = [];
            foreach ( $postalCodes as $key => $postalCode ) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    // 'unit_number' => $unitNumbers[ $key ],
                ];
            }

            BillingAddress::insert( $billingAddresses );
        }
        $postalCodes = $request->input( 'c_postal_code' );
        $addresses = $request->input( 'c_address' );
        $unitNumbers = $request->input( 'c_unit_no' );
        $contactNumbers = $request->input( 'c_contact_no' );
        $emailIds  = $request->input( 'c_email_id' );
        $territory =  $request->input( 'c_territory' );

        // dd( $email_id );
        $personInchargeNames  = $request->input( 'c_person_incharge_name' );
        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
            $serviceAddresses = [];
            foreach ( $postalCodes as $key => $postalCode ) {
                $serviceAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    'unit_number' => $unitNumbers[ $key ],
                    'contact_no' => $contactNumbers[ $key ],
                    'email_id' => $emailIds[ $key ],
                    'person_incharge_name' => $personInchargeNames[ $key ],
                    'territory' => $territory[ $key ],
                ];
            }
            ServiceAddress::insert( $serviceAddresses );
        }
        $contact_name =  $request->input( 'c_contact_name' );
        $mobile_no = $request->input( 'c_mobile_no' );
        if ( !empty( $contact_name ) && !empty( $mobile_no ) ) {
            $additional_contact = [];
            foreach ( $contact_name as $key => $name ) {
                $additional_contact[] = [
                    'customer_id' => $customer->id,
                    'contact_name' => $name,
                    'mobile_no' => $mobile_no[ $key ],

                ];
            }
            AdditionalContact::insert( $additional_contact );
        }
        return response()->json( [ 'success' => 'Customer registered successfully!' ] );
    }

    public function residential() {
        $residentialCustomers = CRM::where( 'customer_type', 'residential_customer_type' )
            ->orderByRaw('CASE 
            WHEN status = 1 THEN 1
            WHEN status = 2 THEN 2
            WHEN status = 0 THEN 3
            ELSE 4
        END')->get();
        $data = [];
        $new_data = [];
        foreach ( $residentialCustomers as $key => $item ) {
            $statusBadge = match ( $item->status ) {
                '0' => '<span class="badge bg-red">Block</span>',
                '1' => '<span class="badge bg-green">Active</span>',
                '2' => '<span class="badge bg-gray">Inactive</span>',
                default => ''
            }
            ;
            // $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')">Edit</a>';
            $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
            $action .= '<a href="#" class="btn btn_delete_crm" onclick="delete_crm(' . $item->id . ')" data-customer-id=" ' .$item->id. ' ">
   <i class="fa-solid fa-trash me-2 text-red"></i>
</a>
';
            $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $item->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';

            $new_data[] = array(
                'sno' => $key + 1,
                'id' => $item->id,
                'mobile_number' => $item->mobile_number,
                'email' => $item->email,
                'status' => $statusBadge,
                'customer_name' => $item->customer_name,
                'outstanding_Amount' => '10$',
                'action' => $action
            );

        }

        $output = array(
            'draw' => intval( $_GET[ 'draw' ] ),
            'recordsTotal' => count( $data ),
            'recordsFiltered' => count( $data ),
            'data' => $new_data,
        );
        // dd( $output );
        echo json_encode( $output );
    }

    public function commercial() {
        $residentialCustomers = CRM::where( 'customer_type', 'commercial_customer_type' ) 
            ->orderByRaw('CASE 
            WHEN status = 1 THEN 1
            WHEN status = 2 THEN 2
            WHEN status = 0 THEN 3
            ELSE 4
        END')->get();
        $data = [];
        $new_data = [];
        foreach ( $residentialCustomers as $key => $item ) {
            $statusBadge = match ( $item->status ) {
                '0' => '<span class="badge bg-red">Block</span>',
                '1' => '<span class="badge bg-green">Active</span>',
                '2' => '<span class="badge bg-gray">Inactive</span>',
                default => '',

            }
            ;
            // $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')">Edit</a>';
            $action = '<a href="#" class="btn btn-edit_crm" onclick="edit_crm_modal(' . $item->id . ')"><i class="fa-solid fa-pencil me-2 text-yellow"></i></a>';
            $action .= '<a href="#" class="btn btn_delete_crm_commercial" onclick="delete_crm_commercial(' . $item->id . ')" data-customer-id=" ' .$item->id. ' ">
   <i class="fa-solid fa-trash me-2 text-red"></i>
</a>
';

            $action .= '<a href="#" class="btn btn-view_crm" onclick="view_crm_modal(' . $item->id . ')"><i class="fa-solid fa-eye me-2 text-blue"></i></a>';
            $new_data[] = array(
                'sno' => $key + 1,
                'id' => $item->id,
                'mobile_number' => $item->mobile_number,
                'email' => $item->email,
                'status' => $statusBadge,
                'customer_name' => $item->customer_name,
                'outstanding_Amount' => '10$',
                'action' => $action

            );

        }

        $output = array(
            'draw' => intval( $_GET[ 'draw' ] ),
            'recordsTotal' => count( $data ),
            'recordsFiltered' => count( $data ),
            'data' => $new_data,
        );
        
        // dd( $output );
        echo json_encode( $output );
    }

    public function edit() {
        $data = Crm::find( request()->id );
        $data[ 'cleaningTypes' ] = explode( ',', $data->cleaning_type );

        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        $additional_contact =  AdditionalContact::where( 'customer_id', request()->id )->get();
        $additional_info = AdditionalInfo::where( 'customer_id', request()->id )->first();
        $service_address = ServiceAddress::where( 'customer_id', request()->id )->get();
        $billing_address = BillingAddress::where( 'customer_id', request()->id )->get();
        $company_info = CompanyInfo::where( 'customer_id', request()->id )->first();
        // dd( $company_info );
        return view( 'admin.crm.edit', compact( 'data', 'territory_list', 'spoken_language', 'payment_terms', 'additional_contact', 'additional_info', 'service_address', 'billing_address', 'company_info' ) );
    }

    public function update_residential_detail( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'customer_name' => 'required|string|max:255',
            'contact_no' => 'required',
            'email' => 'required|email',
        ], [
            'contact_no.required' => 'The mobile number field is required.',
            'email.required' => 'The Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'customer_name.required' => 'Please enter the customer name.',
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
        }

        $cleaningTypes = $request->input( 'cleaning_type' );

        $customer = Crm::find( $request->id );
        // dd( $customer->customer_type );

        $customer->customer_type = $request->input( 'customer_type' );
        $customer->customer_name = $request->input( 'customer_name' );
        $customer->saluation = $request->input( 'saluation' );
        $customer->nick_name = $request->input( 'nick_name' );
        $customer->mobile_number = $request->input( 'contact_no' );
        $customer->created_by = $request->input( 'created_by' );
        $customer->email = $request->input( 'email' );
        $customer->payment_terms = $request->input( 'payment_terms' );
        $customer->status = $request->input( 'status' );
        $customer->territory = $request->input( 'territory' );
        $customer->language_spoken = $request->input( 'language_spoken' );
        $customer->customer_remark = $request->input( 'customer_remark' );
        $customer->language_spoken = $request->input( 'language_spoken' );
        $customer->default_address = $request->input( 'default_address' );

        $customer->cleaning_type = implode( ',', $cleaningTypes );
        $customer->save();

        // Update the Additional Info data
        $additionalInfo = AdditionalInfo::where( 'customer_id', $customer->id )->first();
        $additionalInfo->credit_limit = $request->input( 'credit_limit' );
        $additionalInfo->remark = $request->input( 'remark' );
        $additionalInfo->payment_terms = $request->input( 'info_payment_terms' );
        $additionalInfo->status = $request->input( 'info_status' );
        $additionalInfo->save();

        // Update Additional Contact data
        $contactNames = $request->input( 'contact_name' );
        $mobileNumbers = $request->input( 'additional_mobile_number' );

        if ( !empty( $contactNames ) && !empty( $mobileNumbers ) ) {
            $additionalContacts = [];

            foreach ( $contactNames as $key => $name ) {
                $additionalContacts[] = [
                    'customer_id' => $customer->id,
                    'contact_name' => $name,
                    'mobile_no' => $mobileNumbers[ $key ],
                ];
            }

            AdditionalContact::where( 'customer_id', $customer->id )->delete();
            AdditionalContact::insert( $additionalContacts );
        }

        // Update the billing addresses
        $postalCodes = $request->input( 'postal_code' );
        $addresses = $request->input( 'address' );
        $unitNumbers = $request->input( 'unit_number' );

        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) ) {
            $billingAddresses = [];

            foreach ( $postalCodes as $key => $postalCode ) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    'unit_number' => $unitNumbers[ $key ],
                ];
            }

            BillingAddress::where( 'customer_id', $customer->id )->delete();
            BillingAddress::insert( $billingAddresses );
        }

        // Update the service addresses
        $postalCodes = $request->input( 'postal_code_service' );
        $addresses = $request->input( 'address_service' );
        $unitNumbers = $request->input( 'unit_number_service' );
        $contactNumbers = $request->input( 'phone_no' );
        $emailIds = $request->input( 'email_id' );
        $personInchargeNames = $request->input( 'person_incharge_name' );
        $territory =  $request->input( 'territory' );
        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
            $serviceAddresses = [];

            foreach ( $postalCodes as $key => $postalCode ) {
                $serviceAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    'unit_number' => $unitNumbers[ $key ],
                    'contact_no' => $contactNumbers[ $key ],
                    'email_id' => $emailIds[ $key ],
                    'person_incharge_name' => $personInchargeNames[ $key ],
                    'territory' => $territory[ $key ],
                ];
            }

            ServiceAddress::where( 'customer_id', $customer->id )->delete();
            ServiceAddress::insert( $serviceAddresses );
        }

        return response()->json( [ 'success' => 'Residential Customer updated successfully!' ] );
    }

    public function update_commercial_detail( Request $request ) {
        $validator = Validator::make( $request->all(), [

            'mobile_number' => 'required',
            'email' => 'required|email' ,
            'uen' => 'required',
        ],
        [
            'mobile_number.required' => 'The mobile number field is required.',
            // 'contact_no.regex' => 'Please enter a valid 10-digit mobile number.',
            'email.required' => 'The Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'uen.required' => 'Please Enter uen '
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'errors' => $validator->errors() ] );
        }

        $cleaningTypes = $request->input( 'cleaning_type' );

        $customer = Crm::find( $request->commercial_id );
        // dd( $customer->customer_type );  
        $customer->customer_type = $request->input( 'customer_type' );
        $customer->customer_name = $request->input( 'customer_name' );
        $customer->saluation = $request->input( 'saluation' );
        $customer->mobile_number = $request->input( 'mobile_number' );
        $customer->created_by = $request->input( 'created_by' );
        $customer->email = $request->input( 'email' );
        $customer->payment_terms = $request->input( 'payment_terms' );
        $customer->status = $request->input( 'status' );
        $customer->territory = $request->input( 'territory' );
        $customer->language_spoken = $request->input( 'spoken_language' );
        $customer->customer_remark = $request->input( 'customer_remark' );
        $customer->language_spoken = $request->input( 'language_spoken' );
        $customer->uen = $request->input( 'uen' );
        $customer->default_address = $request->input( 'default_address' );
        $customer->group_company_name = $request->input( 'group_company_name' );
        $customer->individual_company_name = $request->input( 'individual_company_name' );

        $customer->cleaning_type = implode( ',', $cleaningTypes );
        $customer->save();
        //  UPDATE COMPANY INFO
        $companyInfo = CompanyINfo::where( 'customer_id', $customer->id )->first();
        $companyInfo->customer_id = $customer->id;
        $companyInfo->mobile_no = $request->input( 'company_mobile_no' );
        $companyInfo->fax_no = $request->input( 'fax_number' );
        $companyInfo->email = $request->input( 'company_email' );
        $companyInfo->contact_name = $request->input( 'company_contact_name' );
        $companyInfo->save();
        // Update the Additional Info data
        $additionalInfo = AdditionalInfo::where( 'customer_id', $customer->id )->first();
        $additionalInfo->credit_limit = $request->input( 'credit_limit' );
        $additionalInfo->remark = $request->input( 'remark' );
        $additionalInfo->payment_terms = $request->input( 'info_payment_terms' );
        $additionalInfo->status = $request->input( 'info_status' );
        $additionalInfo->save();

        // Update Additional Contact data
        $contactNames = $request->input( 'c_contact_name' );
        $mobileNumbers = $request->input( 'c_mobile_number' );

        if ( !empty( $contactNames ) && !empty( $mobileNumbers ) ) {
            $additionalContacts = [];

            foreach ( $contactNames as $key => $name ) {
                $additionalContacts[] = [
                    'customer_id' => $customer->id,
                    'contact_name' => $name,
                    'mobile_no' => $mobileNumbers[ $key ],
                ];
            }

            AdditionalContact::where( 'customer_id', $customer->id )->delete();
            AdditionalContact::insert( $additionalContacts );
        }

        // Update the billing addresses
        $postalCodes = $request->input( 'postal_code' );
        $addresses = $request->input( 'address' );
        $unitNumbers = $request->input( 'billing_unit_number' );

        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) ) {
            $billingAddresses = [];

            foreach ( $postalCodes as $key => $postalCode ) {
                $billingAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    'unit_number' => $unitNumbers[ $key ],
                ];
            }

            BillingAddress::where( 'customer_id', $customer->id )->delete();
            BillingAddress::insert( $billingAddresses );
        }

        // Update the service addresses
        $postalCodes = $request->input( 'c_postal_code' );
        $addresses = $request->input( 'c_address' );
        $unitNumbers = $request->input( 'c_unit_no' );
        $contactNumbers = $request->input( 'c_contact_no' );
        $emailIds = $request->input( 'c_email_id' );
        $personInchargeNames = $request->input( 'c_person_incharge_name' );
        $zone = $request->input( 'c_zone' );
        $territory = $request->input( 'c_territory' );

        if ( !empty( $postalCodes ) && !empty( $addresses ) && !empty( $unitNumbers ) && !empty( $contactNumbers ) && !empty( $emailIds ) && !empty( $personInchargeNames ) ) {
            $serviceAddresses = [];

            foreach ( $postalCodes as $key => $postalCode ) {
                $serviceAddresses[] = [
                    'customer_id' => $customer->id,
                    'postal_code' => $postalCode,
                    'address' => $addresses[ $key ],
                    'unit_number' => $unitNumbers[ $key ],
                    'contact_no' => $contactNumbers[ $key ],
                    'email_id' => $emailIds[ $key ],
                    'person_incharge_name' => $personInchargeNames[ $key ],
                    'zone' => $zone[ $key ],
                    'territory' => $territory[ $key ],
                ];
            }

            ServiceAddress::where( 'customer_id', $customer->id )->delete();
            ServiceAddress::insert( $serviceAddresses );
        }

        return response()->json( [ 'success' => 'Commercial Customer updated successfully!' ] );
    }

    public function view() {
        $data = Crm::find( request()->id );
        $data[ 'cleaningTypes' ] = explode( ',', $data->cleaning_type );

        $territory_list = Territory::all();
        $spoken_language = LanguageSpoken::all();
        $payment_terms = PaymentTerms::all();
        $additional_contact =  AdditionalContact::where( 'customer_id', request()->id )->get();
        $additional_info = AdditionalInfo::where( 'customer_id', request()->id )->first();
        $service_address = ServiceAddress::where( 'customer_id', request()->id )->get();
        $billing_address = BillingAddress::where( 'customer_id', request()->id )->get();
        $company_info = CompanyInfo::where( 'customer_id', request()->id )->first();
        // dd( $company_info );
        return view( 'admin.crm.view', compact( 'data', 'territory_list', 'spoken_language', 'payment_terms', 'additional_contact', 'additional_info', 'service_address', 'billing_address', 'company_info' ) );
    }

    public function deleteResidentialData( Request $request, $id ) {
        $crm = Crm::find( $id );
        if ( $crm ) {
            $crm->delete();

            AdditionalInfo::where( 'customer_id', $crm->id )->delete();
            AdditionalContact::where( 'customer_id', $crm->id )->delete();
            BillingAddress::where( 'customer_id', $crm->id )->delete();
            ServiceAddress::where( 'customer_id', $crm->id )->delete();
            return response()->json( [ 'message' => 'Customer deleted successfully' ], 200 );
        } else {
            return response()->json( [ 'message' => 'Customer not found' ], 404 );
        }

    }

    public function deleteCommercialData( Request $request, $id ) {
        $crm = Crm::find( $id );
        if ( $crm ) {
            $crm->delete();
            CompanyInfo::where( 'customer_id', $crm->id )->delete();
            AdditionalInfo::where( 'customer_id', $crm->id )->delete();
            AdditionalContact::where( 'customer_id', $crm->id )->delete();
            BillingAddress::where( 'customer_id', $crm->id )->delete();
            ServiceAddress::where( 'customer_id', $crm->id )->delete();
            return response()->json( [ 'message' => 'Customer deleted successfully' ], 200 );
        } else {
            return response()->json( [ 'message' => 'Customer not found' ], 404 );
        }

    }
}
