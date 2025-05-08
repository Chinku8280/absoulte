<div class="modal-header">
    <h5 class="modal-title">Edit Payment Method</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label"> Payment Method:</label>
                <select  id="payment_method" class="form-control payment_method">
                    <option value="Asia Pay" @if(isset($payment) && $payment->payment_method == 'Asia Pay') {{'selected'}} @endif>Asia Pay</option>
                    <option value="Offline" @if(isset($payment) && $payment->payment_method == 'Offline') {{'selected'}} @endif>Offline</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Payment Option:</label>
                <input type="text" id="payment_option" class="form-control payment_option" value="{{$payment->payment_option}}">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="updatePayment({{$payment->id}})">Update</button>
        </div>
    </div>
</div>

<script>
    function updatePayment(methodId){
        var payment_method = $('.payment_method').val();
        var payment_option = $('.payment_option').val();
        console.log('payment_method:',payment_method);
        console.log('payment_option:',payment_option);
        $.ajax({
            url: "{{route('payment.method.update')}}",
            method: 'POST', 
            data: {
                "_token": "{{ csrf_token() }}",
                'id': methodId,
                'payment_method': payment_method,
                'payment_option': payment_option
            },
            success: function(response) {
                location.reload();
            },
        });
    }
</script>
