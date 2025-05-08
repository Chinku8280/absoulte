"use strict";

$(document).ready(function () {

    /**--------------------------------------------------------------------------------------
     * [SESSION MESSAGES]
     * @blade : layout/automationjs.blade.php
     * @description: show session set noty messages
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-session-message").length) {
        var session_message = $("#js-trigger-session-message").attr('data-message');
        var message_type = $("#js-trigger-session-message").attr('data-type');
        NX.notification({
            'type': message_type,
            'duration': 7000,
            'message': session_message,
        });
    }

    /**--------------------------------------------------------------------------------------
     * [FORCE PASSWORD CHANGE]
     * @blade : layout/automationjs.blade.php
     * @description: force password change for new users
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-force-password-change").length) {
        //close any open modals
        $('.modal').modal('hide');
        //show password reset popup
        $("#js-trigger-force-password-change").trigger('click');
    }



    /**--------------------------------------------------------------------------------------
     * [LOAD DYNAMIC CONTENT]
     * @blade : layout/automationjs.blade.php
     * @description: force password change for new users
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-dynamic-modal").length) {
        var trigger_id = $("#js-trigger-dynamic-modal").attr('data-payload');
        //show password reset popup
        $(trigger_id).trigger('click');
    }



    /**--------------------------------------------------------------------------------------
     * [POLLING - GENERAL]
     * @blade : layout/foo.blade.php
     * @description: polling timers
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-general-polling").length) {
        function nxTimerPolling() {
            nxAjaxUxRequest($("#js-trigger-general-polling"));
            setTimeout(nxTimerPolling, 15000);
        };
        nxTimerPolling();
    }



    /**--------------------------------------------------------------------------------------
     * [POLLING - TIMERS]
     * @blade : layout/foo.blade.php
     * @description: polling genereal
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-general-timers").length) {
        function nxGeneralPolling() {
            nxAjaxUxRequest($("#js-trigger-general-timers"));
            setTimeout(nxGeneralPolling, 15000);
        };
        nxGeneralPolling();
    }


     /**--------------------------------------------------------------------------------------
     * [POLLING - TOP NAV TIMER]
     * @blade : layout/foo.blade.php
     * @description: polling genereal
     * -------------------------------------------------------------------------------------*/
     if ($("#js-trigger-topnav-timer").length) {
        function nxGeneralPolling() {
            nxAjaxUxRequest($("#js-trigger-topnav-timer"));
            setTimeout(nxGeneralPolling, 15000);
        };
        nxGeneralPolling();
    }




    /**--------------------------------------------------------------------------------------
     * [SESSION MESSAGES]
     * @blade : layout/automationjs.blade.php
     * @description: show session set noty messages
     * -------------------------------------------------------------------------------------*/
    if ($("#js-trigger-session-message").length) {
        var session_message = $("#js-trigger-session-message").attr('data-message');
        var message_type = $("#js-trigger-session-message").attr('data-type');
        NX.notification({
            'type': message_type,
            'duration': 7000,
            'message': session_message,
        });
    }


});




/**--------------------------------------------------------------------------------------
 * [MAIN MENU SCROLL BAR]
 * @description: show scroll bar
 * -------------------------------------------------------------------------------------*/
function nxSettingsLeftMenuScroll() {
    const navLeftScroll = new PerfectScrollbar('#settings-scroll-sidebar', {
        wheelSpeed: 2,
        wheelPropagation: true,
        minScrollbarLength: 20
    });
}
$(document).ready(function () {
    if ($("#settings-scroll-sidebar").length) {
        //add special class to menu items that have submenu
        $(".sidenav-menu-item").has("ul").addClass('has-submenu');

        //special sub menu highlighting for ajax link
        $(".js-submenu-ajax").on('click', function () {
            $(".js-submenu-ajax").removeClass('active');
            $("a.active").removeClass('active');
            $(this).addClass('active');
        });
        //autoscroll menu
        nxSettingsLeftMenuScroll();
    }
});


/**--------------------------------------------------------------------------------------
 * [CATEGORIES]
 * @page : settings/categories
 * @description: javascript for the categories section (in settings)
 * -------------------------------------------------------------------------------------*/
function NXBootCategories() {

    if ($("#categories-table-wrapper").length) {

        //variables
        var page_section = $("#categories-table-wrapper").attr('data-payload');


        //activate left menu, specifically for ticket departments
        if (page_section == 'ticket') {
            var current_state = $("#settings-menu-tickets").attr('aria-expanded');
            if (current_state == 'false') {
                $("#settings-menu-tickets").trigger('click');
                $("#settings-menu-tickets-departments").addClass('active');
            }
            //exit
            return;
        }
    }

    //activate left menu item
    var current_state = $("#settings-menu-categories").attr('aria-expanded');
    if (current_state == 'false') {
        $("#settings-menu-categories").trigger('click');
        $("#settings-menu-categories-" + page_section).addClass('active');
    }

}



/**--------------------------------------------------------------------------------------
 * [CATEGORIES - CREATE AND EDIT]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXCategoriesCreate() {
    //add category - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            category_name: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}

/**--------------------------------------------------------------------------------------
 * [CARDS - BOOT JAVASCRIPT FOR ALL ACTIONS ON THE CARD
 * @blade : task\modal.blade.php
 * @description: all he jsactionson cards
 * -------------------------------------------------------------------------------------*/
