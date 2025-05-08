<div class="modal-header">
    <h5 class="modal-title">View Customer Detail</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label">Select Customer Type</label>
        <select class="form-select" id="myselection">
            <option selected="">Select Option</option>
            <option value="One" {{ $data->customer_type == 'residential_customer_type' ? 'selected' : '' }}>
                Residential</option>
            <option value="Two" {{ $data->customer_type == 'commercial_customer_type' ? 'selected' : '' }}>Commercial
            </option>
        </select>
    </div>
    <form id="residential_customer_form" method="POST">
        @csrf
        <input type="hidden" value="residential_customer_type" name="customer_type">
        <input type="hidden" value="{{ $data->id }}" name="id">
        <div id="showOne" class="row myDiv" style="display: none;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Name<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" disabled name="saluation"
                                style="padding: 0.4375rem 1rem 0.4375rem 0.75rem;">

                                @foreach ($salutation_data as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $data->saluation == $item->id ? 'selected' : '' }}>
                                        {{ $item->salutation_name }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control w-50" name="customer_name"
                                value="{{ $data->customer_name }}" placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Contact number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contact_no" value="{{ $data->mobile_number }}"
                            placeholder="Enter Number" disabled>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="{{ $data->email }}"
                            placeholder="Enter Email" disabled>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Created By<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="created_by" disabled>
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Language Spoken</label>
                        <select type="text" class="form-select" id="select-countries" name="language_spoken"
                            disabled>
                            @foreach ($spoken_language as $list)
                                <option value="{{ $list->id }}"
                                    {{ $list->id == $data->language_spoken ? 'selected' : '' }}>
                                    {{ $list->language_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">Type of Services<span class="text-danger">*</span></label>
                        <textarea name="cleaning_type" class="form-control" cols="30" rows="2" disabled>{{-- {{ $data->cleaning_type ?? "" }} --}}{{($data->service_type) ?? ''}}</textarea>                   
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Status<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="status" disabled>
                            <option value="1" {{ $data->status == '1' ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $data->status == '2' ? 'selected' : '' }}>Inactive</option>
                            <option value="0" {{ $data->status == '3' ? 'selected' : '' }}>Block</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Lead Source</label>
                        <select name="lead_source" id="lead_source" class="form-select" disabled>
                            @foreach ($source_data as $source)
                                <option value="{{ $source->source_name }}"
                                    {{ $source->source_name == $data->lead_source ? 'selected' : '' }}>
                                    {{ $source->source_name }}
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Pending Invoices Limit</label>
                        <input type="number" name="pending_invoice_limit" class="form-control" min="0" value="{{$data->pending_invoice_limit}}" readonly>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Sales Order Renewal</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="renewal" {{($data->renewal == 1) ? 'checked' : ''}} disabled>
                            {{-- <label class="form-check-label">Sales Order Renewal</label> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Remark</label>
                        {{-- <input type="text" class="form-control" name="customer_remark"
                            value="{{ $data->customer_remark }}" placeholder="Enter Remarks" disabled> --}}

                            <textarea class="form-control" name="customer_remark" placeholder="Enter Remarks" cols="30" rows="5" disabled>{{ $data->customer_remark }}</textarea>    
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
                                        @foreach ($additional_contact as $contact)
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Name</label>
                                                        <input type="text" class="form-control"
                                                            name="contact_name[]" disabled placeholder="Enter Name"
                                                            value="{{ $contact->contact_name }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Mobile Number</label>
                                                        <input type="text" class="form-control"
                                                            name="additional_mobile_number[]" disabled
                                                            placeholder="Enter Number"
                                                            value="{{ $contact->mobile_no }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-4 col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="text" class="form-control"
                                                            name="additional_email[]" disabled
                                                            placeholder="Enter Email"
                                                            value="{{ $contact->email }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
                                                            name="credit_limit" disabled placeholder="0"
                                                            value="{{ $additional_info->credit_limit ?? '' }}">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Terms</label>
                                                        <select class="form-select" name="payment_terms" disabled>
                                                            @foreach ($payment_terms as $list)
                                                                @if ($list->id == $additional_info->payment_terms)
                                                                    <option value="{{ $list->id }}" selected>{{ $list->payment_terms ?? '' }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-3" role="tabpanel">
                                    {{-- <div id="newinput"></div> --}}
                                    @foreach ($service_address as $address)
                                        <div class="row my-3" id="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text" placeholder="Enter Name" disabled
                                                        name="person_incharge_name[]" class="form-control"
                                                        value="{{ $address->person_incharge_name ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" placeholder="Enter Number" disabled
                                                        name="phone_no[]" class="form-control"
                                                        value="{{ $address->contact_no ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email Id</label>
                                                    <input type="text" placeholder="Enter Email" name="email_id[]"
                                                        disabled class="form-control"
                                                        value="{{ $address->email_id ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" placeholder="Enter Code"
                                                        name="postal_code_service[]" disabled
                                                        class="form-control postal-code"
                                                        onchange="handlePostalCodeLookup(this)"
                                                        value="{{ $address->postal_code ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Zone</label>
                                                    <input type="text" placeholder="Enter Zone" disabled
                                                        name="zone[]" class="form-control"
                                                        value="{{ $address->zone ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" placeholder="Enter Unit No." disabled
                                                        name="unit_number_service[]" class="form-control"
                                                        value="{{ $address->unit_number ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <textarea type="text" placeholder="Enter Address" id="address_service" name="c_address_bil[]"
                                                        class="form-control bill_address" disabled>{{ $address->address ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="tab-pane" id="tabs-4" role="tabpanel">
                                    @foreach ($billing_address as $address)
                                        <div class="row my-3" id="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text"
                                                        value="{{ $address->person_incharge_name ?? '' }}"
                                                        name="c_person_incharge_name_bil[]" class="form-control"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" value="{{ $address->contact_no ?? '' }}"
                                                        name="c_contact_no_bil[]" class="form-control"disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email</label>
                                                    <input type="email" value="{{ $address->email ?? '' }}"
                                                        name="c_email_bil[]" class="form-control"disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" value="{{ $address->postal_code ?? '' }}"
                                                        name="c_postal_code_bil[]" class="form-control postal-code"
                                                        onchange="handlePostalCodeLookup(this)" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" value="{{ $address->unit_number ?? '' }}"
                                                        name="c_unit_no_bil[]" class="form-control" required=""
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <textarea type="text" value="" name="c_address_bil[]" class="form-control address" disabled>{{ $address->address ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </form>

    <form id="commercial_customer_form" method="POST">
        @csrf
        <input type="hidden" value="commercial_customer_type" name="customer_type">
        <input type="hidden" value="{{ $data->id }}" name="commercial_id">
        <div id="showTwo" class="myDiv" style="display: none;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Name<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-select" disabled name="saluation"
                                style="padding: 0.4375rem 1rem 0.4375rem 0.75rem;">

                                @foreach ($salutation_data as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $data->saluation == $item->id ? 'selected' : '' }}>
                                        {{ $item->salutation_name }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control w-50" name="customer_name"
                                value="{{ $data->customer_name }}" placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">UEN</label>
                        <input type="text" class="form-control" name="uen" value="{{ $data->uen }}"
                            placeholder="Enter UEN Number" disabled>
                    </div>
                </div>
                {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Group Company Name</label>
                        <input type="text" class="form-control" name="group_company_name"
                            value="{{ $data->group_company_name }}" placeholder="Group Company Name" disabled>
                    </div>
                </div> --}}
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Company Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="individual_company_name"
                            value="{{ $data->individual_company_name }}" placeholder="Company Name"
                            disabled>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Contact number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mobile_number"
                            value="{{ $data->mobile_number }}" placeholder="Enter Number" disabled>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="{{ $data->email }}"
                            placeholder="Enter Email" disabled>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Created By<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="created_by" disabled>
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->username }}</option>

                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Language Spoken</label>
                        <select type="text" class="form-select" id="spoken_language" disabled>
                            @foreach ($spoken_language as $list)
                                <option value="{{ $list->id }}"
                                    {{ $list->id == $data->language_spoken ? 'selected' : '' }}>
                                    {{ $list->language_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">Type of Services<span class="text-danger">*</span></label>
                        <textarea name="cleaning_type" class="form-control" cols="30" rows="2" disabled>{{-- {{ $data->cleaning_type ?? "" }} --}}{{$data->service_type ?? ''}}</textarea>                   
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Status<span class="text-danger">*</span></label>
                        <select type="text" class="form-select" name="status" disabled>
                            <option value="1" {{ $data->status == '1' ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $data->status == '2' ? 'selected' : '' }}>Inactive</option>
                            <option value="0" {{ $data->status == '3' ? 'selected' : '' }}>Block</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-6">
                        <label class="form-label">Lead Source</label>
                        <select name="lead_source" id="lead_source" class="form-select" disabled>
                            @foreach ($source_data as $source)
                                <option value="{{ $source->source_name }}"
                                    {{ $source->source_name == $data->lead_source ? 'selected' : '' }}>
                                    {{ $source->source_name }}
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Pending Invoices Limit</label>
                        <input type="number" name="pending_invoice_limit" class="form-control" min="0" value="{{$data->pending_invoice_limit}}" readonly>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Sales Order Renewal</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="renewal" {{($data->renewal == 1) ? 'checked' : ''}} disabled>
                            {{-- <label class="form-check-label">Sales Order Renewal</label> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Customer Remark</label>
                        {{-- <input type="text" class="form-control" name="customer_remark"
                            placeholder="Enter Remarks" value="{{ $data->customer_remark }}" disabled> --}}

                        <textarea class="form-control" name="customer_remark" placeholder="Enter Remarks" cols="30" rows="5" disabled>{{ $data->customer_remark }}</textarea>
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
                                                <input type="text" class="form-control" disabled
                                                    name="company_contact_name" placeholder="Enter Name"
                                                    value="{{ $company_info->contact_name ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input type="text" class="form-control" name="company_mobile_no"
                                                    disabled placeholder="Enter Number"
                                                    value="{{ $company_info->mobile_no ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Fax Number</label>
                                                <input type="text" class="form-control" name="fax_number" disabled
                                                    placeholder="Enter Number"
                                                    value="{{ $company_info->fax_no ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-1">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" disabled
                                                    name="company_email" placeholder="Enter Email"
                                                    value="{{ $company_info->email ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-01" role="tabpanel">                                 
                                    @foreach ($additional_contact as $contact)
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Name</label>
                                                    <input type="text" class="form-control" disabled
                                                        name="c_contact_name[]" placeholder="Enter Name"
                                                        value="{{ $contact->contact_name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile Number</label>
                                                    <input type="text" class="form-control" disabled
                                                        name="c_mobile_number[]" placeholder="Enter Number"
                                                        value="{{ $contact->mobile_no }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="text" class="form-control"
                                                        name="additional_email[]" disabled
                                                        placeholder="Enter Email"
                                                        value="{{ $contact->email }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach                                
                                </div>
                                <div class="tab-pane" id="tabs-02" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Credit limit</label>
                                                        <input type="number" class="form-control"
                                                            name="credit_limit" placeholder="0" disabled
                                                            value="{{ $additional_info->credit_limit ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Payment Terms</label>
                                                        <select class="form-select" name="info_payment_terms"
                                                            disabled>
                                                            @foreach ($payment_terms as $list)
                                                                @if ($list->id == $additional_info->payment_terms)
                                                                    <option value="{{ $list->id }}" selected>{{ $list->payment_terms ?? '' }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-03" role="tabpanel">
                                    {{-- <div id="newinput-2"></div> --}}
                                    @foreach ($service_address as $address)
                                        <div class="row my-3">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text" placeholder="Enter Name" disabled
                                                        name="c_person_incharge_name[]" class="form-control"
                                                        value="{{ $address->person_incharge_name }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" placeholder="Enter Number" disabled
                                                        name="c_contact_no[]" class="form-control"
                                                        value="{{ $address->contact_no }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email Id</label>
                                                    <input type="text" placeholder="Enter Email" disabled
                                                        name="c_email_id[]" class="form-control"
                                                        value="{{ $address->email_id }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" placeholder="Enter Code" disabled
                                                        name="c_postal_code[]" class="form-control postal-code"
                                                        onchange="handlePostalCodeLookup(this)"
                                                        value="{{ $address->postal_code }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Zone</label>
                                                    <input type="text" placeholder="Enter Zone" disabled
                                                        name="c_zone[]" class="form-control"
                                                        value="{{ $address->zone }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" placeholder="Enter Unit No." disabled
                                                        name="c_unit_no[]" class="form-control"
                                                        value="{{ $address->unit_number }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <textarea type="text" placeholder="Enter Address" disabled name="c_address[]" class="form-control address"
                                                        value="" disabled>{{ $address->address }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="tab-pane" id="tabs-04" role="tabpanel">
                                    @foreach ($billing_address as $address)
                                        <div class="row my-3" id="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Person Incharge Name</label>
                                                    <input type="text"
                                                        value="{{ $address->person_incharge_name ?? '' }}"
                                                        name="c_person_incharge_name_bil[]" class="form-control"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Contact No</label>
                                                    <input type="text" value="{{ $address->contact_no ?? '' }}"
                                                        name="c_contact_no_bil[]" class="form-control"disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Email</label>
                                                    <input type="email" value="{{ $address->email ?? '' }}"
                                                        name="c_email_bil[]" class="form-control"disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Postal Code</label>
                                                    <input type="text" value="{{ $address->postal_code ?? '' }}"
                                                        name="c_postal_code_bil[]" class="form-control postal-code"
                                                        onchange="handlePostalCodeLookup(this)" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="name">Unit No</label>
                                                    <input type="text" value="{{ $address->unit_number ?? '' }}"
                                                        name="c_unit_no_bil[]" class="form-control" required=""
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="name">Address</label>
                                                    <textarea value="" name="c_address_bil[]" class="form-control address" disabled>{{ $address->address ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
</div>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <script>
        $(document).ready(function() {
            var customerType = "{{ $data->customer_type }}";
            if (customerType === 'residential_customer_type') {
                $('#showOne').show();
                $('#showTwo').hide();
                $('#myselection option[value="Two"]').prop('disabled', true);
            } else if (customerType === 'commercial_customer_type') {
                $('#showOne').hide();
                $('#showTwo').show();
                $('#myselection option[value="One"]').prop('disabled', true);
            }

            $('#myselection').on('change', function() {
                var demovalue = $(this).val();
                $("div.myDiv").hide();
                $("#show" + demovalue).show();

                // Enable or disable the other option based on the selection
                if (demovalue === 'One') {
                    $('#myselection option[value="Two"]').prop('disabled', true);
                    $('#myselection option[value="One"]').prop('disabled', false);
                } else if (demovalue === 'Two') {
                    $('#myselection option[value="One"]').prop('disabled', true);
                    $('#myselection option[value="Two"]').prop('disabled', false);
                }
            });
        });
    </script>
