/**
 * Created by Thieu-LM on 25/03/2017.
 * library filter
 */
var Filter = {
    data            : {
        filter      : {},
        search      : {},
        order_by    : {},
        tableElement: ".manage-table"
    },
    setData         : function (type, field, key, value, action) {
        var filterData = this.data;
        if (type && field) {
            filterData[type][field] = filterData[type][field] || {};
            if (typeof key == "string") {
                // remove special character
                key = key.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
            }
            filterData[type][field][key] = value;
            this.data = filterData;
            this.listenChangeData(action);
        }
    },
    getData         : function () {
        return this.data;
    },
    removeData      : function (type, field, key, action) {
        var filterData = this.data;
        if (filterData[type][field] != undefined && filterData[type][field][key] != undefined) {
            delete filterData[type][field][key];
        }
        this.listenChangeData(action);
    },
    resetData       : function (action) {
        this.data = {
            filter      : {},
            search      : {},
            order_by    : {},
            tableElement: ".manage-table"
        };
        this.listenChangeData(action);
    },
    listenChangeData: function (action) {
        // process when data change
        console.log("filterData changed");
        syncViewAdvanceFilter();
        var filterData = this.data;
        var tableElement = filterData.tableElement;
        if (action == 1) {
            if (typeof createAjaxTable != 'undefined') {
                createAjaxTable($(tableElement));
            }
        }
    }
};
$(document).ready(function (e) {

    // bind event for advance filter
    bindEventAdvanceFilter();

    // Click filter custom
    var htmlFilter = {};
    $(".btn-filter.btn-filter-custom, .row-btn-close, .row-btn-save").on("click", function () {
        var obj = $(this);
        obj.closest(".manage-filter").find(".btn-filter-custom").toggleClass("show");
        var manageFilter = obj.closest(".manage-filter").find(".row-custom");
        manageFilter.toggleClass("show");
        if (manageFilter.hasClass("show")) {
            $(".filter-custom-row").each(function () {
                $(this).removeClass("hidden");
            });
            $(".filter-select, .filter-input, .filter-range").each(function () {
                $(this).attr("action-type", 0);
            });
        } else {
            if (obj.attr("submit-filter") == 1) {
                // ajax
                if (typeof createAjaxTable != 'undefined') {
                    createAjaxTable($(Filter.data.tableElement));
                }
            } else if (obj.attr("reset-filter") == 1 && obj.hasClass("active")) {
                // reset input
                manageFilter.find("input").each(function (e) {
                    $(this).val("");
                });
                // reset option select2
                $(".filter-select .e-filter-add-value").each(function () {
                    changeOptionSelect2($(this), [], false);
                });
                // reset range slider
                manageFilter.find(".filter-range .range-slider").each(function () {
                    var value = $(this).attr('data-value').split(';');
                    var slider = $(this).data("ionRangeSlider");
                    slider.update({
                        from: value[0],
                        to: value[1]
                    });
                });
                //reset data
                Filter.resetData(1);
            }
            checkAllFilterRowEmpty($(".filter-custom-row"));
            // btn
            $(".filter-custom-row.popup-row-btn").addClass("hidden");
            // select
            $(".filter-quick-add-input").each(function () {
                $(this).addClass("hidden");
            });
        }
    });

    // click search data
    $(".btn-search").on("click", function (e) {
        searchData($(this));
    });
    // enter search
    $(".search-part input[type=text]").on("keyup", function (e) {
        if ($(this).val() != "") {
            $(this).siblings(".btn-close-search").removeClass("hidden");
        } else {
            $(this).siblings(".btn-close-search").addClass("hidden");
        }
        if (e.which == 13 || e.keyCode == 13) {
            searchData($(this));
        }
    });
    // remove text search
    $(".search-part .btn-close-search").on("click", function (e) {
        var obj = $(this);
        obj.addClass("hidden");
        var objInput = obj.siblings("input[type=text]");
        objInput.val("");
        if (obj.attr("ajax") == 1) { // ajax get table
            obj.attr("ajax", 0);
            Filter.setData("search", objInput.attr("name"), 0, "", 1);
        }
    });


    // click filter public/private
    initViewStatusFilter();
    initViewStatusCustomizeFilter();
    $(".filter-status .btn-status").on("click", function () {
        var filterValue = $(this).attr("status-value");
        var filterKey = $(this).attr("status-key");
        if (!$(this).hasClass("active")) {
            // add value
            $(this).addClass("active");
            Filter.setData("filter", "public", filterKey, filterValue, 1)
        } else {
            // remove value
            $(this).removeClass("active");
            Filter.removeData("filter", "public", filterKey, 1)
        }
        initViewStatusFilter();
    });
    $(".filter-status-customize .btn-status").on("click", function () {
        let filterValue = $(this).attr("status-value");
        let filterKey = $(this).attr("status-key");
        let key = $(this).closest('.filter-status-customize').data('field');
        if (!$(this).hasClass("active")) {
            // add value
            $(this).addClass("active");
            Filter.setData("filter", key, filterKey, filterValue, 1)
        } else {
            // remove value
            $(this).removeClass("active");
            Filter.removeData("filter", key, filterKey, 1)
        }
        initViewStatusCustomizeFilter();
    });
    // click order
    $(".filter-sort select").on("change", function () {
        var orderKey = $(this).val();
        var orderType = $(this).children(":selected").attr("sort-type");
        var valueOrder = {};
        valueOrder[orderKey] = orderType;
        Filter.setData("order_by", "quick_sort", 0, valueOrder, 1)
    });
});
/**
 * Event bind for advance filter
 */
