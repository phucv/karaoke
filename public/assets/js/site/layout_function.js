/**
 * Created by Thieu-LM on 3/4/2017.
 * List function for layout - load before layout
 */

// ThieuLM: custom search select2
function custom_select2(obj, attr_name) {
    obj.select2({
        matcher: function (term, text, option) {
            console.log(option);
            var attr_value = option.attr(attr_name);
            if (attr_value == undefined) {
                attr_value = "";
            }
            return text.toUpperCase().indexOf(term.toUpperCase()) >= 0
                || option.val().toUpperCase().indexOf(term.toUpperCase()) >= 0
                || attr_value.toUpperCase().indexOf(term.toUpperCase()) >= 0;
        }
    });
}

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

function get_notification_setting() {
    var url = $('.notify').attr("data-load-setting");
    if (!url)
        return;
    var setting = 0;
    $.ajax({
        url    : url,
        async  : false,
        success: function (data) {
            data = JSON.parse(data);
            if (data.type_get == "disable") {
                setting = 0;
            } else {
                setting = 1;
            }
            // console.log(setting);
        },
        error  : function () {
            console.log('error');
        }
    });

    return setting;
}

function get_total_notification() {
    var url = $('.notify').attr("data-total");
    if (!url)
        return;
    var total = 0;
    $.ajax({
        url    : url,
        async  : false,
        success: function (data) {
            data = JSON.parse(data);
            total = data;
        },
        error  : function () {
            console.log('error');
        }
    });

    return total;
}

