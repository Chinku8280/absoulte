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
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Customer Name<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" name="saluation"
                                style="padding: 0.4375rem 1rem 0.4375rem 0.75rem;">
                                <option value="1">Mr</option>
                                <option value="2">Miss</option>
                            </select>
                            <input type="text" class="form-control w-50" name="customer_name"
                                placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Contact number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_no" placeholder="Enter Number">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Created By<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="created_by">
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>
                            {{-- <option value="34">Super Admin</option> --}}
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
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
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Status<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="status">
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                            <option value="0">Block</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Customer Remark</label>
                        <input type="text" class="form-control" name="customer_remark"
                            placeholder="Enter Remarks">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Default Address</label>
                        <input type="text" class="form-control" name="default_address"
                            placeholder="Enter Address">
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
                                    <div class="tab-pane active show" id="tabs-1" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Name</label>
                                                    <input type="text" class="form-control" name="contact_name[]"
                                                        placeholder="Enter Name">
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile Number</label>
                                                    <input type="text" class="form-control"
                                                        name="additional_mobile_number[]" placeholder="Enter Number">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <label class="form-label" style="visibility: hidden;">Add</label>
                                                <a href="#" class="btn btn-primary add-row-contact">
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
                                    </div>
                                    <div id="additional-rows"></div>
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
                                    <button type="button" class="btn btn-blue add-row" id="rowAdder">+ Add
                                        Address</button>
                                    <div id="newinput"></div>
                                    <div class="row my-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Person Incharge Name</label>
                                                <input type="text" placeholder="Enter Name"
                                                    name="person_incharge_name[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Contact No</label>
                                                <input type="text" placeholder="Enter Number" name="phone_no[]"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Email Id</label>
                                                <input type="text" placeholder="Enter Email" name="email_id[]"
                                                    class="form-control">
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
                                                    name="zone_service[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Address</label>
                                                <input type="text" placeholder="Enter Address"
                                                    id="address_service" name="address_service[]"
                                                    class="form-control address">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Unit No</label>
                                                <input type="text" placeholder="Enter Unit No."
                                                    name="unit_number_service[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Territory</label>
                                                <input type="text" placeholder="Enter Territory."
                                                    name="territory[]" id="territory" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-4" role="tabpanel">

                                    <button type="button" class="btn btn-blue add-row">+ Add Address</button>
                                
                                    <div class="row my-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Person Incharge Name</label>
                                                <input type="text" placeholder="Enter Name"
                                                    name="person_incharge_name_bill[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Contact No</label>
                                                <input type="text" placeholder="Enter Number"
                                                    name="phone_no_bill[]" class="form-control">
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
                                                <label for="name">Address</label>
                                                <input type="text" placeholder="Enter Address"
                                                    id="address_service" name="address_bill[]"
                                                    class="form-control bill_address">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Unit No</label>
                                                <input type="text" placeholder="Enter Unit No."
                                                    name="unit_number_bill[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newInput"></div>
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
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Customer Name<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" name="saluation"
                                style="padding: 0.4375rem 1rem 0.4375rem 0.75rem;">
                                <option value="1">Mr</option>
                                <option value="2">Miss</option>
                            </select>
                            <input type="text" class="form-control w-50" name="customer_name"
                                placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">UEN<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="uen" placeholder="Enter Id Number">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Group Company Name</label>
                        <input type="text" class="form-control" name="group_company_name"
                            placeholder="Group Company Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Individual Company Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="individual_company_name"
                            placeholder="Individual Company Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Contact number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mobile_number" placeholder="Enter Number">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Created By<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="created_by">
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-1">
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
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Status<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="status">
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                            <option value="0">Block</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">
                    <div class="mb-3">
                        <label class="form-label">Customer Remark</label>
                        <input type="text" class="form-control" name="customer_remark"
                            placeholder="Enter Remarks">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-1">

                    <div class="mb-6">
                        <label class="form-label">Default Address</label>
                        <input type="text" class="form-control" name="default_address"
                            placeholder="Enter Address">
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
                                                <input type="text" class="form-control" name="contact_name"
                                                    placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="text" class="form-control" name="company_mobile_no"
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
                                                <input type="text" class="form-control" name="company_email"
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
                                                <input type="text" class="form-control" name="c_contact_name[]"
                                                    placeholder="Enter Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="text" class="form-control" name="c_mobile_no[]"
                                                    placeholder="Enter Number">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="form-label" style="visibility: hidden;">Add</label>
                                            <a href="#" class="btn btn-primary add-row-contact2">
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
                                    <button type="button" class="btn btn-blue add-row" id="rowAdder-2">+ Add
                                        Address</button>
                                    <div id="newinput-2"></div>
                                    <div class="row my-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Person Incharge Name</label>
                                                <input type="text" placeholder="Enter Name"
                                                    name="c_person_incharge_name[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Contact No</label>
                                                <input type="text" placeholder="Enter Number"
                                                    name="c_contact_no[]" class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Email Id</label>
                                                <input type="text" placeholder="Enter Email" name="c_email_id[]"
                                                    class="form-control" required="">
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
                                                    class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Address</label>
                                                <input type="text" placeholder="Enter Address" name="c_address[]"
                                                    class="form-control address">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Unit No</label>
                                                <input type="text" placeholder="Enter Unit No." name="c_unit_no[]"
                                                    class="form-control" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Territory</label>
                                                <input type="text" placeholder="Enter Territory."
                                                    name="c_territory[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-04" role="tabpanel">
                                    {{-- <div class="row">
                           <div class="col-md-12">
                              <div class="table-responsive mb-3">
                                 <table
                                    class="table card-table table-vcenter text-nowrap table-transparent"
                                    id="billing_address_2">
                                    <thead>
                                       <tr>
                                          <th>Postal Codes</th>
                                          <th>Address</th>
                                          <th>Unit No</th>
                                          <th>
                                             <button type="button"
                                                class="btn btn-blue add-row-2">+</button>
                                          </th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>
                                             <input class="form-control postal-code" type="text" placeholder="Enter Code" name="postal_code[]"/>
                                          </td>
                                          <td>
                                             <input class="form-control address" type="text" placeholder="Address" name="address[]"/>
                                          </td>
                                          <td>
                                             <input class="form-control" type="text" placeholder="Enter Unit NoC" name="billing_unit_number[]"/>
                                          </td>
                                          <td>
                                             <button type="button" class="btn btn-danger delete-row-2">-</button>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div> --}}
                                    <button type="button" class="btn btn-blue add-row-2">+</button>
                                    <div class="row my-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Person Incharge Name</label>
                                                <input type="text" placeholder="Enter Name"
                                                    name="c_person_incharge_name_bil[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Contact No</label>
                                                <input type="text" placeholder="Enter Number"
                                                    name="c_contact_no_bil[]" class="form-control">
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
                                                <label for="name">Address</label>
                                                <input type="text" placeholder="Enter Address"
                                                    id="address_service" name="c_address_bil[]"
                                                    class="form-control bill_address">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="name">Unit No</label>
                                                <input type="text" placeholder="Enter Unit No."
                                                    name="c_unit_no_bil[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="newInput-2"></div>
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

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <script>
        $(document).ready(function() {
            $('#myselection').on('change', function() {
                var demovalue = $(this).val();
                $("div.myDiv").hide();
                $("#show" + demovalue).show();
            });
        });
    </script>
    <script>
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
    </script>
    <script>
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
                    
                    $("#address_service").val(address);                   
                    $("#territory").val(territory);    

                    var postalCode = get_postal.substring(0, 2);
                    $.ajax({

                        url: "{{ route('get.zone.name', ['postalCode' => '__postalCode__']) }}"
                            .replace('__postalCode__', postalCode),
                        method: 'GET',
                        success: function(response) {
                            console.log(response);
                            var zoneName = response.zone_name;
                            $("#zone_service").val(zoneName);
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
                    $(".bill_address").val(JSON.results[0].ADDRESS);
                }
            });
        }

        $(function() {
            // Add row button click event
            $('#rowAdder').click(function() {
                var newRowAdd = `
                    <div class="row my-3" id="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Person Incharge Name</label>
                        <input type="text" placeholder="Enter Name" name="person_incharge_name[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Contact No</label>
                        <input type="text" placeholder="Enter Number" name="phone_no[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Email Id</label>
                        <input type="text" placeholder="Enter Email" name="email_id[]" class="form-control">
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
                        <input type="text" placeholder="Enter Zone" name="zone[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                        <label for="name">Address</label>
                        <input type="text" id="address_service" placeholder="Enter Address" name="address_service[]" class="form-control address">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                        <label for="name">Unit No</label>
                        <input type="text" placeholder="Enter Unit No." name="unit_number_service[]" class="form-control" required="">
                        </div>
                    </div>
                        <div class="col-md-3">
                        <div class="form-group mb-3">
                        <label for="name">Territory</label>
                        <input type="text" placeholder="Enter Unit No." name="territory[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1" style="display: block; align-items: center;">
                        <button type="button" class="btn btn-danger deleteRow">-</button>
                    </div>
                    </div>`;

                $('#newinput').append(newRowAdd);
            });

            // Delete row button click event
            $('body').on('click', '.deleteRow', function() {
                $(this).parents('#row').remove();
            });


        });
    </script>
    <script type="text/javascript">
        $("#rowAdder-2").click(function() {
            newRowAdd =
                '<div class="row my-3" id="row">  <div class="col-md-4">' +
                '<div class="form-group mb-3">' +
                ' <label for="name">Person Incharge Name FK</label><input type="text" placeholder="Enter Name" name="c_person_incharge_name[]" class="form-control" required=""></div></div>' +
                '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="c_contact_no[]" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="c_email_id[]" class="form-control" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="c_postal_code[]" class="form-control postal-code" onchange="handlePostalCodeLookup(this)"></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="c_zone[]" class="form-control"></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" id placeholder="Enter Address" name="c_address[]" class="form-control address"> </div> </div>' +
                '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="c_unit_no[]" class="form-control"> </div> </div>' +
                '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Territory</label><input type="text" placeholder="Enter Territory." name="c_territory[]" class="form-control"> </div> </div>' +
                '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="DeleteRow-2">-</button></div></div>';
            console.log(newRowAdd);
            $('#newinput-2').append(newRowAdd);
        });

        $("body").on("click", "#DeleteRow-2", function() {
            $(this).parents("#row").remove();
        })
    </script>
    <!-- Tabler Core -->


    <script>
        $(function() {
            $('.add-row').click(function() {
                var template = `<div class="row my-3" id="row">
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
                                        <label for="name">Postal Code</label>
                                        <input type="text" placeholder="Enter Code" name="postal_code_bill[]" class="form-control postal-code" onchange="handlePostalCode(this)">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                        <label for="name">Address</label>
                                        <input type="text" placeholder="Enter Address" name="address_bill[]" class="form-control bill_address">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                        <label for="name">Unit No</label>
                                        <input type="text" placeholder="Enter Unit No." name="unit_number_bill[]" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="display: flex; align-items: center;">
                                        <button type="button" class="btn btn-danger deleteRow">-</button>
                                    </div>
                                    </div>`;
                $('#newInput').append(template);
            });

            $('#billing_address').on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
    <script>
        $(function() {
            $('#edit_billing_address .edit_add-row').click(function() {
                var template =
                    '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger edit_delete-row">-</button></td></tr>';
                $('#edit_billing_address tbody').append(template);
            });
            $('#edit_billing_address').on('click', '.edit_delete-row', function() {
                $(this).parent().parent().remove();
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            // $(document).on('change', '.postal-code', function() {
            //     var postalCode = $(this).val();
            //     var addressField = $(this).closest('tr').find('.address');

            //     $.ajax({
            //         url: 'get-address',
            //         method: 'GET',
            //         data: {
            //             postal_code: postalCode
            //         },
            //         success: function(response) {
            //             console.log(response);
            //             if (response.hasOwnProperty('address')) {
            //                 addressField.val(response.address);
            //             } else {
            //                 iziToast.error({
            //                     title: 'Error',
            //                     message: 'Postal code not found',
            //                     position: 'topRight'
            //                 });
            //             }
            //         },
            //         error: function(jqXHR, textStatus, errorThrown) {
            //             // console.log(response);
            //             iziToast.error({
            //                 title: 'Error',
            //                 message: 'An error occurred',
            //                 position: 'topRight'
            //             });
            //         }
            //     });
            // });

            $('.add-row-2').click(function() {
                var template = `<div class="row my-3" id="row">
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
           <label for="name">Postal Code</label>
           <input type="text" placeholder="Enter Code" name="c_address_bil[]" id="postal_code_service" class="form-control postal-code" onchange="handlePostalCode(this)">
         </div>
       </div>
       <div class="col-md-4">
         <div class="form-group mb-3">
           <label for="name">Address</label>
           <input type="text" placeholder="Enter Address" name="c_postal_code_bil[]" class="form-control bill_address">
         </div>
       </div>
       <div class="col-md-3">
         <div class="form-group mb-3">
           <label for="name">Unit No</label>
           <input type="text" placeholder="Enter Unit No." name="c_unit_no_bil[]" class="form-control" required="">
         </div>
       </div>
       <div class="col-md-1" style="display: flex; align-items: center;">
         <button type="button" class="btn btn-danger deleteRow">-</button>
       </div>
     </div>`;

                $('#newInput-2').append(template);
            });

            $('#billing_address_2').on('click', '.delete-row-2', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
    <script type="text/javascript">
        $("#edit_rowAdder-2").click(function() {
            newRowAdd =
                '<div class="row my-3" id="row">  <div class="col-md-4">' +
                '<div class="form-group mb-3">' +
                ' <label for="name">Person Incharge Name F</label><input type="text" placeholder="Enter Name" name="name" class="form-control" required=""></div></div>' +
                '<div class="col-md-4"><div class="form-group mb-3"> <label for="name">Contact No</label><input type="text" placeholder="Enter Number" name="name" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Email Id</label><input type="text" placeholder="Enter Email" name="name" class="form-control" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Postal Code</label><input type="text" placeholder="Enter Code" name="name" class="form-control" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Zone</label><input type="text" placeholder="Enter Zone" name="name" class="form-control" required=""></div> </div>' +
                '<div class="col-md-4"><div class="form-group mb-3"><label for="name">Address</label><input type="text" placeholder="Enter Address" name="name" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-3"><div class="form-group mb-3"> <label for="name">Unit No</label><input type="text" placeholder="Enter Unit No." name="name" class="form-control" required=""> </div> </div>' +
                '<div class="col-md-1" style="display: flex; align-items: center;">  <button type="button" class="btn btn-danger" id="edit_DeleteRow-2">-</button></div></div>';

            $('#edit_newinput-2').append(newRowAdd);
        });

        $("body").on("click", "#edit_DeleteRow-2", function() {
            $(this).parents("#row").remove();
        })
    </script>
    <script>
        $(function() {
            $('#billing_address_22 .add-row-22').click(function() {
                var template = `<tr>
           <td>
           <input class="form-control" type="text" placeholder="Enter Code" />
           </td>
           <td>
           <input class="form-control" type="text" placeholder="Address"/>
           </td>
           <td>
           <input class="form-control" type="text" placeholder="Enter Unit No"/>
           </td>
           <td>
           <button type="button" class="btn btn-danger delete-row-2">-</button>
           </td>
           </tr>`;
                $('#billing_address_22 tbody').append(template);
            });
            $('#billing_address_22').on('click', '.delete-row-22', function() {
                $(this).parent().parent().remove();
            });
        })
    </script>
    <script>
        $(function() {
            $('#service_address .add-row').click(function() {
                var template =
                    '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                $('#service_address tbody').append(template);
            });
            $('#service_address').on('click', '.delete-row', function() {
                $(this).parent().parent().remove();
            });
        })
    </script>
    <script>
        $(function() {
            $('#commercial_service_address .add-row').click(function() {
                var template =
                    '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                $('#commercial_service_address tbody').append(template);
            });
            $('#commercial_service_address').on('click', '.delete-row', function() {
                $(this).parent().parent().remove();
            });
        })
    </script>
    <script>
        $(function() {
            $('#commercial_billing_address .add-row').click(function() {
                var template =
                    '<tr><td><input class="form-control" type="text" placeholder="Enter Code" /></td><td><input class="form-control" type="text" placeholder="Address"/></td><td><input class="form-control" type="text" placeholder="Enter Unit No"/></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
                $('#commercial_billing_address tbody').append(template);
            });
            $('#commercial_billing_address').on('click', '.delete-row', function() {
                $(this).parent().parent().remove();
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            // Add row
            $(document).on('click', '.add-row-contact', function() {
                var newRow = `<div class="row mt-3">
           <div class="col-lg-5 col-md-5 col-sm-12">
               <div class="mb-3">
                   
                   <input type="text" class="form-control" name="contact_name[]" placeholder="Enter Name">
               </div>
           </div>
           <div class="col-lg-5 col-md-5 col-sm-12">
               <div class="mb-3">
                  
                   <input type="text" class="form-control" name="additional_mobile_number[]" placeholder="Enter Number">
               </div>
           </div>
           <div class="col-lg-2">
               <label class="form-label" style="visibility: hidden;">Add</label>
               <a href="#" class="btn btn-danger remove-row">
                   <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                       <path d="M18 6l-6 6m0 -6l6 6"></path>
                   </svg>
               </a>
           </div>
       </div>`;

                $('#additional-rows').append(newRow);
            });

            // Remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.row').remove();
            });

            $(document).on('click', '.add-row-contact2', function() {
                var newRow = '<div class="row">' +
                    '<div class="col-lg-5 col-md-5 col-sm-12">' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Contact Name</label>' +
                    '<input type="text" class="form-control" name="c_contact_name[]" placeholder="Enter Name">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-lg-5 col-md-5 col-sm-12">' +
                    '<div class="mb-3">' +
                    '<label class="form-label">Mobile Number</label>' +
                    '<input type="text" class="form-control" name="c_mobile_no[]" placeholder="Enter Number">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-lg-2">' +
                    '<label class="form-label" style="visibility: hidden;">Add</label>' +
                    '<a href="#" class="btn btn-danger remove-row">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                    '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
                    '<path d="M18 6l-6 6 6 6"></path>' +
                    '<path d="M6 18l6 -6 -6 -6"></path>' +
                    '</svg>' +
                    '</a>' +
                    '</div>' +
                    '</div>';

                $(this).closest('.tab-pane').append(newRow);
            });
        });
    </script>
    <script>
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

                    } else {
                        iziToast.success({
                            message: response.success,
                            position: 'topRight'
                        });

                        $('#add-crm-modal').modal('hide');
                        window.location.reload();

                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }

            });

        })

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

                    } else {
                        iziToast.success({
                            message: response.success,
                            position: 'topRight'
                        });

                        $('#add-crm-modal').modal('hide');
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {

                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
            });
        })
    </script>
    <style>

    </style>
