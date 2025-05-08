$(document).ready(function () {
    var employee_id = jQuery('#employee_id').val();
    var month_year = jQuery('#month_year').val();
    var company_id = jQuery('#aj_company').val();

    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "payroll/payslip_list/?employee_id=" + employee_id + "&company_id=" + company_id + "&month_year=" + month_year,
            type: 'GET'
        },
        "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });
    jQuery("#aj_company").change(function () {
        jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function (data, status) {
            jQuery('#employee_ajax').html(data);
        });
    });
    jQuery("#aj_companyx").change(function () {
        jQuery.get(escapeHtmlSecure(base_url + "/get_company_plocations/" + jQuery(this).val()), function (data, status) {
            jQuery('#location_ajax').html(data);
        });
    });
    // Month & Year
    $('.month_year').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm-yy',
        yearRange: '1970:' + (new Date().getFullYear()+ 1),
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

    // delete
    /* Delete data */
    $("#delete_record").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=2&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                } else {
                    $('.delete-modal').modal('toggle');
                    xin_table.api().ajax.reload(function () {
                        toastr.success(JSON.result);
                    }, true);
                }
            }
        });
    });

    // detail modal data payroll
    $('.payroll_template_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var modal = $(this);
        $.ajax({
            url: site_url + 'payroll/payroll_template_read/',
            type: "GET",
            data: 'jd=1&is_ajax=11&mode=not_paid&data=payroll_template&type=payroll_template&employee_id=' + employee_id,
            success: function (response) {
                if (response) {
                    $("#ajax_modal_payroll").html(response);
                }
            }
        });
    });

    /*$('.view-modal-data').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var pay_id = button.data('pay_id');
    var modal = $(this);
    $.ajax({
        url: site_url+'payroll/payroll_template_approve/',
        type: "GET",
        data: 'jd=1&is_ajax=11&mode=not_paid&data=payroll_approve&type=payroll_approve&pay_id='+pay_id,
        success: function (response) {
            if(response) {
                $("#ajax_modal").html(response);
            }
        }
    });
    });*/

    // detail modal data  hourlywages
    $('.hourlywages_template_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var payment_date = $('#month_year').val();
        var company_id = button.data('company_id');
        var modal = $(this);
        $.ajax({
            url: site_url + 'payroll/hourlywage_template_read/',
            type: "GET",
            data: 'jd=1&is_ajax=11&mode=not_paid&data=hourly_payslip&type=read_hourly_payment&employee_id=' + employee_id + '&pay_date=' + payment_date + '&company_id=' + company_id,
            success: function (response) {
                if (response) {
                    $("#ajax_modal_hourlywages").html(response);
                }
            }
        });
    });

    // detail modal data
    $('.detail_modal_data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var pay_id = button.data('pay_id');
        var company_id = button.data('company_id');
        var modal = $(this);
        $.ajax({
            url: site_url + 'payroll/make_payment_view/',
            type: "GET",
            data: 'jd=1&is_ajax=11&mode=modal&data=pay_payment&type=pay_payment&emp_id=' + employee_id + '&pay_id=' + pay_id + '&company_id=' + company_id,
            success: function (response) {
                if (response) {
                    $("#ajax_modal_details").html(response);
                }
            }
        });
    });


    // detail modal data
    $('.emo_monthly_pay').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var payment_date = $('#month_year').val();
        var company_id = button.data('company_id');
        var modal = $(this);
        $.ajax({
            url: site_url + 'payroll/pay_salary/',
            type: "GET",
            data: 'jd=1&is_ajax=11&data=payment&type=monthly_payment&employee_id=' + employee_id + '&pay_date=' + payment_date + '&company_id=' + company_id,
            success: function (response) {
                if (response) {
                    $("#emo_monthly_pay_aj").html(response);
                }
            }
        });
    });

    $('.emo_hourly_pay').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var payment_date = $('#month_year').val();
        var company_id = button.data('company_id');
        var modal = $(this);
        $.ajax({
            url: site_url + 'payroll/pay_hourly/',
            type: "GET",
            data: 'jd=1&is_ajax=11&data=hourly_payment&type=fhourly_payment&employee_id=' + employee_id + '&pay_date=' + payment_date + '&company_id=' + company_id,
            success: function (response) {
                if (response) {
                    $("#emo_hourly_pay_aj").html(response);
                }
            }
        });
    });
    // bulk payments
    $("#bulk_payment").submit(function (e) {
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        var employee_id = jQuery('#employee_id').val();
        var bmonth_year = jQuery('#bmonth_year').val();
        var company_id = jQuery('#aj_company').val()
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=1&add_type=payroll&form=" + action,
            cache: false,
            success: function (JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    var xin_table3 = $('#xin_table').dataTable({
                        "bDestroy": true,
                        "ajax": {
                            url: site_url + "payroll/payslip_list/?employee_id=" + employee_id + "&company_id=" + company_id + "&month_year=" + bmonth_year,
                            type: 'GET'
                        },
                        "fnDrawCallback": function (settings) {
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    });
                    xin_table3.api().ajax.reload(function () {
                        toastr.success(JSON.result);
                    }, true);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });
    /* Add data */
    /*Form Submit*/
    $("#user_salary_template").submit(function (e) {
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=1&edit_type=payroll&form=" + action,
            cache: false,
            success: function (JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('.save').prop('disabled', false);
                    $('.icon-spinner3').hide();
                } else {
                    xin_table.api().ajax.reload(function () {
                        toastr.success(JSON.result);
                    }, true);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                }
            }
        });
    });

    /* Set Salary Details*/
    $("#set_salary_details").submit(function (e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        var employee_id = jQuery('#employee_id').val();
        var month_year = jQuery('#month_year').val();
        var company_id = jQuery('#aj_company').val();
        // On page load: datatable
        $('#p_month').html(month_year);
        var xin_table2 = $('#xin_table').dataTable({
            "bDestroy": true,
            "ajax": {
                url: site_url + "payroll/payslip_list/?employee_id=" + employee_id + "&company_id=" + company_id + "&month_year=" + month_year,
                type: 'GET'
            },
            "fnDrawCallback": function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        xin_table2.api().ajax.reload(function () { }, true);
    });

});
$(document).on("click", ".delete", function () {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/payslip_delete/' + $(this).data('record-id')) + '/';
});
function change_paid_amount() {
    var user_id=$("#u_id").val();
    if ($("#total_overtime").val() != "") {
        var overtime = $("#total_overtime").val();
    } else {
        var overtime = 0;
    }
    var total_amount = $("#payment_amount").val();
    var net_salary = $("#net_salary").val();
    var emp_dob = $("#emp_dob").val();
    var pay_date=$("#pay_date").val();
    if($("#gross_salary").val() != ""){
        var gross_salary = $("#gross_salary").val();

    }else{
    var gross_salary = 0;

    }
    
  
    if( $("#total_fund_contribution").val() != ""){
        var contribution = $("#total_fund_contribution").val();
    }else{
        var contribution = 0;
    }
    if ($("#total_other_payments").val() != "") {
        var extra_payment = $("#total_other_payments").val();
    } else {
        var extra_payment = 0;
    }
    if ($("#leave_deductions").val() != "") {
        var leave_deductions = $("#leave_deductions").val();
    } else {
        var leave_deductions = 0;
    }
    if ($("#total_allowances").val() != "") {
        var total_allowances = $("#total_allowances").val();
    } else {
        var total_allowances = 0;
    }
    if ($("#total_commissions").val() != "") {
        var total_commissions = $("#total_commissions").val();
    } else {
        var total_commissions = 0;
    }
    if ($("#total_loan").val() != "") {
        var total_loan = $("#total_loan").val();
    } else {
        var total_loan = 0;
    }
    if ($("#total_statutory_deductions").val() != "") {
        var total_statutory_deductions = $("#total_statutory_deductions").val();
    } else {
        var total_statutory_deductions = 0;
    }
    if ($("#total_cpf_employee").val() != "") {
        var total_cpf_employee = $("#total_cpf_employee").val();
    } else {
        var total_cpf_employee = 0;
    }
    if ($("#total_cpf_employer").val() != "") {
        var total_cpf_employer = $("#total_cpf_employer").val();
    } else {
        var total_cpf_employer = 0;
    }
    if ($("#total_fund_contribution").val() != "") {
        var total_fund_contribution = $("#total_fund_contribution").val();
    } else {
        var total_fund_contribution = 0;
    }
    if ($("#total_share").val() != "") {
        var total_share = $("#total_share").val();
    } else {
        var total_share = 0;
    }

    if ($("#employee_claim").val() != "") {
        var claim_amount = $("#employee_claim").val();
    } else {
        var claim_amount = 0;
    }
    let new_amount = parseFloat(gross_salary) + parseFloat(overtime) + parseFloat(extra_payment) - parseFloat(leave_deductions) + parseFloat(total_allowances) + parseFloat(total_commissions) - parseFloat(total_loan) - parseFloat(total_statutory_deductions) - parseFloat(total_cpf_employee) + parseFloat(total_cpf_employer) - parseFloat(contribution) + parseFloat(total_share) + parseFloat(claim_amount);
    $("#payment_amount").val(new_amount);
    $("#net_salary").val(new_amount);
    var g_additional_wage = parseFloat(overtime) + parseFloat(extra_payment)+ parseFloat(total_allowances) + parseFloat(total_commissions);
    $.ajax({
        url: base_url+'/get_contribution_amount',
        type:"GET",
        data:{'user_id':user_id,'gross_salary':gross_salary,'emp_dob':emp_dob,'pay_date':pay_date,'g_additional_wage':g_additional_wage},
       
        success:function(response){
            if(response != "" || response != null){
            var data1=JSON.parse(response);
            
            var total=data1.shg_fund_deduction_amount+data1.ashg_fund_deduction_amount;
            var cpf = parseFloat(data1.cpf_employer) + parseFloat(data1.cpf_employee);
            $("#total_fund_contribution").val(total);
            $("#total_cpf_employee").val(data1.cpf_employee);
             $("#cpf_employer").val(data1.cpf_employer);
            $("#total_cpf_employer").val(cpf);
            
            $("#shg_fund_deduction_amount1").hide();
            $("#ashg_fund_deduction_amount1").hide();
            if(data1.shg_fund_deduction_amount > 0){
                $("#shg_fund_deduction_amount").html('<div class="form-group" id="shg_fund_deduction_amount1"><label for="name">'+data1.shg_fund_name+'</label><input type="hidden" id="loan_title_1" name="loan_title[]" class="form-control" value="'+data1.shg_fund_name+'"><input type="text" id="loan_amount_1" name="loan_amount[]" class="form-control" value="'+data1.shg_fund_deduction_amount+'"></div>');
            }
            if(data1.ashg_fund_deduction_amount > 0){
                $("#ashg_fund_deduction_amount").html('<div class="form-group" id="ashg_fund_deduction_amount1"><label for="name">'+data1.ashg_fund_name+'</label><input type="hidden" id="loan_title_1" name="loan_title[]" class="form-control" value="'+data1.ashg_fund_name+'"><input type="text" id="loan_amount_1" name="loan_amount[]" class="form-control" value="'+data1.ashg_fund_deduction_amount+'"></div>');

            }
            let new_amount = parseFloat(gross_salary) + parseFloat(overtime) + parseFloat(extra_payment) - parseFloat(leave_deductions) + parseFloat(total_allowances) + parseFloat(total_commissions) - parseFloat(total_loan) - parseFloat(total_statutory_deductions) - cpf - total + parseFloat(total_share) + parseFloat(claim_amount);
            $("#payment_amount").val(new_amount);
            $("#net_salary").val(new_amount);
            console.log(parseFloat(gross_salary) + parseFloat(overtime) + parseFloat(extra_payment)+ parseFloat(total_allowances) + parseFloat(total_commissions)+ parseFloat(total_share) + parseFloat(claim_amount));
            console.log(parseFloat(gross_salary) + parseFloat(overtime) + parseFloat(extra_payment)+ parseFloat(total_allowances) + parseFloat(total_commissions)+ parseFloat(total_share) + parseFloat(claim_amount));
            }
        },
        error: function() {
            
        }
    });
    



}
