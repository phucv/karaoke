/**
 * Created by miunh on 2/9/2017.
 */
$(document).ready(function () {
    $(document).on("click", ".e-safe-delete", safe_delete);
    $(document).on("click", ".e-confirm-delete-modal .close", close_confirm_delete_modal);
    $(document).on("click", ".e-confirm-delete-modal .cancel-deleted", close_confirm_delete_modal);
    function safe_delete(e) {
        e && e.preventDefault();
        var modal = $('.e-confirm-delete-modal');
        modal.find('.delete-student').attr('url', $(this).data('url'));
        modal.fadeIn();
    }

    function close_confirm_delete_modal(e) {
        $(this).closest('.e-confirm-delete-modal').fadeOut();
    }

    var $manageWrapper = $(".manage-wrapper");
    var $result = $(".result");
    // round down table area to integer
    roundDownResultWidth();

    // add data-full-text to question (for cut string)
    updateQuestionFullTextData();

    // init custom scrollbar
    // $(".table-body").mCustomScrollbar({
    //     theme            : "dark",
    //     axis             : "yx",
    //     scrollbarPosition: "outside",
    //     callbacks        : {
    //         whileScrolling: function () {
    //             $(".table-header").css("left", this.mcs.left);
    //         },
    //         onInit        : function () {
    //             syncTableWidth();
    //         }
    //     }
    // });

    // $(".checkboxes-wrapper").mCustomScrollbar({
    //     theme            : "dark",
    //     scrollbarPosition: "outside"
    // })

    // cut string handle, click detail button will pop up modal
    var $modal = $("#myModal");
    $(document).on("click", ".myBtn", function () {
        var id = $(this).attr('data-value');
        var questionContent = $(this).parent().data("full-text");
        // console.log(questionContent);
        $(".modal-text").text(questionContent);
        $modal.css("display", "block");
        $modal.attr('data-value', id);
    });
    $(document).on("click", ".close", function () {
        $modal.css("display", "none");
    });
    $(document).mouseup(function (e) {
        var container = $(".modal-content > p");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $modal.css("display", "none");
        }
    });

    function updateQuestionFullTextData() {
        $(".question-content").each(function () {
            $(this).data("full-text", $(this).text());
            cutStringHandler($(this));
        });
    }

    setTableBody();
    // $(".table-body").scroll(function () {
    //     $(".table-header").css("left", -1 * this.scrollLeft);
    // });

    // update button on typing on search box
    $(".search-ques").keyup(function () {
        // console.log($(this).val());
        //src1 is link img when active
        var src1 = $('.search-ques-btn').attr('url');
        //src2 is link img when not active
        var src2 = $('.search-ques-btn').attr('data-url');
        if ($(this).val() != "") {
            $(".search-ques-btn").css("background-color", "#4990e2");
            $(".search-ques-btn img").attr("src", src1);
        } else {
            $(".search-ques-btn").css("background-color", "white");
            $(".search-ques-btn img").attr("src", src2);
        }
    });

    // checkbox question handler
    var choosenQuestions = [];
    var $addExamBtn = $(".add-exam");
    var $addExamText = $(".add-exam-text");
    var $checkboxColBtn = $(".checkbox-col-btn");
    $(".checkbox-col-btn").change(function () {
        updateChoosenQuestionsArray($(this));
        updateCreateExamButton();
    });

    function updateChoosenQuestionsArray(checkedQuestion) {
        var thisIdCol = parseInt(checkedQuestion.attr("id").substring(7));
        if (checkedQuestion.is(":checked")) {
            // check for duplicate
            for (var i = choosenQuestions.length; i--;) {
                if (choosenQuestions[i].id == thisIdCol) {
                    return;
                }
            }
            var tmpCheckedQuestion = {
                id      : thisIdCol,
                selector: checkedQuestion
            }
            choosenQuestions.push(tmpCheckedQuestion);
            // console.log(choosenQuestions);
        } else {
            for (var i = choosenQuestions.length; i--;) {
                if (choosenQuestions[i].id == thisIdCol) {
                    choosenQuestions.splice(i, 1);
                }
            }
            // console.log(choosenQuestions);
        }

        if (choosenQuestions.length == $checkboxColBtn.length) {
            $(".checkbox-head-btn").prop("checked", true);
        } else {
            $(".checkbox-head-btn").prop("checked", false);
        }
    }

    // click div also check checkbox
    $(".checkbox-col").click(function () {
        $(this).children(".checkbox-col-btn").click();
    });
    $(".checkbox-col-btn").click(function (e) {
        e.stopPropagation();
    });
    $(".checkbox-col-label").click(function (e) {
        e.stopPropagation();
    });

    // handle click checkbox-head-btn (select all)
    $(".checkbox-head-btn").change(function () {
        if (this.checked) {
            checkAllQuestionCheckbox();
            updateCreateExamButton();
        } else {
            uncheckAllQuestionCheckbox();
            updateCreateExamButton();
        }
    });

    // click div also check checkbox-head-btn (select all)
    $(".checkbox-head").click(function () {
        $(this).children(".checkbox-head-btn").click();
    });
    $(".checkbox-head-btn").click(function (e) {
        e.stopPropagation();
    });
    $(".checkbox-head-label").click(function (e) {
        e.stopPropagation();
    });

    // unselect all handler button
    $(document).on("click", ".unselect-all", function () {
        choosenQuestions.length = 0;
        uncheckAllQuestionCheckbox();
        updateCreateExamButton();
    });

    // click search button
    // $(".search-ques-btn").click(function () {
    //     findQuestionOnQuestionContent();
    // });

    $(".search-ques").keyup(function (e) {
        if (e.keyCode == 13) {
            findQuestionOnQuestionContent();
        }
    });

    function findQuestionOnQuestionContent() {
        // var searchValue = $(".search-ques").val();
        // var table = $("#bodytable");
        // $.ajax({
        //     url     : "searchQuestion.php",
        //     type    : "post",
        //     data    : {searchValue: searchValue},
        //     dataType: "text",
        //     success : function (response) {
        //         $(table).children("tbody").html(response);
        //         updateQuestionFullTextData();
        //     },
        //     error   : function () {
        //         console.log("error on fetching question");
        //     }
        // });
    }

    // click filter button open filter fields popup
    $(".filter-btn").click(function () {
        revertCheckboxesFilterStatus();
        togglePopup();
    });

    $(".popup-filter-field .cancel-btn").click(function () {
        revertCheckboxesFilterStatus();
        togglePopup();
    });

    function checkAllQuestionCheckbox() {
        $checkboxColBtn.each(function () {
            $(this).prop("checked", true);
            updateChoosenQuestionsArray($(this));
        });
    }

    function uncheckAllQuestionCheckbox() {
        $checkboxColBtn.each(function () {
            $(this).prop("checked", false);
            updateChoosenQuestionsArray($(this));
        });
    }

    function revertCheckboxesFilterStatus() {
        var allCheckbox = [$categoryCheckbox, $levelCheckbox, $requiredprogramCheckbox];
        for (var i = 0; i <= 2; i++) {
            allCheckbox[i].each(function () {
                var thisIdFilter = parseInt($(this).attr("id").substring(10));
                var isChoosenBefore = false;
                for (var i = choosenFields.length; i--;) {
                    if (choosenFields[i].id == thisIdFilter) {
                        isChoosenBefore = true;
                        return;
                    }
                }
                if (isChoosenBefore) {
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            });
        }
    }

    // apply filter handle
    var choosenFields = [];
    var $categoryCheckbox = $(".category-checkbox");
    var $levelCheckbox = $(".level-checkbox");
    var $requiredprogramCheckbox = $(".required-program-checkbox");
    $(".apply-btn").click(function () {
        var allCheckbox = [$categoryCheckbox, $levelCheckbox, $requiredprogramCheckbox];
        for (var i = 0; i <= 2; i++) {
            allCheckbox[i].each(function () {
                var thisIdFilter = parseInt($(this).attr("id").substring(10));
                if (this.checked) {
                    // check for duplicate
                    for (var i = choosenFields.length; i--;) {
                        if (choosenFields[i].id == thisIdFilter) {
                            return;
                        }
                    }

                    var checkedField = {
                        id             : thisIdFilter,
                        // label: $(this).parent().text(),
                        label          : $("label[for='id-filter-" + thisIdFilter + "']").text(),
                        backgroundColor: $(this).data("background")
                    }

                    choosenFields.push(checkedField);
                } else {
                    for (var i = choosenFields.length; i--;) {
                        if (choosenFields[i].id == thisIdFilter) {
                            choosenFields.splice(i, 1);
                        }
                    }
                }
            });
        }
        // console.log(choosenFields);

        // add tags from choosenFields
        updateTagsHtml();
        togglePopup();
    });

    $(document).on("click", ".tag .tag-text, .tag .close", function () {
        var tagDataID = $(this).parent().data("id");
        $(".popup-filter-field input[data-id='" + tagDataID + "']").prop("checked", false);
        for (var i = choosenFields.length; i--;) {
            if (choosenFields[i].id == tagDataID) {
                choosenFields.splice(i, 1);
            }
        }
        updateTagsHtml();
    });

    $(document).on("click", ".tag-wrapper .tag-text", function () {
        togglePopup();
    });

    $(".side-menu").on("updateWidth", function () {
        roundDownResultWidth();
    });

    $(document).on("click", "#menu", function () {
        roundDownResultWidth();
    });

    // $(window).resize(function () {
    //     updateCutStringQuestionContent();
    //     roundDownResultWidth();
    //     setTableBody();
    //     syncTableWidth();
    //     updateFilterTagsDisplay();
    //     updateTagsHtml();
    // });

    function togglePopup() {
        if ($("#myPopup").hasClass("show")) {
            $("#myPopup").removeClass("show");
            $(".manage-wrapper .result").removeClass("hide");
        } else {
            $("#myPopup").addClass("show");
            $(".manage-wrapper .result").addClass("hide");
        }
    }

    function openPopup() {
        $("#myPopup").addClass("show");
        $(".manage-wrapper .result").addClass("hide");
    }

    function closePopup() {
        $("#myPopup").removeClass("show");
        $(".manage-wrapper .result").removeClass("hide");
    }

    function updateFilterTagsDisplay() {

    }

    function cutStringHandler($string) {
        var screenType = getScreenType();
        var maxLength = 160;
        switch (screenType) {
            case "laptop": {
                maxLength = 72;
                break;
            }
            case "tablet": {
                maxLength = 70;
                break;
            }
            case "mobileL":
            case "mobileM":
            case "mobileS": {
                maxLength = 45;
                break;
            }
        }
        cutString($string, maxLength);
    }

    function cutString($string, maxLength) {
        var updatedText = $string.data("full-text");
        // console.log(updatedText);
        if (updatedText.length <= maxLength) {
            return;
        } else {
            updatedText = updatedText.substr(0, maxLength);
            updatedText += "... &nbsp<button class='myBtn'>Chi tiết</button>";
            $string.html(updatedText);
        }
    }

    function updateCutStringQuestionContent() {
        // console.log($(".question-content"));
        $(".question-content").each(function () {
            cutStringHandler($(this));
        });
    }

    function getScreenType() {
        var windowWidth = $(window).width();
        if (windowWidth >= 1440) {
            return "laptopL";
        } else if (windowWidth >= 1024) {
            return "laptop";
        } else if (windowWidth >= 768) {
            return "tablet";
        } else if (windowWidth >= 425) {
            return "mobileL";
        } else if (windowWidth >= 375) {
            return "mobileM";
        } else return "mobileS";
    }

    // var seeMoreTagsItems = "";
    function updateTagsHtml() {
        var updatedTagsHtml = "";
        var tagsWidth = 40; // init value is tag wrapper button
        var tagsTextCount = 0;
        var tagsText = "";
        var filterTagsDivWidth = $(".filter-tags").width();
        var overflowCount = 0;
        var overflowTagsId = "";
        var seeMoreTags = "";

        for (var i = 0; i < choosenFields.length; i++) {
            var thisTagTextLength = choosenFields[i].label.trim().length + 1; // dont know why must +1
            tagsText += choosenFields[i].label.trim();
            ;
            tagsTextCount += choosenFields[i].label.trim().length + 1; // same above
            tagsWidth += thisTagTextLength * 12.6;
            if (tagsWidth > filterTagsDivWidth) {
                overflowCount++;
                overflowTagsId += choosenFields[i].id + " ";
                // seeMoreTagsItems += "\
                // <div class='tag' data-id='"+choosenFields[i].id+"'>\
                // 	<span class='tag-text' style='background-color: "+choosenFields[i].backgroundColor+"'>"+choosenFields[i].label+"</span>\
                // 	<span class='close' style='background-color: "+choosenFields[i].backgroundColor+"'>&times;</span>\
                // </div>";
            } else {
                updatedTagsHtml += "<div class='tag' data-id='" + choosenFields[i].id + "'>\
				<span class='tag-text' style='background-color: " + choosenFields[i].backgroundColor + "'>" + choosenFields[i].label + "</span>\
				<span class='close' style='background-color: " + choosenFields[i].backgroundColor + "'>&times;</span>\
				</div>";
            }
        }
        // console.log(filterTagsDivWidth);
        // console.log(tagsTextCount);
        // console.log(tagsWidth);

        if (overflowCount > 0) {
            seeMoreTags += "<div class='tag-wrapper' data-id='" + overflowTagsId + "' title='" + 'Còn ' + overflowCount + ' mục nữa' + "'>\
			<span class='tag-text' style='background-color: #ccc'>" + overflowCount + "+</span>\
			</div>";

            updatedTagsHtml += seeMoreTags;
        }

        $(".filter-tags").html(updatedTagsHtml);
    }

    function updateCreateExamButton() {
        if (choosenQuestions.length > 0) {
            $addExamBtn.addClass("create-able");
            var updateText = "&nbsp Từ <b>" + choosenQuestions.length + "</b> câu hỏi đã chọn hoặc <div class='unselect-all'><span class='close'>&times;</span><span class='unselect-text'> Bỏ chọn</span></div>";
            $addExamText.html(updateText);
        } else {
            $addExamBtn.removeClass("create-able");
            $addExamText.html("&nbsp Chọn câu hỏi để bắt đầu tạo đề thi");
        }
    }

    function roundDownResultWidth() {
        var roundedDownResultWidth = Math.floor($manageWrapper.width());
        // console.log(roundedDownResultWidth);
        // $result.width(roundedDownResultWidth);
    }

    function setTableBody() {
        $(".table-body").height($(".inner-container").height() - $(".table-header").height());
        var windowHeight = $(window).height();
        var max_height = windowHeight - 300;
        $(".table-body").css("max-height", max_height);

    }

    function syncTableWidth() {
        var updateWidth = $("#mCSB_1").width() - 1;
        $("#mCSB_1_container").width(updateWidth);
        // console.log("mCSB_1_container: "+$("#mCSB_1_container").width());
    }
});