function bindEventAdvanceFilter() {
    // check data filter select default
    checkDataFilterDefault();
    // hidden input when click outside
    eventClickOutside($(".filter-quick-add-input"));
    // click filter select quick add
    $(".filter-quick-add-btn, .data-filter-default").on("click", function (e) {
        var filterSelect = $(this).closest(".filter-custom-row");
        // hide default
        filterSelect.find(".data-filter-default").addClass("hidden");
        // hide btn plus
        filterSelect.find(".filter-quick-add-btn").addClass("hidden");
        // show input
        var filterQuickAddInput = filterSelect.find(".filter-quick-add-input");
        filterQuickAddInput.removeClass("hidden");
        bindSelect2Filter(filterQuickAddInput, true);
    });

    // change data filter select -> Xu ly loi select2: Uncaught TypeError: Cannot read property 'current' of null
    $(".e-filter-add-value").on("select2:select", function (e) {
        var obj = $(this);
        // add data filter
        addDataFilterCustom(obj);
    });
    // bind event change data input
    $(".e-filter-range").on("change", function () {
        var obj = $(this).closest(".data-filter-custom");
        // show/hide icon remove
        var btnRemove = $(this).siblings(".remove-date");
        if ($(this).val() != "") {
            btnRemove.removeClass("hidden");
        } else {
            btnRemove.addClass("hidden");
        }
        // set min/max for datepicker
        if ($(this).hasClass("date-picker")) {
            // set min/max date
            var selectedDate = $(this).val();
            var objRangeFrom = obj.find("input[name=range-from]");
            var objRangeTo = obj.find("input[name=range-to]");
            var nameInput = $(this).attr("name");
            if (nameInput == "range-from") {
                objRangeTo.datepicker("option", "minDate", selectedDate);
            } else if (nameInput == "range-to") {
                objRangeFrom.datepicker("option", "maxDate", selectedDate);
            }
        }
        // get data filter input
        var type = obj.attr("filter-type");
        var field = obj.attr("filter-field");
        var action = obj.closest(".filter-custom-row").attr("action-type");
        var valueFrom = obj.find("input[name=range-from]").val();
        var valueTo = obj.find("input[name=range-to]").val();
        var value = {
            from: valueFrom,
            to  : valueTo
        };
        // change data filter input
        Filter.setData(type, field, 0, value, action);
    });
    // remove data date
    $(".remove-date").on("click", function (e) {
        $(this).closest(".data-input").find("input").val("");
        // remove data filter
        var obj = $(this).closest(".data-filter-custom");
        var type = obj.attr("filter-type");
        var field = obj.attr("filter-field");
        var action = obj.closest(".filter-custom-row").attr("action-type");
        var objRangeFrom = obj.find("input[name=range-from]");
        var objRangeTo = obj.find("input[name=range-to]");
        var valueFrom = '';
        var valueTo = '';

        if (!objRangeFrom.val() && !objRangeTo.val()) {
            Filter.removeData(type, field, 0, action);
        } else {
            if (!objRangeFrom.val() && objRangeTo.val()) {
                valueTo = objRangeTo.val();
            } else if (objRangeFrom.val() && !objRangeTo.val()) {
                valueFrom = objRangeFrom.val();
            }
            var value = {
                from: valueFrom,
                to  : valueTo
            };
            // change data filter input
            Filter.setData(type, field, 0, value, action);
        }
        $(this).addClass("hidden");
    });
}

