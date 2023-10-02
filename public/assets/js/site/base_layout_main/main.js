$(document).ready(function () {
    //run when loading page ready
    setWidthSlideCourse();
    checkPercentRatingCourse();

    //mouseenter, mouseleave, mouseover
    $(document).on('mouseenter mouseleave', '.js_navbar_cat', hoverCategories);
    $(document).on('mouseenter mouseleave', '.js_navbar_cat_dropdown li', hoverListItemCategories);
    $(document).on('mouseenter mouseleave', '.js_comp_icon_noty', hoverNotifyIcon);
    $(document).on('mouseenter', '.js_comp_item', showSlideCourse);
    $(document).on('mouseenter', '.js_menu_cat_item', showSubmenu_cat);

    let flagSlideCourse = 1,
        flagSubmenu_categories = 1;
    $(document).on('mouseover', 'body', function () {
        if (flagSlideCourse && $('.js_comp_item_hover.clone').length) {
            $('.js_comp_item_hover.clone').remove();
        }

        if (flagSubmenu_categories) {
            $('.js_menu_cat_sub').removeClass('active');
        }
    });

    $(document).on('mouseenter mouseleave', '.js_comp_item_layout, .js_comp_item_hover.clone', function (e) {
        if (e.type == 'mouseenter') {
            flagSlideCourse = 0;
        } else {
            flagSlideCourse = 1;
        }
    });

    $(document).on('mouseenter mouseleave', '.js_menu_cat_item, .js_menu_cat_sub', function (e) {
        if (e.type == 'mouseenter') {
            flagSubmenu_categories = 0;
        } else {
            flagSubmenu_categories = 1;
        }
    });

    //click
    $(document).on('click', '.js_comp_item_arrow', clickArrowSlideCourse);
    $(document).on('click', '.js_comp_btn_login_register', openLoginRegister);
    $(document).on('click', '.js_login_regis_close', closeLoginRegister);
    $(document).on('click', '.js_navbar_mobi_search', showNavbarSearchMobi);
    $(document).on('click', '.js_navbar_mobi_humbuger', showNavbarMenuMobi);
    $(document).on('click', '.js_navbar_mobile_overlay', hideNavbarMenuMobi);
    $(document).on('click', '.js_navbar_open_categories', showMavbarCategoriesMobi);
    $(document).on('click', '.js_navbar_mobile_hide_categories', hideMavbarCategoriesMobi);
    $(document).on('click', '.js_navbar_acount_options', showMavbarCategoriesSubMobi);
    $(document).on('click', '.js_navbar_mobile_hide_categories_sub', hideMavbarCategoriesSubMobi);
    // $(document).on('click', '.js_comp_item_add_cart_btn', clickAddToCartSlideBtn);
    $(document).on('click', '.js_comp_item_wishlist', clickAddToWishlistSlideBtn);
    $(document).on('click', '.js_added_course_btn_close_icon', clickCloseAddToCartSlideBtn);
    $(document).on('click', '.js_curriculum_expand', showAllCurriculum);
    $(document).on('click', '.js_curriculum_child .card', checkExpandOrCollapseCurriculum);
    $(document).on('click', '.js_intro_course_details_coupon', showCouponCourseDetails);
    $(document).on('click', '.js_tabs_layout .list-group-item, .js_tabs_layout_mobi .card-header', clickTabsCourse);

    $.each($('.js_tabs_layout_mobi'), function () {
        if ($(this).find('.card').length == 1) {
            $(this).css({
                'border-bottom': '1px solid rgba(0, 0, 0, 0.125)'
            })
        }
    });

    //foreach obj

    $.each($('.cc_tabs_layout_mobi .collapse, .cc_curriculum_child .collapse, .cc_payment_done_accordion_layout .collapse'), function () {
        if (!$(this).hasClass('show')) {
            $(this).parent().find('.card-header').addClass('collapsed');
        }
    });

    // check_first_login();

    // change language in home
    $('select.js_select_language').on('change', function (e) {
        window.location = $(this).val();
    })

    var url_show_notification = $("body").attr("url-popup-notification");
    var url_maintain = $("body").attr('data-url-maintain');
    if (url_show_notification) {
        call_ajax_link(url_show_notification, '', $('body'), '');
    } else if (url_maintain) {
        let current_time = new Date().getTime();
        let notify_time = parseInt($("body").attr('data-time-maintain')) * 1000;
        setTimeout(() => {
            call_ajax_link(url_maintain, '', $('body'), '')
        }, notify_time - current_time);
    }
});

