$(document).on('click', '.js_comp_item_add_cart_btn', clickAddToCartSlideBtn);
$(document).on('click', '.js_comp_item_go_cart_btn', clickGoToCart);
$(document).on('click', '.js_list_shop_cart_remove', clickDeleteCart)

/**
 * add to cart
 */
function clickAddToCartSlideBtn() {
    var obj = $(this);
    $('.js_comp_item_hover.hover').addClass('added_to_cart');
    var url = obj.data('url');
    var package_program_id = obj.data('id');
    $.ajax({
        url     : url + package_program_id,
        type    : 'POST',
        data    : "",
        dataType: 'json',
        success : function (data) {
            if (data.status == 1) {
                $('body').find('.js_comp_item_add_cart_btn[data-id=' + data.program.id + ']').css('display', 'none');
                $('body').find('.js_comp_item_go_cart_btn[data-id=' + data.program.id + ']').css('display', 'block');
                $('.js_block_added_course .cc_added_course_content .mr-2').find('span').html(data.program.name);
                $('.js_block_added_course').find('.js_comp_item_go_cart_btn').attr('data-url', data.url_cart);
                if (data.total_cart - 1 == 0) {
                    $('.js_shop_cart_add_remove .js_comp_dropdown .js_comp_shop_cat_empty').css('display', 'none !important');
                    var frame_cart = '<div class=" cc_navbar_shop_cart js_navbar_shop_cart" style="display: block"><div class="cc_navbar_shop_cart_child"></div></div>';
                    $('.js_shop_cart_add_remove .js_comp_dropdown').append(frame_cart)
                }
                var add_cart = '<a href="' + data.url_detail_course + '" class="js_item_cart_shop"><div class="cc_navbar_shop_cart_img"><img src="' + data.link_images + '" alt="' + data.program.name + '" class="img-fluid"></div><div class="cc_navbar_shop_cart_content ml-2 text-left"><div class="cc_navbar_shop_cart_title"><span class="font-weight-bold">' + data.program.name + ' </span></div><div class="cc_navbar_shop_cart_author"><span>' + data.program.option.author + '</span></div><div class="cc_navbar_shop_cart_money"><span class="font-weight-bold">' + data.program.price + ' </span><s>' + data.program.option.price_real + '</s></div></div></a>';
                $('.js_shop_cart_add_remove .js_navbar_shop_cart .cc_navbar_shop_cart_child').append(add_cart);
                var title_total_money = $('.msg-js-cart').attr('data-total-money');
                var title_go_to_cart = $('.msg-js-cart').attr('data-go-to-cart');
                if (data.total_cart - 1 == 0) {
                    var frame_button_go_cart = '<div class="cc_navbar_shop_cart_total"><p class="text-left"><span>' + title_total_money + ': </span><span class="font-weight-bold ml-2 mr-2">data.total_price</span><s>data.total_price_real</s></p><button type="button" data-url="" class="btn cc_comp_btn_second_color js_comp_item_go_cart_btn">' + title_go_to_cart + '</button></div>';
                    $('.js_shop_cart_add_remove .js_navbar_shop_cart').append(frame_button_go_cart);
                }
                $('.js_shop_cart_add_remove .cc_navbar_shop_cart_total .font-weight-bold').html(data.total_price);
                $('.js_shop_cart_add_remove .cc_navbar_shop_cart_total p s').html(data.total_price_real);
                $('.js_comp_icon_noty .cc_comp_icon_noty_num').html(data.total_cart);
                $('.js_shop_cart_add_remove .js_comp_item_go_cart_btn').attr("data-url", data.url_cart);

            } else {
                $('.cc_added_course_content').html(data.msg);
            }
        },
        error   : function (a, b, c) {
            console.log(a, b, c);
        },
    });
    $('.js_block_added_course').fadeIn();
}

/**
 * go to page cart
 */
function clickGoToCart() {
    window.location = $(this).data("url");
}

/**
 * delete course in cart
 */
function clickDeleteCart() {
    var obj = $(this);
    var url = obj.data("url");
    var url_ajax = obj.data("url-ajax");
    var package_program_id = obj.data("id");
    show_loading();
    $.ajax({
        url     : url + package_program_id,
        type    : 'POST',
        data    : "",
        dataType: 'json',
        success : function (data) {
            var msg_cart_null = $('.msg-js-cart').attr('data-cart-null');
            var title_go_to_cart = $('.msg-js-cart').attr('data-go-to-cart');
            if (data.total_cart == 0) {
                $('.js_navbar_shop_cart').css('display', 'none');
                var cart_null = '<div class="cc_comp_shop_cat_empty js_comp_shop_cat_empty"><p>' + msg_cart_null + '</p><a href="' + data.url_cart + '">' + title_go_to_cart + '</a></div>';
                $('.js_shop_cart_add_remove .js_comp_dropdown').append(cart_null);
            }
            $('.js_shop_cart_add_remove .cc_navbar_shop_cart_total .font-weight-bold').html(data.total_price_real);
            $('.js_shop_cart_add_remove .cc_navbar_shop_cart_total p s').html(data.total_price);
            $('.js_shop_cart_add_remove .js_comp_icon_noty .cc_comp_icon_noty_num').html(data.total_cart);
            $('.cc_navbar_shop_cart_child').find('a[data-id=' + package_program_id + ']').remove();
            $('.cc_comp_icon_noty_num').text(data.total_cart);
            ajax_cart(url_ajax);
        },
        error   : function (a, b, c) {
            console.log(a, b, c);
        },
    });
}

/**
 * ajax content cart
 *
 * param url ajax
 */
function ajax_cart(url) {
    $.ajax({
        url     : url,
        type    : 'POST',
        data    : "",
        dataType: 'json',
        success : function (data) {
            if (data.status) {
                $('.content').html(data.html);
            } else {
                location.reload();
            }
            hide_loading();
        }
    });
}