function NXBootCards() {

    //comments
    if ($("#cardModal").length) {

        //focus on the editor
        $(document).on('click', '#card-coment-placeholder-input', function () {
            tinymce.get('card-comment-tinmyce').setContent('');
            $("#card-coment-placeholder-input-container").hide();
            $("#card-comment-tinmyce-container").show();
            tinymce.execCommand('mceFocus', true, 'card-comment-tinmyce');
        });

        //close editor
        $(document).on('click', '#card-comment-close-button', function () {
            $("#card-comment-tinmyce-container").hide();
            $("#card-coment-placeholder-input-container").show();
        });
        //post comment
        $(document).off('click', '#card-comment-post-button').on('click', '#card-comment-post-button', function (e) {
            $("#card-comment-tinmyce-container").hide();
            $("#card-coment-placeholder-input-container").show();
            nxAjaxUxRequest($(this));
        });
    }

    //EDIT DESCRIPTION   
    if ($("#cardModal").length) {
        NX.card_description = $("#card-description-container");
        NX.card_descrition_selector = '#card-description-container';
        NX.card_description_submit = $("#card-description-submit");
        NX.card_description_edit = $("#card-description-edit");
        NX.card_description_input = $("#card-description-input");

        //edit button clicked
        $(document).on('click', '#card-description-button-edit', function () {
            tinymce.remove("#card-description-container");
            NX.card_description_original_text = NX.card_description.html();
            NX.card_description_height = NX.card_description.outerHeight();
            NX.card_description.addClass('card-tinymce-textarea');
            NX.card_description_edit.hide();
            NX.card_description_submit.show();
            nxTinyMCEBasic(NX.card_description_height, NX.card_descrition_selector);
        });
        //cancel button clicked
        $(document).on('click', '#card-description-button-cancel', function () {
            NX.card_description.removeClass('card-tinymce-textarea');
            NX.card_description_submit.hide();
            NX.card_description_edit.show();
            tinymce.remove("#card-description-container");
            NX.card_description.html(NX.card_description_original_text);
        });
        $(document).off('click', '.js-description-save').on('click', '.js-description-save', function () {
            NX.card_description_input.val(NX.card_description.html());
            NX.card_description.removeClass('card-tinymce-textarea');
            NX.card_description_submit.hide();
            NX.card_description_edit.show();
            tinymce.remove("#card-description-container");
            $("#card-description-container").html('<div class="loading w-px-150 h-px-30"></div>');
            nxAjaxUxRequest($(this));
        });
    }

    //CARD TITLE
    if ($("#cardModal").length) {
        NX.card_title_edit_original = '';
        //start
        $(document).on('click', '#card-title-editable', function () {
            NX.card_title_edit_original = $(this).html();
            $(this).hide();
            $(".card-title-input").val(NX.card_title_edit_original);
            $("#card-title-edit").show();
        });
        //cancel
        $(document).on('click', '#card-title-button-cancel', function () {
            $("#card-title-editable").html(NX.card_title_edit_original);
            $("#card-title-edit").hide();
            $("#card-title-editable").show();
        });
        //show loading annimation
        $(document).on('click', '#card-title-button-save', function () {
            $("#card-title-editable").html('<div class="loading w-px-150 h-px-30"></div>');
            $("#card-title-edit").hide();
            $("#card-title-editable").show();
            nxAjaxUxRequest($(this));
        });
    }


    /** -------------------------------------------
     * COMMENTS TEXT EDITOR
     *--------------------------------------------*/
    if ($("#cardModal").length) {

        tinymce.execCommand('mceRemoveEditor', true, 'card-comment-tinmyce');
        //initialize
        tinymce.init({
            selector: '#card-comment-tinmyce',
            mode: 'exact',
            theme: "modern",
            skin: 'light',
            branding: false,
            menubar: false,
            statusbar: false,
            plugins: [
                "advlist autolink lists link image preview",
                "paste autoresize"
            ],
            height: 200,
            toolbar: 'bold link bullist numlist blockquote',
            //autosave/update text area
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }



    /** -------------------------------------------
     * OTHER ACTIONS
     *--------------------------------------------*/
    if ($("#cardModal").length) {
        //default date pickers
        $(document).find(".card-pickadate").datepicker({
            format: NX.date_picker_format,
            language: "lang",
            autoclose: true,
            class: "datepicker-default",
            todayHighlight: false
        });

        //reset date
        $('.card-pickadate').datepicker('clearDates');
        //ajax request
        $('.card-pickadate').on('changeDate', function (e) {
            //update form date
            $("#" + $(this).attr('data-hidden-field')).val(moment(e.date).format('YYYY-MM-DD'));
            //update value
            $("#" + $(this).attr('data-container')).html(moment(e.date).format(NX.date_moment_format));
            //send request
            nxAjaxUxRequest($(this));
        });


        /** -------------------------------------------------------------------------------
         *  Something in the left panel or datepicker or other popover has been clicked
         *  - close any static popover windows
         * ------------------------------------------------------------------------------- */
        $(document).on('click', '#card-left-panel, .js-card-settings-button, .card-pickadate', function () {
            $('.js-card-settings-button-static').popover('hide');
        });

        $(document).on('click', '#card-leads-left-panel, .js-card-settings-button, .card-pickadate', function () {
            $('.js-card-settings-button-static').popover('hide');
        });

        /** ---------------------------------------------------
         *  Right panel settings buttons
         *  - basic actions buttons
         *  - auto close
         * -------------------------------------------------- */
        $(document).find(".js-card-settings-button").each(function () {
            $(this).popover({
                html: true,
                sanitize: false, //The HTML is NOT user generated
                placement: 'bottom',
                offset: '-65,0',
                trigger: 'focus',
                template: NX.basic_popover_template,
                title: $(this).attr('data-title'),
                content: function () {
                    //popover elemenet
                    return $('#' + $(this).attr('data-popover-content')).html();
                }
            });
        });

        /** ---------------------------------------------------
         *  Right panel settings buttons
         *  - basic actions buttons
         *  - no auto close
         * -------------------------------------------------- */
        $(document).find(".js-card-settings-button-static").each(function () {
            $(this).popover({
                html: true,
                sanitize: false, //The HTML is NOT user generated
                placement: 'bottom',
                offset: '-65,0',
                template: NX.basic_popover_template,
                title: $(this).attr('data-title'),
                content: function () {
                    //popover elemenet
                    return $('#' + $(this).attr('data-popover-content')).html();
                }
            });
        });


        /** ------------------------------------------------------------------------------
         *  - close any other static popover windows
         * ------------------------------------------------------------------------------- */
        $(document).on('click', '.js-card-settings-button-static', function () {
            $('.js-card-settings-button-static').not(this).popover('hide');
        });



        /**-------------------------------------------------------------
         * EDITING TASK STATUS
         * ------------------------------------------------------------*/
        $(document).off('click', '.card-tasks-update-status-link').on('click', '.card-tasks-update-status-link', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //cart display element
            var $card_display_element = $("#card-task-status-text");
            //set current value text
            $(".popover-body").find("#current_task_status_text").val($card_display_element.html());
            //add loading
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //set hidden form
            $(this).closest('.popover').find("#task_status").val($(this).attr('data-value'));
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING TASK PRIORITy
         * ------------------------------------------------------------*/
        $(document).off('click', '.card-tasks-update-priority-link').on('click', '.card-tasks-update-priority-link', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //cart display element
            var $card_display_element = $("#card-task-priority-text");
            //set current value text
            $(".popover-body").find("#current_task_priority_text").val($card_display_element.html());
            //add loading
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //set hidden form
            $(this).closest('.popover').find("#task_priority").val($(this).attr('data-value'));
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING TASK VISIBILITY
         * ------------------------------------------------------------*/
        $(document).off('click', '.card-tasks-update-visibility-link').on('click', '.card-tasks-update-visibility-link', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //cart display element
            var $card_display_element = $("#card-task-client-visibility-text");
            //set current value text
            $(".popover-body").find("#current_task_client_visibility_text").val($card_display_element.html());
            //add loading
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //set hidden form
            $(this).closest('.popover').find("#task_client_visibility").val($(this).attr('data-value'));
            $(this).closest('.popover').find("#current_task_client_visibility_text").val($(this).attr('data-text'));
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING TASK MILESTONE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-tasks-update-milestone-button').on('click', '#card-tasks-update-milestone-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //get selected text
            var $select = $(".popover-body").find("#task_milestoneid");
            var selected_text = $select.find('option:selected').text();
            $("#card-task-milestone-title").html(selected_text);
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING LEAD NAME
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-name').on('click', '#card-lead-name', function () {
            //update names in the form
            $(".popover-body").find("#lead_firstname").val($(document).find("#card-lead-firstname-containter").html());
            $(".popover-body").find("#lead_lastname").val($(document).find("#card-lead-lastname-containter").html());
        });
        $(document).off('click', '#card-leads-update-name-button').on('click', '#card-leads-update-name-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset current name
            $("#card-lead-firstname-containter").html('');
            $("#card-lead-lastname-containter").html('');
            //add loading
            $("#card-lead-element-container-name").addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });

        /**-------------------------------------------------------------
         * EDITING LEAD VALUE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-value').on('click', '#card-lead-value', function () {
            $(".popover-body").find("#lead_value").val($(this).attr('data-value'));
        });
        $(document).off('click', '#card-leads-update-value-button').on('click', '#card-leads-update-value-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-value");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });



        /**-------------------------------------------------------------
         * EDITING LEAD STATUS
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-status-button').on('click', '#card-leads-update-status-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-status-text");
            //current text value
            $(".popover-body").find("#current_lead_status_text").val($card_display_element.html());
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING LEAD CATEGORY
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-category-button').on('click', '#card-leads-update-category-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-category-text");
            //current text value
            $(".popover-body").find("#current_lead_category_text").val($card_display_element.html());
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING LEAD PHONE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-phone').on('click', '#card-lead-phone', function () {
            var current_value = ($(this).html() == '---') ? '' : $(this).html();
            $(".popover-body").find("#lead_phone").val(current_value);
        });
        $(document).off('click', '#card-leads-update-phone-button').on('click', '#card-leads-update-phone-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-phone");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * EDITING LEAD EMAIL
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-lead-email').on('click', '#card-lead-email', function () {
            var current_value = ($(this).html() == '---') ? '' : $(this).html();
            $(".popover-body").find("#lead_email").val(current_value);
        });
        $(document).off('click', '#card-leads-update-email-button').on('click', '#card-leads-update-email-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-email");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });



        /**-------------------------------------------------------------
         * EDITING LEAD SOURCE
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-source-button').on('click', '#card-leads-update-source-button', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //reset data & add loading class
            var $card_display_element = $("#card-lead-source-text");
            $card_display_element.html('---');
            $card_display_element.attr('data-value', '');
            $card_display_element.addClass('loading');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });



        /**-------------------------------------------------------------
         * UPDATING ASSIGNED USERS
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-tasks-update-assigned').on('click', '#card-tasks-update-assigned', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //add loading class
            $("#task-assigned-container").html('');
            $("#task-assigned-container").addClass('loading-placeholder');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });

        /**-------------------------------------------------------------
         * UPDATING ASSIGNED USERS
         * ------------------------------------------------------------*/
        $(document).off('click', '#card-leads-update-assigned').on('click', '#card-leads-update-assigned', function () {
            //update the buttons parent popover
            $(this).attr('data-form-id', $(this).closest('.popover').attr('id'));
            //add loading class
            $("#lead-assigned-container").html('');
            $("#lead-assigned-container").addClass('loading-placeholder');
            //close static popovers
            $('.js-card-settings-button-static').popover('hide');
            //send request
            nxAjaxUxRequest($(this));
        });


        /**-------------------------------------------------------------
         * FILE UPLOAD TOGGLE
         * ------------------------------------------------------------*/
        $(document).off('click', '#js-card-toggle-fileupload').on('click', '#js-card-toggle-fileupload', function () {
            $(document).find("#card-fileupload-container").toggle();
        });
    }



    /**-------------------------------------------------------------
     * FILE UPLOAD
     * ------------------------------------------------------------*/
    if ($("#card-attachments").length) {
        //get the url
        var upload_url = $("#card-attachments").attr('data-upload-url');
        //attache the files
        if ($("#card_fileupload").length) {
            $("#card_fileupload").dropzone({
                url: upload_url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                init: function () {
                    this.on("error", function (file, message, xhr) {
                        //is there a message from backend [abort() response]
                        if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                            var error = $.parseJSON(xhr.response);
                            if (typeof error === 'object' && typeof error.notification != 'undefined') {
                                var message = error.notification.value;
                            } else {
                                var message = NXLANG.generic_error;
                            }
                        }

                        //any other message
                        var message = (typeof message == 'undefined' || message == '' ||
                            typeof message == 'object') ? NXLANG.generic_error : message;

                        //error message
                        NX.notification({
                            type: 'error',
                            message: message
                        });
                        //remove the file
                        this.removeFile(file);
                    });
                },
                success: function (file, response) {
                    $("#card-fileupload-container").hide();
                    //get the priview box dom elemen
                    var $preview = $(file.previewElement);
                    //add to the list
                    $("#card-attachments-container").prepend(response.attachment);
                    //remove the file
                    this.removeFile(file);
                }
            });
        }
    }


    /**-------------------------------------------------------------
     * POLL TASK TIMER
     * ------------------------------------------------------------*/
    if ($("#cardModal").length) {
        function nxTimerTaskPolling() {
            if ($("#timerTaskPollingTrigger").length) {
                nxAjaxUxRequest($("#timerTaskPollingTrigger"));
            }
            setTimeout(nxTimerTaskPolling, 45000);
        };
        nxTimerTaskPolling();
    }
};


/**--------------------------------------------------------------------------------------
 * [AUTHENTICATON] 
 * @blade : task\modal.blade.php
 * @description: all login, signup, forgot password js
 * -------------------------------------------------------------------------------------*/
function NXAuthentication() {
    /*----------------------------------------------------------------
     * login  - form validation
     *--------------------------------------------------------------*/
    if ($("#reloginForm").length) {
        $("#reloginForm").validate({
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            submitHandler: function (form) {
                nxAjaxUxRequest($("#reloginModalButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * signup - form validation
     *--------------------------------------------------------------*/
    if ($("#signUpForm").length) {
        $("#signUpForm").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                client_company_name: "required",
                email: "required",
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            submitHandler: function (form) {
                nxAjaxUxRequest($("#signupButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * reset password - form validation
     *---------------------------------------------------------------*/
    if ($("#resetPasswordForm").length) {
        $("#resetPasswordForm").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            submitHandler: function (form) {
                nxAjaxUxRequest($("#resetPasswordSubminButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * login - form validation
     *--------------------------------------------------------------*/
    if ($("#loginForm").length) {
        $("#loginForm").validate({
            rules: {
                email: "required",
                password: "required",
            },
            submitHandler: function (form) {
                nxAjaxUxRequest($("#loginSubmitButton"));
            }
        });
    }


    /*----------------------------------------------------------------
     * forgot password -  form validation
     *---------------------------------------------------------------*/
    if ($("#forgotPasswordForm").length) {
        $("#forgotPasswordForm").validate({
            rules: {
                email: "required",
            },
            submitHandler: function (form) {
                nxAjaxUxRequest($("#forgotSubmitButton"));
            }
        });
    }

    /*----------------------------------------------------------------
     * relogin - form validation
     *--------------------------------------------------------------*/
    if ($("#reloginForm").length) {
        $("#reloginForm").validate({
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            submitHandler: function (form) {
                nxAjaxUxRequest($("#reloginModalButton"));
            }
        });
    }
};
NXAuthentication();


/**--------------------------------------------------------------------------------------
 * [COMMENTS] 
 * @blade : comments\wrapper.blade.php
 * @description: show tinymce comment box
 * -------------------------------------------------------------------------------------*/
function NXPostGeneralComment() {

    //variable
    var unique_comment_id = $("#js-trigger-comments").attr('data-payload');

    //remove existing
    tinymce.remove('#editor-' + unique_comment_id);
    //initialize
    tinymce.init({
        selector: '#editor-' + unique_comment_id,
        mode: 'exact',
        theme: "modern",
        skin: 'light',
        branding: false,
        menubar: false,
        statusbar: false,
        plugins: [
            "advlist autolink lists link image preview code",
            "table paste autoresize"
        ],
        height: 200,
        toolbar: 'undo redo bold image link bullist numlist blockquote code',
        //autosave/update text area
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
    //focus on the editor
    $(document).on('click', '#placeholder-container-' + unique_comment_id, function () {
        tinymce.get('editor-' + unique_comment_id).setContent('');
        tinymce.execCommand('mceFocus', true, 'editor-' + unique_comment_id);
    });
}


/**--------------------------------------------------------------------------------------
 * [COMMENTS] 
 * @blade : contacts\mdals\add-edit.blade.php
 * @description: validation for the add contact form
 * -------------------------------------------------------------------------------------*/
function NXContacts() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            email: "required",
            clientid: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [ESTIMATE] 
 * @blade : estimates\components\modals\add-edit-inc.blade.php
 * @description: validation for add/edit estimates form
 * -------------------------------------------------------------------------------------*/
function NXEstimates() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_date_add_edit: "required",
            bill_clientid: "required",
            bill_status: "required",
            bill_categoryid: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [EXPENSES] 
 * @blade : expenses\components\modals\add-edit-inc.blade.php
 * @description: validation for add/edit expenses form
 * -------------------------------------------------------------------------------------*/
function NXEstimates() {
    //variable
    var expense_modal_trigger_clients_project_list = $("#js-trigger-expenses").attr('data-payload');
    var expense_client_id = $("#js-trigger-expenses").attr('data-client-id');


    //file upload
    $("#fileupload_expense_receipt").dropzone({
        url: "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });


    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            expense_description: "required",
            expense_date: "required",
            expense_amount: "required",
            expense_categoryid: "required"
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });



    //create expense
    if (expense_modal_trigger_clients_project_list == 'show') {
        //client select element
        var $dropdown = $("#expense_clientid");

        //selected client
        var client_id = expense_client_id;

        // Construct my data to select
        var data = {
            "id": client_id
        };

        // Set the value
        $dropdown.val(client_id);

        // Change the select2 control to update it visually
        $dropdown.trigger("change");

        // Manually fire the event with my data
        $dropdown.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    }
}

/**--------------------------------------------------------------------------------------
 * [CLIENT - UPLOAD LOGO]
 * @blade : clients\components\modals\update-logo.blade.php
 * @description: logo uploading inside client dashboard
 * -------------------------------------------------------------------------------------*/
function NXClientUploadLogo() {

    if ($("#js-trigger-clients-modal-upload-logo").length) {

        var client_id = $("#js-trigger-clients-modal-upload-logo").attr('data-payload');

        //upload client logo
        $("#fileupload_single_image").dropzone({
            url: "/uploadlogo",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            maxFiles: 1,
            maxFilesize: 2, // MB
            acceptedFiles: 'image/jpeg, image/png',
            thumbnailWidth: null,
            thumbnailHeight: null,
            init: function () {
                this.on("error", function (file, message, xhr) {

                    //is there a message from backend [abort() response]
                    if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                        var error = $.parseJSON(xhr.response);
                        var message = error.notification.value;
                    }

                    //any other message
                    var message = (typeof message == 'undefined' || message == '' ||
                        typeof message == 'object') ? NXLANG.generic_error : message;

                    //error message
                    NX.notification({
                        type: 'error',
                        message: message
                    });
                    //remove the file
                    this.removeFile(file);
                });
            },
            success: function (file, response) {
                //get the priview box dom elemen
                var $preview = $(file.previewElement);
                //create a hidden form field for this file
                $preview.append('<input type="hidden" name="logo_filename"  value="' + response
                    .filename + '">');
                $preview.append('<input type="hidden" name="logo_directory"  value="' + response
                    .uniqueid + '">');
                $preview.append('<input type="hidden" name="client_id"  value="' + client_id +
                    '">');
            }
        });

        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {},
            submitHandler: function (form) {
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}


/**--------------------------------------------------------------------------------------
 * [FILES] 
 * @blade : files\components\modals\add-edit-inc.blade.php
 * @description: validation for add/edit expenses form
 * -------------------------------------------------------------------------------------*/
function NXFiles() {

    //uplaod files
    $("#fileupload_files").dropzone({
        url: "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    if (typeof error === 'object' && typeof error.notification != 'undefined') {
                        var message = error.notification.value;
                    } else {
                        var message = NXLANG.generic_error;
                    }
                }

                //system generated errors (e.g. apache)
                if (typeof xhr != 'undefined' && typeof xhr.statusText != 'undefined') {
                    //file too large (php.ini settings)
                    if (xhr.statusText == 'Payload Too Large') {
                        var message = NXLANG.file_too_big;
                    }
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });



    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [HOME PAGE - ADMIN] 
 * @blade : home\admin\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeAdmin() {

    //projects list scroll
    if ($("#dashboard-admin-events").length) {
        const ps = new PerfectScrollbar('#dashboard-admin-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }

    //leads chart
    if ($("#leadsWidget").length) {
        var chart = c3.generate({
            bindto: '#leadsWidget',
            data: {
                columns: NX.admin_home_c3_leads_data,
                type: 'donut',
                onclick: function (d, i) {},
                onmouseover: function (d, i) {},
                onmouseout: function (d, i) {}
            },
            donut: {
                label: {
                    show: false
                },
                title: NX.admin_home_c3_leads_title,
                width: 20,

            },

            legend: {
                hide: true
            },
            color: {
                pattern: NX.admin_home_c3_leads_colors
            }
        });
    }



    //income vs expenses
    if ($("#admin-dhasboard-income-vs-expenses").length) {
        var chart = new Chartist.Line('.incomeexpenses', {
            labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            series: [
                NX.admin_home_chart_income,
                NX.admin_home_chart_expenses
            ]
        }, {
            lineSmooth: Chartist.Interpolation.simple({
                divisor: 2
            }),
            showArea: true,
            low: 0,
            fullWidth: true,
            plugins: [
                Chartist.plugins.tooltip()
            ],

        });

        chart.on('draw', function (ctx) {
            if (ctx.type === 'area') {
                ctx.element.attr({
                    x1: ctx.x1 + 0.001
                });
            }
        });
        chart.on('created', function (ctx) {
            var defs = ctx.svg.elem('defs');
            defs.elem('linearGradient', {
                id: 'gradient',
                x1: 0,
                y1: 1,
                x2: 0,
                y2: 0
            }).elem('stop', {
                offset: 0,
                'stop-color': 'rgba(255, 255, 255, 1)'
            }).parent().elem('stop', {
                offset: 1,
                'stop-color': 'rgba(36, 210, 181, 1)'
            });
        });
        var chart = [chart];
    }
}
if ($("#js-trigger-home-admin-wrapper").length) {
    NXHomeAdmin();
}


/**--------------------------------------------------------------------------------------
 * [HOME PAGE - TEAM] 
 * @blade : home\team\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeTeam() {
    //perfect scroll
    if ($("#dashboard-client-projects").length) {
        const ps2 = new PerfectScrollbar('#dashboard-client-projects', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }


    //perfect scroll
    if ($("#dashboard-client-events").length) {
        const ps = new PerfectScrollbar('#dashboard-client-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }
}
if ($("#js-trigger-home-team-wrapper").length) {
    NXHomeTeam();
}


/**--------------------------------------------------------------------------------------
 * [HOME PAGE - CLIENT] 
 * @blade : home\team\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeTeam() {
    //perfect scroll
    if ($("#dashboard-client-projects").length) {
        const ps2 = new PerfectScrollbar('#dashboard-client-projects', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }


    //perfect scroll
    if ($("#dashboard-client-events").length) {
        const ps = new PerfectScrollbar('#dashboard-client-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }
}
if ($("#js-trigger-home-team-wrapper").length) {
    NXHomeTeam();
}



/**--------------------------------------------------------------------------------------
 * [HOME PAGE - CLIENT] 
 * @blade : home\client\wrapper.blade.php
 * @description: display dashboard widgets
 * -------------------------------------------------------------------------------------*/
function NXHomeClient() {
    //perfect scroll
    if ($("#dashboard-client-projects").length) {
        const ps2 = new PerfectScrollbar('#dashboard-client-projects', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }


    //perfect scroll
    if ($("#dashboard-client-events").length) {
        const ps = new PerfectScrollbar('#dashboard-client-events', {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });
    }
}
if ($("#js-trigger-home-team-wrapper").length) {
    NXHomeClient();
}



/**--------------------------------------------------------------------------------------
 * [INVOICE - CLONE] 
 * @blade : invoices\components\modals\clone.blade.php
 * @description: validation for cloning an invoice
 * -------------------------------------------------------------------------------------*/
function NXInvoiceClone() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_clientid: "required",
            bill_due_date: "required",
            bill_categoryid: "required",
            bill_date: "required",
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [INVOICE - RECURRING] 
 * @blade : invoices\components\modals\recurring-settings.blade.php
 * @description: validation for recurring an invoice
 * -------------------------------------------------------------------------------------*/
function NXInvoiceRecurring() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_recurring_next: "required",
            bill_recurring_duration: "required",
            bill_recurring_period: "required",
            bill_recurring_cycles: "required",
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [INVOICE - ADD] 
 * @blade : invoices\components\modals\add-edit-inc.blade.php
 * @description: validation for creating an invoice
 * -------------------------------------------------------------------------------------*/
function NXInvoiceCreate() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            bill_due_date_add_edit: "required",
            bill_categoryid: "required",
            bill_date_add_edit: "required",
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [ITEMS - ADD - EDIT] 
 * @blade : items\components\modals\add-edit-inc.blade.php
 * @description: validation for creating a product
 * -------------------------------------------------------------------------------------*/
function NXItemCreate() {
    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            item_description: "required",
            item_categoryid: "required",
            item_unit: "required",
            item_rate: "required"
        },
        submitHandler: function (form) {

            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [KB ARTICLE - CREATE] 
 * @description: validation for creating an article
 * -------------------------------------------------------------------------------------*/
function NXArticleCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            knowledgebase_title: "required",
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [LEADS - CONVERT] 
 * @description: validation for converting a lead
 * -------------------------------------------------------------------------------------*/
function NXLeadConvert() {
    $("#convertLeadForm").validate({
        rules: {
            first_name: 'required',
            last_name: 'required',
            client_company_name: 'required',
            email: 'required'
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#createCustomerButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [LEADS - CREATE] 
 * @description: validation for creating a lead
 * -------------------------------------------------------------------------------------*/
function NXLeadCreate() {

    //clean up lead value field - strip symbols and none numric
    $(document).on('click', '#commonModalSubmitButton', function () {
        if ($('#lead_value').length) {
            var lead_value = $("#lead_value").val();
            $("#lead_value").val(lead_value.replace(/[^0-9.]/g, ""));
        }
    });

    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            lead_title: "required",
            lead_firstname: "required",
            lead_lastname: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [LEADS - KANBAN] 
 * @description: bootstrap the kanban board
 * -------------------------------------------------------------------------------------*/
function NXLeadsKanban() {

    if ($(".js-trigger-leads-kanban-board").length) {

        //variable
        var lead_position = $("#leads-view-wrapper").attr('data-position');

        //dga and drop
        var cardsDraggable = dragula({
                isContainer: function (el) {
                    return el.classList.contains('kanban-content');
                }
            }).on('drag', function (card) {
                // add 'is-moving' class to element being dragged
                card.classList.add('is-moving');
            })
            .on('dragend', function (card) {
                // remove 'is-moving' class from element after dragging has stopped
                card.classList.remove('is-moving');
                // add the 'is-moved' class for 600ms then remove it
                window.setTimeout(function () {
                    card.classList.add('is-moved');
                    window.setTimeout(function () {
                        card.classList.remove('is-moved');
                    }, 600);
                }, 100);

                //this card id
                var this_lead_id = $(card).attr('data-lead-id');

                //previous card's lead  id
                var previous_list = $(card).prevAll()
                var previous_lead_id = '';
                previous_list.each(function () {
                    if ($(this).hasClass('kanban-card')) {
                        previous_lead_id = $(this).attr('data-lead-id');
                        return false;
                    }
                });

                //next card's lead id
                var next_lead_id = $(card).next('.kanban-card').attr('data-lead-id');
                if (typeof next_lead_id == 'undefined') {
                    next_lead_id = '';
                }

                //board
                var board_name = $(card).parent('.kanban-content').attr('data-board-name');

                //ajax update request
                var update_position = $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: lead_position,
                    dataType: 'json',
                    data: 'lead_id=' + this_lead_id + '&previous_lead_id=' + previous_lead_id +
                        '&next_lead_id=' + next_lead_id + '&status=' + board_name,
                });
            });
    }
}
if ($(".js-trigger-leads-kanban-board").length) {
    NXLeadsKanban();
}


/**--------------------------------------------------------------------------------------
 * [LEADS - CREATE] 
 * @description: validation for creating a lead
 * -------------------------------------------------------------------------------------*/
function NXTaskCreate() {

    //task type selector
    $(".task_type_selector").on('click', function () {
        $("#task_type").val($(this).val());
    });

    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            task_title: "required",
            task_projectid: "required",
            task_priority: "required",
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [LEADS - KANBAN] 
 * @description: bootstrap the kanban board
 * -------------------------------------------------------------------------------------*/
function NXTasksKanban() {

    if ($("#js-tasks-kanban-wrapper").length) {

        //position
        var task_position = $("#js-tasks-kanban-wrapper").attr('data-position');

        //gragable
        var cardsDraggable = dragula({
                isContainer: function (el) {
                    return el.classList.contains('kanban-content');
                }
            }).on('drag', function (card) {
                // add 'is-moving' class to element being dragged
                card.classList.add('is-moving');
            })
            .on('dragend', function (card) {
                // remove 'is-moving' class from element after dragging has stopped
                card.classList.remove('is-moving');
                // add the 'is-moved' class for 600ms then remove it
                window.setTimeout(function () {
                    card.classList.add('is-moved');
                    window.setTimeout(function () {
                        card.classList.remove('is-moved');
                    }, 600);
                }, 100);

                //this card id
                var this_task_id = $(card).attr('data-task-id');

                //previous card's task  id
                var previous_list = $(card).prevAll()
                var previous_task_id = '';
                previous_list.each(function () {
                    if ($(this).hasClass('kanban-card')) {
                        previous_task_id = $(this).attr('data-task-id');
                        return false;
                    }
                });

                //next card's task id
                var next_task_id = $(card).next('.kanban-card').attr('data-task-id');
                if (typeof next_task_id == 'undefined') {
                    next_task_id = '';
                }

                //board
                var board_name = $(card).parent('.kanban-content').attr('data-board-name');

                //ajax update request
                var update_position = $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: task_position,
                    dataType: 'json',
                    data: 'task_id=' + this_task_id + '&previous_task_id=' + previous_task_id +
                        '&next_task_id=' + next_task_id + '&status=' + board_name,
                });
            });
    }
}
if ($("#js-tasks-kanban-wrapper").length) {
    NXTasksKanban();
}

/**--------------------------------------------------------------------------------------
 * [LEADS - SHOW] 
 * @description: js on lead card
 * -------------------------------------------------------------------------------------*/
function NXLeadAttachFiles() {

    $("#fileupload_lead").dropzone({
        url: "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [MILESTONE PAGE] 
 * @description: drag and drop table rows
 * -------------------------------------------------------------------------------------*/
function NXMilestonesDragDrop() {

    if ($("#js-trigger-milestones-sorting").length) {
        /*----------------------------------------------------------------
         * drag and drop milestone positions
         *--------------------------------------------------------------*/
        var container = document.getElementById('milestones-td-container');

        var stagesDraggable = dragula([container]);

        //make every board dragable area
        stagesDraggable.on('drag', function (stage) {
            stage.classList.add('is-moving');
        });
        stagesDraggable.on('dragend', function (stage) {
            stage.classList.remove('is-moving');
            window.setTimeout(function () {
                stage.classList.add('is-moved');
                window.setTimeout(function () {
                    stage.classList.remove('is-moved');
                }, 600);
            }, 100);

            //update the list
            nxAjaxUxRequest($("#milestones-table"));
        });
    }
}
if ($("#js-trigger-milestones-sorting").length) {
    NXMilestones();
}


/**--------------------------------------------------------------------------------------
 * [MILESTONE MODAL] 
 * @description: validation on creating milestone
 * -------------------------------------------------------------------------------------*/
function NXMilestonesCreate() {

    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            milestone_title: "required",
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [NOTES MODAL] 
 * @description: validation on creating notes
 * -------------------------------------------------------------------------------------*/
function NXNotesCreate() {

    nxTinyMCEBasic();

    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            note_title: "required",
            note_description: "required"
        },
        submitHandler: function (form) {

            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [LEFT MENU - CLIENTS] 
 * @blade : nav\leftmenu-client.blade.php
 * @description: js for the left menu
 * -------------------------------------------------------------------------------------*/
function NXANavCLient() {
    $(".sidenav-menu-item").has("ul").addClass('has-submenu');
}
if ($("#js-trigger-nav-client").length) {
    NXHomeClient();
}



/**--------------------------------------------------------------------------------------
 * [LEFT MENU - CLIENTS] 
 * @blade : nav\leftmenu-team.blade.php
 * @description: js for the left menu
 * -------------------------------------------------------------------------------------*/
function NXANavTeam() {
    /*----------------------------------------------------------------
     * add special class to menu items that have submenu
     *--------------------------------------------------------------*/
    $(".sidenav-menu-item").has("ul").addClass('has-submenu');


    /*----------------------------------------------------------------
     * left menu scroll
     *---------------------------------------------------------------*/
    $(document).on('click', '.sidebartoggler ', function () {
        //scroll mini menu back to top
        document.getElementById('main-scroll-sidebar').scrollTop = 0;
    });



    /*----------------------------------------------------------------
     * left menu toltip
     *---------------------------------------------------------------*/
    if ($('body').hasClass('mini-sidebar')) {
        $(".menu-with-tooltip").addClass('menu-tooltip');
    } else {
        $(".menu-with-tooltip").removeClass('menu-tooltip');
    }
    $('.menu-tooltip').tooltip({
        trigger: 'hover',
        placement: 'right',
        template: '<div class="tooltip menu-tooltips" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
    });
    $(document).on('click', '.menu-tooltip', function () {
        $('.menu-tooltip menu-with-tooltip').tooltip("hide");
    });
}
if ($("#js-trigger-nav-team").length) {
    NXANavTeam();
    nxMainLeftMenuScroll();
}



/**--------------------------------------------------------------------------------------
 * [STRIPE BUTTON] 
 * @description: geneate button shown on invoice page
 * -------------------------------------------------------------------------------------*/
function NXStripePaymentButton() {

    //get api keys
    var stripe_api_key = $("#js-pay-stripe").attr('data-key');
    var payment_session = $("#js-pay-stripe").attr('data-session');

    //redirect user to checkout page
    $(document).on('click', '#invoice-stripe-payment-button', function () {
        //set stripe public ket
        var stripe = Stripe(stripe_api_key);
        //redirect to stripe checkout page
        stripe.redirectToCheckout({
            sessionId: payment_session
        }).then(function (result) {
            //an error occured and stripe could not redirect
        });
    });
}


/**--------------------------------------------------------------------------------------
 * [PAYMENT MODAL] 
 * @description: validation for creating a new payment
 * -------------------------------------------------------------------------------------*/
function NXPayementCreate() {
    //validate
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            payment_invoiceid: "required",
            payment_amount: "required",
            payment_date: "required",
            payment_gateway: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [PROJECT DETAILS] 
 * @description: editing project details on project page
 * -------------------------------------------------------------------------------------*/
function NXProjectDetails() {

    //editor variables
    NX.project_description = $("#project-description");
    NX.project_description_original_text = $("#project-description").html();
    NX.project_descrition_selector = '#project-description';
    NX.project_description_submit = $("#project-description-submit")
    NX.project_description_edit = $("#project-description-edit")
    NX.project_description_height = $("#project-description").outerHeight();
    NX.project_details_tags_edit = $("#project-details-edit-tags")
    NX.project_details_tags = $("#project-details-tags")

    //edit button clicked
    $(document).on('click', '#project-description-button-edit', function () {
        NX.project_description_edit.hide();
        NX.project_description.addClass('tinymce-textarea');
        NX.project_details_tags.hide();
        NX.project_details_tags_edit.show();
        NX.project_description_submit.show();
        nxTinyMCEBasic(NX.project_description_height, NX.project_descrition_selector);
    });

    //cancel button clicked
    $(document).on('click', '#project-description-button-cancel', function () {
        NX.project_description.removeClass('tinymce-textarea');
        NX.project_description_submit.hide();
        NX.project_description_edit.show();
        NX.project_details_tags.show();
        NX.project_details_tags_edit.hide();
        NX.project_description.html(NX.project_description_original_text);
        tinymce.remove();
    });
    //save button clicked
    $(document).on('click', '#project-description-button-save', function () {
        try {
            $("#description").val(tinymce.activeEditor.getContent());
        } catch (err) {}
        //unbind events
        $(this).off("click");
        $("#project-description-button-edit").off("click");
        $("#project-description-button-cancel").off("click");
        //make request
        nxAjaxUxRequest($(this));
        tinymce.remove();
    });
}


/**--------------------------------------------------------------------------------------
 * [PROJECT - DYNAMIC] 
 * @description: show dynamic project pages
 * -------------------------------------------------------------------------------------*/
if ($("#dynamic-project-content").length) {
    nxAjaxUxRequest($("#dynamic-project-content"));
}


/**--------------------------------------------------------------------------------------
 * [LEFT MENU - CLIENTS] 
 * @blade : nav\leftmenu-client.blade.php
 * @description: js for the left menu
 * -------------------------------------------------------------------------------------*/
function NXANavCLient() {
    $(".sidenav-menu-item").has("ul").addClass('has-submenu');
}
if ($("#js-trigger-nav-client").length) {
    NXHomeClient();
}


/**--------------------------------------------------------------------------------------
 * [PROJECT - CHARTS] 
 * @description: render charts of project page
 * -------------------------------------------------------------------------------------*/
if ($("#project_details").length) {
    var progress = $("#project_details").attr('data-progress');
    //set color
    var chart = c3.generate({
        bindto: '#project_progress_chart',
        data: {
            columns: [
                ['data', progress]
            ],
            type: 'gauge'
        },
        color: {
            pattern: ['#24d2b5']
        },
        gauge: {
            width: 22,
        },
        size: {
            height: 90,
            width: 150
        }
    });
}


/**--------------------------------------------------------------------------------------
 * PROJECT - MODAL] 
 * @description: edit projects modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditProject() {


    //page section
    var page_section = $("#js-projects-modal-add-edit").attr('data-section');


    //reset editor
    nxTinyMCEBasic();

    //progress slider
    var progress = document.getElementById('edit_project_progress_bar');
    noUiSlider.create(progress, {
        start: [NX.varInitialProjectProgress],
        connect: true,
        step: 1,
        range: {
            'min': 0,
            'max': 100
        }
    });
    //set display and hidden form field values
    var project_progress_input = document.getElementById('project_progress');
    var project_progress_display = document.getElementById('edit_project_progress_display');
    progress.noUiSlider.on('update', function (values, handle) {
        project_progress_display.innerHTML = values[handle];
        project_progress_input.value = values[handle];
    });



    /** ----------------------------------------------------------
     * create project - form validation
     * ---------------------------------------------------------*/
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                project_title: "required",
                project_clientid: "required",
                project_categoryid: "required",
                project_date_start: "required",
            },
            submitHandler: function (form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    /** ----------------------------------------------------------
     * edit project - form validation
     * ---------------------------------------------------------*/
    if (page_section == 'edit') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                project_title: "required",
                project_categoryid: "required",
                project_date_start: "required",
            },
            submitHandler: function (form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    /** ----------------------------------------------------------
     * sanity - ensure views are checked also
     * ---------------------------------------------------------*/
    $("#clientperm_tasks_create, #clientperm_tasks_collaborate").on("change", function () {
        if ($(this).is(":checked")) {
            $("#clientperm_tasks_view").prop('checked', true);
        }
    });
    $("#clientperm_tasks_view").on("change", function () {
        if (!$(this).is(":checked")) {
            $("#clientperm_tasks_collaborate").prop('checked', false);
            $("#clientperm_tasks_create").prop('checked', false);
        }
    });


    /** ----------------------------------------------------------
     * clean up lead value field - strip symbols and none numric
     * ---------------------------------------------------------*/
    $(document).on('click', '#commonModalSubmitButton', function () {
        if ($('#project_billing_rate').length) {
            var project_billing_rate = $("#project_billing_rate").val();
            $("#project_billing_rate").val(project_billing_rate.replace(/[^0-9.]/g, ""));
        }
    });


    /** ----------------------------------------------------------
     * select2 firefox fix
     * ---------------------------------------------------------*/
    $('#project_clientid').select2({
        dropdownParent: $('#commonModal')
    });
}


/**--------------------------------------------------------------------------------------
 * CLIENT - MODAL] 
 * @description: edit client modal
 * -------------------------------------------------------------------------------------*/
function NXAddEditClients() {
    //page
    var page_section = $("#js-trigger-clients-modal-add-edit").attr('data-payload');
    //validate create clients
    if (page_section == 'create') {
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                client_company_name: "required",
                first_name: "required",
                last_name: "required",
                email: "required",
            },
            submitHandler: function (form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }


    //edit client
    if (page_section == 'edit') {
        //show address section by default
        $("#add_client_address_section").show();
        $("#add_client_other_details").show();
        $("#commonModalForm").validate().destroy();
        $("#commonModalForm").validate({
            rules: {
                client_company_name: "required",
            },
            submitHandler: function (form) {

                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#commonModalSubmitButton"));
            }
        });
    }
}


/**--------------------------------------------------------------------------------------
 * [SETUP WIZARD]
 * @description: admin details section - form validation
 * -------------------------------------------------------------------------------------*/
function NXSetupAdmin() {
    $("#setupForm").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            email: "required",
            password: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#continueButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [SETUP WIZARD]
 * @description: database details section - form validation
 * -------------------------------------------------------------------------------------*/
function NXSetupDatabase() {
    $("#setupForm").validate({
        rules: {
            database_host: "required",
            database_port: "required",
            database_name: "required",
            database_username: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#continueButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETUP WIZARD]
 * @description: database details section - form validation
 * -------------------------------------------------------------------------------------*/
function NXSetupSettings() {
    $("#setupForm").validate({
        rules: {
            settings_company_name: "required",
            settings_system_timezone: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#continueButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TAGS MODAL]
 * @description: add tags form validation
 * -------------------------------------------------------------------------------------*/
function NXTagsCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            tag_title: "required",
            tagresource_type: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TAGS MENU]
 * @description: trigger fortags page
 * -------------------------------------------------------------------------------------*/
function NXTagsMenu() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            tag_title: "required",
            tagresource_type: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TAGS MENU]
 * @description: trigger fortags page
 * -------------------------------------------------------------------------------------*/
function NXTeamCreate() {
    $("#password, #password_confirmation").val('');
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            first_name: 'required',
            last_name: 'required',
            email: 'required',
            role_id: 'required'
        },
        submitHandler: function (form) {

            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TICKET COMPOSE] 
 * @description: create new ticket
 * -------------------------------------------------------------------------------------*/
if ($("#ticket-compose-form").length) {

    //variable
    var user_type = $("#ticket-compose-form").attr('data-user-type');


    //validate
    if (user_type == 'client') {
        $("#ticket-compose-form").validate({
            rules: {
                ticket_categoryid: "required",
                ticket_subject: "required",
                ticket_message: "required",
            },
            submitHandler: function (form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#ticket-compose-form-button"));
            }
        });
    }

    if (user_type == 'team') {
        $("#ticket-compose-form").validate({
            rules: {
                ticket_categoryid: "required",
                ticket_subject: "required",
                ticket_message: "required",
                ticket_clientid: "required",
                ticket_priority: "required",
            },
            submitHandler: function (form) {
                //ajax form, so initiate ajax request here
                nxAjaxUxRequest($("#ticket-compose-form-button"));
            }
        });
    }

    //fileupload
    $("#fileupload_ticket").dropzone({
        url: "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TICKETS EDIT]
 * @description: validation for tickets page
 * -------------------------------------------------------------------------------------*/
function NXTicketEdit() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            ticket_categoryid: "required",
            ticket_subject: "required",
            ticket_message: "required",
            ticket_clientid: "required",
            ticket_priority: "required",
        },
        submitHandler: function (form) {
            //ajax form, so initiate ajax request here
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [TICKETS EDIT]
 * @description: validation for tickets page
 * -------------------------------------------------------------------------------------*/
if ($("#ticket-editor").length) {
    $("#editTicketMessage").validate({
        rules: {

        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#editTicketMessageButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [CLIENT DYNAMIC]
 * @description: load dynamic client page contact
 * -------------------------------------------------------------------------------------*/
if ($("#dynamic-client-content").length) {
    nxAjaxUxRequest($("#dynamic-client-content"));
}

/**--------------------------------------------------------------------------------------
 * [TICKETS REPLY]
 * @description: validation for tickets reply modal
 * -------------------------------------------------------------------------------------*/
function NXTicketReplay() {
    //file upload
    $("#fileupload_ticket_reply").dropzone({
        url: "/fileupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="attachments[' + response.uniqueid +
                ']"  value="' + response.filename + '">');
        }
    });

    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            ticketreply_text: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}




/**--------------------------------------------------------------------------------------
 * [USER - UPDATE AVATAR]
 * @description: validation for user avatar modal
 * -------------------------------------------------------------------------------------*/
function NXUserUpdateAvatar() {

    //upload avatar
    $("#fileupload_avatar").dropzone({
        url: "/avatarupload",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="avatar_filename"  value="' + response.filename + '">');
            $preview.append('<input type="hidden" name="avatar_directory"  value="' + response.uniqueid + '">');
        }
    });


    //validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}


/**--------------------------------------------------------------------------------------
 * [USER - UPDATE PASSWORD]
 * @description: validation for user update passwod
 * -------------------------------------------------------------------------------------*/
function NXUserUpdatePassword() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            password: {
                minlength: 6,
                required: true,
            },
            password_confirmation: {
                equalTo: "#password"
            },
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS DYNAMIC]
 * @description: settings dynamic content
 * -------------------------------------------------------------------------------------*/
if ($("#dynamic-settings-content").length) {
    nxAjaxUxRequest($("#dynamic-settings-content"));
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - KNOWLDGEBASE]
 * @description: validation for KNOWLEDGEBASE
 * -------------------------------------------------------------------------------------*/
function NXSettingsKnowledgebase() {


    //formvalidation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            kbcategory_title: "required"
        },
        submitHandler: function (form) {
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });



    //icon selection
    $(".js-icon-selector").on('click', function () {
        var value = $(this).attr('data-val');
        //reset active
        $(".js-icon-selector").removeClass('active');
        $(this).addClass('active');
        //set input value
        $("#kbcategory_icon").val(value);
        //change display
        $("#icon-selector-display").removeClass();
        $("#icon-selector-display").addClass(value);
        //toggle whole section
        $("#category_display_icons_section").toggle();
    });


    //toggle icons
    $(".js-switch-toggle-icons").on('click', function () {
        $("#category_display_icons_section").toggle();
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - KNOWLDGEBASE]
 * @description: drag and drop for categories
 * -------------------------------------------------------------------------------------*/
function NXSettingsKnowledgebaseCategories() {

    //replace action button
    $(".parent-page-actions").html('');
    $("#list-page-actions").prependTo(".parent-page-actions");


    //drag and drop categories
    var container = document.getElementById('categories-td-container');

    var stagesDraggable = dragula([container]);


    //make every board dragable area
    stagesDraggable.on('drag', function (stage) {
        // add 'is-moving' class to element being dragged
        stage.classList.add('is-moving');
    });
    stagesDraggable.on('dragend', function (stage) {
        // remove 'is-moving' class from element after dragging has stopped
        stage.classList.remove('is-moving');
        // add the 'is-moved' class for 600ms then remove it
        window.setTimeout(function () {
            stage.classList.add('is-moved');
            window.setTimeout(function () {
                stage.classList.remove('is-moved');
            }, 600);
        }, 100);

        //update the list
        nxAjaxUxRequest($("#knowledgebase-categories"));

    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - LEAD STATUS]
 * @description: form aldation for lead status
 * -------------------------------------------------------------------------------------*/
function NXSettingsLeadStatus() {


    //change lead color - update form field
    $(document).on('change', '.leadstatus_colors', function () {
        if (this.checked) {
            $("#leadstatus_color").val($(this).val());
        }
    });


    //create status - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            leadstatus_title: "required"
        },
        submitHandler: function (form) {
            //set selector color
            $(".leadstatus_colors").each(function () {
                if (this.checked) {
                    $("#leadstatus_color").val($(this).val());
                }
            });
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });

}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - LEAD DRAG & DROP]
 * @description: drag and drop for leads
 * -------------------------------------------------------------------------------------*/
function NXSettingsLeadDragDrop() {

    //replace action buttons
    $(".parent-page-actions").html('');
    $("#list-page-actions").prependTo(".parent-page-actions");

    //drag and drop lead positions
    var container = document.getElementById('status-td-container');
    var stagesDraggable = dragula([container]);

    //make every board dragable area
    stagesDraggable.on('drag', function (stage) {
        // add 'is-moving' class to element being dragged
        stage.classList.add('is-moving');
    });
    stagesDraggable.on('dragend', function (stage) {
        // remove 'is-moving' class from element after dragging has stopped
        stage.classList.remove('is-moving');
        // add the 'is-moved' class for 600ms then remove it
        window.setTimeout(function () {
            stage.classList.add('is-moved');
            window.setTimeout(function () {
                stage.classList.remove('is-moved');
            }, 600);
        }, 100);

        //update the list
        nxAjaxUxRequest($("#lead-stages"));

    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - UPLOAD LOGO]
 * @description: apload app logo
 * -------------------------------------------------------------------------------------*/
function NXSettingsLogo() {

    //set variables and payload
    var logo_size = $("#js-settings-logos-modal").attr('data-size');

    //upload logo
    $("#fileupload_single_image").dropzone({
        url: "/upload-app-logo?logo_size=" + logo_size,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFiles: 1,
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg, image/png',
        thumbnailWidth: null,
        thumbnailHeight: null,
        init: function () {
            this.on("error", function (file, message, xhr) {

                //is there a message from backend [abort() response]
                if (typeof xhr != 'undefined' && typeof xhr.response != 'undefined') {
                    var error = $.parseJSON(xhr.response);
                    var message = error.notification.value;
                }

                //any other message
                var message = (typeof message == 'undefined' || message == '' ||
                    typeof message == 'object') ? NXLANG.generic_error : message;

                //error message
                NX.notification({
                    type: 'error',
                    message: message
                });
                //remove the file
                this.removeFile(file);
            });
        },
        success: function (file, response) {
            //get the priview box dom elemen
            var $preview = $(file.previewElement);
            //create a hidden form field for this file
            $preview.append('<input type="hidden" name="logo_filename"  value="' + response
                .filename + '">');
            $preview.append('<input type="hidden" name="logo_directory"  value="' + response
                .uniqueid + '">');
            $preview.append('<input type="hidden" name="logo_size"  value="' + response
                .logo_size + '">');
        }
    });


    //upload logo - form validation
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {},
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - MILESTONES]
 * @description: Add edit
 * -------------------------------------------------------------------------------------*/
function NXSettingsMilestones() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            milestonecategory_title: "required"
        },
        submitHandler: function (form) {
            //ajax request
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - DROP & DROP]
 * @description: Add edit
 * -------------------------------------------------------------------------------------*/
function NXSettingsMilestonesDragDrop() {
    //replace action buttons
    $(".parent-page-actions").html('');
    $("#list-page-actions").prependTo(".parent-page-actions");


    //drag and drop milstone positions
    var container = document.getElementById('milestones-td-container');

    var stagesDraggable = dragula([container]);

    //make every board dragable area
    stagesDraggable.on('drag', function (stage) {
        // add 'is-moving' class to element being dragged
        stage.classList.add('is-moving');
    });
    stagesDraggable.on('dragend', function (stage) {
        // remove 'is-moving' class from element after dragging has stopped
        stage.classList.remove('is-moving');
        // add the 'is-moved' class for 600ms then remove it
        window.setTimeout(function () {
            stage.classList.add('is-moved');
            window.setTimeout(function () {
                stage.classList.remove('is-moved');
            }, 600);
        }, 100);

        //update the list
        nxAjaxUxRequest($("#milestone-stages"));
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - CLIENTS]
 * @description: Add edit
 * -------------------------------------------------------------------------------------*/
function NXSettingsProjectsClients() {

    //sanity - ensure views are checked also
    $("#settings_projects_clientperm_tasks_create, #settings_projects_clientperm_tasks_collaborate").on(
        "change",
        function () {
            if ($(this).is(":checked")) {
                $("#settings_projects_clientperm_tasks_view").prop('checked', true).prop('disabled',
                    true);
            } else {
                if (!$("#settings_projects_clientperm_tasks_create").is(":checked") && !$(
                        "#settings_projects_clientperm_tasks_collaborate")
                    .is(":checked")) {
                    $("#settings_projects_clientperm_tasks_view").prop('disabled', false);
                }
            }
        });
    $("#settings_projects_clientperm_tasks_view").on("change", function () {
        if (!$(this).is(":checked")) {
            $("#settings_projects_clientperm_tasks_collaborate").prop('checked', false);
            $("#settings_projects_clientperm_tasks_create").prop('checked', false);
        }
    });

}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - ROLES]
 * @description: Add edit roles
 * -------------------------------------------------------------------------------------*/
function NXSettingsRoles() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            role_name: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - ROLES]
 * @description: Add edit roles
 * -------------------------------------------------------------------------------------*/
function NXSettingsRolesTable() {
    var $actions = $("#list-page-actions");
    $(".parent-page-actions").html('');
    $actions.prependTo(".parent-page-actions");
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - SOURCES]
 * @description: list sources
 * -------------------------------------------------------------------------------------*/
function NXSettingsSources() {
    var $actions = $("#list-page-actions");
    //replace action buttons
    $(".parent-page-actions").html('');
    $actions.prependTo(".parent-page-actions");
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - SOURCES]
 * @description: Add edit sources
 * -------------------------------------------------------------------------------------*/
function NXSettingsSourcesCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            leadsources_title: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [SETTINGS - TAXES]
 * @description: list taxes
 * -------------------------------------------------------------------------------------*/
function NXSettingsTaxes() {
    var $actions = $("#list-page-actions");
    //replace action buttons
    $(".parent-page-actions").html('');
    $actions.prependTo(".parent-page-actions");
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - TAXES]
 * @description: Add edit taxes
 * -------------------------------------------------------------------------------------*/
function NXSettingsTaxesCreate() {
    $("#commonModalForm").validate().destroy();
    $("#commonModalForm").validate({
        rules: {
            taxrate_name: "required",
            taxrate_value: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}




/**--------------------------------------------------------------------------------------
 * [SETTINGS - UPDATES]
 * @description: check for updates
 * -------------------------------------------------------------------------------------*/
function NXSettingsUpdate() {
    nxAjaxUxRequest($("#updates-checking"));
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - EMAIL TEMPLATES]
 * @description: load email templates editor
 * -------------------------------------------------------------------------------------*/
function NXSettingsEmailTemplates() {

    //text editor
    nxTinyMCEAdvanced(500, '#emailtemplate_body', 'fullpage spellchecker', '');
    setTimeout(function () {
        $("#emailEditWrapper").removeClass('loading');
        $("#emailEditContainer").show();
    }, 1000);


    //fix for validator
    $("#fix-form-email-templates").validate({});
}




/**--------------------------------------------------------------------------------------
 * [SETTINGS - GENERAL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsGeneral() {
    $("#settingsFormGeneral").validate({
        rules: {
            settings_system_timezone: "required",
            settings_system_date_format: "required",
            settings_system_datepicker_format: "required",
            settings_system_default_leftmenu: "required",
            settings_system_default_statspanel: "required",
            settings_system_pagination_limits: "required",
            settings_system_kanban_pagination_limits: "required",
            settings_system_currency_symbol: "required",
            settings_system_decimal_separator: "required",
            settings_system_thousand_separator: "required",
            settings_system_currency_position: "required",
            settings_system_close_modals_body_click: "required",
            settings_system_language_default: "required",
            settings_system_language_allow_users_to_change: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - COMPANY]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsCompany() {
    $("#settingsFormCompany").validate({
        rules: {
            settings_company_name: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}

/**--------------------------------------------------------------------------------------
 * [SETTINGS - FOO]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsProjectsGeneral() {
    $("#settingsFormProjects").validate({
        rules: {
            settings_projects_default_hourly_rate: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - INVOICES]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsInvoices() {
    $("#settingsFormInvoices").validate({
        rules: {
            settings_invoices_recurring_grace_period: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - PAYPAL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsPaypal() {
    $("#settingsFormPaypal").validate({
        rules: {
            settings_paypal_email: "required",
            settings_paypal_currency: "required",
            settings_paypal_display_name: "required",
            settings_stripe_ipn_url: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - BANK]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsBank() {
    $("#settingsFormBank").validate({
        rules: {
            settings_bank_display_name: "required",
            settings_bank_status: "required"
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - STRIPE]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsStripe() {
    $("#settingsFormStripe").validate({
        rules: {
            settings_stripe_public_key: "required",
            settings_stripe_secret_key: "required",
            settings_stripe_webhooks_key: "required",
            settings_stripe_currency: "required",
            settings_stripe_display_name: "required",
            settings_stripe_ipn_url: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}


/**--------------------------------------------------------------------------------------
 * [SETTINGS - EMAIL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsEmailGeneral() {
    $("#settingsFormEmailGeneral").validate({
        rules: {
            settings_email_from_address: "required",
            settings_email_from_name: "required",
            settings_email_server_type: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}



/**--------------------------------------------------------------------------------------
 * [SETTINGS - EMAIL]
 * @description: form validation
 * -------------------------------------------------------------------------------------*/
function NXSettingsEmailSMTP() {
    $("#settingsFormEmailSMTP").validate({
        rules: {
            settings_email_smtp_host: "required",
            settings_email_smtp_port: "required",
            settings_email_smtp_username: "required",
            settings_email_smtp_password: "required",
            settings_email_smtp_encryption: "required",
        },
        submitHandler: function (form) {
            nxAjaxUxRequest($("#commonModalSubmitButton"));
        }
    });
}