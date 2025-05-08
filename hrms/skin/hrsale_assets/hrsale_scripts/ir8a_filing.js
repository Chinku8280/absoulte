$(document).ready(function() {
    $("#efilling").addClass('active');
    $("#ir8a").addClass('active');
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });

    var year = jQuery('#year').val();


    var xin_table_employee_summary = $('#xin_table_employee_summary').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "efiling/employeeSummary/" + year,
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });



    var xin_table_employee_ir8a_form = $('#xin_table_employee_ir8a_form').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "efiling/employeeIr8aForm/" + year,
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('#company').change(function () {
        $('#gen_company').val($(this).val()); 
        var company = this.value;
        var yr = $('#year').val();
        console.log(base_url + "/ir8a/year/" + yr + "/" + company);
        window.location.href = base_url + "/ir8a/year/" + yr + "/" + company;
    });

    $('#year').on('change', function() {
        var yr = this.value;
        var company = $('#company').val();
        console.log(base_url + "/ir8a/year/" + yr + "/" + company);
        window.location.href = base_url + "/ir8a/year/" + yr + "/" + company;
    });

    $("#ir8a_generate_form").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $('#hrload-img').show();
        var year = $('#year').val();
        var company = $('#company').val();
        toastr.info(processing_request);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=ir8a_generate_form&type=ir8a_generate_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    //toastr.clear();
                    $('#hrload-img').hide();
                    $('#generate_cont').hide();
                    // toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                    xin_table_employee_ir8a_form.api().ajax.url(site_url + "efiling/employeeIr8aForm/" + year + "/" + company).load();
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    setTimeout(function() {
                        window.location.reload();
                    }, 2200);

                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    $('#if8aResetModal').on('shown.bs.modal', function() {
        // $('#myInput').trigger('focus')
    });

    $("#ir8a_reset_form").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $('#hrload-img').show();
        var year = $('#year').val();
        toastr.info(processing_request);
        $("#if8aResetModal").modal('toggle');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=ir8a_reset_form&type=ir8a_reset_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    //toastr.clear();
                    $('#hrload-img').hide();
                    $('#generate_cont').hide();
                    // toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                    xin_table_employee_ir8a_form.api().ajax.url(site_url + "efiling/employeeIr8aForm/" + year).load();
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    setTimeout(function() {
                        window.location.reload();
                    }, 2200);

                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });
});