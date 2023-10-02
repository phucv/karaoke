$(document).ready(function () {
    $(document).on('click', '.cc_login_regis_form .btn-login-js', function (e) {
        e.preventDefault();
        login($(this));
    });

    $(document).on('click', '.cc_login_regis_form .btn-register-js', function (e) {
        e.preventDefault();
        register($(this));
    });

    $(document).on('click', '.cc_login_regis_form .btn-forgot-js', function (e) {
        e.preventDefault();
        send_request_forgot_password($(this));
    });

    //value input checkbox term
    $('#customControlAutosizing').val('off');
    $(document).on('click', '#customControlAutosizing', function () {
        if ($(this).val() == 'on') {
            $(this).val('off');
        } else {
            $(this).val('on');
            $('.custom-control-label').css({
                'color': 'black',
            })
        }
    });

    //click forgot_pasword
    $(document).on('click', '.cc_login_regis_midle .cc_login_regis_forgot_pass forgot_password', openLoginRegister);
});


/**
 * register new account
 * @param obj
 */
function register(obj) {
    if (!obj.closest("form").valid()) return;
    let form = obj.closest("form");
    let url = obj.attr('url-register');
    let url_reload = obj.attr('url-reload');
    let data = form.serialize();
    if ($('#customControlAutosizing').val() == 'off') {
        $('.custom-control-label').css({
            'color': 'red',
        })
        return;
    } else {
        show_loading();
        $.ajax({
            url     : url,
            type    : "POST",
            data    : data,
            dataType: "json",
            success : function (data) {
                hide_loading();
                if (data.status) {
                    notify(data.msg, "alert-success", url_reload);
                } else {
                    notify(data.msg, "alert-danger");
                }
            },
        });
    }
}


/**
 * js for login
 * @param obj
 */
function login(obj) {
    if (!obj.closest("form").valid()) return;
    var url = obj.attr('url-login');
    var data = $('.cc_login_regis_form form.login').serialize();
    show_loading();
    $.ajax({
        url     : url,
        type    : "POST",
        data    : data,
        dataType: "json",
        success : function (data) {
            hide_loading();
            if (data['state']) {
                notify(data['msg'], "alert-success", data.redirect);
            } else {
                notify(data['msg'], "alert-danger");
            }
        },
    });
}

/**
 * send email to request reset pwd
 * @param e
 */
function send_request_forgot_password(obj) {
    var btnSendRequest = obj;
    if (!btnSendRequest.closest("form").valid()) return;
    var role = obj.attr('data-role');
    var url = obj.attr('data-url');
    var email = $('#input').val();
    var g_recaptcha_response = $('#g-recaptcha-response').val();
    if(g_recaptcha_response == ''){
        $('.capcha-warning').show();
        $('.rc-anchor-light.rc-anchor-normal').css({'border':'red 1px solid'});
        return;
    }
    if (!email) {
        notify($(".alert-no-email").text(), "alert-danger");
    }
    else {
        show_loading();
        $.ajax({
            url     : url,
            type    : "POST",
            data    : {
                'role'                : role,
                'email'               : email,
                'g-recaptcha-response': g_recaptcha_response
            },
            dataType: "json",
            success : function (data) {
                hide_loading();
                if (data.status == 0) {
                    notify(data.msg, "alert-danger");
                    return false;
                }
                else {
                    notify(data.msg, "alert-success", data.url);
                }

            },
            error   : function (data, status, jqXHR) {
                console.log("error");
                btnSendRequest.prop('disabled', false);
            }
        })
    }
}
