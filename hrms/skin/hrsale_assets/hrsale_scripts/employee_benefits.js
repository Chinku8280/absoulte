$(document).ready(function() {
    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });
    $("#staff").addClass('active');
    $("#employee_benefits").addClass('active');

    $(".nav-tabs-link").click(function() {
        var profile_id = $(this).data('constant');
        var profile_block = $(this).data('constant-block');
        $('.list-group-item').removeClass('active');
        $('.current-tab').hide();
        $('#constant_' + profile_id).addClass('active');
        $('#' + profile_block).show();
    });

    $('#annual_value_field').hide();
    $('#furnished_field').hide();
    $('#rent_paid_field').hide();
    $('#accommodation_type').on('change', function() {
        var act = this.value;
        if (act == 'owned') {
            $('#annual_value_field').show();
            $('#furnished_field').show();
            $('#rent_paid_field').hide();
        } else if (act == 'rented') {
            $('#annual_value_field').hide();
            $('#furnished_field').hide();
            $('#rent_paid_field').show();
        } else {
            $('#annual_value_field').hide();
            $('#furnished_field').hide();
            $('#rent_paid_field').hide();
        }
    });

    $('.cont_date').datepicker({
        changeMonth: true,
        changeYear: true,
        // dateFormat: 'yy-mm-dd',
        dateFormat: js_date_format,
        yearRange: '1940:' + (new Date().getFullYear() + 1),
    });


    $('.edit-modal-data').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var field_id = button.data('field_id');
        var field_tpe = button.data('field_type');
        if (field_tpe == 'read_accommodations') {
            var field_add = '&data=read_accommodations&type=read_accommodations&';
        } else if (field_tpe == 'employee_accommodation') {
            var field_add = '&data=employee_accommodation&type=employee_accommodation&';
        } else if (field_tpe == 'utility') {
            var field_add = '&data=utility&type=utility&';
        } else if (field_tpe == 'driver') {
            var field_add = '&data=driver&type=driver&';
        } else if (field_tpe == 'housekeeping') {
            var field_add = '&data=housekeeping&type=housekeeping&';
        } else if (field_tpe == 'hotel_accommodation') {
            var field_add = '&data=hotel_accommodation&type=hotel_accommodation&';
        } else if (field_tpe == 'other_benefits') {
            var field_add = '&data=other_benefits&type=other_benefits&';
        }
        var modal = $(this);
        $.ajax({
            url: site_url + 'employeebenefits/dialog_' + field_tpe + '/',
            type: "GET",
            data: 'jd=1' + field_add + 'field_id=' + field_id,
            success: function(response) {
                if (response) {
                    $("#ajax_modal").html(response);
                }
            }
        });
    });


    /* Delete data */
    $("#delete_record").submit(function(e) {
        var tk_type = $('#token_type').val();
        $('.icon-spinner3').show();
        if (tk_type == 'accommodations') {
            var field_add = '&is_ajax=9&data=delete_accommodations&type=delete_record&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'employee_accommodation') {
            var field_add = '&is_ajax=10&data=delete_employee_accommodation&type=delete_record&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'utility') {
            var field_add = '&is_ajax=11&data=delete_utility&type=delete_record&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'driver') {
            var field_add = '&is_ajax=12&data=delete_driver&type=delete_record&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'housekeeping') {
            var field_add = '&is_ajax=13&data=delete_housekeeping&type=delete_record&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'hotel_accommodation') {
            var field_add = '&is_ajax=14&data=delete_hotel_accommodation&type=delete_record&';
            var tb_name = 'xin_table_' + tk_type;
        } else if (tk_type == 'other_benefits') {
            var field_add = '&is_ajax=31&data=delete_other_benefits&type=delete_record&';
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
            success: function(JSON) {
                if (JSON.error != '') {
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    $('.icon-spinner3').hide();
                } else {
                    $('.delete-modal').modal('toggle');
                    $('.icon-spinner3').hide();
                    $('#' + tb_name).dataTable().api().ajax.reload(function() {
                        toastr.success(JSON.result);
                    }, true);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                }
            }
        });
    });

    $(document).on("click", ".delete", function() {
        $('input[name=_token]').val($(this).data('record-id'));
        $('input[name=token_type]').val($(this).data('token_type'));
        $('#delete_record').attr('action', site_url + 'employeebenefits/delete_' + $(this).data('token_type') + '/' + $(this).data('record-id')) + '/';
    });









    var xin_table_accommodations = $('#xin_table_accommodations').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employeebenefits/getaccommodation/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var xin_table_employee_accommodation = $('#xin_table_employee_accommodation').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employeebenefits/getemployeeaccommodation/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });


    var xin_table_utility = $('#xin_table_utility').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employeebenefits/getemployeeutility/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var xin_table_driver = $('#xin_table_driver').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employeebenefits/getemployeedriver/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var xin_table_housekeeping = $('#xin_table_housekeeping').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employeebenefits/getemployeehousekeeping/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var xin_table_hotel_accommodation = $('#xin_table_hotel_accommodation').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employeebenefits/getEmployeeHotelAccommodation/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    var xin_table_other_benefits = $('#xin_table_other_benefits').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "employeebenefits/getEmployeeOtherBenefits/",
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('#accommodation_form').submit(function(e) {
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
            data: obj.serialize() + "&is_ajax=4&data=accommodation_form&type=accommodation_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_accommodations.api().ajax.reload(function() {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    window.location.reload(1);
                    jQuery('#accommodation_form')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });

    });

    jQuery(".aj_company").change(function() {
        jQuery.get(base_url + "/get_employees/" + jQuery(this).val(), function(data, status) {
            jQuery('.employee_ajax').html(data);
        });
    });

    jQuery("#aj_accommodation").change(function() {
        jQuery.get(base_url + "/get_accommodation/" + jQuery(this).val(), function(data, status) {
            if (data.result != '') {
                if(data.result.address_line_2 == null){
                    var address = data.result.address_line_1;

                }else{
                    var address = data.result.address_line_1 + ' ' + data.result.address_line_2;

                }

                // const date_from = new Date(data.result.period_from);
                // const df_ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date_from);
                // const df_mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(date_from);
                // const df_da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date_from);


                // const date_to = new Date(data.result.period_to);
                // const dt_ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date_to);
                // const dt_mo = new Intl.DateTimeFormat('en', { month: 'short' }).format(date_to);
                // const dt_da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date_to);

                jQuery('#address').val(address);
                // jQuery('#accommodation_period').val(`${df_da} ${df_mo} ${df_ye}` + ' - ' + `${dt_da} ${dt_mo} ${dt_ye}`);
                // console.log(`${df_da} ${df_mo} ${df_ye}` + ' - ' + `${dt_da} ${dt_mo} ${dt_ye}`)
                jQuery('#accommodation_period').val(data.result.period_from + '-' + data.result.period_to);
            }

        });
    });

    $('#accommodation_employee_form').submit(function(e) {
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
            data: obj.serialize() + "&is_ajax=4&data=accommodation_employee_form&type=accommodation_employee_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_employee_accommodation.api().ajax.reload(function() {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#accommodation_employee_form')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    //Add new options
    $('#utilityCont').on('click', '.utAddbtn', function() {
        var optDiv = $('#utilityDiv'),
            utlContainer = $('#utilityCont'),
            childLength = utlContainer.children().length;
        // console.log(promFmContainer.children().length);

        utlContainer.find('[data-plugin="select_hrm"]').select2('destroy');
        var optDivClone = optDiv.clone();
        optDivClone.attr('id', 'utilityDiv' + childLength);
        optDivClone.removeClass('mt-3');
        optDivClone.find('.utAdd .utAddbtn').remove();
        optDivClone.find("input[type='text']").val("");
        //promFmClone.find('.pFmAdd').attr('class', '.pFmAdd_'+childLength);
        optDivClone.find('.utAdd .form-group').append('<button class="btn icon-btn btn-xs waves-effect waves-light btn-danger opDel" id="opDel_' + childLength + '">Delete <span class="fa fa-minus"></span></a>');
        utlContainer.append(optDivClone);
        utlContainer.find('[data-plugin="select_hrm"]').select2();
        return false;
    });

    //Remove options
    $('#utilityCont').on('click', '.opDel', function() {
        var DelId = $(this).attr('id').replace('opDel_', '');
        $(this).parents('#utilityDiv' + DelId).remove();
        //console.log('#pFm_'+DelId);
        return false;
    });

    $('#benefit_utilities_form').submit(function(e) {
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
            data: obj.serialize() + "&is_ajax=4&data=benefit_utilities_form&type=benefit_utilities_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_utility.api().ajax.reload(function() {
                        toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    $('.select2-selection__rendered').html('--Select--');
                    jQuery('#benefit_utilities_form')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    $('#benefit_driver_form').submit(function(e) {
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
            data: obj.serialize() + "&is_ajax=4&data=benefit_driver_form&type=benefit_driver_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_driver.api().ajax.reload(function() {
                        //toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#benefit_driver_form')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    //Add new options
    $('#houseKeepingCont').on('click', '.utAddbtn', function() {
        var optDiv = $('#housekeepingDiv'),
            utlContainer = $('#houseKeepingCont'),
            childLength = utlContainer.children().length;
        // console.log(promFmContainer.children().length);

        utlContainer.find('[data-plugin="select_hrm"]').select2('destroy');
        var optDivClone = optDiv.clone();
        optDivClone.attr('id', 'housekeepingDiv' + childLength);
        optDivClone.removeClass('mt-3');
        optDivClone.find('.utAdd .utAddbtn').remove();
        optDivClone.find("input[type='text']").val("");
        //promFmClone.find('.pFmAdd').attr('class', '.pFmAdd_'+childLength);
        optDivClone.find('.utAdd .form-group').append('<button class="btn icon-btn btn-xs waves-effect waves-light btn-danger opDel" id="opDel_' + childLength + '">Delete <span class="fa fa-minus"></span></a>');
        utlContainer.append(optDivClone);
        utlContainer.find('[data-plugin="select_hrm"]').select2();
        return false;
    });

    //Remove options
    $('#houseKeepingCont').on('click', '.opDel', function() {
        var DelId = $(this).attr('id').replace('opDel_', '');
        $(this).parents('#housekeepingDiv' + DelId).remove();
        //console.log('#pFm_'+DelId);
        return false;
    });

    $('#benefit_housekeeping_form').submit(function(e) {
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
            data: obj.serialize() + "&is_ajax=4&data=benefit_housekeeping_form&type=benefit_housekeeping_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_housekeeping.api().ajax.reload(function() {
                        //toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#benefit_housekeeping_form')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    $('#benefit_hotel_accommodation_form').submit(function(e) {
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
            data: obj.serialize() + "&is_ajax=4&data=benefit_hotel_accommodation_form&type=benefit_hotel_accommodation_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_hotel_accommodation.api().ajax.reload(function() {
                        //toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#benefit_hotel_accommodation_form')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    //Add new options
    $('#otherBenefitCont').on('click', '.utAddbtn', function() {
        var optDiv = $('#otherBenefitDiv'),
            utlContainer = $('#otherBenefitCont'),
            childLength = utlContainer.children().length;
        // console.log(promFmContainer.children().length);

        utlContainer.find('[data-plugin="select_hrm"]').select2('destroy');
        var optDivClone = optDiv.clone();
        optDivClone.attr('id', 'otherBenefitDiv' + childLength);
        optDivClone.removeClass('mt-3');
        optDivClone.find('.utAdd .utAddbtn').remove();
        optDivClone.find("input[type='text']").val("");
        //promFmClone.find('.pFmAdd').attr('class', '.pFmAdd_'+childLength);
        optDivClone.find('.utAdd .form-group').append('<button class="btn icon-btn btn-xs waves-effect waves-light btn-danger opDel" id="opDel_' + childLength + '">Delete <span class="fa fa-minus"></span></a>');
        utlContainer.append(optDivClone);
        utlContainer.find('[data-plugin="select_hrm"]').select2();
        return false;
    });

    //Remove options
    $('#otherBenefitCont').on('click', '.opDel', function() {
        var DelId = $(this).attr('id').replace('opDel_', '');
        $(this).parents('#otherBenefitDiv' + DelId).remove();
        //console.log('#pFm_'+DelId);
        return false;
    });

    $('#other_benefit_form').submit(function(e) {
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
            data: obj.serialize() + "&is_ajax=4&data=other_benefit_form&type=other_benefit_form&form=" + action,
            cache: false,
            success: function(JSON) {
                console.log(JSON);
                if (JSON.error != '') {
                    //toastr.clear();
                    //$('#hrload-img').hide();
                    toastr.error(JSON.error);
                    $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    jQuery('.save').prop('disabled', false);
                } else {
                    xin_table_other_benefits.api().ajax.reload(function() {
                        //toastr.clear();
                        $('#hrload-img').hide();
                        toastr.success(JSON.result);
                        $('input[name="csrf_hrsale"]').val(JSON.csrf_hash);
                    }, true);
                    jQuery('#other_benefit_form')[0].reset(); // To reset form fields
                    jQuery('.save').prop('disabled', false);
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

});