<style>
    /* Truncate the text to 100 characters with ellipsis */
    .description-cell {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ui-datepicker {
        border: 1px solid #e6e7e9;
        border-radius: 8px 8px 0px 0px;
    }

    #popover {
        position: relative;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 2;
        padding: 10px;
    }

    .hidden {
        display: none;
    }

    .highlighted-date {
        background-color: #ffcc00;
        /* Yellow background */
        color: #000;
        /* Black text color */
        font-weight: bold;
    }

    /* Style for the tooltip (label) */
    .highlighted-date:hover:after {
        content: attr(data-tooltip);
        background-color: #333;
        /* Tooltip background color */
        color: #fff;
        /* Tooltip text color */
        padding: 5px 10px;
        border-radius: 5px;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.3s;
    }

    /* Show tooltip on hover */
    .highlighted-date:hover:after {
        opacity: 1;
    }

    .btn.selected-option {
        border: 2px solid #053ef8;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">Lead Received Payment</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form class="row text-left" id="lead_received_payment_form" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="leadId" value="{{ $leadId }}">
        <input type="hidden" name="payment_option" value="offline">

        <div class="row">

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3>Payment Amount</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-6">
                            <label class="form-label" for="totalAmount" style="font-size: 20px;">Total Payble Amount($):
                            <span id="totalAmount" style="font-size: 20px;">{{$lead->grand_total}}</span></label>
                            <input type="hidden" value="{{$lead->grand_total}}" name="total_amount">
                        </div>

                        <div class="col-md-9">
                            <h5>
                                Offline Payment method :
                            </h5>
                        </div>

                        <div class="responsive-table">
                            <table class="table table-success table-striped border">

                                <tbody id="offline_pay_option">
                                    @foreach ($offlineOptions as $options)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="payment_option_checkbox[]"
                                                        value="{{ $options->payment_option }}"
                                                        id="{{ $options->payment_option }}"
                                                        @if ($options->payment_option === 'Pay Now') checked @endif>
                                                    <label class="form-check-label"
                                                        for="{{ $options->payment_option }}">
                                                        {{ $options->payment_option }}
                                                    </label>
                                                </div>

                                            </td>
                                            <td></td>
                                            <td><input class="form-control" type="number"
                                                    name="payment_amount_checkbox[]" step="any"></td>
                                            <td><input class="form-control" type="file"
                                                    name="payment_proof[]"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
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
                    <div class="customer-card-slide3">
                        <div class="card-body">
                            <h3 class="card-title mb-1" style="color: #1F3BB3;"><b
                                    class="me-2">Customer Details added</b>
                            </h3>
                            <p class="card-p d-flex align-items-center mb-2 ">
                                <i class="fa-solid fa-user me-2"
                                    style="font-size: 14px;"></i>
                                    {{($customer->customer_type == "residential_customer_type")?$customer->customer_name:$customer->individual_company_name}}
                            </p>
                            <p class="card-p d-flex align-items-center mb-2 ">
                                <i class="fa-solid fa-phone me-2"
                                    style="font-size: 14px;"></i>{{ $customer->mobile_number }}
                            </p>
                            <p class="card-p  d-flex align-items-center mb-2">
                                <i class="fa-solid fa-envelope me-2"
                                    style="font-size: 14px;"></i>{{ $customer->email }}
                            </p>
                            {{-- <p class="card-p d-flex mb-2">
                                <i class="fa-solid fa-map-pin me-2 pt-1" style="font-size: 14px;"></i>
                                103 Rasadhi Appartment Wadaj Ahmedabad 380004.
                            </p> --}}
                            {{-- <p class="card-p d-flex mb-2">
                                Total Spend : $0.00
                            </p> --}}
                            <hr class="my-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12" style="text-align: right;">
                <button type="submit" class="btn btn-info" id="submit_btn">Submit</button>
            </div>
        </div>
    </form>
</div>


<script>

    $(document).ready(function () {

        $('body').on('submit', '#lead_received_payment_form', function(e){

            e.preventDefault();

            var data = new FormData($(this)[0]);

            $.ajax({
                url: "{{ route('lead.received-payment.store') }}",
                method: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#submit_btn").attr('disabled', true);
                },
                success: function(response) {
                    console.log(response);

                    if (response.errors)
                    {
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
                    else if(response.status == "success")
                    {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight',
                        });

                        $('#send-invoice').modal('hide');
                        $('#add-lead').modal('hide');
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
                error: function(response){
                    console.log(response);
                },
                complete: function() {
                    $("#submit_btn").attr('disabled', false);
                },

            });

        });

    });

</script>