/**
 * PROCESS FOR SEARCH
 * @param obj
 */
function searchData(obj) {
    var objInput = obj.closest(".search-part").find("input");
    var objRemoveTextSearch = objInput.siblings(".btn-close-search");
    objRemoveTextSearch.attr("ajax", 1);
    var nameSearch = objInput.attr("name");
    var valueSearch = objInput.val();
    Filter.setData("search", nameSearch, 0, valueSearch, 1);
}

/**
 * Sync view status
 */
function initViewStatusFilter() {
    var filterData = Filter.getData().filter;
    if (filterData.public == undefined || $.isEmptyObject(filterData.public)) {
        // init filter public/private
        $(".filter-status .btn-status").each(function () {
            $(this).addClass("active");
            var filterValue = $(this).attr("status-value");
            var filterKey = $(this).attr("status-key");
            // add value
            Filter.setData("filter", "public", filterKey, filterValue, 0);
        });
    }
}

/**
 * Sync view status customize
 */
function initViewStatusCustomizeFilter() {
    let filterData = Filter.getData().filter;
    $(".filter-status-customize").each(function () {
        let key = $(this).data('field');
        if (filterData[key] == undefined || $.isEmptyObject(filterData[key])) {
            // init filter public/private
            $(this).find(".btn-status").each(function () {
                $(this).addClass("active");
                let filterValue = $(this).attr("status-value");
                let filterKey = $(this).attr("status-key");
                // add value
                Filter.setData("filter", key, filterKey, filterValue, 0);
            });
        }
    });

    // filter-status-customize
}

/**
 * Hover icon remove filter data
 */
function hoverValueFilterCustom() {
    $(".value-filter-custom").hover(function () {
        $(this).find(".btn-remove-value-filter-custom").removeClass("hidden");
    }, function () {
        $(this).find(".btn-remove-value-filter-custom").addClass("hidden");
    });
}

/**
 * check data filter-select NULL -> default
 */
function checkDataFilterDefault() {
    $(".filter-select").each(function (e) {
        var obj = $(this);
        if (obj.find(".value-filter-custom").length == 0) {
            obj.find(".data-filter-default").removeClass("hidden");
        } else {
            obj.find(".data-filter-default").addClass("hidden");
        }
    });
}

/**
 * check row filter empty -> hidden
 */
function checkAllFilterRowEmpty(listRowObj) {
    listRowObj.each(function () {
        var filterRow = $(this);
        if (filterRow.hasClass("select-quick")) {
            $(this).attr("action-type", 1);
        } else {
            if (checkFilterRowEmpty(filterRow)) { // empty
                filterRow.addClass("hidden");
                filterRow.attr("action-type", 0);
            } else { // not empty
                filterRow.removeClass("hidden");
                filterRow.attr("action-type", 1);
            }
        }
    });
}
function checkFilterRowEmpty(rowObj) {
    if (rowObj.hasClass("filter-select")) {
        if (rowObj.find(".value-filter-custom").length != 0) {
            return false;
        }
    } else if (rowObj.hasClass("filter-input")) {
        var inputEmpty = true;
        rowObj.find(".data-filter-custom input").each(function () {
            if ($(this).val() != "") inputEmpty = false;
        });
        return inputEmpty;
    } else if (rowObj.hasClass("filter-range")) {
        var slider = rowObj.find(".range-slider");
        if (slider.val() != slider.attr("data-value")) return false;
    }
    return true;
}

/**
 * hidden input when click outside
 * http://stackoverflow.com/questions/1403615/use-jquery-to-hide-a-div-when-the-user-clicks-outside-of-it
 */
