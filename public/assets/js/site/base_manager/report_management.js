/**
 * Created by ACE on 02-08-2018.
 */

function defaultDataTable(data, obj) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        obj.find(".ajax-data-table").html(data.html);
        obj.closest(".manage-content").find(".count-record").text(data.count);
        if (data.msg != undefined) {
            notify(data.msg, "alert-success");
        }
        // initEventTable();
        resizeTableReport($(".e_manager_report"));
    }
}
// resize height table
function resizeTableReport(objTable) {
    let h = window.innerHeight - $(".main-header").outerHeight() - $(".manage-header").outerHeight() - $(".manage-filter").outerHeight() - $(".load-more").outerHeight() - 20;
    objTable.css("height", h);
}

function loadMoreContent() {
    //scroll DIV's Bottom
    let objTable = $('.e_manager_report');
    objTable.off("scroll");
    // enscroll for table
    initEnscrollTable();
    let loading = false;
    objTable.on('scroll', function () {
        if (Math.ceil($(this).scrollTop() + 3 / 2 * $(this).innerHeight()) >= $(this)[0].scrollHeight) {
            if (!loading) {
                loading = true;
                // set callback
                loadMore.data.callback = "appendDataTableReport";
                // change offset
                if (!loadMore.data.isMaxOffset) {
                    let manageTable = $(".manage-table");
                    let limit = loadMore.data.limit;
                    let offset = loadMore.data.offset;
                    offset += limit;
                    // set data condition
                    loadMore.data.offset = offset;
                    createAjaxTable(manageTable, false, true);
                }
            }
        }
    });
    $(".report_management").css("overflow-x", 'scroll');
}

// Callback for load more
function appendDataTableReport(data, obj) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        let ajaxContentTableObj = $($.parseHTML(data.html)).filter(".e_manager_report").find("tbody");
        if (ajaxContentTableObj.find(".e_row_report").length) {
            obj.find(".ajax-data-table .e_manager_report tbody.tbody").append(ajaxContentTableObj.html());
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

function initEnscrollTable() {
    let objScroll = $(".manage-table .e_manager_report");
    objScroll.enscroll("destroy");
    initEnscroll(objScroll);
}
