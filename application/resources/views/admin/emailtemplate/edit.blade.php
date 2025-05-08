<div class="modal-header">
    <h5 class="modal-title" id="exampleModalToggleLabel">Add Email Template</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="{{ route('emailtemplate.update') }}" method="post" id="edit_email_template_form">
        @csrf
        <div class="row mb-4">
            <div class="col-md-12 mb-4">
                <label for=""><b>Company</b></label><br><br>
                <select name="company_id" id="company_id" class="form-control">
                    <option value="">Select Company</option>
                    @foreach ($company as $item)
                        <option value="{{ $item->id }}"
                            @if (isset($template) && $item->id == $template->company_id) {{ 'selected' }} @endif>{{ $item->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for=""><b>Title</b></label><br><br>
                <input type="text" class="form-control" name="title" placeholder="Enter Title"
                    value="@if (isset($template)) {{ $template->title }} @endif" required>
            </div>
            <div class="col-md-6">
                <label for=""><b>Subject</b></label><br><br>
                <input type="text" class="form-control" name="subject" placeholder="Enter Subject"
                    value="@if (isset($template)) {{ $template->subject }} @endif" required>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label for=""><b>CC</b></label><br><br>
                <input type="text" class="form-control" name="cc" placeholder="Enter CC" id="cc-input"
                    value="@if (isset($template)) {{ $template->cc }} @endif">

            </div>
            <div class="col-md-6">
                <label for=""><b>BCC</b></label><br><br>
                <input type="text" class="form-control" name="bcc" placeholder="Enter BCC"
                    value="@if (isset($template)) {{ $template->bcc }} @endif">
            </div>
        </div>
        <div class="mb-3">
            <label for=""><b>Body</b></label><br><br>
            <textarea name="body" id="edit_body" class="form-control">
                {{-- @if (isset($template))
                    {{ strip_tags($template->body) }}
                @endif --}}
                {{$template->body}}
            </textarea>
        </div>
        <input type="hidden" name="id" value="{{ $template->id }}">
        <div class="modal-footer">
            <button type="submit" class="btn btn-info">Save</button>
        </div>
    </form>
</div>

{{-- </div>
  </div> --}}

{{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script> --}}
<script>
    $(document).ready(function() {
        var input = document.getElementById('cc-input');
        new Tagify(input);

    });
    ClassicEditor
        .create(document.querySelector('#edit_body'))
        .catch(error => {
            console.error(error);
        });


    $(document).ready(function() {

        $('#edit_email_template_form').on('submit', function(e) {

            e.preventDefault();

            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(result) {
                    console.log(result);

                    if (result.status == "error") {
                        $.each(result.errors, function(field, errors) {
                            iziToast.error({
                                message: errors,
                                position: 'topRight'
                            });
                        });
                    } else {
                        iziToast.success({
                            message: result.message,
                            position: 'topRight'
                        });

                        window.location.reload();
                    }
                },
                error: function(result) {
                    console.log(result);
                }
            });

        });

    });
</script>
