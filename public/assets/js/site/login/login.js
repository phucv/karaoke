$(document).ready(function () {
    //login by ajax
    $(document).on("submit", "form.e_ajax_submit", login_ajax);
    //send email to reset pwd
    $(document).on('click', '#request_forgot_password', send_request_forgot_password);
    //see detail pwd
    $(document).on('click mousedown', "img.peek-password", show_hide_pwd);
    //submit new password
    $(document).on('click', '#request_reset_password', request_reset_password);

});
/**
 * send email to request reset pwd
 * @param e
 */
function send_request_forgot_password(e) {
    e.preventDefault();
    var btnSendRequest=$(this);
    if (!btnSendRequest.closest("form").valid()) return;
    var role = $('#email').attr('data-role');
    var url = $('#email').attr('data-url');
    var email = $('#input').val();
    if (!email) {
        notify($(".alert-no-email").text(), "alert-danger");
    }
    else {
        show_loading();
        $.ajax({
            url     : url,
            type    : "POST",
            data    : {
                'role' : role,
                'email': email
            },
            dataType: "json",
            success: function (data) {
                hide_loading();
                if (data.status == 0) {
                    notify(data.msg, "alert-danger");
                    return false;
                }
                else {
                    notify(data.msg, "alert-success", data.url);
                }

            },
            error  : function (data, status, jqXHR) {
                console.log("error");
                btnSendRequest.prop('disabled', false);
            }
        })
    }
}

/**
 * to send ajax request update new password
 */
function request_reset_password(e) {
    e.preventDefault();
    var btnResetPassword = $(this);
    if (!btnResetPassword.closest("form").valid()) return;
    var url = $("#pass").attr("data-url");
    var password = $('#password').val();
    var new_pass = $("#pass .password").val();
    var new_pass_repeat = $("#re-pass .password").val();
    show_loading();
    $.post({
        url    : url,
        data   : {'password': password},
        success: function (data, status, jqXHR) {
            hide_loading();
            data = JSON.parse(data);
            btnResetPassword.prop('disabled', false);
            notify(data['msg'], 'alert-success');
            if (data['status']) {
                setTimeout(function () {
                    location.href = data['redirect'];
                }, 1000);
            }
        },
        error  : function (data, status, jqXHR) {
            $("#message").text("Đã có lỗi xảy ra");
            $("#message").show();
            btnResetPassword.prop('disabled', false);
        }
    })

}


/**
 * to show/hide password
 */

function show_hide_pwd(e) {
    var show_pwd_btn = $(this);
    var input_pwd = $(this).parent().find('input.password');
    var current_show_status = show_pwd_btn.attr('data-show');
    if (current_show_status == 1) {
        show_pwd_btn.attr('data-show', 0);
        input_pwd.attr('type', 'password');
    } else {
        show_pwd_btn.attr('data-show', 1);
        input_pwd.attr('type', 'text');
    }
}

/**
 * check login user
 * @param e
 */
function login_ajax(e) {
    e.preventDefault();
    var username = $('.username').val();
    var password = $('.password').val();
    if ($('.remember_me').is(":checked")) {
        var remember = 1;
    } else {
        var remember = 0;
    }
    var data = {
        username: username,
        password: password,
        remember: remember
    };
    var btn = $('form.e_ajax_submit').find('.login-btn');
    btn.attr("disabled", true);
    var url = $(this).attr('data-ajax--url');
    show_loading();
    $.ajax({
        url     : url,
        type    : "POST",
        data    : data,
        dataType: "json",
        success : function (data) {
            hide_loading();
            if (!data['state']) {
                notify(data['msg'], "alert-danger");
                setTimeout(function () {
                    btn.removeAttr("disabled");
                }, 3000);
            } else {
                notify(data['msg'], "alert-success");
                setTimeout(function () {
                    window.location = data['redirect'];
                }, 1000);
            }
        },
        error   : function (a, b, c) {
            console.log('get_line has error', a, b, c);
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

/**
 * Notify by jGrowl
 * @param msg
 * @param group
 * @param urlRedirect
 */
function notify(msg, group = 'alert-danger', urlRedirect = null) {
    // check type alert
    if (group.indexOf("alert-") == -1) {
        group = "alert-" + group;
    }
    // set icon by type alert
    var iconDefault = "warning";
    if (group.indexOf("success") != -1) {
        iconDefault = "check_circle";
    } else if (group.indexOf("error") != -1) {
        iconDefault = "error";
    }
    $.jGrowl("<i class='material-icons'>" + iconDefault + "</i><span>" + msg + "</span>", {
        group        : group,
        position     : 'top-right',
        sticky       : false,
        closeTemplate: '<i class="material-icons">close</i>',
        animateOpen  : {
            width : 'show',
            height: 'show'
        },
        life         : 2000,
        close        : function (e, m, o) {
            if (urlRedirect) {
                window.location = urlRedirect;
            }
        }
    });
}

function show_loading() {
    $("#loadingDiv").removeClass('hidden');
}

function hide_loading() {
    setTimeout(function () {
        $("#loadingDiv").addClass('hidden');
    }, 200);
}