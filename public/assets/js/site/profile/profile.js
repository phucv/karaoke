$(document).ready(function () {
    $(".date-picker").datepicker("option", "maxDate", new Date()).datepicker("option", "yearRange", "-70:+0");
    var activeForm = 1;
    var validateResult = {
        "firstname": false,
        "lastname": false,
        "username": false,
        "email": false,
        "role": false,
        "birthday": false,
        "phone": false,
        "about_me": false,
        "old_pass": false,
        "new_pass": false,
        "new_pass_repeat": false
    };

    // declare all validating input
    var $oldPass = $("#old-pass");
    var $newPass = $("#new-pass");
    var $newPassRepeat = $("#new-pass-repeat");
    var displayName = $("#display-name");

    $(".right-input").on("input", function () {
        // validateElement($(this));
    });

    function validateElement($element) {
        var type = $element.attr("name");
        switch (type) {
            case "username":
                validateResult.username = checkRequire($element, 0);
                break;
            case "old-pass": {
                if (checkRequire($element, 0)) {
                    validateResult.old_pass = checkPassLength($element);
                } else validateResult.old_pass = false;

                if ($newPass.val().length >= 6)
                    validateResult.new_pass = checkDifferentOldPass($element, $newPass);

                break;
            }
            case "new-pass": {
                if (checkRequire($element, 0)) {
                    if (checkPassLength($element)) {
                        validateResult.new_pass = checkDifferentOldPass($oldPass, $element);
                    } else validateResult.new_pass = false;
                } else validateResult.new_pass = false;

                if ($newPassRepeat.val().length >= 6)
                    validateResult.new_pass_repeat = checkEqualRetypeNewPass($element, $newPassRepeat);

                break;
            }
            case "new-pass-repeat": {
                if (checkRequire($element, 1)) {
                    if (checkPassLength($element)) {
                        if (checkEqualRetypeNewPass($newPass, $element)) {
                            validateResult.new_pass_repeat = true;
                            break;
                        }
                    }
                }
                validateResult.new_pass_repeat = false;
                break;
            }
            default: {
                // this element don't have any rule to validate, so this validateResult is true
                for (var validateElement in validateResult) {
                    if (validateResult.hasOwnProperty(validateElement)) {
                        if (validateElement == type) {
                            validateResult[validateElement] = true;
                        }
                    }
                }
            }
        }
    }

    function isInputEqualOrigin($origin, $new) {
        // console.log($origin.val() + " " + $new.val() + ($origin.val() == $new.val()) );
        return $origin.val() == $new.val();
    }

    // mode = 0: normal required check
    // mode != 0: required check for password repeat
    function checkRequire($validateElement, mode) {
        if ($validateElement.prop('required')) {
            var $errorMessageWrapper = $validateElement.siblings(".error-message-wrapper");
            if ($validateElement.val().length == 0) {
                $validateElement.parent().children(".right-input").addClass("has-error");
                if (mode == 0)
                    $errorMessageWrapper.children("p").text("Bạn cần điền " + $validateElement.attr('placeholder'));
                else $errorMessageWrapper.children("p").text("Bạn phải nhập cùng mật khẩu hai lần để xác nhận mật khẩu.");
                $errorMessageWrapper.show();
                return false;
            } else {
                $validateElement.parent().children(".right-input").removeClass("has-error");
                $errorMessageWrapper.hide();
                return true;
            }
        }
    }

    function checkPassLength($validateElement) {
        var $errorMessageWrapper = $validateElement.siblings(".error-message-wrapper");
        if ($validateElement.val().length < 6) {
            $validateElement.parent().children(".right-input").addClass("has-error");
            $errorMessageWrapper.children("p").text("Mật khẩu tối thiểu 6 ký tự.");
            $errorMessageWrapper.show();
            return false;
        } else if ($validateElement.val().length > 30) {
            $validateElement.parent().children(".right-input").addClass("has-error");
            $errorMessageWrapper.children("p").text("Mật khẩu khẩu không dài hơn 30 ký tự.");
            $errorMessageWrapper.show();
            return false;
        } else {
            $validateElement.parent().children(".right-input").removeClass("has-error");
            $errorMessageWrapper.hide();
            return true;
        }
    }

    function checkEqualRetypeNewPass($newPass, $newPassAgain) {
        var $errorMessageWrapperNewPassAgain = $newPassAgain.siblings(".error-message-wrapper");
        if ($newPass.val() != $newPassAgain.val()) {
            $errorMessageWrapperNewPassAgain.parent().children(".right-input").addClass("has-error");
            $errorMessageWrapperNewPassAgain.children("p").text("Nhập lại mật khẩu mới không khớp.");
            $errorMessageWrapperNewPassAgain.show();
            return false;
        } else {
            $errorMessageWrapperNewPassAgain.parent().children(".right-input").removeClass("has-error");
            $errorMessageWrapperNewPassAgain.hide();
            return true;
        }
    }

    function checkDifferentOldPass($oldPass, $newPass) {
        var $errorMessageWrapperNewPass = $newPass.siblings(".error-message-wrapper");
        if ($oldPass.val() == $newPass.val()) {
            $errorMessageWrapperNewPass.parent().children(".right-input").addClass("has-error");
            $errorMessageWrapperNewPass.children("p").text("Mật khẩu này phải khác mật khẩu cũ.");
            $errorMessageWrapperNewPass.show();
        } else {
            $errorMessageWrapperNewPass.parent().children(".right-input").removeClass("has-error");
            $errorMessageWrapperNewPass.hide();
            return true;
        }
    }

    function updateOriginalInputAfterSuccessSave($oldInput, $newInput) {
        $oldInput.val($newInput.val());
    }

    // JS for tab
    $(".form-switch").on("click", function () {
        $(this).addClass("active");
        $(".form-switch").not($(this)).removeClass("active");
        var formID = $(this).data("form");
        if (formID == activeForm) return;
        var form = "#form" + formID.toString();
        $(form).show();
        $(form).addClass("active");

        let formHide = "#form" + activeForm.toString();
        $(formHide).hide();
        $(formHide).removeClass("active");

        activeForm = formID;
        updateWrapperLayoutHeight();
    });
    // Ajax data
    $(".save-button").on("click", function (e) {
        if (!$(this).closest("form").valid()) return;
        show_loading();
        // lay value cua form
        var formID = $(this).data("form");
        var form = "form" + formID.toString();
        var $myForm = $("#" + form);
        $myForm.ajaxSubmit({
            dataType: "json",
            success: function (data) {
                // hideLoadingAnimation();
                if (formID == 1) {
                    if (data.state == 1) {
                        modalHeader.children("h2").text(displayName.val());
                        notify(data.msg, "alert-success");
                    } else if (data.state == 2) {
                        notify(data.msg, "alert-danger");
                    } else {
                        notify(data.msg, "alert-danger");
                    }
                } else {
                    if (data.state == 0) {
                        msg = data.msg;
                        notify(msg, "alert-danger");
                    } else {
                        msg = data.msg;
                        $("#old-pass").val("");
                        $("#new-pass").val("");
                        $("#new-pass-repeat").val("");
                        notify(msg, "alert-success");
                    }
                }
            },
            error: function (data) {
                // hideLoadingAnimation();
                console.log("error");
                if (data) {
                    msg = data.msg;
                } else {
                    msg = "Đã có lỗi xảy ra, xin vui lòng thử lại.";
                }
                notify(msg, "alert-danger");
            },
            complete: function () {
                hide_loading();
            }
        });

        return false;
    });

    // Update profile
    var profileArea = $(".profile-area");
    var profileUpdateBtn = $(".profile-update-btn");

    profileArea.hover(function () {
        profileUpdateBtn.addClass("show");
    });

    profileArea.mouseout(function (event) {
        e = event.toElement || event.relatedTarget;
        if (e.parentNode == this || e == this) {
            return;
        }
        profileUpdateBtn.removeClass("show");

    });

    var myFile = $("#my-file");
    profileUpdateBtn.click(function () {
        // $("#myModal").show();
        myFile.click();
    });

    myFile.on("click", function () {
        $(this).val("");
    });

    var filePreview = $(".file-preview");
    var modalContent = $(".modal-content");
    var modalHeader = $(".modal-header");
    var myModal = $("#myModal");

    myFile.change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(this.files[0]);
            var ext = this.files[0].name.split('.').pop();
            var file_size = this.files[0].size / 1024;
            var arrayExtensions = ["jpg", "jpeg", "png", "gif", "GIF", "JPG", "PNG", "JPEG"];
            if (arrayExtensions.lastIndexOf(ext) == -1) {
                notify("Loại file không phù hợp, chấp nhận các loại file jpg, png, gif.", "alert-danger");
            } else if (file_size > 8192) {
                notify("Dung lượng file quá lớn, dung lượng tối đa là 8MB", "alert-danger");
            } else {
                reader.onloadend = function () {
                    var i = new Image();
                    i.onload = function () {
                        filePreview.css("background-image", "url(" + reader.result + ")");
                        filePreview.css("background-size", "cover");
                        filePreview.css("background-repeat", "no-repeat");
                        myModal.show();
                    };
                    i.src = reader.result;
                }
            }
        }
    });

    myModal.on("click", ".close, .cancel", function () {
        myModal.hide();
    });

    $(document).on("click", function (event) {
        if (event.target.className == "modal") {
            myModal.hide();
        }
    });

    $(".update-avatar").on("submit", function (e) {
        show_loading();
        d = new Date();

        e.preventDefault();
        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                if (data.state == 1) {
                    notify(data.msg, "alert-success");
                    $(".profile-img").attr("src", data.avatar + "?" + d.getTime());
                    $(".has-sub.info .avatar > img").attr("src", data.avatar + "?" + d.getTime());
                    $(".information-menu.has-sub .avatar > img").attr("src", data.avatar + "?" + d.getTime());
                } else {
                    var msg;
                    if (data.error) {
                        msg = data.error.error;
                    } else msg = data.msg;
                    notify(msg, "alert-danger");
                    console.log(data.error);
                }
            },
            error: function () {
                notify("Đã có lỗi xảy ra", "alert-danger");
            },
            complete: function () {
                hide_loading();
                myModal.hide();
            }
        });
        return false;
    });

    $(document).on('change', '.select_info', show_info); // show span name of department or position
    // $(document).on('click', '.row_info_select .name_info', edit_info); //edit department or position
    $(document).on('click', '.add_more', function (e) {
        add_more_info($(this));
    }); // add more row department and position
    $(document).on('click', '.icon_remove', remove_row_info); //remove row position and department
    $(document).on('change', '#email', check_empty);
    $(document).on('change', '#username', check_empty);

    //validate company
    $('#form4').validate({
        onkeyup       : function (e) {
            $(e).valid();
        },
        errorPlacement: function ($error, $element) {
            if ($element.parent().hasClass('r-info-custom')) {
                $error.addClass('error-right');
                $error.appendTo($element.closest(".block-row-info"));
            } else {
                $error.appendTo($element.closest(".row-info"));
            }
        }
    });
});

