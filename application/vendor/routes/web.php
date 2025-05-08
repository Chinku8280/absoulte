<?php

use App\Http\Controllers\BranchController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrmController;
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
use App\Http\Controllers\TermConditionController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $heading_name  = 'Dashboard';
    return view('dashboard.dashboard',compact('heading_name'));
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::delete('/delete-residential/{id}', [CrmController::class,'deleteResidentialData'])->name('delete-residential');
    Route::delete('/delete-commercial/{id}', [CrmController::class,'deleteCommercialData'])->name('delete-commercial');

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
    // ROUTES FOR SERVICE

    Route::get('service/create',[ServiceController::class,'create'])->name('service.create');
    Route::post('/service/store', [ServiceController::class,'store'])->name('service.store');
    Route::get('/service/data', [ServiceController::class, 'fetchData'])->name('service.data');
    Route::delete('/delete-service/{id}', [ServiceController::class,'delete'])->name('delete-service');
    Route::get('/service/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
    Route::get('quotation',[QuotationController::class,'index'])->name('quotation');
    Route::get('salesOrder',[SalesOrderController::class,'index'])->name('salesOrder');
    Route::get('report',[ReportController::class,'index'])->name('report');
    Route::get('setting',[SettingController::class,'index'])->name('setting');
    Route::get('lead/create',[LeadsController::class,'create'])->name('lead.create');
    Route::get('/search', [LeadsController::class, 'search']);
    Route::Post('/lead/search/customer', [LeadsController::class,'search'])->name('lead.customer.search');
    Route::post('/lead/customer/details', [LeadsController::class, 'getCustomerDetails'])->name('lead.customer.details');
    Route::post('/search/service', [LeadsController::class, 'searchSrvice'])->name('search.service');
    Route:: post('/get-service-address', [LeadsController::class,'getServiceAddress'])->name('get.service.address');
    Route:: post('/get-billing-address', [LeadsController::class,'getBillingAddress'])->name('get.billing.address');
    
    Route::post('service/address/store', [LeadsController::class, 'storeServiceAddress'])->name('service.address.store');
    Route::post('billing/address/store', [LeadsController::class, 'storeBillingAddress'])->name('billing.address.store');
    Route::post('lead/store', [LeadsController::class, 'leadStore'])->name('lead.store');
    Route:: post('/set-default-address',  [LeadsController::class,'setDefaultAddress'])->name('set.default.address');
    Route::get('createCustomer/create',[LeadsController::class,'createCustomer'])->name('createCustomer.create');
    // routes/web.php

    Route::delete('/leads/{id}', [LeadsController::class,'destroy'])->name('lead.delete');
    Route::get('updateStatus/create', [LeadsController::class,'updateStatus'])->name('updateStatus.create');
    Route::get('lead/edit',[LeadsController::class,'edit'])->name('lead.edit');
    Route:: post('/updateStatus/store',  [LeadsController::class,'storeUpdateStatus'])->name('updateStatus.store');
    Route:: post('/lead/schedule',  [LeadsController::class,'leadSchedule'])->name('lead.schedule');
    Route:: post('/lead/price',  [LeadsController::class,'leadPrice'])->name('lead.price');
    Route:: post('/lead/preview',  [LeadsController::class,'getlLeadPreview'])->name('get.lead.preview');
    Route:: post('/lead/sendmail',  [LeadsController::class,'sendEmail'])->name('lead.send.mail');
    Route:: get('/lead/delete/priceinfo/{id}',  [LeadsController::class,'deletePriceInfo'])->name('lead.delete.priceinfo');


    Route::get('quotataion/create',[QuotationController::class,'create'])->name('quotation.create');
    Route::Post('quotataion/search/customer', [QuotationController::class,'search'])->name('quotataion.customer.search');
    Route::Post('quotataion/store', [QuotationController::class,'store'])->name('quotataion.store');
    Route::get('quotataion/delete/{id}', [QuotationController::class,'delete'])->name('quotation.delete');
    Route::get('quotataion/view/{id}', [QuotationController::class,'view'])->name('quotation.view');

    Route::get('schedule/index', [ScheduleConteroller::class,'index'])->name('schedule.index');

    Route::post('schedule/create', [ScheduleConteroller::class,'create'])->name('schedule.create');

    Route::get('emailtemplate',[EmailTemplateController::class,'index'])->name('emailtemplate.index');
    Route::get('qutation/template',[EmailTemplateController::class,'test'])->name('qutation.index');
    Route::get('emailtemplate/create',[EmailTemplateController::class,'create'])->name('emailtemplate.create');
    Route::post('emailtemplate/save',[EmailTemplateController::class,'store'])->name('emailtemplate.store');

    Route::get('terms/condition',[TermConditionController::class,'index'])->name('term.condition.index');
    Route::post('terms/condition/store',[TermConditionController::class,'store'])->name('term.condition.store');
    Route::get('terms/condition/delete/{id}',[TermConditionController::class,'delete'])->name('term.condition.delete');
});

require __DIR__.'/auth.php';