function check_first_login() {
    var first_login = $("body").attr("data-login");
    var open = $("body").attr("data-change-info");
    var url = $("body").attr("url-change-info");
    if ((first_login == "0") && (open == 0)) {
        call_ajax_link(url, '', $('body'), '');
    }
}

function showCouponCourseDetails() {
    $(this).hide();
    $(this).parent().find('.js_intro_course_details_coupon_form').show();
}

//navbar categories
function hoverCategories(e) {
    if (e.type == 'mouseenter') {
        $('.js_navbar_cat_dropdown').show();
    } else {
        $('.js_navbar_cat_dropdown').hide();
    }
}

//hover item categories
function hoverListItemCategories(e) {
    if (e.type == 'mouseenter') {
        $(this).find('.js_navbar_cat_dropdown_child').first().show();
    } else {
        $(this).find('.js_navbar_cat_dropdown_child').hide();
    }
}

//hover notify icon
function hoverNotifyIcon(e) {
    if (e.type == 'mouseenter') {
        var count_cart = $('.js_comp_dropdown .js_navbar_shop_cart .cc_navbar_shop_cart_child').find('.js_item_cart_shop').length;
        if (count_cart == 0) {
            $(this).find('.js_comp_shop_cat_empty').show();
            $(this).find('.btn').addClass('cc_hover');
        } else {
            $(this).find('.js_comp_shop_cat_empty').hide();
        }
        $(this).find('.js_comp_dropdown').first().show();
    } else {
        $(this).find('.js_comp_dropdown').hide();
        $(this).find('.btn').removeClass('cc_hover');
    }
}

//get width slide course item
function setWidthSlideCourse() {
    // hàm tính width cho class js_comp_item_child. => nó là khối bao ngoài cho slide khóa học
    // nút arrow prev luôn ẩn khi load vào trang
    $('.js_comp_item_arrow[data-arrow="prev"]').hide();

    // hàm for each tất cả thằng slide khóa học
    $.each($('.js_comp_item_layout'), function () {
        // tính length khối js_comp_item
        let count = $(this).find('.js_comp_item').length,
            // tính width khối js_comp_item + 10 (10 là tổng margin left (5px) và margin right (5px))
            itemWidth = $(this).find('.js_comp_item').innerWidth() + 10;

        width = count * itemWidth;
        if (width > 0) {
            $(this).find('.js_comp_item_child').css({
                'width': width + 'px'
            });
        }

        if ($(this).find('.js_comp_item_child').innerWidth() <= $(this).innerWidth() + 5) {
            $(this).parent().find('.js_comp_item_arrow[data-arrow="next"]').hide();
        }
    });
}

