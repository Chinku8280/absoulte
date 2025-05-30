$(document).ready(function () {

    $("#immigrationstatus").on('change', function () {
        var ims = $(this).val();
        if (ims == 2) {
            $("#pr-form").css("display", "block");
        } else {
            $("#pr-form").css("display", "none");
        }
    });
    $('.date_of_joining').datepicker({
        changeMonth: true,
        changeYear: true,
        //   dateFormat:'yy-mm-dd',
        dateFormat: js_date_format,
        yearRange: '1940:' + (new Date().getFullYear() + 1)
    });

    $('#other_payment_select').change(function () {
        console.log(11)
        if ($(this).val() == 'Other' || $(this).val() == 'Gratuity' || $(this).val() == 'Pension' || $(this).val() == 'Retirement Fund') {
            $('#title_other').css('display', '');
        } else {
            $('#title_other').css('display', 'none');
        }
    })

    // get data
    $('.edit-modal-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var field_id = button.data('field_id');
        var field_tpe = button.data('field_type');
        if (field_tpe == 'salary_payslip') {
            var field_add = '&data=salary_payslip&type=salary_payslip&salary_id=' + button.data('salary_id') + '&';
        } else if (field_tpe == 'contact') {
            var field_add = '&data=emp_contact&type=emp_contact&';
        } else if (field_tpe == 'document') {
            var field_add = '&data=emp_document&type=emp_document&';
        } else if (field_tpe == 'qualification') {
            var field_add = '&data=emp_qualification&type=emp_qualification&';
        } else if (field_tpe == 'work_experience') {
            var field_add = '&data=emp_work_experience&type=emp_work_experience&';
        } else if (field_tpe == 'bank_account') {
            var field_add = '&data=emp_bank_account&type=emp_bank_account&';
        } else if (field_tpe == 'contract') {
            var field_add = '&data=emp_contract&type=emp_contract&';
        } else if (field_tpe == 'leave') {
            var field_add = '&data=emp_leave&type=emp_leave&';
        } else if (field_tpe == 'shift') {
            var field_add = '&data=emp_shift&type=emp_shift&';
        } else if (field_tpe == 'location') {
            var field_add = '&data=emp_location&type=emp_location&';
        } else if (field_tpe == 'imgdocument') {
            var field_add = '&data=e_imgdocument&type=e_imgdocument&';
        } else if (field_tpe == 'salary_allowance') {
            var field_add = '&data=e_salary_allowance&type=e_salary_allowance&';
        } else if (field_tpe == 'salary_loan') {
            var field_add = '&data=e_salary_loan&type=e_salary_loan&';
        } else if (field_tpe == 'emp_overtime') {
            var field_add = '&data=emp_overtime_info&type=emp_overtime_info&';
        } else if (field_tpe == 'salary_commissions') {
            var field_add = '&data=salary_commissions_info&type=salary_commissions_info&';
        } else if (field_tpe == 'salary_statutory_deductions') {
            var field_add = '&data=salary_statutory_deductions_info&type=salary_statutory_deductions_info&';
        } else if (field_tpe == 'salary_other_payments') {
            var field_add = '&data=salary_other_payments_info&type=salary_other_payments_info&';
        } else if (field_tpe == 'security_level') {
            var field_add = '&data=esecurity_level_info&type=esecurity_level_info&';
        } else if (field_tpe == 'employee_leave') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_employee_leave&';
            var tb_name = 'xin_table_emp_leaves';
        } else if (field_tpe == 'employee_claim') {
            var field_add = '&data=es_employee_claim_info&type=es_employee_claim_info&';
        } else if (field_tpe == 'employee_share_option') {
            var field_add = '&data=es_employee_share_option&type=es_employee_share_option&';
        } else if (field_tpe == 'salary_deduction') {
            var field_add = '&data=employee_salary_deduction&type=employee_salary_deduction&';
        }
        else if (field_tpe == 'emp_overtime_rate') {
            var field_add = '&data=emp_overtime_rate&type=emp_overtime_rate&';
        } else if (field_tpe == 'exempt_indicator') {
            var field_add = '&data=emp_exempt_indicator&type=emp_exempt_indicator&';
        }   else if (field_tpe == 'income_tax_born') {
            var field_add = '&data=emp_income_tax_born&type=emp_income_tax_born&';
        }

        var modal = $(this);
        $.ajax({
            url: site_url + 'employees/dialog_' + field_tpe + '/',
            type: "GET",
            data: 'jd=1' + field_add + 'field_id=' + field_id,
            success: function (response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    });

    // Month & Year
    $('.ln_month_year').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        yearRange: '1900:' + (new Date().getFullYear() + 15),
        beforeShow: function (input) {
            $(input).datepicker("widget").addClass('hide-calendar');
        },
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            $(this).datepicker('widget').removeClass('hide-calendar');
            $(this).datepicker('widget').hide();
        }

    });
    /* Update basic info */
    $("#basic_info").submit(function (e) {
        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 1);
        fd.append("type", 'basic_info');
        fd.append("data", 'basic_info');
        fd.append("form", action);
        e.preventDefault();
        $('.icon-spinner3').show();
        $('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        $.ajax({
            url: e.target.action,
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                } else {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.success(JSON.result);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                }
            },
            error: function () {
                //toastr.clear();
                //$('#hrload-img').hide();
                toastr.error(JSON.error);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                $('.icon-spinner3').hide();
                $('.save').prop('disabled', false);
            }
        });
    });
    $("#basic_infoddd").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=1&data=basic_info&type=basic_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                } else {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.success(JSON.result);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
    // get current val
    $(".basic_salary").keyup(function (e) {
        var to_currency_rate = $('#to_currency_rate').val();
        var curr_val = $(this).val();
        var final_val = to_currency_rate * curr_val;
        var float_val = final_val.toFixed(2);
        $('#current_cur_val').html(float_val);
    });
    $(".daily_wages").keyup(function (e) {
        var to_currency_rate = $('#to_currency_rate').val();
        var curr_val = $(this).val();
        var final_val = to_currency_rate * curr_val;
        var float_val = final_val.toFixed(2);
        $('#current_cur_val2').html(float_val);
    });

    /*jQuery("#wages_type").change(function(){
        var wopt = $(this).val();
        if(wopt == 1){
            $('#deduct_options').show();
            $('#half_monthly_is').show();
        } else {
            $('#deduct_options').hide();
            $('#half_monthly_is').hide();
        }
    });*/

    /* Update profile picture */
    $("#f_profile_picture").submit(function (e) {
        var fd = new FormData(this);
        var user_id = $('#user_id').val();
        var session_id = $('#session_id').val();
        $('.icon-spinner3').show();
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 2);
        fd.append("type", 'profile_picture');
        fd.append("data", 'profile_picture');
        fd.append("form", action);
        e.preventDefault();
        $('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        $.ajax({
            url: e.target.action,
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                } else {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.success(JSON.result);
                    $('.icon-spinner3').hide();
                    $('#remove_file').show();
                    $(".profile-photo-emp").remove('checked');
                    $('#u_file').attr("src", JSON.img);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    if (user_id == session_id) {
                        $('.user_avatar').attr("src", JSON.img);
                    }
                    $('.save').prop('disabled', false);
                }
            },
            error: function () {
                //toastr.clear();
                //$('#hrload-img').hide();
                toastr.error(JSON.error);
                $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                $('.icon-spinner3').hide();
                $('.save').prop('disabled', false);
            }
        });
    });

    /* Update social networking */
    $("#f_social_networking").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=social_info&type=social_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            }
        });
    });

    // get departments
    /*jQuery("#aj_company").change(function(){
        jQuery.get(base_url+"/get_departments/"+jQuery(this).val(), function(data, status){
            jQuery('#department_ajax').html(data);
        });
    });*/
    jQuery("#aj_company").change(function () {
        jQuery.get(escapeHtmlSecure(base_url + "/get_company_elocations/" + jQuery(this).val()), function (data, status) {
            jQuery('#location_ajax').html(data);
        });
        jQuery.get(escapeHtmlSecure(base_url + "/get_company_office_shifts/" + jQuery(this).val()), function (data, status) {
            jQuery('#ajax_office_shift').html(data);
        });
    });
    jQuery("#location_id").change(function () {
        jQuery.get(base_url + "/get_location_departments/" + jQuery(this).val(), function (data, status) {
            jQuery('#department_ajax').html(data);
        });
    });
    // get sub departments
    jQuery("#aj_subdepartments").change(function () {
        jQuery.get(base_url + "/get_sub_departments/" + jQuery(this).val(), function (data, status) {
            jQuery('#subdepartment_ajax').html(data);
        });
    });
    // get designations
    jQuery("#aj_subdepartment").change(function () {
        jQuery.get(base_url + "/designation/" + jQuery(this).val(), function (data, status) {
            jQuery('#designation_ajax').html(data);
        });
    });
    jQuery("#is_aj_subdepartments").change(function () {
        jQuery.get(base_url + "/is_designation/" + jQuery(this).val(), function (data, status) {
            jQuery('#designation_ajax').html(data);
        });
    });

    $(".nav-tabs-link").click(function () {
        var profile_id = $(this).data('profile');
        var profile_block = $(this).data('profile-block');
        $('.nav-tabs-link').removeClass('active');
        $('.current-tab').hide();
        $('#user_profile_' + profile_id).addClass('active');
        $('#' + profile_block).show();
    });
    $(".salary-tab").click(function () {
        var profile_id = $(this).data('profile');
        var profile_block = $(this).data('profile-block');
        $('.salary-tab-list').removeClass('active');
        $('.salary-current-tab').hide();
        $('#suser_profile_' + profile_id).addClass('active');
        $('#' + profile_block).show();
    });
    $(".xin-core-hr-opt").click(function () {
        var core_hr_info = $(this).data('core-hr-info');
        var core_profile_block = $(this).data('core-profile-block');
        $('.xin-core-hr-tab').removeClass('active');
        $('.core-current-tab').hide();
        $('#core_hr_' + core_hr_info).addClass('active');
        $('#' + core_profile_block).show();
    });
    $(".core-projects").click(function () {
        var core_project_info = $(this).data('core-project-info');
        var core_projects_block = $(this).data('core-projects-block');
        $('.core-projects-tab').removeClass('active');
        $('#core_projects_' + core_project_info).addClass('active');
        $('.project-current-tab').hide();
        $('#' + core_projects_block).show();
    });


    // On page load: table_contacts
    var xin_table_contact = $('#xin_table_contact').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/contacts/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // leave
    var xin_table_emp_leaves = $('#xin_table_emp_leaves').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/getEmployeeLeaves/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // overtime
    var xin_table_all_over_time = $('#xin_table_all_over_time').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/getEmployeeOvertime/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // claim
    var xin_table_claim = $('#xin_table_claim').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/getEmployeeClaims/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load > documents
    var xin_table_immigration = $('#xin_table_imgdocument').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/immigration/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load > documents
    var xin_table_document = $('#xin_table_document').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/documents/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load > qualification
    var xin_table_qualification = $('#xin_table_qualification').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/qualification/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load 
    var xin_table_work_experience = $('#xin_table_work_experience').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/experience/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load 
    var xin_table_bank_account = $('#xin_table_bank_account').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/bank_account/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    // On page load 
    var xin_table_security_level = $('#xin_table_security_level').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/security_level_list/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load > contract
    var xin_table_contract = $('#xin_table_contract').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/contract/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

      // On page load > contract
      var xin_table_income_tax_born = $('#xin_table_income_tax_born').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/income_tax_born_list/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

      // On page load > exempt_indicator
      var xin_table_exempt_indicator = $('#xin_table_exempt_indicator').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/exempt_indicator_list/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load > leave
    var xin_table_leave = $('#xin_table_leave').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/leave/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load 
    var xin_table_shift = $('#xin_table_shift').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/shift/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load 
    var xin_table_location = $('#xin_table_location').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/location/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load 
    var xin_table_emp_overtime = $('#xin_table_emp_overtime').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_overtime/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load 
    var xin_table_salary = $('#xin_table_all_payslips').dataTable({
        "bDestroy": true,
        "ordering": false,
        "ajax": {
            url: site_url + "employees/set_salary/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var xin_table_allowances_ad = $('#xin_table_all_allowances').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_all_allowances/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    var xin_table_commissions_ad = $('#xin_table_all_commissions').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_all_commissions/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    var xin_table_statutory_deductions_ad = $('#xin_table_all_statutory_deductions').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_all_statutory_deductions/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    var xin_table_deductions = $('#xin_table_deductions').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_deductions/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    var xin_table_other_payments_ad = $('#xin_table_all_other_payments').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_all_other_payments/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    // On page load 
    var xin_table_all_deductions = $('#xin_table_all_deductions').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/salary_all_deductions/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // On page load > xin_hrsale_table

    $('.xin_hrsale_table').DataTable();
    /* Add contact info */
    jQuery("#contact_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=contact_info&type=contact_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_contact.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#contact_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add contact info */
    jQuery("#contact_info2").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save2').prop('disabled', true);
        $('.icon-spinner33').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=contact_info&type=contact_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner33').hide();
                    jQuery('.save2').prop('disabled', false);
                } else {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner33').hide();
                    jQuery('.save2').prop('disabled', false);
                }
            }
        });
    });

    /* Add document info */
    $("#document_info").submit(function (e) {
        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 7);
        fd.append("type", 'document_info');
        fd.append("data", 'document_info');
        fd.append("form", action);
        e.preventDefault();
        $('.icon-spinner3').show();
        $('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        $.ajax({
            url: e.target.action,
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    xin_table_document.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('.icon-spinner3').hide();
                    jQuery('#document_info')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            },
            error: function () {
                //toastr.clear();
                //$('#hrload-img').hide();
                toastr.error(JSON.error);
                $('.save').prop('disabled', false);
            }
        });
    });


    //leave
    $('#employee_update_leave').submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        // console.log(obj);
        $('#hrload-img').show();
        toastr.info(processing_request);

        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=employee_leave_form&type=employee_leave_form&form=" + action,
            cache: false,
            success: function (JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_emp_leaves.api().ajax.reload(function () {
                        //toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('#hrload-img').hide();
                    toastr.clear();
                    toastr.success(JSON.result);
                    jQuery('#employee_update_leave')[0].reset(); // To reset form fields
                    $('[data-plugin="select_hrm"]').val(null).trigger('change');
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });

    //claim
    $('#employee_update_claim').submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');

        var fd = new FormData(this);
        fd.append("is_ajax", 4);
        fd.append("type", 'employee_claim_form');
        fd.append("data", 'employee_claim_form');
        fd.append("form", action);

        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        // console.log(obj);
        $('#hrload-img').show();
        toastr.info(processing_request);

        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            // data: obj.serialize() + "&is_ajax=4&data=employee_claim_form&type=employee_claim_form&form=" + action,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_claim.api().ajax.reload(function () {
                        //toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('#hrload-img').hide();
                    toastr.clear();
                    toastr.success(JSON.result);
                    jQuery('#employee_update_claim')[0].reset(); // To reset form fields
                    $('[data-plugin="select_hrm"]').val(null).trigger('change');
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });



    /* Add document info */
    $("#immigration_info").submit(function (e) {
        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 7);
        fd.append("type", 'immigration_info');
        fd.append("data", 'immigration_info');
        fd.append("form", action);
        e.preventDefault();
        $('.icon-spinner3').show();
        $('.save').prop('disabled', true);
        $('#hrload-img').show();
        toastr.info(processing_request);
        $.ajax({
            url: e.target.action,
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    xin_table_immigration.api().ajax.reload(function () {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('.icon-spinner3').hide();
                    jQuery('#immigration_info')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            },
            error: function () {
                toastr.clear();
                $('#hrload-img').hide();
                toastr.error(JSON.error);
                $('.save').prop('disabled', false);
            }
        });
    });

    /* Add qualification info */
    jQuery("#qualification_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=10&data=qualification_info&type=qualification_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    xin_table_qualification.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#qualification_info')[0].reset(); // To reset form fields
                    $('.icon-spinner3').hide();
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add work experience info */
    jQuery("#work_experience_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=13&data=work_experience_info&type=work_experience_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    xin_table_work_experience.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('.icon-spinner3').hide();
                    jQuery('#work_experience_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add bank account info */
    jQuery("#bank_account_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=16&data=bank_account_info&type=bank_account_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_bank_account.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('.icon-spinner3').hide();
                    jQuery('#bank_account_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });
    jQuery("#security_level_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=16&data=security_level_info&type=security_level_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('.icon-spinner3').hide();
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_security_level.api().ajax.reload(function () {
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('.icon-spinner3').hide();
                    jQuery('#security_level_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add contract info */
    jQuery("#contract_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=19&data=contract_info&type=contract_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_contract.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#contract_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

     /* Add income tax born info */
     jQuery("#income_tax_born_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=19&data=income_tax_born_info&type=income_tax_born_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_income_tax_born.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#income_tax_born_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });


    // "Remission/ Overseas Posting/ Exempt Indicator
    jQuery("#exempt_indicator_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=19&data=exempt_indicator_info&type=exempt_indicator_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_exempt_indicator.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#exempt_indicator_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });


    /* Add leave info */
    jQuery("#leave_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=22&data=leave_info&type=leave_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_leave.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#leave_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add shift info */
    jQuery("#shift_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=25&data=shift_info&type=shift_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_shift.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#shift_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add location info */
    jQuery("#location_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=28&data=location_info&type=location_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_location.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#location_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add change password */
    jQuery("#e_change_password").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=31&data=e_change_password&type=change_password&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    jQuery('#e_change_password')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });

    /* */
    $("#employee_update_salary").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=employee_update_salary&type=employee_update_salary&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.success(JSON.result);
                    xin_table_salary.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
    // add loan
    $("#add_loan_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=loan_info&type=loan_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    xin_table_all_deductions.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    jQuery('#add_loan_info')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Add info */
    jQuery("#employee_update_allowance").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=employee_update_allowance&type=employee_update_allowance&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_allowances_ad.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#employee_update_allowance')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });
    /* */
    jQuery("#employee_update_commissions").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=employee_update_commissions&type=employee_update_commissions&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_commissions_ad.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#employee_update_commissions')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });
    jQuery("#statutory_deductions_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=statutory_deductions_info&type=statutory_deductions_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_statutory_deductions_ad.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#statutory_deductions_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });
    jQuery("#deductions_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=deductions_info&type=deductions_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_deductions.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#deductions_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });
    jQuery("#other_payments_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = jQuery(this),
            action = obj.attr('name');
        jQuery('.save').prop('disabled', true);
        //$('#hrload-img').show();
        //toastr.info(processing_request);
        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=other_payments_info&type=other_payments_info&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_other_payments_ad.api().ajax.reload(function () {
                        //toastr.clear();
                        //$('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#other_payments_info')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            }
        });
    });
    /* */
    $("#overtime_info").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $('#hrload-img').show();
        toastr.info(processing_request);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=emp_overtime&type=emp_overtime&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    xin_table_all_over_time.api().ajax.reload(function () {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                    }, true);
                    $('#hrload-img').hide();
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    jQuery('#overtime_info')[0].reset(); // To reset form fields
                    $('.save').prop('disabled', false);
                }
            }
        });
    });
    $('.view-modal-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var xfield_id = button.data('xfield_id');
        var field_type = button.data('field_type');
        var field_key = '';
        if (field_type == 'awards') {
            var view_info = 'view_award';
            var field_key = 'award_id';
        } else if (field_type == 'travel') {
            var view_info = 'view_travel';
            var field_key = 'travel_id';
        } else if (field_type == 'transfers') {
            var view_info = 'view_transfer';
            var field_key = 'transfer_id';
        } else if (field_type == 'promotion') {
            var view_info = 'view_promotion';
            var field_key = 'promotion_id';
        } else if (field_type == 'complaints') {
            var view_info = 'view_complaint';
            var field_key = 'complaint_id';
        } else if (field_type == 'warning') {
            var view_info = 'view_warning';
            var field_key = 'warning_id';
        }
        var modal = $(this);
        $.ajax({
            url: site_url + field_type + "/read/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=view_modal&data=' + view_info + '&' + field_key + '=' + xfield_id,
            success: function (response) {
                if (response) {
                    $("#ajax_modal_view").html(response);
                }
            }
        });
    });


    /** Allowance
     * Recurring Allowance
     */
    $('#recurring_other_allowance_field').hide();
    $('#rec_allowance').on('change', function () {
        var alw_id = this.value;
        if (alw_id == 'other') {
            $('#recurring_other_allowance_field').show();
        } else {
            $('#recurring_other_allowance_field').hide();
        }
    });

    $('#employee_update_recurring_allowance').submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        // console.log(obj);
        $('#hrload-img').show();
        toastr.info(processing_request);

        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=employee_update_recurring_allowance&type=employee_update_recurring_allowance&form=" + action,
            cache: false,
            success: function (JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_allowances_ad.api().ajax.reload(function () {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#employee_update_recurring_allowance')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });


    // Month & Year
    $('.month_year').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm-yy',
        yearRange: '1900:' + (new Date().getFullYear() + 15),
        beforeShow: function (input) {
            $(input).datepicker("widget").addClass('hide-calendar');
        },
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            $(this).datepicker('widget').removeClass('hide-calendar');
            $(this).datepicker('widget').hide();
        }

    });


    $('#adhoc_other_allowance_field').hide();
    $('#adhoc_allowance').on('change', function () {
        var alw_id = this.value;
        if (alw_id == 'other') {
            $('#adhoc_other_allowance_field').show();
        } else {
            $('#adhoc_other_allowance_field').hide();
        }
    });

    $('#employee_update_adhoc_allowance').submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        // console.log(obj);
        $('#hrload-img').show();
        toastr.info(processing_request);

        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=employee_update_adhoc_allowance&type=employee_update_adhoc_allowance&form=" + action,
            cache: false,
            success: function (JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_allowances_ad.api().ajax.reload(function () {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#employee_update_adhoc_allowance')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });


    $("#overtime_rate_type").on('change', function () {
        $("#overtime_rate").val("");
        $("#overtime_rate_amount").val("");
    });
    $("#overtime_rate").on('change paste keyup', function () {
        var rate = $(this).val();
        var rate_type = $('#overtime_rate_type').val();
        var user_id = $('#user_id').val();

        jQuery.get(base_url + "/get_overtime_rate/" + user_id + "/" + rate_type + "/" + rate, function (data, status) {
            console.log(data);
            if (data.result != '') {
                jQuery('#overtime_rate_amount').val(data.result);
            }
        });
    });


    $('#income_tax_born_select').change(function () {
        // console.log($("#income_tax_born_select option:selected").val());
        if ($("#income_tax_born_select option:selected").val() == 'Yes, tax is PARTIALLY borne by employer' || $("#income_tax_born_select option:selected").val() == 'Yes, a FIXED amount of tax is borne by employee') {
            console.log(14)
            $('#tax_amount_section').css('display', '');
        } else {
            $('#tax_amount_section').css('display', 'none');
        }
    });



    //contribution
    /* */
    $("#employee_update_contribution").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        // console.log(obj);
        $('#hrload-img').show();
        toastr.info(processing_request);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=employee_update_contribution&type=employee_update_contribution&form=" + action,
            cache: false,
            success: function (JSON) {
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
                    toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });


    var xin_table_emp_shares = $('#xin_table_emp_shares').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employees/getEmployeeShareOptions/" + $('#user_id').val(),
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    //share options
    $('#employee_share_options').submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        // console.log(obj);
        $('#hrload-img').show();
        toastr.info(processing_request);

        jQuery.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=4&data=share_options_form&type=share_options_form&form=" + action,
            cache: false,
            success: function (JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_emp_shares.api().ajax.reload(function () {
                        //toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('#hrload-img').hide();
                    toastr.clear();
                    toastr.success(JSON.result);
                    jQuery('#employee_share_options')[0].reset(); // To reset form fields
                    $('[data-plugin="select_hrm"]').val(null).trigger('change');
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });




    /* Delete data */
    $("#delete_record").submit(function (e) {
        var tk_type = $('#token_type').val();
        if (tk_type == 'contact') {
            var field_add = '&is_ajax=6&data=delete_record&type=delete_contact&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'document') {
            var field_add = '&is_ajax=8&data=delete_record&type=delete_document&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'qualification') {
            var field_add = '&is_ajax=12&data=delete_record&type=delete_qualification&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'work_experience') {
            var field_add = '&is_ajax=15&data=delete_record&type=delete_work_experience&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'bank_account') {
            var field_add = '&is_ajax=18&data=delete_record&type=delete_bank_account&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'contract') {
            var field_add = '&is_ajax=21&data=delete_record&type=delete_contract&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'leave') {
            var field_add = '&is_ajax=24&data=delete_record&type=delete_leave&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'shift') {
            var field_add = '&is_ajax=27&data=delete_record&type=delete_shift&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'location') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_location&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'imgdocument') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_imgdocument&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'all_allowances') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_salary_allowance&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'all_deductions') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_salary_loan&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'emp_overtime') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_salary_overtime&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'emp_overtime_rate') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_overtime_record_rate&';
            var tb_name = 'xin_table_all_over_time';
        } else if (tk_type == 'all_commissions') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_all_commissions&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'all_statutory_deductions') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_all_statutory_deductions&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'all_other_payments') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_all_other_payments&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'security_level') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_security_level&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'training_info') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_training_info&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'employee_leave') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_employee_leave&';
            var tb_name = 'xin_table_emp_leaves';
        } else if (tk_type == 'employee_claim') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_employee_claim_info&';
            var tb_name = 'xin_table_claim';
        } else if (tk_type == 'employee_share_option') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_employee_share_option&';
            var tb_name = 'xin_table_emp_shares';
        } else if (tk_type == 'salary_deductions') {
            var field_add = '&is_ajax=30&data=delete_record&type=delete_salary_deduction&';
            var tb_name = 'xin_table_deductions';
        } else if (tk_type == 'exempt_indicator') {
            var field_add = '&is_ajax=6&data=delete_record&type=delete_exempt_indicator&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'income_tax_born') {
            var field_add = '&is_ajax=6&data=delete_record&type=delete_income_tax_born&';
            var tb_name = 'xin_table_' + tk_type;
        } 

        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $.ajax({
            url: e.target.action,
            type: "post",
            data: '?' + obj.serialize() + field_add + "form=" + action,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                } else {
                    $('.delete-modal').modal('toggle');
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('#' + tb_name).dataTable().api().ajax.reload(function () {
                        toastr.success(JSON.result);
                    }, true);

                }
            }
        });
    });
    /// delete a record
    $(document).on("click", ".delete", function () {
        $('input[name=_token]').val($(this).data('record-id'));
        $('input[name=token_type]').val($(this).data('token_type'));
        $('#delete_record').attr('action', site_url + 'employees/delete_' + $(this).data('token_type') + '/' + $(this).data('record-id'));
    });
});
$(document).ready(function () {

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });

    $('#reports_to').select2({
        placeholder: 'Select options', // Placeholder text
        // allowClear: true               // Option to clear selected values
    });

    $('.cont_date').datepicker({
        changeMonth: true,
        changeYear: true,
        // dateFormat: 'yy-mm-dd',
        dateFormat: js_date_format,
        yearRange: '1990:' + (new Date().getFullYear() + 10),
    });
    jQuery("#leaveyear").change(function () {

        var employee_id = $("#employee_id").val();
        var leave_id = $('#leavetype').val();
        $.ajax({
            url: base_url + "/get_leave_details/" + jQuery(this).val() + '/' + employee_id + "/" + leave_id,
            type: "get",
            dataType: 'json',
            success: function (data) {
                console.log(data);
                // $("#carried_leave").val(data.carried_leave);
                $("#hdn_balance_leave").val(data.carried_leave);
                $("#balance_leave").val(parseInt(data.carried_leave));

            }
        });
        // jQuery.get(escapeHtmlSecure(base_url + "/get_leave_details/" + jQuery(this).val()+'/' + employee_id), function(data, status) {
        //     console.log(data);
        //     var ar_data=JSON.parse(data);

        //     $("#carried_leave").val(ar_data.carried_leave);
        //     $("#hdn_balance_leave").val(ar_data.carried_leave);

        //    // jQuery('#balance_leave').html(data);
        // });

    });

    jQuery("#noofdays").keyup(function () {
        if (parseFloat($('#hdn_balance_leave').val()) > 0) {
            var balance_leave = parseFloat($('#hdn_balance_leave').val());
        } else {
            var balance_leave = 0;
        }
        if (parseFloat($(this).val()) > 0) {
            var total_leave = parseFloat($(this).val());
        } else {
            var total_leave = 0;
        }
        var total = balance_leave + total_leave;

        $('#balance_leave').val(total);
    });
    // jQuery("#carried_leave").keyup(function(){
    //     if(parseFloat($('#noofdays').val()) > 0){
    // var no_of_leave = parseFloat($('#noofdays').val());
    // }else{
    //     var no_of_leave =0;
    // }
    // if(parseFloat($(this).val()) > 0){
    // var carried_leave =parseFloat($(this).val());
    // }else{
    //     var carried_leave =0;
    // }

    // var total = no_of_leave + carried_leave;

    // $('#balance_leave').val(total);
    // });
    // jQuery("#carried_leave").keyup(function () { 
    //     console.log($('#balance_leave').val());
    //             if ($("#balance_leave").val() < $(this).val()) {
    //                 toastr.error("Balance Leave Not Less Then Carried Forward Cap!");
    //                 $(this).val("");
    //             }
    //         });
});