// call ajax when form submit
function do_ajax_submit(obj) {
    if (!obj) return;
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

// show loading for modal
function show_loading_modal() {
    // update size overlay
    $("#loading-overlay-modal").height($("body").height());
    $("#loading-overlay-modal").width($("body").width());
    // show loading
    $("#loading-overlay-modal").show();
}

//hide loading for modal
function hide_loading_modal() {
    setTimeout(function () {
        $("#loading-overlay-modal").hide();
    }, 200);
}

/**
 * Update: ThieuLM-10052017
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
    // range slider
    buildRangeSlider();
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

function buildRangeSlider(obj = null) {
    if (!obj) obj = $(".range-slider");
    if (!obj.length) return false;
    $.each(obj, function () {
        var value = $(this).attr('data-value').split(';');
        var type = $(this).attr('data-type');
        var step = $(this).attr('data-step');
        $(this).ionRangeSlider({
            min     : value[0],
            max     : value[1],
            from    : value[0],
            to      : value[1],
            type    : type ? type : "double",
            step    : step ? step : 1,
            onFinish: function (data) {
                changeRangeSlider(data);
            }
        });
    });
}

/*
* destroy player but keep video element
 */
function destroyVideoPlayer(videoId = "") {
    if (videoId) {
        if(videojs.getPlayers()[videoId]) {
            delete videojs.getPlayers()[videoId];
        }
    }
}

/*
* pause all video in container
 */
function pauseVideoElement(container) {
    if (container) {
        container.find("video").each(function () {
            this.pause();
        })
    }
}

/*
* destroy player and remove video element
 */
function destroyVideoPlayerElement(videoId = "") {
    if (videoId) {
        if(videojs.getPlayers()[videoId]) {
            videojs.getPlayers()[videoId].dispose();
        }
    }
}

/**
 * get random string
 * @returns {string}
 */
function get_random_string(prefix = '', hasTime = false) {
    let text = "";
    const possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    for (let i = 0; i < 10; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return prefix + text + (hasTime ? new Date().getTime() : '');
}

/*
* init video player
*
* callback: loadedmetadata | ended | timeupdate | actionafter(action after init)
 */
function initVideoPlayer(idVideo, link, callback = {}, options = {}) {
    console.log('initVideoPlayer', {idVideo, link, callback, options});
    if (!link || !idVideo) {
        return null;
    }

    //destroy video player if it exist
    destroyVideoPlayer(idVideo);

    // check http for link
    if (link.indexOf("http://") == -1 && link.indexOf("https://") == -1) {
        link = "http://" + link
    }

    let player = videojs(idVideo, {
        playbackRates: [0.5, 1, 1.5, 2],
        ...options
    }, function onPlayerReady() {
        if (callback && callback.ended) {
            this.on('ended', callback.ended);
        }
        if (callback && callback.timeupdate) {
            this.on("timeupdate", callback.timeupdate);
        }
    });
    window.OwsCaptureEventInitVideoJs(player);
    let logoImg = $("img.js_img_logo");
    player.watermark({
        file     : logoImg.length ? logoImg.attr("src") : "",
        url      : logoImg.length ? logoImg.closest("a").attr("href") : "",
        clickable: true,
        className: 'vjs-watermark',
    });
    player.src({
        src: link,
    });
    if (typeof player.hlsQualitySelector == 'function') {
        player.hlsQualitySelector();
    }
    player.fluid(true);
    // player.play();
    if (callback && callback.loadedmetadata) {
        player.one('loadedmetadata', () => callback.loadedmetadata(player));
    }
    if (callback && callback.actionafter) {
        callback.actionafter(player);
    }
    return player;
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

/**
 * Linkify youtube URLs which are not already links.
 * https://stackoverflow.com/questions/5830387/how-do-i-find-all-youtube-video-ids-in-a-string-using-a-regex
 */
function getYoutubeIdByUrl(text) {
    var re = /https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/ig;
    return text.replace(re, '$1');
}

/**
 * Create alias for data
 * @param $title
 * @param $separator
 * @param $lowercase
 * @returns {*}
 */
function make_alias($title, $separator, $lowercase) {
    if ($separator === undefined) {
        $separator = "-";
    }
    if ($lowercase === undefined) {
        $lowercase = true;
    }
    $title = $.trim($title);
    $title = $title.replace(/\s+/gi, ' ');
    $title = $title.replace(/á|à|ạ|ả|ã|ă|ắ|ằ|ặ|ẳ|ẵ|â|ấ|ầ|ậ|ẩ|ẫ/g, "a");
    $title = $title.replace(/Á|À|Ạ|Ả|Ã|Â|Ấ|Ầ|Ậ|Ẩ|Ẫ|Ă|Ắ|Ằ|Ặ|Ẳ|Ẵ/g, "A");
    $title = $title.replace(/ó|ò|ọ|ỏ|õ|ô|ố|ồ|ộ|ổ|ỗ|ơ|ớ|ờ|ợ|ở|ỡ/g, "o");
    $title = $title.replace(/Ô|Ố|Ồ|Ộ|Ổ|Ỗ|Ó|Ò|Ọ|Ỏ|Õ|Ơ|Ớ|Ờ|Ợ|Ở|Ỡ/g, "O");
    $title = $title.replace(/é|è|ẹ|ẻ|ẽ|ê|ế|ề|ệ|ể|ễ/g, "e");
    $title = $title.replace(/Ê|Ế|Ề|Ệ|Ể|Ễ|É|È|Ẹ|Ẻ|Ẽ/g, "E");
    $title = $title.replace(/ú|ù|ụ|ủ|ũ|ư|ứ|ừ|ự|ử|ữ/g, "u");
    $title = $title.replace(/Ư|Ứ|Ừ|Ự|Ử|Ữ|Ú|Ù|Ụ|Ủ|Ũ/g, "U");
    $title = $title.replace(/í|ì|ị|ỉ|ĩ/g, "i");
    $title = $title.replace(/Í|Ì|Ị|Ỉ|Ĩ/g, "I");
    $title = $title.replace(/ý|ỳ|ỵ|ỷ|ỹ/g, "y");
    $title = $title.replace(/Ý|Ỳ|Ỵ|Ỷ|Ỹ/g, "Y");
    $title = $title.replace(/đ/g, "d");
    $title = $title.replace(/Đ/g, "D");

    $title = $title.replace(/\{|\}|\$|\||\\|`|!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, $separator);
    $title = $title.replace(/\-+/g, $separator);
    $title = $title.replace(/^\-+|\-+$/g, "");
    $title = $title.replace(/[^0-9A-Za-z\-]/g, "");

    if ($lowercase) {
        $title = $title.toLowerCase();
    }
    return $title;
}

/**
 * function call ajax
 */
function callAjax(url, data, obj, isLoading = true) {
    if (!url) return false;
    $.ajax({
        url       : url,
        type      : "POST",
        data      : data,
        dataType  : "json",
        beforeSend: function () {
            if (isLoading) {
                show_loading();
            }
        },
        success   : function (dataAll) {
            if (window[dataAll.callback]) {
                console.log("Callback: ", dataAll.callback);
                window[dataAll.callback](dataAll, obj);
            } else {
                console.log("Callback function not found:'", dataAll.callback, "'-->Call 'defaultDataTable' instead of");
                defaultCallbackAjax(dataAll, obj);
            }
        },
        error     : function (a, b, c) {
            console.error(a + b + c);
        },
        complete  : function (jqXHR, textStatus) {
            if (isLoading) {
                hide_loading();
            }
        }
    });
}

/**
 * Callback default when call ajax
 */
function defaultCallbackAjax(data, obj) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        // set html for view
        var ajaxElement = obj.find(".ajax-data-table");
        if (ajaxElement.length > 0) {
            ajaxElement.html(data.html);
        }
        // change count list
        var objCount = obj.closest(".wrapper-content-layout").find(".count-record");
        if (objCount.length > 0) {
            objCount.text(data.count);
        }
        if (data.msg != undefined) {
            notify(data.msg, "alert-success");
        }
    }
}

/**
 * init height screen
 */
function initHeight() {
    var height = $(".wrapper-content-layout").height();
    $(".wrapper-content-layout").addClass("height-max");
    $(".wrapper-content-layout").css("min-height", height);
}

/**
 * buld ckeditor
 * @param idElement
 * @param content
 * @param height
 */
function load_ckeditor(idElement, content, height = 200) {
    let urlUploadImage = $("#js_i_data").attr("data-url-upload-ckeditor");
    CKEDITOR.env.isCompatible = true;
    CKEDITOR.replace(idElement, {
        height                 : height,
        removeDialogTabs       : 'image:advanced;image:Link',
        filebrowserUploadUrl   : urlUploadImage,
        filebrowserUploadMethod: 'form',
    });
    CKEDITOR.instances[idElement].setData(content);
}

/**
 * Convert char japan with ruby
 * @param value
 * @returns {*}
 */
function convert_char_ruby(value) {
    let valConvert = value
        /* 半角または全角の縦棒以降の文字をベーステキスト、括弧内の文字をルビテキストとします。 */
        .replace(/[\|｜](.+?)《(.+?)》/g, '<ruby>$1<rt>$2</rt></ruby>')
        .replace(/[\|｜](.+?)（(.+?)）/g, '<ruby>$1<rt>$2</rt></ruby>')
        .replace(/[\|｜](.+?)\((.+?)\)/g, '<ruby>$1<rt>$2</rt></ruby>')
        /* 漢字の連続の後に括弧が存在した場合、一連の漢字をベーステキスト、括弧内の文字をルビテキストとします。 */
        .replace(/([一-龠]+)《(.+?)》/g, '<ruby>$1<rt>$2</rt></ruby>')
        /* ただし丸括弧内の文字はひらがなかカタカナのみを指定できます。 */
        .replace(/([一-龠]+)（([ぁ-んァ-ヶ]+?)）/g, '<ruby>$1<rt>$2</rt></ruby>')
        .replace(/([一-龠]+)\(([ぁ-んァ-ヶ]+?)\)/g, '<ruby>$1<rt>$2</rt></ruby>')
        /* 括弧を括弧のまま表示したい場合は、括弧の直前に縦棒を入力します。 */
        .replace(/[\|｜]《(.+?)》/g, '《$1》')
        .replace(/[\|｜]（(.+?)）/g, '（$1）')
        .replace(/[\|｜]\((.+?)\)/g, '($1)');
    return valConvert;
}