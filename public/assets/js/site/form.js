/**
 * Created by miunh on 2/3/2017.
 */
$(document).ready(function () {
    $(document).on("submit", "form.e_ajax_submit", check_form);
    function check_form(e) {
        var form = $(this);
        ajax_submit_form(form);
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
});