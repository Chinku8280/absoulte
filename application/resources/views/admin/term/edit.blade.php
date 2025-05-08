<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="" method="post" id="edit_term_conditions_form">
        @csrf

        <input type="hidden" name="id" value="{{ $term->id }}">

        <div class="mb-3">
            <label for="company_id" class="form-label">Company Name</label>
            <select name="company_id" id="" class="form-control">
                <option value="">Select Company Name</option>
                @foreach ($company as $item)
                    <option value="{{ $item->id }}" @if (isset($term) && $item->id == $term->company_id) {{ 'selected' }} @endif>
                        {{ $item->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="term_condition" class="form-label">Term & Condition</label>
            {{-- <input type="text" name="term_condition" class="form-control" value="{{ $term->term_condition }}"> --}}

            <textarea name="term_condition" class="form-control" cols="30" rows="10">{{ $term->term_condition }}</textarea>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
