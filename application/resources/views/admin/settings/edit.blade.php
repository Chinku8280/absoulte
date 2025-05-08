<div class="modal-header">
    <h5 class="modal-title">Edit zone</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="add-zone my-3">
        <form id="zone_form" method="post" name="zone_form" action="{{ route('zonesettings.update',$zone->id) }}">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label"> Zone Name:</label>
                        <input type="text" name="zone_name" id="zone_name" class="form-control" value="{{$zone->zone_name}}"
                            required="">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label">P/C Begins With:</label>
                        <input type="text" name="zone_number[]" id="zone_number" class="form-control zoneNumber"
                        value="{{$zone->postal_code}}" placeholder="380004" required=""
                            style="width: 250px; min-height: 36px; height: 17px;" data-role="tagsinput">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label">Color:</label>
                        <input type="color" name="zone_color" id="zone_color" class="form-control form-control-color"
                            value="{{$zone->zone_color}}" title="Choose your color">
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <select name="zone_status" id="zone_status" class="form-control" required>
                        <option value="1" {{$zone->status == 1 ? 'selected' : ''}}>Active</option>
                        <option value="0" {{$zone->status == 0 ? 'selected' : ''}}>Deactive</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveButton">Update </button>

            </div>
        </form>
    </div>
</div>

<script >
    $(document).ready(function() {
        $('.zoneNumber').tagsInput({
           'defaultText': 'Add a P/C',
            'maxChars': 5,
            'onChange': function() {
                // Handle changes here
            }
        });
    });
</script>
