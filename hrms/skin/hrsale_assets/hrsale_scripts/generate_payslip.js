let array_advance = [];
$("body").on("click", '.user_check_box_adavance', function (e) {
    let id = $(this).data('id');

    if ($(this).prop('checked') == true) {

        array_advance.push({ id: id, adavnce_value: 0 });
    } else {

        var index = array_advance.findIndex(item => item.id === id);
        if (index !== -1) {
            array_advance.splice(index, 1);
        }
    }
})


$("body").on("input change", '.advance_class', function (e) {
    e.preventDefault()
    let id = $(this).data('id');
    let value = $(this).val();
    array_advance.forEach(obj => {
        if (obj.id == id) {
            obj.adavnce_value = value

        }
    });

});

let array = [];

$("body").on("click", '.bulk_check_id_input', function (e) {
    let id = $(this).data('id')
    var closestRow = $(this).closest('tr');
    if ($(this).prop('checked') == true) {
        array.push({ id: id, basic_salary: false, share_options_amount: false, motivation_amount: false, General_amount: false, allowance_amount: false, commission: false, restday_pay: false, total_deduction: false, all_overtime_P: false, statutory_deductions_amount: false, loan_de_amount: false, other_payments_amount: false, unpaid_leave_amount: false, balance_amount: 0 });
        closestRow.find('.column_checkbox').map(function () {
            let type = $(this).data('type');

            array.forEach(obj => {
                if (obj.id == id) {
                    obj[type] = true

                }
            });
        })
        closestRow.find('.column_checkbox_paid').map(function () {
            let type = $(this).data('type');

            array.forEach(obj => {
                if (obj.id == id) {
                    obj[type] = "done"

                }
            });
        })
        closestRow.find('.column_checkbox').prop('checked', true);

    } else {

        var index = array.findIndex(item => item.id === id);
        if (index !== -1) {
            array.splice(index, 1);
        }
        closestRow.find('.column_checkbox').prop('checked', false);
    }


})
$(document).on("click", '.header_input_bulk', function (e) {
    // e.preventDefault()
    let type = $(this).data('type');
    let balance_amount = 'balance_amount';

    var columnIndex = $(this).closest('th').index() + 1;
    var isChecked = $(this).prop('checked');

    array.forEach(obj => {
        if (isChecked) {
            obj[type] = true;
        } else {
            obj[type] = false;
        }
    });

    $('#xin_table_bulk tbody tr').each(function () {
        let tr = $(this).closest('tr');
        let id = tr.find('.bulk_check_id_input').data('id');


        let tdValue = parseFloat($(this).find('td:nth-child(' + columnIndex + ')').text().trim());
        let balance = parseFloat(tr.find('.b_am').val());
        if (isChecked) {
            tr.find('.b_am').val(balance - tdValue)
            array.forEach(obj => {
                if (obj.id == id) {

                    obj[balance_amount] = balance - tdValue;
                }

            });
        } else {
            tr.find('.b_am').val(balance + tdValue)
            array.forEach(obj => {
                if (obj.id == id) {

                    obj[balance_amount] = balance + tdValue;
                }

            });
        }
        $(this).find('td:nth-child(' + columnIndex + ') .column_checkbox').prop('checked', isChecked);

    });

})
$("body").on("click", '.column_checkbox', function (e) {

    let tr = $(this).closest('tr');
    let td = $(this).closest('td');
    let tdValue = parseFloat(td.text());

    let balance_amount = parseFloat(tr.find('.b_am').val());
    if ($(this).prop('checked') == true) {


        tr.find('.b_am').val(balance_amount - tdValue)

    } else {
        tr.find('.b_am').val(balance_amount + tdValue)
    }
    let id = $(this).data('id');
    let type = $(this).data('type');
    let obj = array.find(item => item.id === id);
    if (obj) {
        if ($(this).prop('checked') == true) {
            obj[type] = true;
            obj.balance_amount = balance_amount - tdValue

        } else {

            obj[type] = false;
            obj.balance_amount = balance_amount + tdValue
        }
    }

})
// Attach a click event to the checkbox to manually check it
// $(document).on('click', '#selectUsersCheckbox', function (e) {
//     var isChecked = $(this).is(':checked');

