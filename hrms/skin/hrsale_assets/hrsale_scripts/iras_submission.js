$(document).ready(function() {
    $("#efilling").addClass('active');
    $("#iras_submission").addClass('active');

    $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
    $('[data-plugin="select_hrm"]').select2({ width: '100%' });

    var year = jQuery('#year').val();


    var xin_table_employee_summary_ap8a = $('#xin_table_employee_summary_ap8a').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "efiling/employeeSummaryAp8a/" + year,
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });



    var xin_table_employee_ap8a_form = $('#xin_table_employee_ap8a_form').dataTable({
        "bDestroy": true,
        "ajax": {
            url: site_url + "efiling/employeeAp8aForm/" + year,
            type: 'GET'
        },
        "fnDrawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    $('#year').on('change', function() {
        var yr = this.value;
        window.location.href = base_url + "/irassubmission/year/" + yr;
    });

    $("#iras_validation_form").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $('#hrload-img').show();
        var year = $('#year').val();
        toastr.info(processing_request);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=iras_validation_form&type=iras_validation_form&form=" + action,
            cache: false,
            success: function(sData) {
                console.log(sData);
                if (sData.error != '') {
                    //toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(sData.error);
                    $('input[name="csrf_hrsale"]').val(sData.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    var sresult = JSON.parse(sData.result);
                    var iras_status = sresult.statusCode;
                    //toastr.clear();
                    $('#hrload-img').hide();
                    // toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(sData.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);

                    if (iras_status == 200) {
                        toastr.success('Validation Successful');
                        // $('#validation_result').addClass("alert-success").alert();
                        $('#validation_result').html('<div class="alert alert-success alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        $('#validation_result .alert').append("<h4>Validation Successful. No errors found.</h4>");
                    } else if (iras_status == 400) {
                        toastr.success('Errors found');
                        $('#validation_result').html('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        $('#validation_result .alert').append("<h4>Following errors or warnings found.</h4>");
                        var ir8a_errors = sresult.ir8a.errors;
                        var ir8a_warnings = sresult.ir8a.warnings;
                        var ir8s_errors = sresult.ir8s.errors;
                        var ir8s_warnings = sresult.ir8s.warnings;
                        var a8a_errors = sresult.a8a.errors;
                        var a8a_warnings = sresult.a8a.warnings;
                        var a8b_errors = sresult.a8b.errors;
                        var a8b_warnings = sresult.a8b.warnings;

                        if (ir8a_errors.length > 0) {
                            $('#validation_result .alert').append("<p>IR8A Errors :</p><ul>");
                            $.each(ir8a_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (ir8a_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>IR8A Warnings :</p><ul>");
                            $.each(ir8a_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }

                        if (ir8s_errors.length > 0) {
                            $('#validation_result .alert').append("<p>IR8S Errors :</p><ul>");
                            $.each(ir8s_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (ir8s_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>IR8S Warnings :</p><ul>");
                            $.each(ir8s_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }

                        if (a8a_errors.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8A Errors :</p><ul>");
                            $.each(a8a_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (a8a_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8A Warnings :</p><ul>");
                            $.each(a8a_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }

                        if (a8b_errors.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8B Errors :</p><ul>");
                            $.each(a8b_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (a8b_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8B Warnings :</p><ul>");
                            $.each(a8b_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                    } else {
                        $('#validation_result').html('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        $('#validation_result .alert').append("<h5>Error.</h5>");
                        var msg_error = sresult.msgError;
                        $('#validation_result .alert').append("<p>" + msg_error + "</p>");
                    }
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    $("#iras_submission_form").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $('#hrload-img').show();
        var year = $('#year').val();
        toastr.info(processing_request);
        console.log(e.target.action);
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=iras_submission_form&type=iras_submission_form&form=" + action,
            cache: false,
            success: function(sData) {

                console.log(sData);
                if (sData.error != '') {
                    //toastr.clear();
                    $('#hrload-img').hide();
                    toastr.error(sData.error);
                    $('input[name="csrf_hrsale"]').val(sData.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);
                } else {
                    var sresult = JSON.parse(sData.result);
                    var iras_status = sresult.statusCode;
                    //toastr.clear();
                    $('#hrload-img').hide();
                    // toastr.success(JSON.result);
                    $('input[name="csrf_hrsale"]').val(sData.csrf_hash);
                    $('.icon-spinner3').hide();
                    $('.save').prop('disabled', false);

                    if (iras_status == 200) {
                        toastr.success('Submission Successful');
                        $('#validation_result').html('<div class="alert alert-success alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        $('#validation_result .alert').append("<h4>Submission successful</h4>");
                    } else if (iras_status == 400) {
                        toastr.success('Errors found');
                        $('#validation_result').html('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        $('#validation_result .alert').append("<h4>Following errors or warnings found.</h4>");
                        var ir8a_errors = sresult.ir8a.errors;
                        var ir8a_warnings = sresult.ir8a.warnings;
                        var ir8s_errors = sresult.ir8s.errors;
                        var ir8s_warnings = sresult.ir8s.warnings;
                        var a8a_errors = sresult.a8a.errors;
                        var a8a_warnings = sresult.a8a.warnings;
                        var a8b_errors = sresult.a8b.errors;
                        var a8b_warnings = sresult.a8b.warnings;

                        if (ir8a_errors.length > 0) {
                            $('#validation_result .alert').append("<p>IR8A Errors :</p><ul>");
                            $.each(ir8a_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (ir8a_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>IR8A Warnings :</p><ul>");
                            $.each(ir8a_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }

                        if (ir8s_errors.length > 0) {
                            $('#validation_result .alert').append("<p>IR8S Errors :</p><ul>");
                            $.each(ir8s_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (ir8s_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>IR8S Warnings :</p><ul>");
                            $.each(ir8s_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }

                        if (a8a_errors.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8A Errors :</p><ul>");
                            $.each(a8a_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (a8a_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8A Warnings :</p><ul>");
                            $.each(a8a_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }

                        if (a8b_errors.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8B Errors :</p><ul>");
                            $.each(a8b_errors, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }
                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                        if (a8b_warnings.length > 0) {
                            $('#validation_result .alert').append("<p>Appendix 8B Warnings :</p><ul>");
                            $.each(a8b_warnings, function($k, $v) {
                                if ($.type($v) == 'object') {
                                    $.each($v, function(i, j) {
                                        $('#validation_result .alert').append("<li>" + i + " : " + j + "</li>");
                                    });
                                }

                            });
                            $('#validation_result .alert').append("</ul>");
                        }
                    } else {
                        $('#validation_result').html('<div class="alert alert-danger alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                        $('#validation_result .alert').append("<h5>Error.</h5>");
                        var msg_error = sresult.msgError;
                        $('#validation_result .alert').append("<p>" + msg_error + "</p>");
                    }
                }
            },
            error: function(eData) {
                console.log(eData);
            }
        });
    });

    $('#ap8aResetModal').on('shown.bs.modal', function() {
        // $('#myInput').trigger('focus')
    });

    $("#ap8a_reset_form").submit(function(e) {
        /*Form Submit*/
        e.preventDefault();
        var obj = $(this),
            action = obj.attr('name');
        $('.save').prop('disabled', true);
        $('.icon-spinner3').show();
        $('#hrload-img').show();
        var year = $('#year').val();
        toastr.info(processing_request);
        $("#ap8aResetModal").modal('toggle');
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=3&data=ap8a_reset_form&type=ap8a_reset_form&form=" + action,
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
                    xin_table_employee_ap8a_form.api().ajax.url(site_url + "efiling/employeeAp8aForm/" + year).load();
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