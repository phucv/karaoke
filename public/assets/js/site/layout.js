$(document).ready(function () {
    // JS for show information-menu
    $('.information-menu').hover(function () {
        var selectedItem = $(this);
        selectedItem.find('>a .icon').toggleClass('drop-up-arrow drop-down-arrow');
    });
    // JS for menu side-bar-left
    $(document).on('click', '.has-sub > a', function (e) {
        e.preventDefault();
        var hasSubClass = $(this).closest('.has-sub');
        if (hasSubClass.hasClass("show")) {
            hasSubClass.removeClass("show", 500);
        } else {
            hasSubClass.addClass("show", 500);
            // scroll to middle height menu
            var menuObj = $(this).closest("ul.nav");
            menuObj.animate({scrollTop: Math.abs($(this).offset().top - menuObj.find("li").first().offset().top)}, "slow");
        }
    });
    // show-hide menu side-bar-left
    $("#menu").on('click', function (e) {
        var selectedItem = $(this);
        e.preventDefault();
        selectedItem.find('.icon').toggleClass('menu-open menu-close');
        var objScroll = $(".site-enscroll");
        if (selectedItem.hasClass('hide-menu')) {
            $('#menu').removeClass('active');
            // close menu
            objScroll.enscroll("destroy");
            selectedItem.closest('.side-menu').addClass('close');
            initEnscroll(objScroll);
            // close logo
            $(".main-header .logo").addClass('close');
            selectedItem.removeClass('hide-menu');
            selectedItem.addClass('show-menu');
            selectedItem.find(".material-icons").text("fast_forward");
        } else if (selectedItem.hasClass('show-menu')) {
            $('#menu').removeClass('active');
            // Expand menu
            objScroll.enscroll("destroy");
            selectedItem.closest('.side-menu').removeClass('close');
            initEnscroll(objScroll);
            // Expand logo
            $(".main-header .logo").removeClass('close');
            selectedItem.removeClass('show-menu');
            selectedItem.addClass('hide-menu');
            selectedItem.find(".material-icons").text("fast_rewind");
        }
        resizeContainer();
    });

    $('.main-header .side-menu-btn').on('click', function (e) {
        e.preventDefault();
        openNav();
    });

    $(document).mouseup(function (e) {
        if (window.matchMedia('(max-width: 769px)').matches) {
            var container = $('.side-menu');
            if (!container.is(e.target) &&
                container.has(e.target).length === 0) {
                closeNav();
            }
        }
    });
    $(document).on("click", ".e_ajax_link", do_ajax_link);
    // bind event base: datepicker, ...
    resizeContainer();
    initEvent();
    updateWrapperLayoutHeight();
    $(window).resize(function () {
        updateWrapperLayoutHeight();
    });
    // active parent menu of information menu
    activeInfoMenu();
    check_first_login();
    setTimeout(function () { // because updateWrapperLayoutHeight() is running at same time
        initHeight();
    }, 500);

    // js for menu category
    $(document).on('mouseenter mouseleave', '.js_navbar_cat', hoverCategories);
    $(document).on('mouseenter mouseleave', '.js_navbar_cat_dropdown li', hoverListItemCategories);
    $(document).on('keypress keyup keydown', '.js_search_menu', function () {
        let obj = $(this);
        let search = obj.val().toLowerCase();
        let menu = $(".side-menu .nav-list:not(.menu_fixed)");
        if (search.length == 0) {
            obj.closest(".box-search").find(".js_remove").css("display", "none");
            menu.find(".menu-items.match").removeClass("match");
            menu.find(".menu-items.hidden").removeClass("hidden");
            menu.find(".sub-menu-items.match").removeClass("match");
            menu.find(".sub-menu-items.hidden").removeClass("hidden");
        } else {
            obj.closest(".box-search").find(".js_remove").css("display", "block");
            $(".nav-list:not(.menu_fixed) .menu-items").each(function () {
                let menus = $(this).find(".item-text");
                for (let i = 0; i < menus.length; i++) {
                    let name_menu = $(menus[i]).text().toLowerCase();
                    if (name_menu.search(search) >= 0) {
                        $(menus[i]).closest(".sub-menu-items").addClass("match").removeClass("hidden");
                        $(menus[i]).closest(".menu-items").addClass("match").removeClass("hidden");
                    } else {
                        $(menus[i]).closest(".sub-menu-items").removeClass("match").addClass("hidden");
                    }
                }
                if (menus.length == 1) {
                    let name = $(this).find(".item-text").text().toLowerCase();
                    if (name.search(search) >= 0) {
                        $(this).addClass("match").removeClass("hidden");
                    } else {
                        $(this).removeClass("match").addClass("hidden");
                    }
                } else {
                    if ($(this).find(".match").length > 0) {
                        $(this).addClass("match").removeClass("hidden");
                    } else {
                        $(this).removeClass("match").addClass("hidden");
                    }
                }
            });
        }
    });
    $(document).on("click", ".js_remove", function () {
        $(this).closest(".box-search").find(".js_search_menu").val("");
        $(this).css("display", "none");
        let menu = $(".side-menu .nav-list:not(.menu_fixed)");
        menu.find(".match").removeClass("match");
        menu.find(".hidden").removeClass("hidden");
    })
});

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

function check_first_login() {
    var checkFistLogin = $("body").attr("data-fist-login");
    var first_login = $("body").attr("data-login");
    var open = $("body").attr("data-change-info");
    var url = $("body").attr("url-change-info");
    var url_show_notification = $("body").attr("url-popup-notification");
    let url_maintain = $("body").attr('data-url-maintain');
    if (checkFistLogin && (first_login == "0") && (open == 0)) {
        call_ajax_link(url, '', $('body'), '');
    } else if (url_show_notification) {
        call_ajax_link(url_show_notification, '', $('body'), '');
    } else if (url_maintain) {
        let current_time = new Date().getTime();
        let notify_time = parseInt($("body").attr('data-time-maintain')) * 1000;
        setTimeout(() => {
            call_ajax_link(url_maintain, '', $('body'), '')
        }, notify_time - current_time);
    }
}

function openNav() {
    $('.side-menu').css('width', '248px');
    $('.side-menu').css('opacity', '1');
}

function closeNav() {
    $('.side-menu').css('width', '');
    $('.side-menu').css('opacity', '');
}

// update height wrapper-content-layout
function updateWrapperLayoutHeight() {
    $(".wrapper-content-layout").css("min-height", $(window).height() - $(".main-header").height() - 12);
    console.log($('#site-container .content-layout').height());
    let windowHeight = $(window).height() - $(".main-header").height() - 20;
    let layoutHeigth = $(".wrapper-content-layout").height();
    let menuHeight = (windowHeight >= layoutHeigth) ? windowHeight : layoutHeigth;
    menuHeight = menuHeight - 56;
    $('.side-menu > ul').css('max-height', menuHeight);
}

/**
 * resize container when ready
 */
function resizeContainer() {
    let sideMenu = $('.side-menu');
    var menuWidth = sideMenu.width();
    if (!sideMenu.height()) menuWidth = 0;
    var wrapperContentLayout = menuWidth + 40; // padding left + right = 40
    $('#site-container .content-layout').css({'width': 'calc(100% - ' + wrapperContentLayout + 'px', 'margin-left': menuWidth + 'px'});
}

/**
 * active parent menu of information menu
 */
function activeInfoMenu() {
    var subListItem = $(".information-menu .sub-list-item");
    if (subListItem.hasClass("active")) {
        subListItem.closest(".sub-menu-items").addClass("active");
    }
}