//     if (isChecked) {
//         $(this).prop('checked', true);
//         // all_employee_class();
//         $(".column_checkbox").prop('checked', true);
//         $(".header_input_bulk").prop('checked', true);
//         $(".bulk_check_id_input").prop('checked', true);


//     } else {
//         $(this).prop('checked', false);
//         uncheck_all_employee_class();
//     }


//     e.stopPropagation();
// });



// $("#bulk_submit_btn_id").on("click", function (e) {
//     var csrfName = '<?= $this->security->get_csrf_token_name(); ?>'; // CSRF Token name
//     var csrfHash = '<?= $this->security->get_csrf_hash(); ?>'; // CSRF hash

//     e.preventDefault();
//     var bmonth_year = jQuery('#bmonth_year').val();
//     var payment_mode = $('#payment_mode').val();

//     if (payment_mode == "Bank") {
//         $('#myModal').modal('show')
//     } else {
//         $.ajax({
//             type: "POST",
//             url: site_url + "payroll/add_pay_to_all_by_id",
//             data: {
//                 id: array, month_year: bmonth_year, add_type: 'payroll', payment_mode: payment_mode, [csrfName]: csrfHash
//             },
//             // cache: false,
//             success: function (JSON) {
//                 // window.location.reload(1)
//                 if (JSON.error != '') {
//                     toastr.error(JSON.error);
//                 } else {

//                     var employee_id = jQuery('#employee_id').val();
//                     var month_year = jQuery('#month_year').val();
//                     var company_id = jQuery('#aj_company').val();



//                     // window.location.reload(1)
//                 }
//             }

