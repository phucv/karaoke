$(document).ready(function (e) {
    // Get value for limit / offset
    var limitDefault = $(".manage-filter").attr("limit-df");
    limitDefault = parseInt(limitDefault);
    var offsetDefault = $(".manage-filter").attr("offset-df");
    offsetDefault = parseInt(offsetDefault);
    // Define object loadMore -> need change to class
    window.loadMore = {
        data     : {
            limit      : limitDefault,
            offset     : offsetDefault,
            isMaxOffset: false,
            callback   : false
        },
        resetData: function () {
            this.data = {
                limit      : limitDefault,
                offset     : offsetDefault,
                isMaxOffset: false,
                callback   : false
            };
        }
    };
    // ajax-data-table when reload
    createAjaxTable($(".manage-table"));

});

/**
 * Init event hover, click, ... for table manage
 */
function initEventTable() {
    // hover content-row
    $(".content-row").hover(function () {
        $(this).find(".content-col.col-select").removeClass("hidden");
        $(this).find(".content-col.col-delete").removeClass("hidden");
        $(this).find(".content-col .btn-edit").removeClass("hidden");
    }, function () {
        var colSelect = $(this).find(".content-col.col-select");
        if (!colSelect.find("input[type=checkbox]").is(":checked")) {
            colSelect.addClass("hidden");
        }
        $(this).find(".content-col.col-delete").addClass("hidden");
        $(this).find(".content-col .btn-edit").addClass("hidden");
    });
    // select all
    headerSelect($(".header-select"));
    // remove all selected
    $(".btn-unselect").on("click", function () {
        var manageTable = $(this).closest(".manage-filter").siblings(".manage-table");
        unselectAll(manageTable);
    });
    // paging
    $("#paging li:not(.active):not(.disabled) a").on("click", function () {
        var limit = loadMore.data.limit;
        var page = $(this).attr("data-page");
        ajaxTablePaging(limit, page);
    });
    $("#paging .pagesize select").on("change", function () {
        var limit = $(this).val();
        var page = 1;
        ajaxTablePaging(limit, page);
    });
}

/**
 * Select checkbox row
 * @param obj
 */
function headerSelect(obj) {
    obj.on("click", function (e) {
        var count = 0;
        var dataSelected = {};
        if ($(this).hasClass("header-select-all")) {
            // select all
            // tinh so luong row
            $(".header-select:not(.header-select-all)").each(function (index, value) {
                var objCheckbox = $(this).find("input[type=checkbox]");
                objCheckbox.prop('checked', true);
                count++;
                dataSelected[index] = objCheckbox.val();
                // select row
                selectRow($(this), count, dataSelected);
            });
        } else {
            // select one
            // tinh so luong row
            $(".header-select:not(.header-select-all) input[type=checkbox]:checked").each(function (index, value) {
                var objCheckbox = $(this);
                count++;
                dataSelected[index] = objCheckbox.val();
            });
            selectRow($(this), count, dataSelected);
        }
    });
}

/**
 * Event when click select row
 * @param rowObj object row selected
 * @param count count row selected
 * @param dataSelected object data row for delete_many
 */
function selectRow(rowObj, count = 0, dataSelected = {}) {
    if (!rowObj) rowObj = $(this);
    // active row
    activeRow(rowObj);
    // count row selected
    countActiveRow(rowObj, count, dataSelected);
}

/**
 * unselect All rows
 * @param table
 */
function unselectAll(table) {
    table.find(".header-select").each(function () {
        $(this).find("input[type=checkbox]").prop('checked', false);
        $(this).addClass("hidden");
        activeRow($(this));
    });
    hideInfoSelected();
}

// bg color when select row table
function activeRow(rowClass) {
    if ($(rowClass).find("input[type=checkbox]").is(":checked")) {
        $(rowClass).removeClass("hidden");
        $(rowClass).closest(".content-row").addClass("active");
    } else {
        $(rowClass).closest(".content-row").removeClass("active");
    }
}

/**
 * count row active table
 */