function show_info(e) {
    var obj = $(this);
    var position = obj.find(":selected").text();
    var value = obj.val();
    if (value) {
        var position_span = obj.closest(".row_info_select").find(".name_info");
        // change text + data-id and show span
        position_span.text(position);
        position_span.attr("data-id", value);
        position_span.removeClass("hidden");
        // hidden select
        obj.addClass("hidden");
    }
}

function edit_info(e) {
    var obj = $(this);
    var select = obj.closest(".row_info_select").find("select");
    // show select
    var value = obj.attr("data-id");
    select.val(value);
    select.removeClass("hidden");
    // hidden span
    obj.addClass("hidden");
}

function add_more_info(obj = null) {
    if (!obj) obj = $(this);
    var status = true;
    obj.closest(".right-info").find(".po_de_group").each(function () {
        if (($(this).find(".position_row .name_info").attr("data-id") == "")
            && ($(this).find(".position_row .name_info").attr("data-id") == "")) {
            status = false;
        }
    });
    if (status) {
        var template = $(".template-de-po .po_de_group").clone();
        template.removeClass("hidden");
        template.find("select").val("");
        obj.before(template);
    }
}

function remove_row_info(e) {
    var obj = $(this);
    var row_parent = obj.closest(".po_de_group");
    row_parent.remove();
    if ($(".row .right-info .po_de_group").length == 0) {
        add_more_info($(".row .right-info .add_more"));
    }
}

/**
 * check value of object require
 * @param e
 * @param o
 */
function check_empty(e, o) {
    var obj = o ? o : $(this);
    if (obj.val() == '') {
        obj.closest('.row').find('.require').css('color', 'red');
    }
    else {
        obj.closest('.row').find('.require').css('color', '');
    }
}