$(document).ready(function() {
    $('.icheckbox_minimal-blue').removeClass('checked');

    $.fn.dataTable.ext.type.order['custom-date-asc'] = function(a, b) {
        return parseDate(a) - parseDate(b);
    };

    $.fn.dataTable.ext.type.order['custom-date-desc'] = function(a, b) {
        return parseDate(b) - parseDate(a);
    };

    function parseDate(dateRange) {
        // Extract the first date (start date) from the range
        let dateString = dateRange.split(" to ")[0];
        let parts = dateString.split("-");
        return new Date(parts[2], parts[1] - 1, parts[0]).getTime(); // Convert to timestamp
    }


    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "timesheet/leave_list/",
            type: 'GET'
        },
        columnDefs: [
            { targets: 4, type: 'custom-date' } // Apply custom sorting to the first column
        ],
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    var xin_my_team_table = $('#xin_my_team_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "timesheet/my_team_leave_list/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });
    jQuery("#aj_company").change(function() {
        jQuery.get(base_url + "/get_leave_employees/" + jQuery(this).val(), function(data, status) {
            jQuery('#employee_ajax').html(data);
        });
    });
    //employee_id
    //filter
    jQuery("#aj_companyf").change(function() {
        jQuery.get(site_url + "payroll/get_employees/" + jQuery(this).val(), function(data, status) {
            jQuery('#employee_ajaxf').html(data);
        });
    });
    $('#remarks').trumbowyg();
    $("#ihr_report").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var xin_table2 = $('#xin_table').dataTable({
            "bDestroy": true,
            "ajax": {
                url: site_url + "timesheet/leave_list/?ihr=true&company_id=" + $('#aj_companyf').val() + "&employee_id=" + $('#employee_id').val() + "&status=" + $('#status').val() + "&from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val(),
                type: 'GET'
            },
            "fnDrawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        xin_table2.api().ajax.reload(function() {}, true);
    });
    // Date
    $('.date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy',
        yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10),
    });

    /* Delete data */
    $("#delete_record").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=2&type=delete&form=" + action,
            cache: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                } else {
                    $('.delete-modal').modal('toggle');
                    xin_table.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });

    // edit
    $('.edit-modal-data').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var leave_id = button.data('leave_id');
        var modal = $(this);
        $.ajax({
            url: site_url + "timesheet/read_leave_record/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&data=leave&leave_id=' + leave_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    });

    /* Add data */
    /*Form Submit*/
    $("#xin-form").submit(function(e) {
        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 1);
        fd.append("add_type", 'leave');
        fd.append("form", action);
        e.preventDefault();
        $('.icon-spinner3').show();
        $('.save').prop('disabled', true);
        $.ajax({
            url: e.target.action,
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    xin_table.api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('#xin-form')[0].reset(); // To reset form fields
                    $('.add-form').removeClass('in');
                    $('.select2-selection__rendered').html('--Select--');
                    $('.save').prop('disabled', false);
                    $('.icheckbox_minimal-blue').removeClass('checked');
                }
            },
            error: function() {
                toastr.error(JSON.error);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                $('.icon-spinner3').hide();
                $('.save').prop('disabled', false);
            }
        });
    });
});
$(document).on("click", ".delete", function() {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', site_url + 'timesheet/delete_leave/' + $(this).data('record-id'));
});