function countActiveRow(obj, count = 0, dataSelected = {}) {
    var objFilter = obj.closest(".manage-table").siblings(".manage-filter");
    if (count) {
        var countRecord = objFilter.find(".count-rows .count-record").text();
        var countDisplay = count + "/" + countRecord;
        objFilter.find(".show-created .count-select").text(countDisplay);
        showInfoSelected(objFilter, count, dataSelected);
    } else {
        hideInfoSelected(objFilter);
    }
}

/**
 * Show info selected row
 */
function showInfoSelected(objFilter, count = 0, dataSelected = {}) {
    if (!objFilter) objFilter = $(".manage-filter");
    objFilter.find(".show-selected").removeClass("hidden");
    objFilter.find(".count-rows").addClass("hidden");
    objFilter.find(".show-created").removeClass("hidden");
    objFilter.find(".btn-delete-many").attr("data", JSON.stringify(dataSelected));
}

/**
 * Hide info selected row
 */
function hideInfoSelected(objFilter) {
    if (!objFilter) objFilter = $(".manage-filter");
    objFilter.find(".show-selected").addClass("hidden");
    objFilter.find(".count-rows").removeClass("hidden");
    objFilter.find(".show-created").addClass("hidden");
    objFilter.find(".btn-delete-many").attr("data", "");
}

/**
 * Ajax get data table
 * @param obj
 * @param isLoading
 * @param isLoadMore
 * @returns {boolean}
 */
function createAjaxTable(obj, isLoading, isLoadMore) {
    // un select all row
    if (typeof unselectAll != 'undefined' && !isLoadMore) {
        unselectAll($(".manage-table"));
    }
    // get param ajax
    var url = obj.attr("data-url");
    if (!url) {
        return false;
    }
    isLoading = (isLoading == undefined) ? true : isLoading;
    var data = {};
    if (Filter.data != undefined) {
        data = $.extend(data, Filter.data);
    }
    if (isLoadMore) {
        $.extend(data, loadMore.data);
    } else {
        loadMore.resetData();
    }
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
                defaultDataTable(dataAll, obj);
            }
            if (!obj.attr("data-paging")) {
                loadMoreContent(obj);
            }
        },
        error     : function (a, b, c) {
            // alert(a + b + c);
            console.log(a, b, c);
            notify('Đã có lỗi xảy ra. Vui lòng tải lại trang', 'alert-danger');
        },
        complete  : function (jqXHR, textStatus) {
            if (isLoading) {
                hide_loading();
            }
        }
    });
}

// Callback default for createAjaxTable
function defaultDataTable(data, obj) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        obj.find(".ajax-data-table").html(data.html);
        obj.closest(".manage-content").find(".count-record").text(data.count);
        if (data.msg != undefined) {
            notify(data.msg, "alert-success");
        }
        initEventTable();
        resizeTable($(".content-table"));
    }
}

// resize height table
function resizeTable(objTable) {
    var h = $("body").height() - $(".main-header").outerHeight() - $(".manage-header").outerHeight() - $(".manage-filter").outerHeight() - $(".load-more").outerHeight() - 20;
    if (objTable.closest(".manage-table").attr("data-paging")) {
        h -= 32;
    }
    objTable.css("height", h);
}

/**
 * http://stackoverflow.com/questions/21219283/jquery-lazy-load-content-in-div
 */
