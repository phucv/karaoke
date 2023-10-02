<?php
$avatar = (!empty($info_student->avatar)) ? base_url($info_student->avatar) : base_url("assets/images/site/default-avatar.png");
$menu_student = empty($menu_student) ? [] : $menu_student;
?>
<nav class="cc_block_navbar">
    <div class="cc_navbar_mobile_search js_navbar_mobile_search">
        <div class="cc_comp_search">
            <form method="GET" action="<?php echo site_url("site/home_search/index") ?>" class="form-inline">
                <div class="input-group">
                    <input class="form-control" type="text" name="course"
                           placeholder="<?php echo get_string("v-base_layout_main-search_course") ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="cc_navbar_child">
                    <div class="cc_navbar_left">
                        <div class="cc_navbar_mobile d-flex d-md-none">
                            <button type="button" class="btn cc_navbar_mobi_humbuger js_navbar_mobi_humbuger"><i
                                        class="fas fa-bars"></i></button>
                            <button type="button" class="btn cc_navbar_mobi_search js_navbar_mobi_search"><i
                                        class="fas fa-search"></i></button>
                        </div>
                        <div class="cc_navbar_logo">
                            <a class="navbar-brand" href="<?php echo base_url("site/home_main") ?>">
                                <img src="<?php echo (!empty($info_company["logo_company"])) ? base_url($info_company["logo_company"]) : base_url("assets/images/site/home_main/logo_coral.png") ?>"
                                     alt="CloudClass" class="img-fluid cc_navbar_logo js_img_logo">
                            </a>
                        </div>
                    </div>
                    <div class="cc_navbar_center d-none d-md-flex d-lg-flex d-xl-flex">
                        <?php if (isset($warning_maintain)) echo $warning_maintain; ?>
                        <div class="cc_navbar_cat js_navbar_cat">
                            <i class="fas fa-th cc_navbar_cat_icon"></i>
                            <span class="cc_navbar_cat_title"><?php echo get_string("v-base_layout_main-category") ?></span>
                            <!-- Categories Dropdown -->
                            <div class="cc_navbar_cat_dropdown js_navbar_cat_dropdown">
                                <ul>
                                    <?php if (!empty($list_category)) {
                                        foreach ($list_category as $cat) { ?>
                                            <li>
                                                <a class="cat-name" href="<?php echo site_url("site/home_search/index?category=" . $cat->name . "") ?>">
                                                    <span class="cc_navbar_cat_dropdown_arrow float-left">
                                                        <?php if (!empty($cat->icon)) { ?>
                                                            <img src="<?php echo base_url($cat->icon) ?>"
                                                                 alt="" style="width: 25px">
                                                        <?php } else { ?>
                                                            <i class="material-icons">book</i>
                                                        <?php } ?>
                                                    </span>
                                                    <span class="cc_navbar_cat_dropdown_title"><?php echo $cat->name ?></span>
                                                </a>
                                            </li>
                                        <?php }
                                    } ?>
                                </ul>
                            </div>
                            <!-- End Categories Dropdown -->
                        </div>
                        <div class="cc_comp_search">
                            <form method="GET" action="<?php echo site_url("site/home_search/index") ?>"
                                  class="form-inline">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="course"
                                           placeholder="<?php echo get_string("v-base_layout_main-search_course") ?>">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="cc_navbar_right">
