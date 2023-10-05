/**
 * Created by Thieu-LM on 3/4/2017.
 * List function for layout - load before layout
 */

function do_ajax_link(e, source_obj) {
    e.preventDefault();
    var obj;
    if (source_obj) {
        obj = source_obj;
    } else {
        obj = $(this);
    }
    var url = obj.attr("href");
    var data = obj.attr("data");
    var callback = obj.attr("callback");
    if (data) {
        data = JSON.parse(obj.attr("data"));
    }
    call_ajax_link(url, data, obj, callback);
}

function call_ajax_link(url, data, obj, callback) {
    show_loading();
    $.ajax({
        url     : url,
        type    : "POST",
        data    : data,
        dataType: "json",
        success : function (dataAll) {
            if (window[callback]) {
                console.log("Callback:" + callback);
                window[callback](obj, dataAll);
            } else {
                default_callback_ajax_link(obj, dataAll);
            }
        },
        error   : function (a, b, c) {
            notify(a);
        },
        complete: function (jqXHR, textStatus) {
            hide_loading();
        }
    });
}

function default_callback_ajax_link(obj, data) {
    if (data.status == 1) {
        if (data.html) {
            if ($("div.modal.e_modal_content").length > 0) {
                var $modal = $("div.modal.e_modal_content");
            } else {
                var $modal = $("<div class='modal fade e_modal_content'>");
            }
            $modal.html(data.html);
            $modal.modal({
                backdrop: 'static',
            });
        }
    } else {
        if (data.redirect) {
            window.location = data.redirect;
        }
        if (data.msg) {
            notify(data.msg, "alert-danger");
        }
    }
}

// call ajax when form submit
function do_ajax_submit(obj) {
    if (!obj || !obj.length) return;
    //Ajax form data
    obj.on("submit", function (e) {
        e.preventDefault();
        var form = $(this);
        if (!form.valid()) return;
        var data_url = form.attr("data-url");
        $.ajax({
            url        : data_url,
            data       : new FormData(this),
            type       : "POST",
            dataType   : "json",
            contentType: false,
            cache      : false,
            processData: false,
            beforeSend : function () {
                show_loading();
            },
            success    : function (data) {
                var modal = form.closest(".e_modal_content");
                var callback = modal.attr("callback");
                if (data.callback != undefined && window[data.callback]) {
                    callback = data.callback;
                }
                if (callback != undefined && window[callback]) {
                    console.log("Callback:" + callback);
                    window[callback](form, data);
                } else {
                    var url_redirect = "";
                    if (data.url_redirect != undefined) {
                        url_redirect = data.url_redirect;
                    }
                    if (data.status == 1) {
                        notify_ajax(data.msg, "success", url_redirect);
                    } else {
                        notify(data.msg, "warning");
                    }
                }
            },
            error      : function (err) {
                notify("Error: " + err, "warning");
                console.log(err);
            },
            complete   : function () {
                hide_loading();
                form.parents(".e_modal_content").modal("hide");
            }
        });

    });
}

/**
 * Notify by jGrowl
 * @param msg
 * @param group
 * @param urlRedirect
 */
function notify(msg, group = "alert-danger", urlRedirect = null) {
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

function notify_ajax(msg, group, urlRedirect) {
    notify(msg, group, urlRedirect)
}

function show_loading(container = '') {
    if (!container) {
        container = $(".content-layout");
    }
    // update size overlay
    $("#loading-overlay").height(container.height());
    $("#loading-overlay").width(container.width());
    // show loading
    $("#loading-overlay").show();
}

function hide_loading() {
    setTimeout(function () {
        $("#loading-overlay").hide();
    }, 200);
}

/**
 * initEvent for base: datepicker, select2, enscroll
 */
function initEvent() {
    // datetimepicker
    initDatepicker();
    // select2
    initSelect2();
    // enscroll
    initEnscroll();
    // ckeditor
    buildEditor();
}

/**
 * Init datepicker
 * @param obj
 * @param forBirth
 */
function initDatepicker(obj = null, forBirth = false) {
    if (!obj) obj = $("input.date-picker");
    let option = {
        dateFormat : "dd-mm-yy",
        changeMonth: true,
        changeYear : true,
    };
    if (forBirth) option.yearRange = "-70:+0";
    obj.removeClass('hasDatepicker').datepicker(option);
}

/**
 * Init select2
 * @param obj
 */
function initSelect2(obj = null) {
    if (!obj) obj = $("select.site-select2");
    obj.select2({
        tags: true
    });
}

/**
 * Init enscroll
 * @param obj
 */
function initEnscroll(obj = null) {
    if (!obj) obj = $(".site-enscroll");
    obj.enscroll({
        showOnHover   : false,
        easingDuration: 300,
    });
}

/**
 * Build ckeditor
 */
function buildEditor(obj = $(".site-editor")) {
    obj.each(function () {
        var name = $(this).attr("name");
        window[name] = CKEDITOR.replace($(this).get(0),
            {
                // Define the toolbar: http://docs.ckeditor.com/#!/guide/dev_toolbar
                // The full preset from CDN which we used as a base provides more features than we need.
                // Also by default it comes with a 3-line toolbar. Here we put all buttons in a single row.
                toolbar: [
                    {name: 'clipboard', items: ['Undo', 'Redo']},
                    {name: 'styles', items: ['Font', 'FontSize']},
                    {
                        name : 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting']
                    },
                    {name: 'colors', items: ['TextColor', 'BGColor']},
                    {name: 'align', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']},
                    {
                        name : 'paragraph',
                        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
                    },
                    {name: 'insert', items: ['Image', 'Table']},
                    {name: 'tools', items: ['Maximize']},
                ],
            });
    });
}

function defaultCallbackSubmit(form, data) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        if (data.msg != undefined) {
            notify(data.msg, "alert-success");
        }
        createAjaxTable($(".manage-table"));
    }
}

/**
 * init height screen
 */
function initHeight() {
    let wrapperContent = $(".wrapper-content-layout");
    let height = wrapperContent.height();
    wrapperContent.addClass("height-max");
    wrapperContent.css("min-height", height);
}

function formatMoney(n, c, d, t) {
    var c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}