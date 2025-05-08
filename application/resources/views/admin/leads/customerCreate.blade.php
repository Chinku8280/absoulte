<div class="modal-header">
    <h5 class="modal-title">Add Customer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label">Select Customer Type</label>
        <select class="form-select" id="myselection">
            <option selected="">Select Option</option>
            <option value="One">Residential</option>
            <option value="Two">Commercial</option>
        </select>
    </div>

    <form id="residential_customer_form" method="POST">
        @csrf
        <input type="hidden" value="residential_customer_type" name="customer_type">
        <div id="showOne" class="row myDiv" style="display: none;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Name<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" name="saluation"
                                style="padding: 0.4375rem 1rem 0.4375rem 0.75rem;">
                                {{-- <option value="1">Mr</option>
                                <option value="2">Miss</option> --}}
                                @foreach ($salutation_data as $item)
                                    <option value="{{$item->id}}">{{$item->salutation_name}}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control w-50" name="customer_name" id="res_customer_name"
                                placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Contact number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_no" placeholder="Enter Number" id="res_contact_no">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email" id="res_email">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Created By<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="created_by">
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>
                            {{-- <option value="34">Super Admin</option> --}}
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Language Spoken</label>
                        <select type="text" class="form-select" id="select-countries" name="language_spoken">
                            @foreach ($spoken_language as $list)
                                <option value="{{ $list->id }}">{{ $list->language_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <div class="form-label">Type of Services<span class="text-danger">*</span></div>
                        <div class="dropdown" data-control="checkbox-dropdown">
                            <label class="dropdown-label">Select</label>
                            <div class="dropdown-list">
                                <a href="#" data-toggle="check-all"
                                    class="dropdown-option border-bottom text-blue">Check All</a>
                                <label class="dropdown-option">
                                    <input type="checkbox" name="cleaning_type[]" value="Floor Cleaning" />
                                    Floor Cleaning
                                </label>
                                <label class="dropdown-option">
                                    <input type="checkbox" name="cleaning_type[]" value="Home Cleaning" />
                                    Home Cleaning
                                </label>
                                <label class="dropdown-option">
                                    <input type="checkbox" name="cleaning_type[]" value="Office Cleaning" />
                                    Office Cleaning
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Status<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="status">
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                            <option value="0">Block</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Remark</label>
                        {{-- <input type="text" class="form-control" name="customer_remark"
                            placeholder="Enter Remarks"> --}}

                        <textarea class="form-control" name="customer_remark" placeholder="Enter Remarks" cols="30" rows="5"></textarea>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-6">
                        <label class="form-label">Lead Source</label>
                        <select name="lead_source" id="lead_source_tab1" class="form-select">
                            @foreach ($source_data as $source_tab1)
                                <option value="{{$source_tab1->source_name}}" {{ $source_tab1->source_name == 'WhatsApp' ? 'selected' : '' }}>{{$source_tab1->source_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-1" class="nav-link active" data-bs-toggle="tab"
                                        aria-selected="true" role="tab">Additional Contact
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-2" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                        tabindex="-1" role="tab">Additional Info</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-3" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                        tabindex="-1" role="tab">Service Address</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-4" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                        tabindex="-1" role="tab">Billing Address </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="tabs-1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Contact Name</label>
                                                <input type="text" class="form-control" name="res_additional_contact_name[]"
                                                    placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="text" class="form-control"
                                                    name="res_additional_mobile_number[]" placeholder="Enter Number">
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control"
                                                    name="res_additional_email[]" placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="form-label" style="visibility: hidden;">Add</label>
                                            <a href="#" class="btn btn-primary add-row-contact" onclick="res_add_row_contact(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0"
                                                    width="24" height="24" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M12 5l0 14"></path>
                                                    <path d="M5 12l14 0"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="res-additional-rows"></div>
                                </div>

                                <div class="tab-pane" id="tabs-2" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Credit limit</label>
                                                        <input type="number" class="form-control"
                                                            name="credit_limit" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Terms</label>
                                                        <select class="form-select" name="info_payment_terms">
                                                            @foreach ($payment_terms as $list)
                                                                <option value="{{ $list->id }}">
                                                                    {{ $list->payment_terms }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-3" role="tabpanel">
                                    <button type="button" class="btn btn-blue" id="rowAdder">+ Add
                                        Address</button>

                                    <div id="res_newinput">
                                        <div class="row my-3 service_addr_group">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text" placeholder="Enter Name"
                                                        name="person_incharge_name[]" class="form-control person_incharge_name" id="res_serv_addr_pi_name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" placeholder="Enter Number" name="phone_no[]"
                                                        class="form-control phone_no" id="res_serv_addr_pi_no">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email Id</label>
                                                    <input type="text" placeholder="Enter Email" name="email_id[]"
                                                        class="form-control email_id" id="res_serv_addr_pi_email">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" placeholder="Enter Code"
                                                        id="postal_code_service" name="postal_code_service[]"
                                                        class="form-control postal-code"
                                                        onchange="handlePostalCodeLookup(this)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Zone</label>
                                                    <input type="text" placeholder="Enter Zone" id="zone_service"
                                                        name="zone_service[]" class="form-control zone">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" placeholder="Enter Unit No."
                                                        name="unit_number_service[]" class="form-control unit_number_service">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <textarea type="text" placeholder="Enter Address"
                                                        id="address_service" name="address_service[]"
                                                        class="form-control address"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Territory</label>
                                                    <input type="text" placeholder="Enter Territory."
                                                        name="territory[]" id="territory" class="form-control territory">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-4" role="tabpanel">

                                    <div class="row">
                                        <div class="col">
                                            <button type="button" class="btn btn-blue add-row">+ Add Address</button>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <input type="checkbox" name="same_address_res" id="same_address_res">
                                                <label for="">Same as Service address</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="res_newInput_bill">
                                        <div class="row my-3 billing_addr_group">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text" placeholder="Enter Name"
                                                        name="person_incharge_name_bill[]" class="form-control" id="res_bill_addr_pi_name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" placeholder="Enter Number"
                                                        name="phone_no_bill[]" class="form-control" id="res_bill_addr_pi_no">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email</label>
                                                    <input type="email" placeholder="Enter Email"
                                                        name="email_bill[]" class="form-control" id="res_bill_addr_email">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" placeholder="Enter Code"
                                                        id="postal_code_service" name="postal_code_bill[]"
                                                        class="form-control postal-code"
                                                        onchange="handlePostalCode(this)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" placeholder="Enter Unit No."
                                                        name="unit_number_bill[]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <textarea type="text" placeholder="Enter Address"
                                                        id="address_service" name="address_bill[]"
                                                        class="form-control bill_address"></textarea>
                                                </div>
                                            </div>                                          

                                            <input type="hidden" name="zone_bill[]" class="zone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="residential_customer_form_btn">Save
                    changes</button>
            </div>
        </div>
    </form>

    <form id="commercial_customer_form" method="POST">
        @csrf
        <input type="hidden" value="commercial_customer_type" name="customer_type">
        <div id="showTwo" class="myDiv" style="display: none;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Name<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" name="saluation"
                                style="padding: 0.4375rem 1rem 0.4375rem 0.75rem;">
                                {{-- <option value="1">Mr</option>
                                <option value="2">Miss</option> --}}
                                @foreach ($salutation_data as $item)
                                    <option value="{{$item->id}}">{{$item->salutation_name}}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control w-50" name="customer_name"
                                placeholder="Enter Name" id="com_customer_name">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">UEN</label>
                        <input type="text" class="form-control" name="uen" placeholder="Enter UEN Number">
                    </div>
                </div>
                {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Group Company Name</label>
                        <input type="text" class="form-control" name="group_company_name"
                            placeholder="Group Company Name">
                    </div>
                </div> --}}
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Company Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="individual_company_name"
                            placeholder="Company Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Contact number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mobile_number" placeholder="Enter Number" id="com_contact_no">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email" id="com_email">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Created By<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="created_by">
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Language Spoken</label>
                        <select type="text" class="form-select" id="spoken_language" name="spoken_language">
                            @foreach ($spoken_language as $list)
                                <option value="{{ $list->id }}">{{ $list->language_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <div class="form-label">Type of Services <span class="text-danger">*</span></div>
                        <div class="dropdown" data-control="checkbox-dropdown">
                            <label class="dropdown-label">Select</label>
                            <div class="dropdown-list">
                                <a href="#" data-toggle="check-all"
                                    class="dropdown-option border-bottom text-blue">Check All</a>
                                <label class="dropdown-option">
                                    <input type="checkbox" name="cleaning_type[]" value="Floor Cleaning" />
                                    Floor Cleaning
                                </label>
                                <label class="dropdown-option">
                                    <input type="checkbox" name="cleaning_type[]" value="Home Cleaning" />
                                    Home Cleaning
                                </label>
                                <label class="dropdown-option">
                                    <input type="checkbox" name="cleaning_type[]" value="Office Cleaning" />
                                    Office Cleaning
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Status<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="status">
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                            <option value="0">Block</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-6">
                        <label class="form-label">Lead Source</label>
                        <select name="lead_source" id="lead_source_tab2" class="form-select">
                            @foreach ($source_data as $source_tab2)
                                <option value="{{$source_tab2->source_name}}" {{ $source_tab2->source_name == 'WhatsApp' ? 'selected' : '' }}>{{$source_tab2->source_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Remark</label>
                        {{-- <input type="text" class="form-control" name="customer_remark"
                            placeholder="Enter Remarks"> --}}

                        <textarea class="form-control" name="customer_remark" placeholder="Enter Remarks" cols="30" rows="5"></textarea>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-00" class="nav-link active" data-bs-toggle="tab"
                                        aria-selected="true" role="tab">Company Info
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-01" class="nav-link" data-bs-toggle="tab" aria-selected="true"
                                        role="tab">Additional Contact
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-02" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                        tabindex="-1" role="tab">Additional Info</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-03" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                        tabindex="-1" role="tab">Service Address</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-04" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                        tabindex="-1" role="tab">Billing Address </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="tabs-00" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Contact Name </label>
                                                <input type="text" class="form-control" name="contact_name" id="comp_info_contact_name"
                                                    placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="text" class="form-control" name="company_mobile_no" id="comp_info_company_mobile_no"
                                                    placeholder="Enter Number">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Fax Number</label>
                                                <input type="text" class="form-control" name="fax_number"
                                                    placeholder="Enter Number">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" name="company_email" id="comp_info_company_email"
                                                    placeholder="Enter Email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-01" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Contact Name</label>
                                                <input type="text" class="form-control" name="com_additional_contact_name[]"
                                                    placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="text" class="form-control" name="com_additional_mobile_no[]"
                                                    placeholder="Enter Number">
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control"
                                                    name="com_additional_email[]" placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="form-label" style="visibility: hidden;">Add</label>
                                            <a href="#" class="btn btn-primary add-row-contact2" onclick="com_add_row_contact(this)">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0"
                                                    width="24" height="24" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M12 5l0 14"></path>
                                                    <path d="M5 12l14 0"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div id="com-additional-rows"></div>
                                </div>
                                <div class="tab-pane" id="tabs-02" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Credit limit</label>
                                                        <input type="number" class="form-control"
                                                            name="credit_limit" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Terms</label>
                                                        <select class="form-select" name="info_payment_terms">
                                                            @foreach ($payment_terms as $list)
                                                                <option value="{{ $list->id }}">
                                                                    {{ $list->payment_terms }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-03" role="tabpanel">
                                    <button type="button" class="btn btn-blue" id="rowAdder-2">+ Add
                                        Address</button>

                                    <div id="com_newinput">
                                        <div class="row my-3 service_addr_group">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text" placeholder="Enter Name"
                                                        name="c_person_incharge_name[]" class="form-control c_person_incharge_name" id="com_serv_addr_pi_name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" placeholder="Enter Number"
                                                        name="c_contact_no[]" class="form-control c_contact_no" required="" id="com_serv_addr_pi_no">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email Id</label>
                                                    <input type="text" placeholder="Enter Email" name="c_email_id[]"
                                                        class="form-control c_email_id" required="" id="com_serv_addr_pi_email">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" placeholder="Enter Code" name="c_postal_code[]"
                                                        class="form-control postal-code" id="postal_code_service"
                                                        onchange="handlePostalCodeLookup(this)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Zone</label>
                                                    <input type="text" placeholder="Enter Zone" name="c_zone[]"
                                                        class="form-control zone" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" placeholder="Enter Unit No." name="c_unit_no[]"
                                                        class="form-control c_unit_no" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <input type="text" placeholder="Enter Address" name="c_address[]"
                                                        class="form-control address">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Territory</label>
                                                    <input type="text" placeholder="Enter Territory."
                                                        name="c_territory[]" class="form-control territory">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-04" role="tabpanel">

                                    <div class="row">
                                        <div class="col">
                                            <button type="button" class="btn btn-blue add-row-2">+ Add Address</button>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <input type="checkbox" name="same_address_com" id="same_address_com">
                                                <label for="">Same as Service address</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="com_newInput_bill">
                                        <div class="row my-3 billing_addr_group">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text" placeholder="Enter Name"
                                                        name="c_person_incharge_name_bil[]" class="form-control" id="com_bill_addr_pi_name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" placeholder="Enter Number"
                                                        name="c_contact_no_bil[]" class="form-control" id="com_bill_addr_pi_no">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email</label>
                                                    <input type="email" placeholder="Enter Email"
                                                        name="c_email_bil[]" class="form-control" id="com_bill_addr_pi_email">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" placeholder="Enter Code"
                                                        id="postal_code_service" name="c_postal_code_bil[]"
                                                        class="form-control postal-code"
                                                        onchange="handlePostalCode(this)">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" placeholder="Enter Unit No."
                                                        name="c_unit_no_bil[]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <textarea type="text" placeholder="Enter Address"
                                                        id="address_service" name="c_address_bil[]"
                                                        class="form-control bill_address"></textarea>
                                                </div>
                                            </div>                                            

                                            <input type="hidden" name="c_zone_bill[]" class="zone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="commercial_customer_form_btn">Save
                    changes</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#myselection').on('change', function() {
            var demovalue = $(this).val();
            $("div.myDiv").hide();
            $("#show" + demovalue).show();
        });
    });

    (function($) {
        var CheckboxDropdown = function(el) {
            var _this = this;
            this.isOpen = false;
            this.areAllChecked = false;
            this.$el = $(el);
            this.$label = this.$el.find('.dropdown-label');
            this.$checkAll = this.$el.find('[data-toggle="check-all"]').first();
            this.$inputs = this.$el.find('[type="checkbox"]');

            this.onCheckBox();

            this.$label.on('click', function(e) {
                e.preventDefault();
                _this.toggleOpen();
            });

            this.$checkAll.on('click', function(e) {
                e.preventDefault();
                _this.onCheckAll();
            });

            this.$inputs.on('change', function(e) {
                _this.onCheckBox();
            });
        };

        CheckboxDropdown.prototype.onCheckBox = function() {
            this.updateStatus();
        };

        CheckboxDropdown.prototype.updateStatus = function() {
            var checked = this.$el.find(':checked');

            this.areAllChecked = false;
            this.$checkAll.html('Check All');

            if (checked.length <= 0) {
                this.$label.html('Select Options');
            } else if (checked.length === 1) {
                this.$label.html(checked.parent('label').text());
            } else if (checked.length === this.$inputs.length) {
                this.$label.html('All Selected');
                this.areAllChecked = true;
                this.$checkAll.html('Uncheck All');
            } else {
                this.$label.html(checked.length + ' Selected');
            }
        };

        CheckboxDropdown.prototype.onCheckAll = function(checkAll) {
            if (!this.areAllChecked || checkAll) {
                this.areAllChecked = true;
                this.$checkAll.html('Uncheck All');
                this.$inputs.prop('checked', true);
            } else {
                this.areAllChecked = false;
                this.$checkAll.html('Check All');
                this.$inputs.prop('checked', false);
            }

            this.updateStatus();
        };

        CheckboxDropdown.prototype.toggleOpen = function(forceOpen) {
            var _this = this;

            if (!this.isOpen || forceOpen) {
                this.isOpen = true;
                this.$el.addClass('on');
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('[data-control]').length) {
                        _this.toggleOpen();
                    }
                });
            } else {
                this.isOpen = false;
                this.$el.removeClass('on');
                $(document).off('click');
            }
        };

        var checkboxesDropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');
        for (var i = 0, length = checkboxesDropdowns.length; i < length; i++) {
            new CheckboxDropdown(checkboxesDropdowns[i]);
        }
    })(jQuery);

    $(document).ready(function() {
        $("select").on('change', function() {
            $(this).find("option:selected").each(function() {
                var geeks = $(this).attr("value");
                if (geeks) {
                    $(".GFG").not("." + geeks).hide();
                    $("." + geeks).show();
                } else {
                    $(".GFG").hide();
                }

            });
        }).change();
    });
</script>

<script type="text/javascript">

    function handlePostalCodeLookup(element) {
        var get_postal = element.value;

        $.ajax({
            // url: "https://developers.onemap.sg/commonapi/search?searchVal=" + get_postal +
            //     "&returnGeom=Y&getAddrDetails=Y",

            url: "https://www.onemap.gov.sg/api/common/elastic/search?searchVal="+get_postal+"&returnGeom=Y&getAddrDetails=Y&pageNum=1",

            success: function(JSON) {

                var address = JSON.results[0].ADDRESS;
                var searchVal = JSON.results[0].ADDRESS || "";
                var parts = searchVal.split(" ");
                var territory = parts[parts.length - 2] || "";

                // $("#address_service").val(address);
                // $("#territory").val(territory);

                $(element).parents('.service_addr_group').find('.address').val(address);
                $(element).parents('.service_addr_group').find('.territory').val(territory);

                var postalCode = get_postal.substring(0, 2);
                $.ajax({

                    url: "{{ route('get.zone.name', ['postalCode' => '__postalCode__']) }}"
                        .replace('__postalCode__', postalCode),
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        var zoneName = response.zone_name;
                        // $("#zone_service").val(zoneName);
                        $(element).parents('.service_addr_group').find('.zone').val(zoneName);
                    },
                    error: function(error) {
                        console.error('Error fetching zone: ', error);
                    }
                });
            }
        });
    }

    function handlePostalCode(element) {

        // var get_postal = $("#postal_code_service").val();
        var get_postal = element.value;

        $.ajax({
            // url: "https://developers.onemap.sg/commonapi/search?searchVal=" + get_postal +
            //     "&returnGeom=Y&getAddrDetails=Y",

            url: "https://www.onemap.gov.sg/api/common/elastic/search?searchVal="+get_postal+"&returnGeom=Y&getAddrDetails=Y&pageNum=1",

            success: function(JSON) {
                console.log(JSON);
                // $(".bill_address").val(JSON.results[0].ADDRESS);

                $(element).parents('.billing_addr_group').find('.bill_address').val(JSON.results[0].ADDRESS);

                var postalCode = get_postal.substring(0, 2);
                $.ajax({

                    url: "{{ route('get.zone.name', ['postalCode' => '__postalCode__']) }}"
                        .replace('__postalCode__', postalCode),
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        var zoneName = response.zone_name;
                        $(element).parents('.billing_addr_group').find('.zone').val(zoneName);
                    },
                    error: function(error) {
                        console.error('Error fetching zone: ', error);
                    }
                });
            }
        });
    }

    $(function() {

        // Add row button click event
        $('#rowAdder').click(function() {
            var newRowAdd = `
                <div class="row my-3 service_addr_group" id="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Person Incharge Name</label>
                        <input type="text" placeholder="Enter Name" name="person_incharge_name[]" class="form-control person_incharge_name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Contact No</label>
                        <input type="text" placeholder="Enter Number" name="phone_no[]" class="form-control phone_no">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Email Id</label>
                        <input type="text" placeholder="Enter Email" name="email_id[]" class="form-control email_id">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Postal Code</label>
                        <input type="text" placeholder="Enter Code" id="postal_code_service" name="postal_code_service[]" class="form-control postal-code" onchange="handlePostalCodeLookup(this)">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Zone</label>
                        <input type="text" placeholder="Enter Zone" name="zone_service[]" class="form-control zone">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Unit No</label>
                        <input type="text" placeholder="Enter Unit No." name="unit_number_service[]" class="form-control unit_number_service" required="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                        <label for="name">Address</label>
                        <input type="text" id="address_service" placeholder="Enter Address" name="address_service[]" class="form-control address">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="name">Territory</label>
                            <input type="text" placeholder="Enter Territory." name="territory[]" class="form-control territory">
                        </div>
                    </div>
                    <div class="col-md-1" style="display: flex; align-items: center;">
                        <button type="button" class="btn btn-danger deleteRow">-</button>
                    </div>
                </div>`;

            $('#res_newinput').append(newRowAdd);
        });

        // Delete row button click event
        $('body').on('click', '.deleteRow', function() {
            $(this).parents('#row').remove();
        });

        $("#rowAdder-2").click(function() {
            newRowAdd =
                '<div class="row my-3 service_addr_group" id="row">  <div class="col-md-4">' +
                '<div class="form-group mb-3">' +
                ' <label for="name">Person Incharge Name</label><input type="text" placeholder="Enter Name" name="c_person_incharge_name[]" class="form-control c_person_incharge_name" required=""></div></div>' +
                '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="c_contact_no[]" class="form-control c_contact_no" required=""> </div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="c_email_id[]" class="form-control c_email_id" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="c_postal_code[]" class="form-control postal-code" onchange="handlePostalCodeLookup(this)"></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="c_zone[]" class="form-control zone"></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="c_unit_no[]" class="form-control c_unit_no"> </div> </div>' +
                '<div class="col-md-12"><div class="form-group mb-3"><label for="name">Address</label><input type="text" id placeholder="Enter Address" name="c_address[]" class="form-control address"> </div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Territory</label><input type="text" placeholder="Enter Territory." name="c_territory[]" class="form-control territory"> </div> </div>' +
                '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow-2">-</button></div></div>';
            // console.log(newRowAdd);
            $('#com_newinput').append(newRowAdd);
        });

        $('.add-row').click(function() {
            var template = `<div class="row my-3 billing_addr_group" id="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Person Incharge Name</label>
                                        <input type="text" placeholder="Enter Name" name="person_incharge_name_bill[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Contact No</label>
                                        <input type="text" placeholder="Enter Number" name="phone_no_bill[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Email</label>
                                        <input type="email" placeholder="Enter Email" name="email_bill[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Postal Code</label>
                                        <input type="text" placeholder="Enter Code" name="postal_code_bill[]" class="form-control postal-code" onchange="handlePostalCode(this)">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                    <label for="name">Unit No</label>
                                    <input type="text" placeholder="Enter Unit No." name="unit_number_bill[]" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                    <label for="name">Address</label>
                                    <input type="text" placeholder="Enter Address" name="address_bill[]" class="form-control bill_address">
                                    </div>
                                </div>                                 
                                <input type="hidden" name="zone_bill[]" class="zone">
                                <div class="col-md-1" style="display: flex; align-items: center;">
                                    <button type="button" class="btn btn-danger deleteRow">-</button>
                                </div>
                            </div>`;
            $('#res_newInput_bill').append(template);
        });

        $("body").on("click", "#DeleteRow-2", function() {
            $(this).parents("#row").remove();
        });

    });

    $(document).ready(function() {
    
        $('.add-row-2').click(function() {
            var template = `<div class="row my-3 billing_addr_group" id="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Person Incharge Name</label>
                                        <input type="text" placeholder="Enter Name" name="c_person_incharge_name_bil[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Contact No</label>
                                        <input type="text" placeholder="Enter Number" name="c_contact_no_bil[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Email</label>
                                        <input type="email" placeholder="Enter Email" name="c_email_bil[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Postal Code</label>
                                        <input type="text" placeholder="Enter Code" name="c_postal_code_bil[]" id="postal_code_service" class="form-control postal-code" onchange="handlePostalCode(this)">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Unit No</label>
                                        <input type="text" placeholder="Enter Unit No." name="c_unit_no_bil[]" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="name">Address</label>
                                        <input type="text" placeholder="Enter Address" name="c_address_bil[]" class="form-control bill_address">
                                    </div>
                                </div>              
                                <input type="hidden" name="c_zone_bill[]" class="zone">
                                <div class="col-md-1" style="display: flex; align-items: center;">
                                    <button type="button" class="btn btn-danger deleteRow">-</button>
                                </div>
                            </div>`;

            $('#com_newInput_bill').append(template);
        });

        // Remove row
        $('body').on('click', '.remove-row', function() {
            $(this).closest('.row').remove();
        });
    });

    function res_add_row_contact(el)
    {
        var newRow = `<div class="row mt-3">
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Contact Name</label>
                                <input type="text" class="form-control" name="res_additional_contact_name[]" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="res_additional_mobile_number[]" placeholder="Enter Number">
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control"
                                    name="res_additional_email[]" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" style="visibility: hidden;">Add</label>
                            <a href="#" class="btn btn-danger remove-row">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M18 6l-6 6 6 6"></path>
                                    <path d="M6 18l6 -6 -6 -6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>`;

        $('#res-additional-rows').append(newRow);
    }

    function com_add_row_contact(el)
    {
        var newRow = `<div class="row mt-3">
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Contact Name</label>
                                <input type="text" class="form-control" name="com_additional_contact_name[]" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" name="com_additional_mobile_no[]" placeholder="Enter Number">
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control"
                                    name="com_additional_email[]" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" style="visibility: hidden;">Add</label>
                            <a href="#" class="btn btn-danger remove-row">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M18 6l-6 6 6 6"></path>
                                    <path d="M6 18l6 -6 -6 -6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>`;

        $('#com-additional-rows').append(newRow);
    }

    // crm create start

    $(document).ready(function () {

        $("#residential_customer_form_btn").click(function(e) {
            e.preventDefault();
            let form = $('#residential_customer_form')[0];
            let data = new FormData(form);

            $.ajax({
                url: "{{ route('customer.store') }}",
                type: "POST",
                data: data,
                dataType: "JSON",
                processData: false,
                contentType: false,

                success: function(response) {

                    console.log(response);

                    if (response.errors) {
                        var errorMsg = '';
                        $.each(response.errors, function(field, errors) {
                            $.each(errors, function(index, error) {
                                errorMsg += error + '<br>';
                            });
                        });
                        iziToast.error({
                            message: errorMsg,
                            position: 'topRight'
                        });

                    }
                    else if (response.status == "success"){
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        $('#add-customer').modal('hide');
                        window.location.reload();

                    }
                    else
                    {
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
                // error: function(response) {
                //     console.log(response);
                // }

            });

        });

        // AJAX FOR SAVE DATA OF COMMERCIAL
        $("#commercial_customer_form_btn").click(function(e) {
            e.preventDefault();
            let form = $('#commercial_customer_form')[0];
            let data = new FormData(form);

            $.ajax({
                url: "{{ route('commercial.customer.store') }}",
                type: "POST",
                data: data,
                dataType: "JSON",
                processData: false,
                contentType: false,

                success: function(response) {

                    console.log(response);

                    if (response.errors) {
                        var errorMsg = '';
                        $.each(response.errors, function(field, errors) {
                            $.each(errors, function(index, error) {
                                errorMsg += error + '<br>';
                            });
                        });
                        iziToast.error({
                            message: errorMsg,
                            position: 'topRight'
                        });

                    }
                    else if (response.status == "success") {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });

                        $('#add-customer').modal('hide');
                        window.location.reload();
                    }
                    else
                    {
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {

                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
            });
        });

        // click on same as service address (residential)

        $('body').on('change', '#same_address_res', function(){

            if($(this).is(':checked'))
            {
                var size = $("#add-customer #residential_customer_form #res_newinput").find(".service_addr_group").length;

                if(size > 0)
                {
                    $('#add-customer #residential_customer_form #res_newInput_bill').html("");

                    var el = $("#add-customer #residential_customer_form #res_newinput .service_addr_group");

                    for(var i=0; i<size; i++)
                    {
                        var temp_person_incharge = el.eq(i).find(".person_incharge_name").val();
                        var temp_mobile_no = el.eq(i).find(".phone_no").val();
                        var temp_email = el.eq(i).find(".email_id").val();
                        var temp_postal_code = el.eq(i).find(".postal-code").val();
                        var temp_delivery_address = el.eq(i).find(".address").val();
                        var temp_unit_no = el.eq(i).find(".unit_number_service").val();
                        var temp_zone = el.eq(i).find(".zone").val();

                        var html = `<div class="row my-3 billing_addr_group" id="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                            <label for="name">Person Incharge Name</label>
                                            <input type="text" placeholder="Enter Name" name="person_incharge_name_bill[]" class="form-control" value="${temp_person_incharge}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                            <label for="name">Contact No</label>
                                            <input type="text" placeholder="Enter Number" name="phone_no_bill[]" class="form-control" value="${temp_mobile_no}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Email</label>
                                                <input type="email" placeholder="Enter Email" name="email_bill[]" class="form-control" value="${temp_email}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                            <label for="name">Postal Code</label>
                                            <input type="text" placeholder="Enter Code" name="postal_code_bill[]" class="form-control postal-code" onchange="handlePostalCode(this)" value="${temp_postal_code}">
                                            </div>
                                        </div>                                      
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                            <label for="name">Unit No</label>
                                            <input type="text" placeholder="Enter Unit No." name="unit_number_bill[]" class="form-control" required value="${temp_unit_no}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                            <label for="name">Address</label>
                                            <input type="text" placeholder="Enter Address" name="address_bill[]" class="form-control bill_address" value="${temp_delivery_address}">
                                            </div>
                                        </div>
                                        <input type="hidden" name="zone_bill[]" class="zone" value="${temp_zone}">
                                        <div class="col-md-1" style="display: flex; align-items: center;">
                                            <button type="button" class="btn btn-danger deleteRow">-</button>
                                        </div>
                                    </div>`;

                        $('#add-customer #residential_customer_form #res_newInput_bill').append(html);
                    }
                }
            }
            else
            {
                $('#add-customer #residential_customer_form #res_newInput_bill').html("");
            }

        });

        // click on same as service address (commercial)

        $('body').on('change', '#same_address_com', function(){

            if($(this).is(':checked'))
            {
                var size = $("#add-customer #commercial_customer_form #com_newinput").find(".service_addr_group").length;

                if(size > 0)
                {
                    $('#add-customer #commercial_customer_form #com_newInput_bill').html("");

                    var el = $("#add-customer #commercial_customer_form #com_newinput .service_addr_group");

                    for(var i=0; i<size; i++)
                    {
                        var temp_person_incharge = el.eq(i).find(".c_person_incharge_name").val();
                        var temp_mobile_no = el.eq(i).find(".c_contact_no").val();
                        var temp_email = el.eq(i).find(".c_email_id").val();
                        var temp_postal_code = el.eq(i).find(".postal-code").val();
                        var temp_delivery_address = el.eq(i).find(".address").val();
                        var temp_unit_no = el.eq(i).find(".c_unit_no").val();
                        var temp_zone = el.eq(i).find(".zone").val();

                        var html = `<div class="row my-3 billing_addr_group" id="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Person Incharge Name</label>
                                                <input type="text" placeholder="Enter Name" name="c_person_incharge_name_bil[]" class="form-control" value="${temp_person_incharge}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Contact No</label>
                                                <input type="text" placeholder="Enter Number" name="c_contact_no_bil[]" class="form-control" value="${temp_mobile_no}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Email</label>
                                                <input type="email" placeholder="Enter Email" name="c_email_bil[]" class="form-control" value="${temp_email}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Postal Code</label>
                                                <input type="text" placeholder="Enter Code" name="c_postal_code_bil[]" id="postal_code_service" class="form-control postal-code" onchange="handlePostalCode(this)" value="${temp_postal_code}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="name">Address</label>
                                                <input type="text" placeholder="Enter Address" name="c_address_bil[]" class="form-control bill_address" value="${temp_delivery_address}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Unit No</label>
                                                <input type="text" placeholder="Enter Unit No." name="c_unit_no_bil[]" class="form-control" required value="${temp_unit_no}">
                                            </div>
                                        </div>
                                        <input type="hidden" name="c_zone_bill[]" class="zone" value="${temp_zone}">
                                        <div class="col-md-1" style="display: flex; align-items: center;">
                                            <button type="button" class="btn btn-danger deleteRow">-</button>
                                        </div>
                                    </div>`;

                        $('#add-customer #commercial_customer_form #com_newInput_bill').append(html);
                    }
                }
            }
            else
            {
                $('#add-customer #commercial_customer_form #com_newInput_bill').html("");
            }

        });

        // customer name, contact number and email should be auto copy to service address and billing address (residential)

        $('body').on('blur', '#res_customer_name', function(){

            var res_customer_name = $(this).val();
            $("#res_serv_addr_pi_name").val(res_customer_name);
            $("#res_bill_addr_pi_name").val(res_customer_name);

        });

        $('body').on('blur', '#res_contact_no', function(){

            var res_contact_no = $(this).val();
            $("#res_serv_addr_pi_no").val(res_contact_no);
            $("#res_bill_addr_pi_no").val(res_contact_no);

        });

        $('body').on('blur', '#res_email', function(){

            var res_email = $(this).val();
            $("#res_serv_addr_pi_email").val(res_email);
            $("#res_bill_addr_email").val(res_email);

        });

        // customer name, contact number and email should be auto copy to service address and billing address (commercial)

        $('body').on('blur', '#com_customer_name', function(){

            var com_customer_name = $(this).val();
            $("#com_serv_addr_pi_name").val(com_customer_name);
            $("#com_bill_addr_pi_name").val(com_customer_name);
            $("#comp_info_contact_name").val(com_customer_name);

        });

        $('body').on('blur', '#com_contact_no', function(){

            var com_contact_no = $(this).val();
            $("#com_serv_addr_pi_no").val(com_contact_no);
            $("#com_bill_addr_pi_no").val(com_contact_no);
            $("#comp_info_company_mobile_no").val(com_contact_no);

        });

        $('body').on('blur', '#com_email', function(){

            var com_email = $(this).val();
            $("#com_serv_addr_pi_email").val(com_email);
            $("#comp_info_company_email").val(com_email);
            $("#com_bill_addr_pi_email").val(com_email);

        });

    });

    // crm create end
</script>
