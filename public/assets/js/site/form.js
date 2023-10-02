/**
 * Created by miunh on 2/3/2017.
 */
$(document).ready(function () {
    var selector = creat_input_selector(":not(.disable_validate),");
    // change_view_form();
    // $(document).on("click", "a[href='#']", function (e) {
    //     e.preventDefault();
    // });
    // $(document).on("change", selector, check_value);
    $(document).on("submit", "form.e_ajax_submit", check_form);
    function check_form(e) {
        var check_all = true;
        var form = $(this);
        var selector = creat_input_selector(":not(.disable_validate),");
        form.find(selector).each(function () {
            var temp = check_value(e, $(this));
            check_all = check_all && temp;
        });

        if (check_all) {
            ajax_submit_form(form);
        }
        e.preventDefault();
        return false;
    }

    function ajax_submit_form(form) {
        var btn = form.find("button[type='submit']");
        btn.removeClass('btn-primary');
        btn.addClass('btn-danger');
        btn.html('Loading ...');
        btn.attr('disabled', 'disabled');
        show_loading();
        form.ajaxSubmit({
            type    : "POST",
            dataType: "text",
            cache   : false,
            success : function (dataAll) {
                var temp = dataAll.split($("body").attr("data-barack"));
                var data = {};
                for (var i = 0; i < temp.length; i++) {
                    data = $.extend(true, {}, data, JSON.parse(temp[i]));
                }
                if (window[data.callback]) {
                    console.log("Callback: ", data.callback);
                    window[data.callback](data, form, btn);
                } else {
                    console.log("Callback not found:'", data.callback, "'-->Call 'default_form_submit_respone' instead of");
                    default_form_submit_respone(data, form, btn);
                }
            },
            error   : function (a, b, c) {
                btn.html('Error');
                btn.removeClass('btn-success');
                btn.removeAttr('disabled');
//            alert(a + b + c);
            },
            complete: function (jqXHR, textStatus) {
                hide_loading();
            }
        });
    }

    function check_value(e, source_obj) {
        return true; //TODO: validate in client
        var obj;
        if (source_obj) {
            obj = source_obj;
        } else {
            obj = $(this);
        }
        if (!obj.hasOwnProperty("closest")) {
            return true;
        }
        var parent = obj.closest(".form-group");
        var value = $.trim(obj.val());
        if (obj.attr("type") == "number") {
            if (value.length != 0) {
                if (!(!isNaN(parseFloat(value)) && isFinite(value))) {
                    change_error_state(obj, false);
                    parent.find("label.help-block").html("Dữ liệu nhập vào là số");
                    return false;
                } else {
                    change_error_state(obj, true);
                }
            }
        }
        if (obj.attr("required") != undefined && obj.attr("required") != "0") {
            if (value.length == 0) {
                change_error_state(obj, false);
                parent.find("label.help-block").html("Trường này là bắt buộc");
                return false;
            } else {
                change_error_state(obj, true);
            }
        }
        if (obj.attr("minlength") != undefined && obj.attr("minlength") > 1) {
            if (value.length < obj.attr("minlength")) {
                change_error_state(obj, false);
                parent.find("label.help-block").html("Độ dài tối thiểu là " + obj.attr("minlength") + " ký tự");
                return false;
            } else {
                change_error_state(obj, true);
            }
        }
        if (obj.attr("maxlength") != undefined && obj.attr("maxlength") > 1) {
            if (value.length > obj.attr("maxlength")) {
                change_error_state(obj, false);
                parent.find("label.help-block").html("Độ dài tối đa là " + obj.attr("maxlength") + " ký tự");
                return false;
            } else {
                change_error_state(obj, true);
            }
        }

        if (obj.attr("is_email") != undefined && obj.attr("is_email") != 0) {
            if (obj.attr("required") == undefined || obj.attr("required") == "0") {
                change_error_state(obj, true);
            } else {
                if (!is_email(value)) {
                    change_error_state(obj, false);
                    parent.find("label.help-block").html("Trường này yêu cầu là email!");
                    return false;
                } else {
                    change_error_state(obj, true);
                }
            }
        }
        if (obj.attr("recheck") != undefined && obj.attr("recheck") != 0) {
            var selector = creat_input_selector("[name='" + obj.attr("recheck") + "']");
            if (value != $(selector).val()) {
                change_error_state(obj, false);
                parent.find("label.help-block").html("Dữ liệu nhập lại không đúng");
                return false;
            } else {
                change_error_state(obj, true);
            }
        }

        if (obj.attr("allow_null") == undefined && obj.prop("tagName") == "SELECT") {
            if (value == 0) {
                change_error_state(obj, false);
                parent.find("label.help-block").html("Trường này không được bỏ trống");
                return false;
            } else {
                change_error_state(obj, true);
            }
        }
        change_error_state(obj, true);
        return true;
    }

    function default_form_submit_respone(data, form, button) {
        button.removeAttr('disabled');
        var jgrow = "alert-danger";
        if (data.state == 1) { /* success */
            button.removeClass('btn-danger');
            button.addClass('btn-success');
            button.html('Thành công ...');
            jgrow = "alert-success";
        } else if (data.state == 0) { /* Invalida data */
            button.addClass('btn-danger');
            button.removeClass('btn-success');
            button.html('Thất bại ...');
        } else if (data.state == 2) { /* Server error */
            button.addClass('btn-danger');
            button.removeClass('btn-success');
            button.html('Thất bại ...');
        } else {
            button.addClass('btn-danger');
            button.removeClass('btn-success');
            button.html('Không rõ kết quả');
        }
        // if (data.error) {
        //     show_error(data.error, form);
        // }

        $.jGrowl(data.msg, {
            group        : jgrow,
            position     : 'top-right',
            sticky       : false,
            closeTemplate: '<i class="fa fa-times" aria-hidden="true"></i>',
            animateOpen  : {
                width : 'show',
                height: 'show'
            },
            afterOpen    : function () {
                if (data.redirect) {
                    window.location = data.redirect;
                }
            }
        });
    }


    // function
    function creat_input_selector(more) {
        var input = "input,select,radio,textarea";
        var temp = input.split(",");
        var selector = "";
        if (more.substr(more.length - 1) != ",") {
            selector = temp.join(more + ",") + more;
        } else {
            selector = temp.join(more) + more.slice(0, more.length - 1);
        }
        return selector;
    }

    /**
     * Change error state of input in form
     * @param {jQuery} obj
     * @param {bool} is_valid
     * @returns change View
     */
    function change_error_state(obj, is_valid) {
        var parent = obj.closest(".form-group");
        if (is_valid) {
            parent.removeClass("has-error");
            parent.addClass("has-success");
            parent.find("label.help-block").hide();
        } else {
            parent.removeClass("has-success");
            parent.addClass("has-error");
            var error = parent.find("label.help-block"); //length;//.children(".error");
            if (error.length) {
                error.show();
            } else {
                parent.append("<label class='help-block col-sm-8 col-xs-12 col-sm-offset-3'></label>").show();
            }
        }
    }
});