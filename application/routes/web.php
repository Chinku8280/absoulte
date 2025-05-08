<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CleanerController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageSpokenController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\PaymentTermsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\ServiceCompanyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TerritoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScheduleConteroller;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeadPaymentController;
use App\Http\Controllers\PaymentApprovalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\QuotationPaymentController;
use App\Http\Controllers\TermConditionController;
use Google\Service\Adsense\Row;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     if (Auth::check()) 
//     {
//         return redirect()->route('dashboard');
//     }
//     else
//     {
//         return view('auth.login');
//     }
// });



Route::get('/', function () {
    return view('auth.login');

    // if (Auth::viaRemember()) {
    //     return redirect()->route('dashboard'); // Log in via remember token
    // }
    // else{
    //     return view('auth.login');
    // }
});

// Route::get('/dashboard', function () {
//     $heading_name  = 'Dashboard';
//     return view('dashboard.dashboard',compact('heading_name'));
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', [DashboardController::class, 'home'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/logout', [AuthenticatedSessionController::class,'destroy']);

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/old-dashboard', 'old_home')->name('old-dashboard');

        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/get-chart-data', 'get_sales_order_chart_data')->name('dashboard.get-sales-order-chart-data');
        Route::get('/dashboard/get-schedule-jobs-count', 'get_schedule_jobs_count')->name('dashboard.get-schedule-jobs-count');
        Route::get('/dashboard/get-leads-chart-data', 'get_leads_chart_data')->name('dashboard.get-leads-chart-data');
        Route::get('/dashboard/get-quotation-chart-data', 'get_quotation_chart_data')->name('dashboard.get-quotation-chart-data');
        Route::get('/dashboard/get-job-order-chart-data', 'get_job_order_chart_data')->name('dashboard.get-job-order-chart-data');
    });
});

// leads
Route::get('/leads/reject-mail/{lead_id}', [LeadsController::class,'reject_lead_mail']);
Route::get('/leads/confirm-mail/{lead_id}', [LeadsController::class,'confirm_lead_mail']);

// quotation
Route::get('/quotation/reject-mail/{quotation_id}', [QuotationController::class,'reject_quotation_mail']);
Route::get('/quotation/confirm-mail/{quotation_id}', [QuotationController::class,'confirm_quotation_mail']);

Route::controller(LeadPaymentController::class)->group(function () {
    Route::get('/lead/payment-response/success', 'payment_success_response')->name('lead.payment-success-response');
    Route::get('/lead/payment-response/cancel', 'payment_cancel_response')->name('lead.payment-cancel-response');
    Route::get('/lead/payment-response/failed', 'payment_failed_response')->name('lead.payment-failed-response');
});