//         });
//     }
// })



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
        yearRange: '1970:' + (new Date().getFullYear() + 1),
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

    $('.view-modal-data').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var payment_date = $('#month_year').val();
        var company_id = button.data('company_id');
        var modal = $(this);
        $.ajax({
            url: site_url + 'payroll/pay_salary_again/',
            type: "GET",
            data: 'jd=1&is_ajax=11&data=payment&type=monthly_payment&employee_id=' + employee_id + '&pay_date=' + payment_date + '&company_id=' + company_id,
            success: function (response) {
                if (response) {
                    $("#ajax_modal_view").html(response);
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

        // $('#xin_table_bulk_advance').empty();
        $('#xin_table_bulk tbody').empty();

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
 


    $('.emo_hourly_pay_again').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var payment_date = $('#month_year').val();
        var company_id = button.data('company_id');
        var modal = $(this);
        $.ajax({
            url: site_url + 'payroll/pay_hourly/',
            type: "GET",
            data: 'jd=1&is_ajax=11&data=hourly_payment&type=emo_hourly_pay_again&employee_id=' + employee_id + '&pay_date=' + payment_date + '&company_id=' + company_id,
            success: function (response) {
                if (response) {
                    $("#emo_hourly_pay_aj_again").html(response);
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

    $("#bulk_pyment_advance").click(function (e) {
        e.preventDefault();
        var employee_id = jQuery('#employee_id').val();
        var bmonth_year = jQuery('#bmonth_year').val();
        var company_id = jQuery('#aj_company').val()
        var client_type = $('#client_type').val();
        var payment_mode = $('#payment_mode').val();
        array = [];
        $('#payslip_list_tBLW').css("display", "none");
        $('#payslip_list_bulk').css("display", "none");
        $('#bulk_submit_div_id').css("display", "none");
        $('#bulk_submit_div_id_advance').css("display", "block");
        $('#payslip_list_bulk_advance').css("display", "block");
        $('#xin_table_bulk').DataTable().destroy();
        $('#xin_table_bulk_advance').DataTable().destroy();
        var xin_table1 = $('#xin_table_bulk_advance').dataTable({
            "ordering": false,
            "ajax": {
                url: site_url + "payroll/payslip_list_bulk_advance/?employee_id=" + employee_id + "&company_id=" + company_id + "&month_year=" + bmonth_year,
                type: 'GET'
            },
            "fnDrawCallback": function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
        });
        xin_table1.api().ajax.reload(function () {
            toastr.success(JSON.result);
        }, true);
        $('.icon-spinner3').hide();
        $('.save').prop('disabled', false);
    });

    $('#bulk_submit_btn_id_ad').on("click", function (e) {
        e.preventDefault();
        var bmonth_year = jQuery('#bmonth_year').val();
        $.ajax({
            type: "POST",
            url: base_url + "/add_advance_salary",
            data: { id: array_advance, month: bmonth_year },

            success: function (JSON) {
                window.location.reload(1)
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
        $('#payslip_list_bulk').css("display", "none");
        $('#payslip_list_tBLW').css("display", "block");
        $('#bulk_submit_div_id').css("display", "none");
        $('#bulk_submit_div_id_advance').css("display", "none");
        $('#payslip_list_bulk_advance').css("display", "none");
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



    // bulk payment listing 
    $("#bulk_payment").submit(function (e) {
        e.preventDefault();
        var employee_id = jQuery('#employee_id').val();
        var bmonth_year = jQuery('#bmonth_year').val();
        var company_id = jQuery('#aj_companyx').val()
        var location_id = jQuery('#aj_location_id').val()

        var client_type = $('#client_type').val();
        var payment_mode = $('#payment_mode').val();
        array = [];

        $('#payslip_list_tBLW').css("display", "none");
        $('#payslip_list_bulk').css("display", "block");
        $('#bulk_submit_div_id_advance').css("display", "none");
        $('#payslip_list_bulk_advance').css("display", "none");
        $('#xin_table_bulk').DataTable().destroy();
        var xin_table1 = $('#xin_table_bulk').dataTable({
            "ordering": false,
            "ajax": {
                url: site_url + "payroll/payslip_list_bulk/?employee_id=" + employee_id + "&company_id=" + company_id + "&month_year=" + bmonth_year,
                type: 'GET'
            },
            "fnDrawCallback": function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            },
        });



        xin_table1.api().ajax.reload(function () {
            toastr.success(JSON.result);
            $('#bulk_submit_div_id').css('display', 'block')


            $('.main_check_box').click(function () {
                const isChecked = $(this).is(':checked');
                $(this).closest('tr').find('.row-item').prop('checked', isChecked);

            });


            $('.header_input_bulk').click(function () {
                var columnClass = $(this).data('column');
                var isChecked = $(this).is(':checked');
                console.log(columnClass);
                // Check all checkboxes in the same column
                $('.' + columnClass).prop('checked', isChecked);
            });

            $('.row-item').click(function () {
                let isChecked = $(this).is(':checked');
                $('.row-item').each(function () {
                    // isChecked = $(this).is(':checked'); 
                    if ($(this).is(':checked')) {
                        isChecked = true;
                    }
                });
                $(this).closest('tr').find('.main_check_box').prop('checked', isChecked);
            });


            $('.bc_checked_class').click(function () {
                let check_id = [];
                let uncheck_id = [];

                let bc_checked_class = $(this);

                // $('.bc_checked_class').each(function (index, data) {
                //     if ($(this).prop('checked')) {
                //         check_id.push($(this).prop('id'));
                //     } else {
                //         uncheck_id.push($(this).prop('id'));
                //     }
                // })
                $('.main_check_box').each(function (index, data) {
                    if ($(this).prop('checked')) {
                        $(this).closest('tr').find('.bc_checked_class').each(function (index, data) {
                            if ($(this).prop('checked')) {
                                check_id.push($(this).prop('id'));
                            } else {
                                uncheck_id.push($(this).prop('id'));
                            }
                        })
                    }
                })
                // console.log(check_id);

                let user_id = $(this).closest('tr').find('.main_check_box').val();
                var bmonth_year = jQuery('#bmonth_year').val();

                $.ajax({
                    url: site_url + "payroll/check_payment",
                    type: 'get',
                    dataType: 'json',
                    data: {
                        user_id: user_id,
                        pay_date: bmonth_year,
                        check_id: check_id,
                        uncheck_id: uncheck_id,
                    },
                    success: function (data) {
                        console.log(data)
                        bc_checked_class.closest('tr').find('.leave_deductions').val(data.unpaid_leave_amount);
                        bc_checked_class.closest('tr').find('.total_employee_deduction').val(data.total_deduction);
                        bc_checked_class.closest('tr').find('.total_cpf_employee').val(data.total_cpf_employee);
                        bc_checked_class.closest('tr').find('.total_cpf_employer').val(data.total_cpf_employer);
                        bc_checked_class.closest('tr').find('.total_cpf').val(data.cpf_total);
                        bc_checked_class.closest('tr').find('.total_fund_contribution').val(data.contribution)
                        bc_checked_class.closest('tr').find('.payment_amount_s').val(data.net_salary);
                        bc_checked_class.closest('tr').find('.net_salary_s').val(data.net_salary);
                        bc_checked_class.closest('tr').find('.balance_amount').val(data.balance);
                    },
                    error: function (error) {
                        console.log(error)
                    }
                });
            });





        }, true);
        $('.icon-spinner3').hide();
        $('.save').prop('disabled', false);
    });

    // Form submission handling
    $("#add_pay_bulk").submit(function (e) {
        e.preventDefault();
        let check_id = [];
        let uncheck_id = [];
        $('.bc_checked_class').each(function (index, data) {
            if ($(this).prop('checked')) {
                check_id.push($(this).prop('id'));
            } else {
                uncheck_id.push($(this).prop('id'));
            }
        })

        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');
        fd.append("is_ajax", 11);
        fd.append("add_type", 'add_monthly_payment');
        fd.append("data", 'monthly');
        fd.append("form", action);
        fd.append("check_id", check_id);
        fd.append("uncheck_id", uncheck_id);
        fd.append("payment_mode", $('#payment_mode').val());
        fd.append('pay_date', $('#bmonth_year').val())

        $.ajax({
            type: "POST",
            url: e.target.action,
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            dataType:'json',
            success: function (JSON) {
                console.log(JSON);
                if (JSON.status != 'success') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                } else {
                    console.log(11);
                    $('#payslip_list_tBLW').css("display", "block");
                    $('#payslip_list_bulk').css("display", "none");
                    $('#bulk_submit_div_id_advance').css("display", "none");
                    $('#payslip_list_bulk_advance').css("display", "none");
                    $('#xin_table').DataTable().destroy();

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
                    xin_table.api().ajax.reload(function () {
                        toastr.success("Payment Complete");
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);


                    if (JSON.bank == 'DBS') {
                        var blob = new Blob([JSON.data], { type: 'text/plain' });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'GIRO-' + JSON.date + '.txt'; // File name

                        document.body.appendChild(link);
                        link.click();


                        document.body.removeChild(link);
                        // window.location.reload(1)
                    }

                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });

    });


    // for advance make payment
    $("#add_pay_bulk_advance").submit(function (e) {
        e.preventDefault();
        var fd = new FormData(this);
        var obj = $(this),
            action = obj.attr('name');

        fd.append("is_ajax", 11);
        fd.append("add_type", 'add_monthly_payment');
        fd.append("data", 'monthly');
        fd.append("form", action);
        fd.append('pay_date', $('#bmonth_year').val())

        $.ajax({
            type: "POST",
            url: e.target.action,
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function (JSON) {
                if (JSON.error != undefined) {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                } else {

                    $('#payslip_list_tBLW').css("display", "block");
                    $('#payslip_list_bulk').css("display", "none");
                    $('#bulk_submit_div_id_advance').css("display", "none");
                    $('#payslip_list_bulk_advance').css("display", "none");
                    $('#xin_table').DataTable().destroy();

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
                    xin_table.api().ajax.reload(function () {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.save').prop('disabled', false);
                }
            },
            error: function (eData) {
                console.log(eData);
            }
        });
    });


    $('#aj_companyx').change(function () {
        let company_id = $(this).val();
        $.ajax({
            url: "get_bank_details",
            type: 'get',
            dataType: 'json',
            data: {
                company_id: company_id
            },
            success: function (data) {
                console.log(data);
                let len = data.length;
                $('#payment_mode').empty();
                $('#payment_mode').append(`<option value="Cash">Cash</option>`)
                data.forEach(element => {
                    console.log(element)
                    $('#payment_mode').append(`<option value="${element['bank_name']}">${element['bank_name']}</option>`)
                });
            },
            error: function (error) {
                console.log(error)
            }
        })
    });



    $('.main_check_box').click(function () {
        console.log(44);
        const isChecked = $(this).is(':checked');
        $(this).closest('tr').find('.row-item').prop('checked', isChecked);

    });


    $(document).on('click', '#selectUsersCheckbox', function (e) {
        var isChecked = $(this).is(':checked');

        if (isChecked) {
            $(this).prop('checked', true);
            $(".column_checkbox").prop('checked', true);
            $(".header_input_bulk").prop('checked', true);
            $(".bulk_check_id_input").prop('checked', true);
            $('.main_check_box').prop('checked', true);
            $('.row-item').prop('checked', true);
        } else {
            $(this).prop('checked', false);
            $(".column_checkbox").prop('checked', false);
            $(".header_input_bulk").prop('checked', false);
            $(".bulk_check_id_input").prop('checked', false);
            $('.main_check_box').prop('checked', false);
            $('.row-item').prop('checked', false);
        }
    });



    // $("#uploadForm_hrms").on("submit", function (e) {
    //     e.preventDefault();
    //     var bmonth_year = jQuery('#bmonth_year').val();
    //     var payment_mode = $('#payment_mode').val();
    //     let type_bank = $("#Choose_Banck").val();
    //     let m_date_month = $("#m_date_month").val();


    //     if (type_bank == "DBS") {
    //         $.ajax({
    //             url: site_url + "payroll/payslip_list_bulk_rigo",
    //             type: "POST",
    //             dataType: "json",
    //             data: { id: array, month_year: bmonth_year, add_type: 'payroll', payment_mode: payment_mode, rigo: 1, m_date_month: m_date_month },
    //             success: function (re) {

    //                 var blob = new Blob([re], { type: 'text/plain' });
    //                 var link = document.createElement('a');
    //                 link.href = window.URL.createObjectURL(blob);
    //                 link.download = 'GIRO-' + m_date_month + '.txt'; // File name


    //                 document.body.appendChild(link);
    //                 link.click();


    //                 document.body.removeChild(link);
    //                 window.location.reload(1)
    //             }
    //         })

    //     } else {
    //         $.ajax({
    //             url: site_url + "payroll/payslip_list_bulk_rigo_ibms",
    //             type: "POST",
    //             dataType: "json",
    //             data: { id: array, month_year: bmonth_year, add_type: 'payroll', payment_mode: payment_mode, rigo: 1, m_date_month: m_date_month },
    //             success: function (re) {

    //                 var ws = XLSX.utils.aoa_to_sheet(re);

    //                 ws['!cols'] = [
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 100 },
    //                     { wpx: 10 },
    //                     { wpx: 400 },
    //                 ];
    //                 var wb = XLSX.utils.book_new();
    //                 XLSX.utils.book_append_sheet(wb, ws, "CIMB");


    //                 XLSX.writeFile(wb, "CIMB-" + m_date_month + ".xlsx");

    //                 window.location.reload(1)
    //             }
    //         })

    //     }


    // })





});
$(document).on("click", ".delete", function () {
    $('input[name=_token]').val($(this).data('record-id'));
    $('#delete_record').attr('action', base_url + '/payslip_delete/' + $(this).data('record-id')) + '/';
});
