{{-- <div class="modal modal-blur fade" id="view-quotation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-scrollable" role="document"> --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Quatation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-link card-link-pop">
                            <div class="card-status-start bg-primary"></div>
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-white text-primary">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-map-pin" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                        <path
                                            d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">

                                <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                    <b>Service Address</b>
                                </h3>


                                <p class="m-0">{{$quotation->service_address}}</p>
                                <hr class="my-3">
                                <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                    <b>Billing Address</b>
                                </h3>


                                <p class="m-0">{{$quotation->billing_address}}</</p>
                                <!-- <hr class="my-3">
                              <h3 class="card-title mb-1" style="color: #1F3BB3;">

                                <b>Cleaning Details</b>
                              </h3>


                              <p class="m-0">Deposite Type : Wave</p>
                              <p class="m-0">Date Of Cleaning : 25/04/2023</p>
                              <p class="m-0">Time Of Cleaning : 05:47 PM</p> -->


                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table
                                                class="table card-table table-vcenter text-center text-nowrap datatable">
                                                <thead>
                                                    <tr>
                                                        <th>SL NO</th>
                                                        <th>Item</th>
                                                        <th>Unit Price</th>
                                                        <th>Quantity</th>
                                                        <th>Gross Amount ($)</th>
                                                        <th>Discount (%)</th>


                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($service as $key => $item)
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$item->name}}</td>

                                                            <td>${{$item->unit_price}}</td>
                                                            <td>{{$item->quantity ? $item->quantity : 1}}</td>

                                                            <td>${{$item->gross_amount}}</td>
                                                            <td>{{$item->discount ? $item->discount : 0}}%</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-link card-link-pop">
                                    <div class="card-status-start bg-primary"></div>
                                    <div class="card-stamp">
                                        <div class="card-stamp-icon bg-white text-primary">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="card-title mb-1" style="color: #1F3BB3;">
                                            @if($customer->customer_type === 'commercial_customer_type')
                                                <b class="me-2">Company Details</b>
                                            @else
                                                <b class="me-2">Customer Details</b>
                                            @endif
                                        </h3>


                                        <p class="m-0">
                                            <i class="fa-solid fa-user me-2 pt-1" style="font-size: 14px;"></i>
                                            @if($customer->customer_type === 'commercial_customer_type')
                                                {{ $customer->individual_company_name }}
                                            @else
                                                {{ $customer->customer_name }}
                                            @endif
                                            
                                        </p>
                                        <p class="m-0">
                                            <i class="fa-solid fa-phone me-2 pt-1" style="font-size: 14px;"></i>
                                            +65-{{ $customer->mobile_number }}
                                        </p>
                                        <!-- <p class="m-0">
                                          <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                          103 Rasadhi Appartment Wadaj Ahmedabad
                                          380004.
                                        </p> -->

                                        <hr class="my-3">
                                        <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                                class="me-2">Amount Details</b>
                                        </h3>

                                        <div class="row">
                                            <div class="col-md-7">
                                                <p class="m-0">Total (before tax):</p>
                                                <p class="m-0">Total Tax:</p>
                                                <p class="m-0">Total Discount:</p>
                                                <h3>Grand Total:</h3>
                                            </div>
                                            <div class="col-md-5">
                                                {{-- <p class="m-0">${{array_sum($service['unit_price'])}}</p> --}}
                                                <p class="m-0">${{ $quotation->amount }}</p>
                                                <p class="m-0">${{ $quotation->tax }}</p>
                                                <p class="m-0">${{ $quotation->tax }}</p>
                                                <h3>${{ $quotation->grand_total }}</h3>
                                            </div>
                                        </div>
                                        {{-- <button type="button" class="btn btn-info w-100 mt-3"
                                            data-dismiss="modal">Confirm</button> --}}
                                    </div>
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
    {{-- </div>
</div> --}}