<!--                        <div class="cc_comp_btn_noty d-none d-md-block d-lg-block d-xl-block ml-3 btn-noty-hidden">-->
<!--                            <a class="cc_comp_btn_active_cod js_btn_active_cod"-->
<!--                               href="--><?php //echo site_url('site/home_active') ?><!--">-->
<!--                                <i class="material-icons"-->
<!--                                   title="--><?php //echo get_string('v-base_layout_main-active_order'); ?><!--">lock_open</i>--><?php //echo get_string('v-base_layout_main-active_order'); ?>
<!--                            </a>-->
<!--                        </div>-->
                        <div class="cc_comp_btn_noty js_comp_icon_noty d-none d-md-block d-lg-block d-xl-block ml-3 btn-noty-hidden">
                            <a href="<?php echo site_url('site/teacher') ?>" class="text-center btn cc_comp_btn_none_outline_color">
                                <?php echo get_string("v-base_layout_main-is_teacher") ?>
                            </a>
                            <div class="cc_comp_dropdown text-center js_comp_dropdown cc_more_info">
                                <p>
                                    <?php echo get_string("v-base_layout_main-msg_become_instructor") ?>
                                </p>
                                <a href="<?php echo site_url('site/teacher') ?>">
                                    <?php echo get_string("v-base_layout_main-login") ?>
                                </a>
                            </div>
                        </div>
                        <div class="cc_comp_icon_noty js_comp_icon_noty js_shop_cart_add_remove">
                            <a href="#" class="text-center btn">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cc_comp_icon_noty_num"><?php echo $total_course_in_cart ?></span>
                            </a>
                            <div class="cc_comp_dropdown text-center js_comp_dropdown">
                                <?php if (empty($list_cart)) { ?>
                                    <div class="cc_comp_shop_cat_empty js_comp_shop_cat_empty">
                                        <p>
                                            <?php echo get_string("v-base_layout_main-cart_null") ?>
                                        </p>
                                        <a href="<?php echo site_url("site/home_cart") ?>">
                                            <?php echo get_string("v-base_layout_main-go_to_cart") ?>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class=" cc_navbar_shop_cart js_navbar_shop_cart" style="display: block">
                                        <div class="cc_navbar_shop_cart_child">
                                            <?php foreach ($list_cart as $cart) { ?>
                                                <a href="<?php echo $url_detail_course . "0/" . $cart["id"] ?>"
                                                   class="js_item_cart_shop" data-id="<?php echo $cart["id"] ?>">
                                                    <div class="cc_navbar_shop_cart_img">
                                                        <img src="<?php echo (!empty($cart["option"]["avatar"])) ? base_url($cart["option"]["avatar"]) : base_url("assets/images/site/home_main/shop_cat.jpg") ?>"
                                                             alt="<?php echo !empty($cart["name"]) ? $cart["name"] : "" ?>"
                                                             class="img-fluid">
                                                    </div>
                                                    <div class="cc_navbar_shop_cart_content ml-2 text-left">
                                                        <div class="cc_navbar_shop_cart_title">
                                                            <span class="font-weight-bold"><?php echo !empty($cart["name"]) ? $cart["name"] : "" ?></span>
                                                        </div>
                                                        <div class="cc_navbar_shop_cart_author">
                                                            <span><?php echo (!empty($cart["option"]["author"])) ? $cart["option"]["author"] : "" ?></span>
                                                        </div>
                                                        <div class="cc_navbar_shop_cart_money">
                                                            <span class="font-weight-bold"><?php echo (!empty($cart["price"])) ? number_format($cart["price"], 0, ',', '.') . " " . (!empty($cart["option"]["curency"]) ? $cart["option"]["curency"] : " VND") : get_string("v-base_layout_main-free") ?></span>
                                                            <s><?php echo (!empty($cart["option"]["price_real"]) && !empty($cart["price"]) && ($cart["price"] < $cart["option"]["price_real"])) ? number_format($cart["option"]["price_real"], 0, ',', '.') : " " ?></s>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                        </div>
                                        <div class="cc_navbar_shop_cart_total">
                                            <p class="text-left">
                                            <span><?php echo get_string("v-base_layout_main-total_money") ?>
                                                : </span><span
                                                        class="package-price font-weight-bold ml-2 mr-2"><?php echo (!empty($total_price)) ? number_format($total_price, 0, ',', '.') . " VND" : "" ?></span><s><?php echo (!empty($total_price_real)) ? number_format($total_price_real, 0, ',', '.') : "" ?></s>
                                            </p>
                                            <button type="button" data-url="<?php echo site_url("site/home_cart") ?>"
                                                    class="btn cc_comp_btn_second_color js_comp_item_go_cart_btn"><?php echo get_string("v-base_layout_main-go_to_cart") ?>
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if (!isset($_SESSION['user_data']) || $_SESSION['user_data']->role_alias == 'contact') {
                            ?>
                            <div class="cc_navbar_log d-none d-md-flex d-lg-flex d-xl-flex">
                                <button type="button" class="btn cc_comp_btn_outline_color js_comp_btn_login_register"
                                        data-type="login"><?php echo get_string("v-base_layout_main-login") ?>
                                </button>
                                <button type="button" class="btn cc_comp_btn_main_color js_comp_btn_login_register"
                                        data-type="register"><?php echo get_string("v-base_layout_main-register") ?>
                                </button>
                            </div>
                            <?php
                        } else { ?>
                            <div class="cc_navbar_acount js_comp_icon_noty d-none d-md-block d-lg-block d-xl-block">
                                <a href="<?php echo site_url('site/profile'); ?>"
                                   style="background-image: url('<?php echo $avatar ?>')">
                                </a>
                                <div class="cc_comp_dropdown cc_navbar_acount_dropdown text-center js_comp_dropdown">
                                    <ul>
                                        <li class="profile">
                                            <a href="<?php echo site_url('site/profile'); ?>">
                                                <div class="cc_navbar_acount_icons"
                                                     style="background-image: url('<?php echo $avatar ?>')">
                                                    <span style="margin: auto"></span>
                                                </div>
                                                <span class="cc_navbar_acount_options text-left ml-2">
                                                    <span class="font-weight-bold"><?php echo empty($_SESSION['user_data']->display_name) ? $_SESSION['user_data']->username : $_SESSION['user_data']->display_name; ?></span>
                                                    <span><?php echo isset($_SESSION['user_data']->email) ? $_SESSION['user_data']->email : ''; ?></span>
                                                </span>
                                            </a>
                                        </li>
                                        <?php if (!empty($ls_role)) {
                                            foreach ($ls_role as $role) { ?>
                                                <li>
                                                    <a href="<?php echo site_url("/site/login/change_role/$role->id") ?>">
                                                        <div class="cc_navbar_acount_icons">
                                                            <i class="material-icons">cached</i>
                                                        </div>
                                                        <span class="cc_navbar_acount_options text-left ml-2">
                                                        <span><?php echo get_string("v-base_layout_main-change_role", $role->name); ?></span>
                                                    </span>
                                                    </a>
                                                </li>
                                            <?php }
                                        } ?>
                                        <li>
                                            <a href="<?php echo site_url('site/home_main'); ?>">
                                                <div class="cc_navbar_acount_icons">
                                                    <i class="fa fa-home"></i>
                                                </div>
                                                <span class="cc_navbar_acount_options text-left ml-2">
												<span><?php echo get_string('v-base_layout_main-home_main') ?></span>
											</span>
                                            </a>
                                        </li>
                                        <?php foreach ($menu_student as $menu) { ?>
                                            <li>
                                                <a href="<?php echo $menu["url"]; ?>">
                                                    <div class="cc_navbar_acount_icons">
                                                        <?php echo $menu["icon"]; ?>
                                                    </div>
                                                    <span class="cc_navbar_acount_options text-left ml-2">
                                                        <span><?php echo $menu["text"]; ?></span>
                                                    </span>
                                                </a>
                                            </li>
                                         <?php } ?>
                                        <li>
                                            <a href="<?php echo site_url('site/login/logout') ?>">
                                                <div class="cc_navbar_acount_icons">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </div>
                                                <span class="cc_navbar_acount_options text-left ml-2">
												<span><?php echo get_string("v-base_layout_main-logout") ?></span>
											</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <div class="cc_navbar_acount js_comp_icon_noty d-none d-md-block d-lg-block d-xl-block hidden template">
                            <a href="<?php echo site_url('site/profile'); ?>" class="js_show_avatar">
                            </a>
                            <div class="cc_comp_dropdown cc_navbar_acount_dropdown text-center js_comp_dropdown">
                                <ul>
                                    <li class="profile">
                                        <a href="<?php echo site_url('site/profile'); ?>">
                                            <div class="cc_navbar_acount_icons js_show_avatar">
                                            </div>
                                            <span class="cc_navbar_acount_options text-left ml-2">
												<span class="font-weight-bold"></span>
												<span></span>
											</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('site/home_main'); ?>">
                                            <div class="cc_navbar_acount_icons">
                                                <i class="fa fa-home"></i>
                                            </div>
                                            <span class="cc_navbar_acount_options text-left ml-2">
												<span><?php echo get_string('v-base_layout_main-home_main') ?></span>
											</span>
                                        </a>
                                    </li>
                                    <?php foreach ($menu_student as $menu) { ?>
                                        <li>
                                            <a href="<?php echo $menu["url"]; ?>">
                                                <div class="cc_navbar_acount_icons">
                                                    <?php echo $menu["icon"]; ?>
                                                </div>
                                                <span class="cc_navbar_acount_options text-left ml-2">
                                                        <span><?php echo $menu["text"]; ?></span>
                                                    </span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href="<?php echo site_url('site/login/logout') ?>">
                                            <div class="cc_navbar_acount_icons">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </div>
                                            <span class="cc_navbar_acount_options text-left ml-2">
												<span><?php echo get_string("v-base_layout_main-logout") ?></span>
											</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!--topbar mobile-->
<nav class="cc_block_navbar_mobile">
    <div class="cc_navbar_mobile_overlay js_navbar_mobile_overlay"></div>
    <div class="cc_navbar_mobile_layout js_navbar_mobile_layout">
        <ul>
            <li>
                <a href="#" class="js_navbar_open_categories">
                    <div class="cc_navbar_acount_icons">
                        <i class="fab fa-accusoft"></i>
                    </div>
                    <span class="cc_navbar_acount_options text-left ml-2">
												<span><?php echo get_string('v-base_layout_main-category') ?></span>
											</span>
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <?php
            if (isset($_SESSION['user_data'])) {
                ?>
                <span class="js_menu_userdata">
                    <li style="padding: 5px 0;">
                        <a href="<?php echo site_url('site/profile'); ?>">
                            <div class="cc_navbar_acount_icons border_outline text-center"
                                 style="background-image: url('<?php echo $avatar ?>')">
                                </div>
                            <span class="cc_navbar_acount_options text-left ml-2">
                                        <span class="font-weight-bold"><?php echo empty($_SESSION['user_data']->display_name) ? $_SESSION['user_data']->username : $_SESSION['user_data']->display_name;  ?></span>
                                        <span><?php echo isset($_SESSION['user_data']->email) ? $_SESSION['user_data']->email : ''; ?></span>
                                    </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('site/home_main'); ?>">
                            <div class="cc_navbar_acount_icons">
                                <i class="fa fa-home"></i>
                            </div>
                            <span class="cc_navbar_acount_options text-left ml-2">
                                                    <span><?php echo get_string('v-base_layout_main-home_main') ?></span>
                                                </span>
                        </a>
                    </li>
                    <?php foreach ($menu_student as $menu) { ?>
                        <li>
                            <a href="<?php echo $menu["url"]; ?>">
                                <div class="cc_navbar_acount_icons">
                                    <?php echo $menu["icon"]; ?>
                                </div>
                                <span class="cc_navbar_acount_options text-left ml-2">
                                    <span><?php echo $menu["text"]; ?></span>
                                </span>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo site_url('site/login/logout') ?>">
                            <div class="cc_navbar_acount_icons">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span class="cc_navbar_acount_options text-left ml-2">
                                                    <span><?php echo get_string("v-base_layout_main-logout") ?></span>
                                                </span>
                        </a>
                    </li>
                </span>
                <?php
            }
            ?>
            <?php
            if (!isset($_SESSION['user_data']) || (isset($_SESSION['user_data']) && $_SESSION['user_data']->role_alias == 'contact')) {
                ?>
                <li class="cc_navbar_mobile_log_regis">
                    <a class="btn cc_comp_btn_outline_color" href="<?php echo site_url('site/home_active'); ?>"
                       data-type="login"><?php echo get_string("v-base_layout_main-active") ?>
                    </a>
                    <button type="button" class="btn cc_comp_btn_outline_color js_comp_btn_login_register"
                            data-type="login"><?php echo get_string("v-base_layout_main-login") ?>
                    </button>
                    <button type="button" class="btn cc_comp_btn_main_color js_comp_btn_login_register"
                            data-type="register"><?php echo get_string("v-base_layout_main-register") ?>
                    </button>
                </li>
                <?php
            }
            ?>
            <span class="js_menu_userdata hidden template">
                    <li style="padding: 5px 0;">
                        <a href="<?php echo site_url('site/profile'); ?>">
                            <div class="cc_navbar_acount_icons border_outline text-center js_show_avatar">
                                </div>
                            <span class="cc_navbar_acount_options text-left ml-2">
                                        <span class="font-weight-bold"><?php echo empty($_SESSION['user_data']->display_name) ? (isset($_SESSION['user_data']->username) ? $_SESSION['user_data']->username : '') : $_SESSION['user_data']->display_name; ?></span>
                                        <span><?php echo isset($_SESSION['user_data']->email) ? $_SESSION['user_data']->email : ''; ?></span>
                                    </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo site_url('site/home_main'); ?>">
                            <div class="cc_navbar_acount_icons">
                                <i class="fa fa-home"></i>
                            </div>
                            <span class="cc_navbar_acount_options text-left ml-2">
                                                    <span><?php echo get_string('v-base_layout_main-home_main') ?></span>
                                                </span>
                        </a>
                    </li>
                    <?php foreach ($menu_student as $menu) { ?>
                        <li>
                            <a href="<?php echo $menu["url"]; ?>">
                                <div class="cc_navbar_acount_icons">
                                    <?php echo $menu["icon"]; ?>
                                </div>
                                <span class="cc_navbar_acount_options text-left ml-2">
                                    <span><?php echo $menu["text"]; ?></span>
                                </span>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo site_url('site/login/logout') ?>">
                            <div class="cc_navbar_acount_icons">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span class="cc_navbar_acount_options text-left ml-2">
                                                    <span><?php echo get_string("v-base_layout_main-logout") ?></span>
                                                </span>
                        </a>
                    </li>
                </span>
        </ul>
    </div>
    <div class="cc_navbar_mobile_layout cc_navbar_mobile_categories js_navbar_mobile_categories">
        <ul>
            <li>
                <a href="#" class="js_navbar_mobile_hide_categories">
                    <i class="fas fa-angle-left"></i>
                    <span class="cc_navbar_acount_options text-left ml-2">
						<span><?php echo get_string("v-base_layout_main-menu") ?></span>
					</span>
                </a>
            </li>
            <?php if (!empty($list_category)) {
                foreach ($list_category as $cat) { ?>
                    <li>
                        <a href="<?php echo site_url("site/home_search/index?category=" . $cat->name . "") ?>">
                            <span class="cc_navbar_acount_options text-left js_navbar_acount_options"
                                  data-id="<?php echo $cat->id ?>">
                                <span><?php
                                    echo $cat->name
                                    ?></span>
                            </span>
                        </a>
                    </li>
                <?php }
            } ?>
        </ul>
    </div>
</nav>
<!--login and register-->
<div class="cc_block_login_regis js_block_login_regis">
    <div class="cc_login_regis_layout js_login_regis_layout" data-type="login">
        <div class="cc_login_regis_header">
            <span class="font-weight-bold"><?php echo get_string("v-base_layout_main-login_account") ?></span>
            <button type="button" class="btn float-right js_login_regis_close"><i class="fas fa-times"></i></button>
        </div>
        <div class="cc_login_regis_midle">
            <div class="cc_login_regis_google">
                <a id="google" class="login-w-gg" href="<?php echo site_url('site/google_api/index/student') ?>">
                    <span><i class="fab fa-google"></i><?php echo get_string("v-base_layout_main-continue_google") ?></span>
                </a>
            </div>
            <div class="cc_login_regis_form">
                <p class="notify" style="color: red"></p>
                <form action="" method="post" class="login">
                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <input type="text" name="username" class="form-control form-control-lg"
                               placeholder="<?php echo get_string('v-home_main-top_bar-username'); ?>" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock lock"></i>
                        <input type="password" name="password" class="form-control form-control-lg"
                               placeholder="<?php echo get_string('v-home_main-top_bar-password'); ?>" minlength="6"
                               maxlength="30"
                               required>
                    </div>
                    <button type="submit" url-login="<?php echo site_url('site/login/check'); ?>"
                            url-reload="<?php echo base_url('site/home_main') ?>"
                            class="btn cc_comp_btn_main_color btn-lg btn-login-js"><?php echo get_string("v-base_layout_main-login") ?></button>
                </form>
            </div>
            <div class="cc_login_regis_forgot_pass text-center">
                <span><?php echo get_string('v-base_layout_main-or') ?> </span><a
                        href="<?php echo site_url('site/login/forgot_password') ?>"><?php echo get_string("v-base_layout_main-forget_password") ?></a>
            </div>
            <div class="cc_login_regis_terms text-center">
                <span
                <?php echo get_string("v-base_layout_main-msg_register") ?>
                </span>
            </div>
            <div class="separate"></div>
        </div>
        <div class="cc_login_regis_have_account text-center">
            <span><?php echo get_string("v-base_layout_main-question_account") ?> </span><a href="#"
                                                                                            class="font-weight-bold js_comp_btn_login_register"
                                                                                            data-type="register"><?php echo get_string("v-base_layout_main-register") ?></a>
        </div>
    </div>
    <div class="cc_login_regis_layout js_login_regis_layout" data-type="register">
        <div class="cc_login_regis_header">
            <span class="font-weight-bold"><?php echo get_string("v-base_layout_main-register_and_leard") ?></span>
            <button type="button" class="btn float-right js_login_regis_close"><i class="fas fa-times"></i></button>
        </div>
        <div class="cc_login_regis_midle">
            <div class="cc_login_regis_form">
                <form action="" method="post" class="signup">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control form-control-lg" name="display_name"
                               placeholder="<?php echo get_string('v-home_main-top_bar-fullname') ?>" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" class="form-control form-control-lg" name="username"
                               placeholder="<?php echo get_string('v-home_main-top_bar-username') ?>" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" class="form-control form-control-lg" name="email"
                               placeholder="<?php echo get_string('v-home_main-top_bar-email') ?>" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-phone fa-rotate-90"></i>
                        <input type="text" class="form-control form-control-lg" name="phone"
                               placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock lock"></i>
                        <input type="password" class="form-control form-control-lg" name="password"
                               placeholder="<?php echo get_string('v-home_main-top_bar-password') ?>" required
                               minlength="6" maxlength="30">
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customControlAutosizing">
                        <label class="custom-control-label" for="customControlAutosizing">
                            <?php echo get_string("v-base_layout_main-msg_to_email") ?>
                        </label>
                    </div>
                    <button url-register="<?php echo site_url('site/login/register_acc'); ?>"
                            url-reload="<?php echo site_url('site/list_course_program') ?>"
                            class="btn cc_comp_btn_main_color btn-lg mt-2 btn-register-js"><?php echo get_string("v-base_layout_main-register") ?></button>
                </form>
            </div>
            <div class="cc_login_regis_terms text-center">
				<span>
				    <?php echo get_string("v-base_layout_main-msg_register") ?>
                </span>
            </div>
            <div class="separate"></div>
        </div>
        <div class="cc_login_regis_have_account text-center">
            <span><?php echo get_string("v-base_layout_main-account_exit") ?> </span><a href="#"
                                                                                        class="font-weight-bold js_comp_btn_login_register"
                                                                                        data-type="login"><?php echo get_string("v-base_layout_main-login") ?></a>
        </div>
    </div>
    <div class="cc_login_regis_layout js_login_regis_layout" data-type="active_key_1">
        <div class="cc_login_regis_header">
            <span class="font-weight-bold"><?php echo get_string("v-base_layout_main-step_1") ?></span>
            <button type="button" class="btn float-right js_login_regis_close"><i class="fas fa-times"></i></button>
        </div>
        <div class="cc_comp_hr"></div>
        <div class="cc_login_regis_midle">
            <div class="cc_key_register_title text-center">
                <p class="mb-2 font-weight-bold">
                    <?php echo get_string("v-base_layout_main-activate_course") ?>
                </p>
                <p class="mb-0">
                    <?php echo get_string("v-base_layout_main-type_key") ?>
                </p>
            </div>
            <div class="cc_login_regis_form">
                <form action="" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" placeholder="">
                    </div>
                    <button type="submit"
                            class="btn cc_comp_btn_main_color btn-lg"><?php echo get_string('v-home_main-top_bar-active') ?></button>
                </form>
            </div>
            <div class="cc_login_regis_terms text-center mb-5">
				<span>
					<?php echo get_string("v-base_layout_main-msg_register") ?>
				</span>
            </div>
        </div>
    </div>
    <div class="cc_login_regis_layout js_login_regis_layout" data-type="forgot_password">
        <div class="cc_login_regis_header">
            <span class="font-weight-bold"><?php echo get_string("v-base_layout_main-login_account") ?></span>
            <button type="button" class="btn float-right js_login_regis_close"><i class="fas fa-times"></i></button>
        </div>
        <div class="cc_login_regis_midle">
            <div class="cc_login_regis_form">
                <form action="" method="post" class="forgot_password">
                    <div class="capcha-warning">
                        <div>
                            <i class="fas fa-exclamation-triangle"></i>
                            <div class="notify-capcha"><?php echo get_string('v-base_layout_main-notify_capcha') ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-envelope email"></i>
                        <input type="email" id="input" class="form-control form-control-lg" name="email"
                               placeholder="<?php echo get_string('v-home_main-top_bar-email') ?>" required>
                    </div>
                    <div class="reset-or-login">
                        <button type="submit"
                                data-url="<?php echo site_url('site/login/send_request_forgot_password') ?>"
                                class="btn cc_comp_btn_main_color btn-lg btn-forgot-js"
                                data-role="student"><?php echo get_string('v-base_layout_main-reset') ?></button>
                        <span><?php echo get_string('v-base_layout_main-or') ?> <a href="#"
                                                                                   class="font-weight-bold js_comp_btn_login_register"
                                                                                   data-type="login"><?php echo get_string("v-base_layout_main-login") ?></a></span>
                    </div>
                    <div class="forgot-password-capcha">
                        <div class="g-recaptcha" data-sitekey="6LetK3YUAAAAAIoZE1gjRGW4w5bt_iyiLp97pbP_"></div>
                    </div>
                </form>
            </div>
            <div class="separate"></div>
        </div>
    </div>
</div>
<?php echo empty($view_message_add_cart) ? '' : $view_message_add_cart; ?>
<div class="msg-js-cart" data-total-money="<?php echo get_string("v-base_layout_main-total_money") ?>"
     data-go-to-cart="<?php echo get_string("v-base_layout_main-go_to_cart") ?>"
     data-cart-null="<?php echo get_string("v-base_layout_main-cart_null") ?>"
     js-base-url="<?php echo base_url() ?>"
     js-images-default="<?php echo "assets/images/site/default-avatar.png" ?>"
>

</div>