function eventClickOutside(obj) {
    $(document).mousedown(function (e) {
        if (!obj.is(e.target) // if the target of the click isn't the container...
            && obj.has(e.target).length === 0  // ... nor a descendant of the container
        ) {
            $.each(obj, function () {
                let input = $(this);
                // hidden input
                input.addClass("hidden");
                // show button plus
                if (!input.closest(".filter-custom-row").hasClass("not_multiple")
                    || (input.closest(".filter-custom-row").hasClass("not_multiple") && !input.closest(".filter-custom-row").find(".value-filter-custom").length)) {
                    input.closest(".filter-custom-row").find(".filter-quick-add-btn").removeClass("hidden");
                }
                if (input.closest(".filter-custom-row").hasClass("not_multiple")
                    && input.closest(".filter-custom-row").find(".data-filter-append .value-filter-custom").length) {
                    input.closest(".filter-custom-row").find(".data-filter-append .value-filter-custom").removeClass("hidden");
                }
            });
            checkDataFilterDefault();
        }
    });
}

/**
 * ADD DATA FILTER
 */
function addDataFilterCustom(obj) {
    var filterType = obj.attr("filter-type");
    var filterField = obj.attr("filter-field");
    var optionValue = obj.val();
    var optionText = obj.children(":selected").text();
    // Reset data Filter if not multiple
    if (obj.closest(".not_multiple").length) {
        delete Filter.data[filterType][filterField];
        changeOptionSelect2(obj);
    }
    // Disable value chosen
    changeOptionSelect2(obj, optionValue);
    // add data to filter
    var dataTemplate = $("#data-filter-template").html();
    if (dataTemplate != undefined && optionValue != "") {
        let filterSelect = obj.closest(".filter-select");
        obj.val("").trigger("change");
        let actionSubmit = filterSelect.attr("action-type");
        // change dataFilter
        Filter.setData(filterType, filterField, optionText, optionValue, actionSubmit);
    }
}

/**
 * REMOVE DATA FILTER
 */
function removeValueFilterCustom() {
    $(".btn-remove-value-filter-custom").on("click", function (e) {
        // get data for remove
        var obj = $(this);
        var filterKey = obj.attr("filter-key");
        var filterType = obj.attr("filter-type");
        var filterField = obj.attr("filter-field");
        var optionValue = [];
        var objSelect2 = null;
        // remove all value
        $(".value-filter-custom").each(function (e) {
            var filterSelectObj = $(this).closest(".filter-select");
            var btnRemoveValueFilterCustomObj = $(this).find(".btn-remove-value-filter-custom");
            if (
                filterKey == btnRemoveValueFilterCustomObj.attr("filter-key") &&
                filterType == btnRemoveValueFilterCustomObj.attr("filter-type") &&
                filterField == btnRemoveValueFilterCustomObj.attr("filter-field")
            ) {
                // add data to optionValue
                var optionValueTmp = $(this).find(".filter-value-text").text();
                optionValue.push(optionValueTmp);
                objSelect2 = obj.closest(".filter-select").find(".e-filter-add-value");
                // remove element
                $(this).remove();
                // change dataFilter
                let actionSubmit = filterSelectObj.attr("action-type");
                Filter.removeData(filterType, filterField, filterKey, actionSubmit);
            }
        });
        // Enable option select2 by value
        if (optionValue.length > 0 && objSelect2) {
            changeOptionSelect2(objSelect2, optionValue, false);
        }
    });
}

/**
 * Bind select 2
 * @param objParent object parent of select
 * @param open
 */
function bindSelect2Filter(objParent, open) {
    try {
        let url = objParent.find("select.select2").attr("url-ajax");
        // let url = "https://api.github.com/search/repositories";
        if (url) {
            objParent.find("select.select2").select2({
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    method : 'post',
                    data: function (params) {
                        let q =  params.term; // search term
                        let filter = {
                            q: params.term,
                            limit: params.limit,
                            offset: (parseInt(params.page) - 1) * params.limit,
                            search: {'search_all' : {q}},
                        };
                        if ($(this).data("search")) {
                            filter['filter'] = $(this).data("search");
                        }
                        return filter;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        params.limit = data.limit || 30;
                        return {
                            results: data.record_list_data,
                            pagination: {
                                more: (params.page * params.limit) < data.count_record_list_data
                            }
                        };
                    },
                    cache: true
                },
                // placeholder: 'Search for a repository',
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                // tags: true,
                dropdownParent: objParent,
                open: true
            });
        } else {
            objParent.find("select.select2").select2({tags: true, dropdownParent: objParent, open: true});
        }
        if (open) {
            objParent.find("select.select2").select2('open');
        }
    } catch (err) {
        console.log(err);
    }
}

function formatRepo (repo) {
    if (repo.loading) {
        return repo.text;
    }
    return repo.name;
}