//click arrow slide course
function clickArrowSlideCourse() {
    let type = $(this).data('arrow'),
        target = $(this).parent(),
        layoutWidth = target.innerWidth(),
        itemChildWidth = target.find('.js_comp_item_child').innerWidth(),
        itemWidth = target.find('.js_comp_item').innerWidth() + 10,
        count = target.data('count') == undefined ?
            0 :
            target.data('count'),
        countArrow = target.data('arrow') == undefined ?
            0 :
            target.data('arrow');

    if (type == 'next') {
        target.find('.js_comp_item_child').css({
            'transform': 'translate(' + (
                count - itemWidth) + 'px)'
        });
        target.removeData('count').attr('data-count', (count - itemWidth));
        target.find('.js_comp_item_arrow[data-arrow="prev"]').show();
        target.removeData('arrow').attr('data-arrow', countArrow + 1);
    } else {
        target.find('.js_comp_item_child').css({
            'transform': 'translate(' + (
                count + itemWidth) + 'px)'
        });
        target.removeData('count').attr('data-count', (count + itemWidth));
        target.find('.js_comp_item_arrow[data-arrow="next"]').show();
        target.removeData('arrow').attr('data-arrow', countArrow - 1);
    }

    let devine = 1;
    if ($('.container').width() == 690) {
        devine = 3;
    } else if ($('.container').width() == 930) {
        devine = 4;
    } else if ($('.container').width() == 1110) {
        devine = 5;
    }
    if (target.is('.js_students_say_layout_parent') && devine != 1) {
        devine = 3;
    }

    if (target.data('count') == 0) {
        target.find('.js_comp_item_arrow[data-arrow="prev"]').hide();
        target.find('.js_comp_item_arrow[data-arrow="next"]').show();
    } else if (itemChildWidth + target.data('count') == itemWidth * (
        devine = $(this).parents('.js_tabs_child').find('.js_tabs_content').length ?
            3 :
            devine)) {
        target.find('.js_comp_item_arrow[data-arrow="prev"]').show();
        target.find('.js_comp_item_arrow[data-arrow="next"]').hide();
    }
}

//hover Slide Course Item
function showSlideCourse(e) {
    if ($(window).width() > 1024) {
        $('.js_comp_item_hover').removeClass('hover');
        $(this).find('.js_comp_item_hover').addClass('hover');

        let slideCourseHtml = $(this).find('.js_comp_item_hover').clone(),
            posTop = $(this).parents('.row').position().top - 50,
            posLeft = $(this).position().left,
            courseItemWidth = $(this).width(),
            dataArrow = $(this).parents('.js_comp_item_layout_parent').data('arrow') == undefined ?
                0 :
                $(this).parents('.js_comp_item_layout_parent').data('arrow'),
            px,
            checkTabsTop = 0,
            checkTabsLeft = 0;

        if ($(this).parents('.tab-content').length) {
            checkTabsTop = 69;
            if ($('.container').width() == 930) {
                checkTabsLeft = (courseItemWidth * 1) + 20;
            } else if ($('.container').width() == 1110) {
                checkTabsLeft = (courseItemWidth * 2) + 20;
            }
        }

        slideCourseHtml.addClass('clone');

        $('.js_comp_item_hover.clone').remove();
        $('body').append(slideCourseHtml);

        let slideCourseCloneWidth = slideCourseHtml.width();

        slideCourseHtml.removeClass('d-none');
        if (e.pageX > ($(window).width() / 2)) {
            slideCourseHtml.addClass('right');
            slideCourseHtml.css({
                'top' : posTop + checkTabsTop,
                'left': posLeft + (dataArrow * 222) - slideCourseCloneWidth + checkTabsLeft - ((
                    px = dataArrow != 0 ?
                        10 * dataArrow :
                        0) + dataArrow * courseItemWidth) + ($(window).width() - $('.container').width()) / 2 - 49
            });
        } else {
            slideCourseHtml.addClass('left');
            slideCourseHtml.css({
                'top' : posTop + checkTabsTop,
                'left': posLeft + (dataArrow * 222) + courseItemWidth + checkTabsLeft - ((
                    px = dataArrow != 0 ?
                        10 * dataArrow :
                        0) + dataArrow * courseItemWidth) + ($(window).width() - $('.container').width()) / 2 + 15
            });
        }
    }
}

//login - register
function openLoginRegister() {
    $('.js_block_login_regis').show();
    $('.js_login_regis_layout').hide();
    $('.js_login_regis_layout[data-type="' + $(this).data('type') + '"]').fadeIn();
    $('body').addClass('overflow_hidden');
}

function closeLoginRegister() {
    $('.js_block_login_regis').fadeOut();
    $('.js_login_regis_layout').hide();
    $('body').removeClass('overflow_hidden');
}

//show sub menu categories
function showSubmenu_cat() {
    let dataId = $(this).data('id');
    $('.js_menu_cat_sub').removeClass('active');
    $('.js_menu_cat_sub[data-id="' + dataId + '"]').addClass('active');
}