function loadMoreContent(obj) {
    // scroll DIV's Bottom
    var objTable = obj ? obj.find('.content-table') : $('.content-table');
    objTable.off("scroll");
    // enscroll for table
    initEnscrollTable();
    let tableHeight = objTable.outerHeight();
    let contentRowHeight = objTable.find('.content-row').outerHeight();
    let currentRecord = loadMore.data.limit + loadMore.data.offset;
    var objFilter = objTable.closest(".manage-table").siblings(".manage-filter");
    var countRecord = objFilter.find(".count-rows .count-record").text();
    console.log(countRecord, loadMore.data.limit, loadMore.data.offset);
    if (currentRecord * contentRowHeight < tableHeight && currentRecord < countRecord && contentRowHeight) {
        console.log(countRecord, currentRecord, currentRecord * contentRowHeight, tableHeight);
        let emptyHeight = tableHeight - currentRecord * contentRowHeight;
        let numberRecordFill = Math.ceil(emptyHeight / contentRowHeight);
        // set callback
        loadMore.data.callback = "appendDataTable";
        // change offset
        if (!loadMore.data.isMaxOffset) {
            let manageTable = obj ? obj : $(".manage-table");
            var limit = numberRecordFill;
            var offset = loadMore.data.offset;
            offset += limit;
            // set data condition
            loadMore.data.offset = offset;
            loadMore.data.limit = limit;
            createAjaxTable(manageTable, false, true);
        }
    } else {
        var loading = false;
        objTable.on('scroll', function () {
            if (Math.ceil($(this).scrollTop() + 3 / 2 * $(this).innerHeight()) >= $(this)[0].scrollHeight) {
                if (!loading) {
                    loading = true;
                    // set callback
                    loadMore.data.callback = "appendDataTable";
                    // change offset
                    if (!loadMore.data.isMaxOffset) {
                        let manageTable = obj ? obj : $(".manage-table");
                        var limit = loadMore.data.limit;
                        var offset = loadMore.data.offset;
                        offset += limit;
                        // set data condition
                        loadMore.data.offset = offset;
                        createAjaxTable(manageTable, false, true);
                    }
                }
            }
        });
    }
}

// Callback for load more
function appendDataTable(data, obj) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        var ajaxContentTableObj = $($.parseHTML(data.html)).filter(".content-table");
        if (ajaxContentTableObj.find(".content-row").length) {
            obj.find(".ajax-data-table .content-table").append(ajaxContentTableObj.html());
        } else {
            // set isMaxOffset
            loadMore.data.isMaxOffset = true;
        }
        var viewMore = obj.find(".ajax-data-table").find(".load-more .view-more");
        viewMore.addClass("hidden", 300);
        if (data.msg != undefined) {
            notify(data.msg, "alert-success");
        }
        initEventTable();
    }
}

// Callback for load more
function appendDataTableReport(data, obj) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        let ajaxContentTableObj = $($.parseHTML(data.html)).filter(".manager-report").find("tbody");
        if (ajaxContentTableObj.find(".e_row").length) {
            obj.find(".ajax-data-table .manager-report tbody.tbody").append(ajaxContentTableObj.html());
        } else {
            // set isMaxOffset
            loadMore.data.isMaxOffset = true;
        }
        let viewMore = obj.find(".ajax-data-table").find(".load-more .view-more");
        viewMore.addClass("hidden", 300);
        if (data.msg != undefined) {
            notify(data.msg, "alert-success");
        }
    }
}

//Callback when change status
function callbackChangeStatus(obj, data) {
    if (data.status == 1) {
        obj.closest(".col-status").find(".row-status").each(function () {
            $(this).removeClass("active");
        });
        obj.addClass("active");
        var classData = obj.attr("class-data");
        var aliasIcon = obj.find("i.material-icons").html();
        var iconObj = obj.closest(".col-status").find(".status-icon i.material-icons");
        iconObj.attr("class", "material-icons " + classData);
        iconObj.html(aliasIcon);
        if (data.msg) {
            notify(data.msg, "alert-success");
        }
    } else {
        if (data.msg) {
            notify(data.msg, "alert-error");
        }
        if (data.redirect) {
            window.location = data.redirect;
        }
    }
}

/**
 * init enscroll for table-content
 */
function initEnscrollTable() {
    var objScroll = $(".manage-table .content-table");
    objScroll.enscroll("destroy");
    initEnscroll(objScroll);
}

// overwrite - update height wrapper-content-layout
function updateWrapperLayoutHeight() {
    $(".wrapper-content-layout").css("height", $(window).height() - $(".main-header").height() - 12);
}

function ajaxTablePaging(limit, page) {
    // set data condition
    loadMore.data.offset = parseInt(limit) * (parseInt(page) - 1);
    loadMore.data.limit = limit;
    var manageTable = $(".manage-table");
    createAjaxTable(manageTable, true, true);
}