Route::controller(QuotationPaymentController::class)->group(function () {
    Route::get('/quotation/payment-response/success', 'payment_success_response')->name('quotation.payment-success-response');
    Route::get('/quotation/payment-response/cancel', 'payment_cancel_response')->name('quotation.payment-cancel-response');
    Route::get('/quotation/payment-response/failed', 'payment_failed_response')->name('quotation.payment-failed-response');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // ROUTE FOR CRM CONTROLLER
    Route::get('/crm', [CrmController::class, 'index'])->name('crm');
    Route::get('crm/create',[CrmController::class,'create'])->name('crm.create');
    Route::get('crm/add_branch',[CrmController::class,'add_branch'])->name('crm.add_branch');
    Route::post('/customer/store', [CrmController::class,'store_residential_detail'])->name('customer.store');
    Route::post('/commercial/customer/store', [CrmController::class,'store_commercial_details'])->name('commercial.customer.store');
    //  Route::get('/get-customers', [CrmController::class, 'getCustomers']);
    Route::get('/residential/customers', [CrmController::class, 'residential'])->name('customers.residential');
    Route::get('/commercial/customers', [CrmController::class, 'commercial'])->name('customers.commercial');
    Route::get('/crm/edit/{id}', [CrmController::class, 'edit'])->name('crm.edit');
    Route::post('/customer/update', [CrmController::class,'update_residential_detail'])->name('customer.update');
    Route::post('/commercial/customer/update', [CrmController::class,'update_commercial_detail'])->name('commercial.customer.update');
    Route::get('/crm/view/{id}', [CrmController::class, 'view'])->name('crm.view');
    Route::post('/delete-residential', [CrmController::class,'deleteResidentialData'])->name('delete-residential');
    Route::post('/delete-commercial', [CrmController::class,'deleteCommercialData'])->name('delete-commercial');
    Route::post('/search/residential/customer', [CrmController::class,'searchResidential'])->name('search.residential.customer');
    Route::post('/search/commercial/customer', [CrmController::class,'searchCommercial'])->name('search.commercial.customer');
    Route::post('/crm/bulk-upload', [CrmController::class,'crm_bulk_upload'])->name('crm.bulk-upload');
    
    Route::get('/get-zone-by-postal-code/{postalCode}', [CrmController::class,'getZoneByPostalCode'])->name('get.zone.name');
    
    // transaction-history
    Route::get('/crm/transaction-history/{id}', [CrmController::class,'transaction_history'])->name('crm.transaction-history');
    Route::get('/crm/get-quotation-table-data', [CrmController::class,'transaction_history_quotation_data'])->name('crm.get-quotation-table-data');
    Route::get('/crm/get-invoice-table-data', [CrmController::class,'transaction_history_invoice_data'])->name('crm.get-invoice-table-data');
    Route::get('/crm/get-sales-order-table-data', [CrmController::class,'transaction_history_sales_order_data'])->name('crm.get-sales-order-table-data');
    Route::get('/crm/get-payment-history-table-data', [CrmController::class,'transaction_history_payment_data'])->name('crm.get-payment-history-table-data');
    Route::get('/crm/get-session-details-table-data', [CrmController::class,'transaction_history_session_details_data'])->name('crm.get-session-details-table-data');
    Route::get('/crm/view-session-details', [CrmController::class,'transaction_history_view_session_details'])->name('crm.view-session-details');

    Route::get('/crm/quotation.create/{id}', [CrmController::class,'quotation_create'])->name('crm.quotation.create');

    Route::get('/crm#commercial', [CrmController::class, 'index'])->name('crm-commercial-tab');
    Route::get('/crm#residential', [CrmController::class, 'index'])->name('crm-residential-tab');
    Route::get('/crm/{status}', [CrmController::class, 'crm_active'])->name('crm-active');

    Route::get('/crm/log-report/{id}', [CrmController::class,'log_report'])->name('crm.log-report');

    // ROUTE FOR BRANCH CONTROLLER
    Route::post('/branch/store', [BranchController::class,'store'])->name('branch.store');
    Route::get('branch/list',[BranchController::class,'branch_list'])->name('branch.list');
    Route::get('/leads', [LeadsController::class, 'index'])->name('leads');
    Route::resource('products', ProductController::class);
    Route::resource('roles',RoleController::class);
    Route::resource('users',UserController::class);
    Route::get('/get-address', [CrmController::class,'getAddress']);
    Route::get('territory',[TerritoryController::class,'index'])->name('territory');
    Route::get('territory/create',[TerritoryController::class,'create'])->name('territory.create');
    Route::post('territory/store',[TerritoryController::class,'store'])->name('territory.store');
    Route::get('territory/showData',[TerritoryController::class,'showData'])->name('territory.showData');
    Route::get('/territory/edit/{id}', [TerritoryController::class, 'edit'])->name('territory.edit');

    Route::delete('/delete-territory/{id}', [TerritoryController::class, 'delete'])->name('delete-territory');
    Route::get('languageSpoken',[LanguageSpokenController::class,'index'])->name('languageSpoken');
    Route::get('languageSpoken/create',[LanguageSpokenController::class,'create'])->name('languageSpoken.create');
    Route::post('languageSpoken/store',[LanguageSpokenController::class,'store'])->name('languageSpoken.store');
    Route::get('languageSpoken/showData',[LanguageSpokenController::class,'showData'])->name('language.showData');
    Route::delete('/delete-languageSpoken/{id}', [LanguageSpokenController::class, 'delete'])->name('delete-languageSpoken');
    Route::get('/languageSpoken/edit/{id}', [LanguageSpokenController::class, 'edit'])->name('languageSpoken.edit');


    Route::get('paymentTerms',[PaymentTermsController::class,'index'])->name('paymentTerms');
    Route::get('paymentTerms/create',[PaymentTermsController::class,'create'])->name('paymentTerms.create');
    Route::post('paymentTerms/store',[PaymentTermsController::class,'store'])->name('paymentTerms.store');
    Route::get('paymentTerms/showData',[PaymentTermsController::class,'showData'])->name('paymentTerms.showData');
    Route::delete('/delete-paymentTerms/{id}', [PaymentTermsController::class, 'delete'])->name('delete-paymentTerms');
    Route::get('/paymentTerms/edit/{id}', [PaymentTermsController::class, 'edit'])->name('paymentTerms.edit');
    // ROUTES FOR COMPANY
    Route::get('services',[ServiceCompanyController::class,'index'])->name('services');
    Route::get('company/create',[ServiceCompanyController::class,'create'])->name('company.create');
    Route::post('/company/store', [ServiceCompanyController::class,'store'])->name('company.store');
    Route::get('/company/data', [ServiceCompanyController::class, 'fetchData'])->name('company.data');
    Route::delete('/delete-company/{id}', [ServiceCompanyController::class,'delete'])->name('delete-company');
    Route::get('/company/edit/{id}', [ServiceCompanyController::class, 'edit'])->name('company.edit');

    // ROUTES FOR Cleaner
    Route::get('cleaners',[CleanerController::class,'index'])->name('cleaners');
    Route::get('/cleaners/data', [CleanerController::class, 'cleanerData'])->name('cleaners.data');
    Route::get('/cleaners/edit', [CleanerController::class, 'cleaner_edit'])->name('cleaners.edit');
    Route::post('/cleaners/update', [CleanerController::class, 'cleaner_update'])->name('cleaners.update');

    // ROUTES FOR team
    Route::get('/team/data', [CleanerController::class, 'teamData'])->name('team.data');
    Route::get('/team/create',[CleanerController::class,'create'])->name('team.create');
    Route::post('/team/store', [CleanerController::class,'store'])->name('team.store');
    Route::delete('/delete-team/{id}', [CleanerController::class,'delete'])->name('delete-team');
    Route::get('/team/edit/{id}', [CleanerController::class, 'edit'])->name('team.edit');
    Route::get('/team/get-superviser', [CleanerController::class, 'get_superviser'])->name('team.get-superviser');

    // service
    Route::get('service/create',[ServiceController::class,'create'])->name('service.create');
    Route::post('/service/store', [ServiceController::class,'store'])->name('service.store');
    Route::get('/download-sample-file', [ServiceController::class, 'downloadSampleFile'])->name('download.sample.file');
    Route::get('/service/data', [ServiceController::class, 'fetchData'])->name('service.data');
    Route::delete('/delete-service/{id}', [ServiceController::class,'delete'])->name('delete-service');
    Route::get('/service/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
    Route::post('/upload-bulk-service', [ServiceController::class, 'uploadBulkService'])->name('upload.bulk.service');

    Route::get('services#service',[ServiceCompanyController::class,'index'])->name('services-tab');

    // sales order
    Route::controller(SalesOrderController::class)->group(function () {
        Route::get('/salesOrder','index')->name('salesOrder');
        Route::get('/salesorder/view','view')->name('sales.order.view');
        Route::get('/salesorder/edit','edit')->name('sales.order.edit');
        Route::get('/get-cleaner-service-address/{id}', 'getAddress')->name('get.cleaner.service.address');

        Route::get('/sales-order/schedule-date-table-details', 'schedule_date_table_details')->name('sales-order.schedule-date-table-details');
        Route::get('/sales-order/edit-schedule-date-table-details', 'edit_schedule_date_table_details')->name('sales-order.edit-schedule-date-table-details');
             
        Route::post('/sales-order/unassign', 'unassign')->name('sales-order.unassign');

        Route::get('/sales-order/check-renewal', 'check_renewal')->name('sales-order.check-renewal');
        Route::post('/sales-order/renewal', 'renewal')->name('sales-order.renewal');

        // create sales order
        Route::get('/sales-order/create', 'create')->name('sales-order.create');
        Route::post('/sales-order/preview', 'get_preview')->name('get.sales-order.preview');
        Route::post('/sales-order/store', 'store')->name('sales-order.store');

        Route::get('/sales-order/view-assign-cleaner', 'view_assign_cleaner')->name('sales-order.view-assign-cleaner');
    
        Route::get('/sales-order/filter/{temp?}/{year?}', 'filter_sales_order')->name('sales-order.filter');

        Route::get('/schedule-date-check-cleaner-exists', 'schedule_date_check_cleaner_exists')->name('schedule-date-check-cleaner-exists');
        
        Route::get('/delivery-date-check-driver-exists', 'delivery_date_check_driver_exists')->name('delivery-date-check-driver-exists');

        Route::get('/sales-order/log-report/{id}', 'log_report')->name('sales-order.log-report');

        // edit sales order
        Route::get('/sales-order/edit/{id}', 'edit_sales_order')->name('sales-order.edit');
        Route::post('/sales-order/update', 'update')->name('sales-order.update');
    });

    // settings
    Route::get('setting',[SettingController::class,'index'])->name('setting');
    Route::get('zone/create',[SettingController::class,'create'])->name('zone.create');
    Route::post('setting/store',[SettingController::class,'store'])->name('setting.store');
    Route::Post('/zone-settings', [SettingController::class,'zone_store'])->name('zonesettings.store');
    Route::Post('/payment-settings', [SettingController::class,'paymentMethodStore'])->name('payment.method.store');
    Route::Post('/payment-update', [SettingController::class,'paymentMethodUpdate'])->name('payment.method.update');
    Route::delete('/zone-settings/{id}', [SettingController::class, 'destroy'])->name('zone.delete');
    Route::get('/zone-settings/edit/{id}', [SettingController::class, 'fetch'])->name('zone.edit');
    Route::get('/payment-settings/edit/{id}', [SettingController::class, 'paymentEdit'])->name('payment.method.edit');

    Route::get('/payment/delete/{id}', [SettingController::class,'paymentDelete'])->name('payment.delete');
    Route::get('/zone/fetch/{id}', [SettingController::class,'fetch'])->name('zone.fetch');
    Route::post('/zone/update/{id}', [SettingController::class,'update'])->name('zonesettings.update');


    Route::delete('salutation/delete-data/{id}', [SettingController::class, 'salutation_destroy'])->name('salutation.delete');

    Route::post('/salutation/update/{id}', [SettingController::class,'salutation_update'])->name('salutation.update');

    // tax
    Route::post('/setting/tax-store', [SettingController::class,'tax_store'])->name('setting.tax-store');
    Route::get('/setting/get-tax-table-data', [SettingController::class,'get_tax_table_data'])->name('setting.get-tax-table-data');
    Route::get('/setting/tax-edit', [SettingController::class,'tax_edit'])->name('setting.tax-edit');
    Route::post('/setting/tax-update', [SettingController::class,'tax_update'])->name('setting.tax-update');
    Route::delete('/setting/tax/delete-data/{id}', [SettingController::class, 'tax_destroy'])->name('setting.tax.delete');
    Route::get('/setting/tax/set-default', [SettingController::class, 'tax_set_default'])->name('setting.tax.set-default');

    //source
    Route::post('source/store',[SettingController::class,'source_store'])->name('source.store');
    Route::delete('source/delete-data/{id}', [SettingController::class, 'source_destroy'])->name('source.delete');
    Route::post('/source/update/{id}', [SettingController::class,'source_update'])->name('source.update');

    // type of services
    Route::post('/setting/service-type/store', [SettingController::class, 'service_type_store'])->name('setting.service-type.store');
    Route::get('/setting/get-service-type-table-data', [SettingController::class, 'get_service_type_table_data'])->name('setting.get-service-type-table-data');
    Route::get('/setting/service-type/delete', [SettingController::class, 'service_type_delete'])->name('setting.service-type.delete');
    Route::get('/setting/service-type/edit', [SettingController::class, 'service_type_edit'])->name('setting.service-type.edit');
    Route::post('/setting/service-type/update', [SettingController::class, 'service_type_update'])->name('setting.service-type.update');

    // lead
    Route::get('lead/create',[LeadsController::class,'create'])->name('lead.create');
    Route::get('/search', [LeadsController::class, 'search']);
    Route::Post('/lead/search/customer', [LeadsController::class,'search'])->name('lead.customer.search');
    Route::post('/lead/customer/details', [LeadsController::class, 'getCustomerDetails'])->name('lead.customer.details');
    Route::post('/search/service', [LeadsController::class, 'searchSrvice'])->name('search.service');
    Route:: post('/get-service-address', [LeadsController::class,'getServiceAddress'])->name('get.service.address');
    Route:: post('/get-billing-address', [LeadsController::class,'getBillingAddress'])->name('get.billing.address');
    Route:: post('/billing-address', [LeadsController::class,'BillingAddress'])->name('get.address');

    Route::post('service/address/store', [LeadsController::class, 'storeServiceAddress'])->name('service.address.store');
    Route::post('billing/address/store', [LeadsController::class, 'storeBillingAddress'])->name('billing.address.store');
    Route::post('lead/store', [LeadsController::class, 'leadStore'])->name('lead.store');
    Route::post('lead/update', [LeadsController::class, 'leadUpdateStore'])->name('lead.update');
    Route:: post('/set-default-address',  [LeadsController::class,'setDefaultAddress'])->name('set.default.address');
    Route::get('createCustomer/create',[LeadsController::class,'createCustomer'])->name('createCustomer.create');
    Route::get('mail/confirm/{company_id}',[LeadsController::class,'confirmMail'])->name('lead.mail.confirm');
    // routes/web.php
    // Route::delete('/leads/{id}', [LeadsController::class,'destroy'])->name('lead.delete');
    Route::post('/leads/delete', [LeadsController::class,'destroy'])->name('lead.delete');
    Route::get('updateStatus/create', [LeadsController::class,'updateStatus'])->name('updateStatus.create');
    Route::get('lead/edit',[LeadsController::class,'edit'])->name('lead.edit');
    Route:: post('/updateStatus/store',  [LeadsController::class,'storeUpdateStatus'])->name('updateStatus.store');
    Route:: post('/lead/schedule',  [LeadsController::class,'leadSchedule'])->name('lead.schedule');
    Route:: post('/lead/price/info',  [LeadsController::class,'leadPriceInfo'])->name('lead.price.info');
    Route:: post('/lead/preview',  [LeadsController::class,'getlLeadPreview'])->name('get.lead.preview');
    Route:: post('/lead/sendmail',  [LeadsController::class,'sendEmail'])->name('lead.send.mail');
    // Route:: post('/lead/sendpaymentmail',  [PaymentController::class,'createPaymentLink'])->name('lead.send.payment.mail');
    Route:: post('/lead/invoice/sendmail',  [LeadsController::class,'sendInvoiceEmail'])->name('lead.send.invoice.mail');
    Route:: get('/lead/delete/priceinfo/{id}',  [LeadsController::class,'deletePriceInfo'])->name('lead.delete.priceinfo');

    Route::post('lead/get/emaildata',[LeadsController::class,'getEmailData'])->name('get.email.data');
    Route::get('lead/download/quotation',[LeadsController::class,'downloadLeadQuotation'])->name('download.quotation');
    Route::get('lead/view',[LeadsController::class,'view_lead'])->name('lead.view');
    Route::post('/leads/reject', [LeadsController::class,'reject'])->name('lead.reject');
    
    Route::post('/leads/confirm', [LeadsController::class,'confirm_lead'])->name('lead.confirm');
    Route::post('lead/store-confirm', [LeadsController::class, 'leadStore_confirm'])->name('lead.store-confirm');
    Route::post('lead/update-confirm', [LeadsController::class, 'leadUpdateStore_confirm'])->name('lead.update-confirm');
    
    Route::post('/lead/view-pdf', [LeadsController::class, 'lead_view_pdf'])->name('lead.view-pdf');
    Route::get('/lead/view-download-pdf', [LeadsController::class, 'view_download_pdf'])->name('lead.view-download-pdf');
    Route::get('/lead/log-report/{id}', [LeadsController::class, 'log_report'])->name('lead.log-report');

    Route::get('/lead/get-past-transaction-details', [LeadsController::class, 'get_past_transaction_details'])->name('lead.get-past-transaction-details');

    // draft lead save and update
    Route::controller(LeadsController::class)->group(function () {
        Route::post('/lead/draft/save-step-1', 'draft_save_step_1')->name('lead.draft.save-step-1');
        Route::post('/lead/draft/save-step-2', 'draft_save_step_2')->name('lead.draft.save-step-2');
        Route::post('/lead/draft/save-step-3', 'draft_save_step_3')->name('lead.draft.save-step-3');
        Route::post('/lead/draft/save-step-4', 'draft_save_step_4')->name('lead.draft.save-step-4');

        Route::post('/lead/draft/update-step-2', 'draft_update_step_2')->name('lead.draft.update-step-2');
    });

    Route::get('/get-hoildays-list', [HolidayController::class,'holiday_list'])->name('get-hoildays-list');

    // lead payment
    Route::controller(LeadPaymentController::class)->group(function () {
        Route::get('/lead/send-payment', 'send_payment')->name('lead.send-payment');        
        Route::post('/lead/process-payment', 'processPayment')->name('lead.process.payment');
        // Route::get('/lead/payment-response', 'payment_success_response')->name('lead.payment-success-response');
        Route::post('/lead/send-payment-offline', 'send_payment_offline')->name('lead.send-payment.offline');
        
        Route::post('/lead/send-payment-advice/confirm', 'confirm_payment_advice')->name('lead.send-payment-advice.confirm');

        Route::get('/lead/received-payment', 'received_payment')->name('lead.received-payment');
        Route::post('/lead/received-payment/store', 'received_payment_store')->name('lead.received-payment.store');
        Route::post('/lead/received-payment/send-email', 'received_payment_send_email')->name('lead.received-payment.send-email');

        // view download
        Route::post('/lead/payment-advice/view-pdf', 'payment_advice_view_pdf')->name('lead.payment-advice.view-pdf');
        Route::get('/lead/payment-advice/view-download-pdf', 'payment_advice_view_download_pdf')->name('lead.payment-advice.view-download-pdf');
    });

    // quotation payment
    Route::controller(QuotationPaymentController::class)->group(function () {
        Route::get('/quotation/send-payment', 'send_payment')->name('quotation.send-payment');
        Route::post('/quotation/process-payment', 'processPayment')->name('quotation.process.payment');
        // Route::get('/quotation/payment-response', 'payment_success_response')->name('quotation.payment-success-response');
        Route::post('/quotation/send-payment-offline', 'send_payment_offline')->name('quotation.send-payment.offline');
        
        Route::post('/quotation/send-payment-advice/confirm', 'confirm_payment_advice')->name('quotation.send-payment-advice.confirm');

        Route::get('/quotation/received-payment', 'received_payment')->name('quotation.received-payment');
        Route::post('/quotation/received-payment/store', 'received_payment_store')->name('quotation.received-payment.store');
        Route::post('/quotation/received-payment/send-email', 'received_payment_send_email')->name('quotation.received-payment.send-email');
    
        // view download
        Route::post('/quotation/payment-advice/view-pdf', 'payment_advice_view_pdf')->name('quotation.payment-advice.view-pdf');
        Route::get('/quotation/payment-advice/view-download-pdf', 'payment_advice_view_download_pdf')->name('quotation.payment-advice.view-download-pdf');
    });

    
    // quotation

    Route::get('quotation',[QuotationController::class,'index'])->name('quotation');
    Route::post('get/residential/quotation',[QuotationController::class,'getResidentialData'])->name('get.residential.data');
    Route::post('get/commercial/quotation',[QuotationController::class,'getCommercialData'])->name('get.commercial.data');
    Route::get('quotataion/create',[QuotationController::class,'create'])->name('quotation.create');
    Route::Post('quotataion/search/customer', [QuotationController::class,'search'])->name('quotataion.customer.search');
    Route::Post('quotataion/store', [QuotationController::class,'store'])->name('quotation.store');
    Route::Post('quotataion/update', [QuotationController::class,'update'])->name('quotation.update');
    Route::Post('quotataion/confrm', [QuotationController::class,'confirmQuotation'])->name('confirm.quotation');
    //Route:: post('/quotataion/preview',  [QuotationController::class,'getlQuotationPreview'])->name('get.quotataion.preview');
    Route::post('/quotataion/delete', [QuotationController::class,'delete'])->name('quotation.delete');

    Route::get('quotataion/view/{id}', [QuotationController::class,'view'])->name('quotation.view');

    Route::get('quotataion/view', [QuotationController::class,'view'])->name('quotation.view');
    Route::post('quotataion/preview',  [QuotationController::class,'getQuotationPreview'])->name('get.quotation.preview');
    Route::get('quotataion/edit', [QuotationController::class,'edit'])->name('quotation.edit');
    Route::post('quotataion/get/emaildata',[QuotationController::class,'getemailData'])->name('get.quotation.email');
    Route::post('/quotation/sendmail',  [QuotationController::class,'sendEmail'])->name('quotation.send.mail');
    Route::post('/search/residential/quotation', [QuotationController::class,'searchResidential'])->name('search.residential.quotation');
    Route::get('/quotation/download/{id}', [QuotationController::class,'download_quotation'])->name('quotation.download');
    Route::post('/quotation/change-status', [QuotationController::class,'change_status'])->name('quotation.change-status');
    Route::post('/quotataion/duplicate', [QuotationController::class,'duplicate'])->name('quotation.duplicate');
    Route::get('/quotataion/log-report/{id}', [QuotationController::class,'log_report'])->name('quotation.log-report');
    Route::post('/quotation/get-email-data', [QuotationController::class,'get_email_data'])->name('quotation.get-email-data');
    Route::post('/quotation/send-email', [QuotationController::class,'send_email'])->name('quotation.send-email');
    Route::post('/quotation/add-update/send-mail', [QuotationController::class,'add_update_send_email'])->name('quotation.add-update.send-mail');

    Route::get('/schedule', [ScheduleConteroller::class,'index'])->name('schedule');
    Route::get('/schedule/get-event-data', [ScheduleConteroller::class,'get_event_data'])->name('schedule.get-event-data');
    Route::post('schedule/create', [ScheduleConteroller::class,'create'])->name('schedule.create');
    Route::get('/Cleaner/edit/{id}', [ScheduleConteroller::class, 'fetch'])->name('cleaner.edit');
    Route::get('/schedule/edit/{id}', [ScheduleConteroller::class, 'getDataFromSchedule'])->name('schedule.edit');
    Route::post('/Cleaner/update/{id}', [ScheduleConteroller::class,'update'])->name('cleaner.update');
    Route::post('/schedule/update/{id}', [ScheduleConteroller::class,'scheduleUpdate'])->name('schedule.update');
    Route::post('/schedule/update', [ScheduleConteroller::class,'eventUpdate'])->name('schedule.event.update');
    Route::get('/schedule/cleaner-details/{cleaner_type}/{cleaner_id}', [ScheduleConteroller::class,'cleaner_details'])->name('schedule.cleaner-details');
    Route::get('/schedule/cleaner-upcoming-schedule-get-table-data', [ScheduleConteroller::class, 'cleaner_upcoming_schedule_get_table_data'])->name('schedule.cleaner-upcoming-schedule-get-table-data');
    Route::get('/schedule/cleaner-past-schedule-get-table-data', [ScheduleConteroller::class, 'cleaner_past_schedule_get_table_data'])->name('schedule.cleaner-past-schedule-get-table-data');
    Route::post('/schedule/cancel-job', [ScheduleConteroller::class, 'cancel_job'])->name('schedule.cancel-job');
    Route::post('/schedule/reset-job', [ScheduleConteroller::class, 'reset_job'])->name('schedule.reset-job');
    Route::post('/schedule/complete-job', [ScheduleConteroller::class, 'complete_job'])->name('schedule.complete-job');

    Route::get('emailtemplate',[EmailTemplateController::class,'index'])->name('emailtemplate.index');
    Route::get('qutation/template',[EmailTemplateController::class,'test'])->name('qutation.index');
    Route::get('emailtemplate/create',[EmailTemplateController::class,'create'])->name('emailtemplate.create');
    Route::post('emailtemplate/save',[EmailTemplateController::class,'store'])->name('emailtemplate.store');
    Route::post('emailtemplate/update',[EmailTemplateController::class,'update'])->name('emailtemplate.update');
    Route::get('emailtemplate/edit',[EmailTemplateController::class,'edit'])->name('template.edit');
    Route::get('emailtemplate/delete/{id}',[EmailTemplateController::class,'destroy'])->name('template.delete');

    // Terms and Condition
    Route::get('/terms/condition',[TermConditionController::class,'index'])->name('term.condition.index');
    Route::post('/terms/condition/store',[TermConditionController::class,'store'])->name('term.condition.store');
    Route::get('/terms/condition/edit',[TermConditionController::class,'edit'])->name('term.condition.edit');
    Route::post('/terms/condition/update',[TermConditionController::class,'update'])->name('term.condition.update');
    Route::get('/terms/condition/delete',[TermConditionController::class,'delete'])->name('term.condition.delete');
    Route::get('/term-condition/get-table-data', [TermConditionController::class,'get_table_data'])->name('term-condition.get-table-data');

    // payment
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/payment', 'index')->name('payment.index');
        Route::get('/payment/received-payment/{id}', 'received_payment')->name('payment.recieved-payment');

        Route::get('/all-payments', 'all_payments')->name('all-payments');
        Route::get('/all-payments/get-table-data', 'all_payments_get_table_data')->name('all-payments.get-table-data');
        Route::get('/all-payments/view-payment-proof',  'all_payments_view_payment_proof')->name('all-payments.view-payment-proof');
    });

    // payment history
    Route::controller(PaymentHistoryController::class)->group(function () {
        Route::get('/payment-history', 'index')->name('payment-history.index');
        Route::post('/payment-history/store', 'store')->name('payment-history.store');
        Route::get('/payment-history/get-table-data', 'get_table_data')->name('payment-history.get-table-data');
        Route::get('/payment-history/show/{id}', 'show')->name('payment-history.show');
        Route::get('/payment-history/view-get-table-data', 'view_get_table_data')->name('payment-history.view-get-table-data');
        Route::post('/payment-history/reject-payment', 'reject_payment')->name('payment-history.reject-payment');
        Route::post('/payment-history/send-email', 'send_email')->name('payment-history.send-email');    
        Route::get('/payment-history/log-report/{id}', 'log_report')->name('payment-history.log-report');   
    });

    // Payment Approval
    Route::controller(PaymentApprovalController::class)->group(function () {
        Route::get('/payment-approve', 'index')->name('payment-approve.index');
        Route::get('/payment-approve/get-table-data', 'get_table_data')->name('payment-approve.get-table-data');
        Route::get('/payment-approve/get-payment-details', 'get_payment_details')->name('payment-approve.get-payment-details');
        Route::post('/payment-approve/approve-payment', 'approve_payment')->name('payment-approve.approve-payment');
        Route::get('/payment-approve/get-quotation-data', 'get_quotation_data')->name('payment-approve.get-quotation-data');
        Route::post('/payment-approve/send-email', 'send_email')->name('payment-approve.send-email');
        Route::post('/payment-approve/reject-payment', 'reject_payment')->name('payment-approve.reject-payment');
    });

    // finance
    Route::controller(FinanceController::class)->group(function () {
        Route::get('/finance', 'index')->name('finance');
        Route::get('/finance/get-table-data', 'get_table_data')->name('finance.get-table-data');
        Route::get('/finance/get-table-data-by-company', 'get_table_data_by_company')->name('finance.get-table-data-by-company');
        Route::get('/finance/view-invoice/{id}', 'view_invoice')->name('finance.view-invoice');
        Route::get('/finance/download-invoice/{id}', 'download_invoice')->name('finance.download-invoice');
        Route::get('/finance/edit/{id}', 'edit')->name('finance.edit');
        Route::POST('/finance/edit/preview', 'preview')->name('finance.edit.preview');

        Route::post('/finance/update-invoice/send-mail', 'update_invoice_send_mail')->name('finance.update-invoice.send-mail');
        Route::post('/finance/update-invoice', 'update_invoice')->name('finance.update-invoice');

        Route::get('/finance/make-payment/{id}', 'make_payment')->name('finance.make-payment');
        Route::post('/finance/make-payment/store', 'store_make_payment')->name('finance.make-payment.store');
        Route::post('/finance/make-payment/send-email', 'store_make_payment_send_email')->name('finance.make-payment.send-email');
        Route::get('/finance/log-report/{invoice_no}', 'log_report')->name('finance.log-report');
        
        Route::post('/finance/get-email-data', 'get_email_data')->name('finance.get-email-data');
        Route::post('/finance/send-email', 'send_email')->name('finance.send-email');
    });

    // quotation payment
    Route::controller(QuotationPaymentController::class)->group(function () {
        Route::get('/quotation/payment/{id}', 'quotation_payment_preview')->name('quotation.payment');
        Route::post('/quotation/online-payment', 'online_payment')->name('quotation.online-payment');
        Route::get('/quotation/online-payment-success-response', 'online_payment_success_response')->name('quotation.online-payment-success-response');
        Route::post('/quotation/offline-payment', 'offline_payment')->name('quotation.offline-payment');
    });

    // report
    Route::controller(ReportController::class)->group(function () {
        Route::get('/report', 'index')->name('report');
        Route::get('/report/invoice-table-data', 'report_invoice_table_data')->name('report.invoice-table-data');
        Route::get('/report/sales-order-table-data', 'report_sales_order_table_data')->name('report.sales-order-table-data');
        Route::get('/report/job-order-details-table-data', 'job_order_details_table_data')->name('report.job-order-details-table-data');
        Route::get('/report/log-details-table-data', 'log_details_table_data')->name('report.log-details-table-data');
        Route::get('/report/reminder-log-report-table-data', 'reminder_log_report_table_data')->name('report.reminder-log-report-table-data');
    });

});
require __DIR__.'/auth.php';
