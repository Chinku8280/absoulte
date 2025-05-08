$(document).ready(function() {
    var xin_table = $('#xin_table').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "reports/employee_leave_list/",
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
        yearRange: '1970:' + (new Date().getFullYear() + 1),
        beforeShow: function(input) {
            $(input).datepicker("widget").show();
        }
    });


    //    Year
      $('.year').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy',
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
                url: site_url + "reports/employee_leave_list/" + start_date + "/" + end_date + "/" + user_id + "/" + company_id,
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


    $("#employee_leave_reports").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var year = $('#year').val();
        var user_id = $('#employee_id').val();
        var company_id = $('#aj_company').val();
        var xin_table2 = $('#xin_table').dataTable({
            "bDestroy": true,
            "ajax": {
                url: site_url + "reports/employee_leave_report_list/" + year + "/" + user_id + "/" + company_id,
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


    $('.edit-modal-data').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var employee_id = button.data('employee_id');
        var leave_opt = button.data('leave_opt');
        var modal = $(this);
        $.ajax({
            url: base_url + "/read_leave_details/",
            type: "GET",
            data: 'jd=1&is_ajax=1&mode=modal&type=leave_status&employee_id=' + employee_id + '&leave_opt=' + leave_opt,
            success: function(response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    });



    $('#generate_employee_leave_report').click(function () {
        var user_id = $('#employee_id').val();
        var year = $('#year').val();
        $.ajax({
            url: base_url + "/generate_employee_leave_report/",
            type: "GET",
            data: {
                employee_id: user_id,
                year:year
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data) {
                // console.log(response);
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'data.xlsx';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        })
    });



    $('#employee_leave_balance_report_table').DataTable( {       
		scrollX:        true,
		scrollCollapse: false,
		autoWidth:         true,  
		paging:         true,    
		"bSort" : false,
		columnDefs: [
			{ "width": "240px", "targets": [0] },
		  ],
		  dom: 'lBfrtip',
		//   "buttons": ['csv', 'excel', 'print']	
        buttons: [
            {
                extend: 'excel',
                title: function() {
                    return 'Leave Balance Report';
                }
            },
            {
                extend: 'print',
                title: function() {
                    return 'Leave Balance Report';
                }
            },
            {
                extend: 'pdfHtml5',
                title: function() {
                    return 'Leave Balance Report';
                }
            },
        ]
    });	
    

    $('#employee_leave_application_balance_report_table').DataTable( {       
		scrollX:        true,
		scrollCollapse: false,
		autoWidth:         true,  
		paging:         true,    
		"bSort" : false,
		columnDefs: [
			{ "width": "240px", "targets": [0] },
		  ],
		  dom: 'lBfrtip',
		//   "buttons": ['csv', 'excel', 'print']	
        buttons: [
            {
                extend: 'excel',
                title: function() {
                    return 'Leave Application Report';
                }
            },
            {
                extend: 'print',
                title: function() {
                    return 'Leave Application Report';
                }
            },
            {
                extend: 'pdfHtml5',
                title: function() {
                    return 'Leave Application Report';
                }
            },
        ]
	});	



});