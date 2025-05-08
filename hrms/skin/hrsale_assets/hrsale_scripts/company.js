$(document).ready(function () {
	var xin_table = $('#xin_table').dataTable({
		"bDestroy": true,
		"ajax": {
			url: base_url + "/company_list/",
			type: 'GET'
		},
		dom: 'lBfrtip',
		"buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
		"fnDrawCallback": function (settings) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});

	$('[data-plugin="xin_select"]').select2($(this).attr('data-options'));
	$('[data-plugin="xin_select"]').select2({ width: '100%' });


	$('.add_new_section').click(function () {
		
		$('.bank_section').append(` <div class="show_section"><div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Bank Name
                                    </label>
                                    <input type="text" name="bank_name[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Bank Account Number
                                    </label>
                                    <input type="text" name="bank_account_number[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="email">Bank Code
                                    </label>
                                    <input type="text" name="bank_code[]" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 22px;">
                                <div class="form-group">
                                    <button type="button"
                                        class="btn icon-btn  btn-success waves-effect waves-light delete_new_section">
                                        <span class="fa fa-trash-o"></span>
                                    </button>
                                </div>
                            </div></div>`);

		$('.delete_new_section').click(function () {
			console.log(11)
			$(this).closest('.show_section').remove();
		})

	})


	/* Delete data */
	$("#delete_record").submit(function (e) {
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=2&form=" + action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
				} else {
					$('.delete-modal').modal('toggle');
					xin_table.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
				}
			}
		});
	});

	// edit
	$('.edit-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var company_id = button.data('company_id');
		var modal = $(this);
		$.ajax({
			url: base_url + "/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=company&company_id=' + company_id,
			success: function (response) {
				if (response) {
					$("#ajax_modal").html(response);
				}
			}
		});
	});

	// view
	$('.view-modal-data').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var company_id = button.data('company_id');
		var modal = $(this);
		$.ajax({
			url: base_url + "/read/",
			type: "GET",
			data: 'jd=1&is_ajax=1&mode=modal&data=view_company&company_id=' + company_id,
			success: function (response) {
				if (response) {
					$("#ajax_modal_view").html(response);
				}
			}
		});
	});

	/* Add data */ /*Form Submit*/
	$("#xin-form").submit(function (e) {
		var fd = new FormData(this);
		var obj = $(this), action = obj.attr('name');
		fd.append("is_ajax", 1);
		fd.append("add_type", 'company');
		fd.append("form", action);
		e.preventDefault();
		$('.save').prop('disabled', true);

		$.ajax({
			url: base_url + '/add_company/',//e.target.action,
			type: "POST",
			data: fd,
			contentType: false,
			cache: false,
			processData: false,
			success: function (JSON) {
				if (JSON.error != '') {
					toastr.error(JSON.error);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					xin_table.api().ajax.reload(function () {
						toastr.success(JSON.result);
					}, true);
					$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
					$('#xin-form')[0].reset(); // To reset form fields
					$('.add-form').removeClass('in');
					$('.select2-selection__rendered').html('--Select--');
					$('.save').prop('disabled', false);
				}
			},
			error: function () {
				toastr.error(JSON.error);
				$('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
				$('.save').prop('disabled', false);
			}
		});
	});
});
//open the lateral panel
$(document).on("click", ".cd-btn", function () {
	event.preventDefault();
	var company_id = $(this).data('company_id');
	$.ajax({
		url: site_url + "company/read_info/",
		type: "GET",
		data: 'jd=1&is_ajax=1&mode=modal&data=view_company&company_id=' + company_id,
		success: function (response) {
			if (response) {
				//alert(response);
				$('.cd-panel').addClass('is-visible');
				$("#cd-panel").html(response);
			}
		}
	});

});
//clode the lateral panel
$(document).on("click", ".cd-panel", function () {
	if ($(event.target).is('.cd-panel') || $(event.target).is('.cd-panel-close')) {
		$('.cd-panel').removeClass('is-visible');
		event.preventDefault();
	}
});

$(document).on("click", ".delete", function () {
	$('input[name=_token]').val($(this).data('record-id'));
	$('#delete_record').attr('action', base_url + '/delete/' + $(this).data('record-id'));
});