function formatRepoSelection (repo) {
    if (repo.full_name) {
        $(repo.element).text(repo.full_name);
    }
    return repo.full_name || repo.text;
}

/**
 * Enable/Disable option select2 by value
 */
function changeOptionSelect2(objSelect2, optionValue = [], disable = true, reset = false) {
    if (typeof optionValue == "string") {
        optionValue = [optionValue];
    }
    objSelect2.find("option").each(function () {
        var valueOption = $(this).val();
        if ($.inArray(valueOption, optionValue) != -1) {
            if (disable) {
                $(this).attr("disabled", "disabled");
            } else {
                $(this).removeAttr("disabled");
            }
        }
        // reset option
        if (optionValue.length == 0) {
            if ($(this).attr("disabled") != undefined) $(this).removeAttr("disabled");
        }
    });
    // bind select2
    var filterQuickAddInput = objSelect2.closest(".filter-quick-add-input");
    bindSelect2Filter(filterQuickAddInput, false);
}

/**
 * Sync dataFilter with view
 */
function syncViewAdvanceFilter() {
    var dataFilter = Filter.getData().filter;
    var dataTemplate = $("#data-filter-template").html();
    var manageFilter = $(".manage-filter");
    var hasFilter = false;
    manageFilter.find(".data-filter-custom").each(function () {
        var filterCustom = $(this);
        // sync filter select
        var filterField = filterCustom.attr("filter-field");
        var filterCustomHtml = "";
        if (typeof dataFilter[filterField] == "object") {
            $.each(dataFilter[filterField], function (key, value) {
                if (typeof value == "string") {
                    filterCustomHtml += getDataFilterTemplate(dataTemplate, "filter", filterField, key, value);
                } else if (typeof value == "object" && value) {
                    if ((value.from != undefined && value.from != "") || (value.to != undefined && value.to)) {
                        hasFilter = true;
                    }
                }
            })
        }
        filterCustom.find(".data-filter-append").html(filterCustomHtml);
        if (filterCustomHtml != "") {
            hasFilter = true;
        }
        // click remove data filter
        removeValueFilterCustom();
        // check data filter select default
        checkDataFilterDefault();
    });
    var customRow = manageFilter.find(".filter-custom-row.not_multiple");
    if (hasFilter) { // has filter
        manageFilter.find(".btn-filter-custom").addClass("active");
        manageFilter.find(".row-btn-close").addClass("active");

        if (customRow.length) {
            customRow.find(".filter-quick-add").find(".filter-quick-add-input").addClass("hidden");
            customRow.find(".filter-quick-add").find(".filter-quick-add-btn").addClass("hidden");
        }
    } else {
        manageFilter.find(".btn-filter-custom").removeClass("active");
        manageFilter.find(".row-btn-close").removeClass("active");

        if (customRow.length) {
            customRow.find(".filter-quick-add").find(".filter-quick-add-btn").removeClass("hidden");
        }
    }
    if (customRow.length) {
        // click change data filter
        changeValueFilterCustom();
    }
}

/**
 * REPLACE DATA TEMPLATE
 */
function getDataFilterTemplate(dataTemplate, type, field, key, value) {
    var tmp = dataTemplate;
    tmp = tmp.replace(/FILTER_VALUE/g, value);
    tmp = tmp.replace(/FILTER_KEY_DATA/g, key);
    tmp = tmp.replace(/FILTER_TYPE_DATA/g, type);
    tmp = tmp.replace(/FILTER_FIELD_DATA/g, field);
    return tmp;
}

function changeRangeSlider(data) {
    var obj = data.input.closest(".data-filter-custom");
    // get data filter input
    var type = obj.attr("filter-type");
    var field = obj.attr("filter-field");
    var action = obj.closest(".filter-custom-row").attr("action-type");
    var value = data.input.val();
    if (data.input.attr("data-value") == data.input.val()) {
        Filter.removeData(type, field, 0, action);
    } else {
        Filter.setData(type, field, 0, value, action);
    }
}

function changeValueFilterCustom() {
    // change data not multiple
    $(".not_multiple .filter-value-text").on("click", function (e) {
        let obj = $(this);
        obj.closest(".value-filter-custom").addClass("hidden");
        let customRow = obj.closest(".filter-custom-row");
        customRow.find(".filter-quick-add-btn").click();
    });
    
}