//show navbar search mobile
function showNavbarSearchMobi() {
    $('.js_navbar_mobile_search').toggleClass('active');
}

//show navbar menu mobile
function showNavbarMenuMobi() {
    $('.js_navbar_mobile_overlay, .js_navbar_mobile_layout').addClass('active');
    $('body').addClass('overflow_hidden');
}

//hide navbar search mobile
function hideNavbarMenuMobi() {
    $('.js_navbar_mobile_overlay, .js_navbar_mobile_layout, .js_navbar_mobile_categories, .js_navbar_mobile_categorie_sub').removeClass('active');
    $('body').removeClass('overflow_hidden');
}

//show navbar categories mobile
function showMavbarCategoriesMobi() {
    $('.js_navbar_mobile_layout').removeClass('active');
    $('.js_navbar_mobile_categories').addClass('active');
}

//hide navbar categories mobile
function hideMavbarCategoriesMobi() {
    $('.js_navbar_mobile_layout').addClass('active');
    $('.js_navbar_mobile_categories, .js_navbar_mobile_categorie_sub').removeClass('active');
}

//show navbar categories sub
function showMavbarCategoriesSubMobi() {
    let dataId = $(this).data('id');

    $('.js_navbar_mobile_categorie_sub').addClass('active');
    $('.js_navbar_mobile_categories').removeClass('active');
}

//hide navbar categories sub
function hideMavbarCategoriesSubMobi() {
    $('.js_navbar_mobile_categorie_sub').removeClass('active');
    $('.js_navbar_mobile_categories').addClass('active');
}

//click add to cart in slide course item
// function clickAddToCartSlideBtn() {
//     $('.js_comp_item_hover.hover').addClass('added_to_cart');
//     $('.js_block_added_course').fadeIn();
// }

//click to icon close modal
function clickCloseAddToCartSlideBtn() {
    $(this).parents('.js_block_added_course').fadeOut();
}

//click add to wishlist in slide course item
function clickAddToWishlistSlideBtn() {
    $('.js_comp_item_hover.hover').toggleClass('added_to_wishlist');
}

//show loading
function show_loading() {
    $("#loadingDiv").removeClass('hidden');
}

//hide loading
function hide_loading() {
    setTimeout(function () {
        $("#loadingDiv").addClass('hidden');
    }, 200);
}

//show all curriculum
function showAllCurriculum() {
    if (!$(this).hasClass('active')) {
        $(this).addClass('active');
        $('.card-header').removeClass('collapsed');
        $('.collapse').addClass('show');
        var text = $(this).attr("data-txt-close");
        $(this).text(text);
    } else {
        $(this).removeClass('active');
        $('.card-header').addClass('collapsed');
        $('.collapse').removeClass('show');
        var text = $(this).attr("data-txt-open");
        $(this).text(text);
    }
}

//check expand curriculum
function checkExpandOrCollapseCurriculum() {
    var text = $(".js_curriculum_expand").attr("data-txt-open");
    $('.js_curriculum_expand').text(text);
    $('.js_curriculum_expand').removeClass('active');
}

//check percent rating course
function checkPercentRatingCourse() {
    $.each($('.js_student_feedback_col_number'), function () {
        let percent = $(this).data('number');
        $(this).parents('.js_student_feedback_col_item').find('.js_student_feedback_col_per_child').css({
            'width': percent + '%'
        })
    });
}

//click tabs -> hide arrow
function clickTabsCourse() {
    $.each($('.js_comp_item_layout'), function () {
        // tính length khối js_comp_item
        let count = $(this).find('.js_comp_item').length,
            // tính width khối js_comp_item + 10 (10 là tổng margin left (5px) và margin right (5px))
            itemWidth = $(this).find('.js_comp_item').innerWidth() + 10;
        width = count * itemWidth;

        $(this).find('.js_comp_item_child').css({
            'width': width + 'px'
        });

        if ($(this).find('.js_comp_item_child').innerWidth() <= $(this).innerWidth()) {
            $(this).parent().find('.js_comp_item_arrow[data-arrow="next"]').hide();
        }
    });
}
