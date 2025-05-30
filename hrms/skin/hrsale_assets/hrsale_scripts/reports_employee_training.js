$(document).ready(function() {
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "reports/training_list/",
            type: 'GET'
        },
        dom: 'lBfrtip',
        "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });
    jQuery("#aj_company").change(function() {
        jQuery.get(base_url + "/get_employees_att/" + jQuery(this).val(), function(data, status) {
            jQuery('#employee_ajax').html(data);
        });
    });
    // Month & Year
    $('.training_date').datepicker({
        changeMonth: true,
        changeYear: true,
        //maxDate: '0',
        dateFormat: js_date_format,
        altField: "#date_format",
        altFormat: js_date_format,
        yearRange: '1970:' + (new Date().getFullYear() +1),
        beforeShow: function(input) {
            $(input).datepicker("widget").show();
        }
    });

    /* attendance datewise report */
    $("#training_report").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var user_id = $('#employee_id').val();
        var company_id = $('#aj_company').val();
        var xin_table2 = $('#xin_table').dataTable({
            "bDestroy": true,
            "ajax": {
                url: site_url + "reports/training_list/" + start_date + "/" + end_date + "/" + user_id + "/" + company_id,
                type: 'GET'
            },
            dom: 'lBfrtip',
            "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
            "fnDrawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        toastr.success('Request Submit.');
        xin_table2.api().ajax.reload(function() {}, true);
    });
});