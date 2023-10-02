<?php
$avatar = (!empty($user_data->avatar)) ? base_url($user_data->avatar) : base_url("assets/images/site/default-avatar.png");
$ci = &get_instance();
$current_controller = $ci->load->get_var('current_controller');
$current_method = $ci->load->get_var('current_method');
$child_current = $current_controller . "." . $current_method;
$child_current_all = $current_controller . ".*";
function active_menu($obj_active, $child_current, $child_current_all) {
    $child_active = isset($obj_active) ? explode(";", $obj_active) : array();
    if (in_array($child_current, $child_active) || in_array($child_current_all, $child_active)) {
        echo "active";
    }
}

$role = $this->session->userdata("user_data")->role_alias;
if (!empty($live_class)) {
    $duration = $live_class->duration;
    $start = $live_class->time_start;
    $time_diff = strtotime($start) + ($duration + 15) * 60 - time();
}
echo minify_css_js('css', "assets/css/site/top_bar_student.css", "top_bar_student.css");
?>

<header class="main-header header-student">
    <div class="logo">
        <a href="<?php echo site_url(); ?>">
            <img class="img img_full js_img_logo"
                 src="<?php echo !empty($url_logo) ? base_url($url_logo) : base_url('assets/images/site/logo.png'); ?>">
            <img class="img_mini" style="width: 50%;"
                 src="<?php echo base_url('assets/images/site/logo_mini.png'); ?>">
        </a>
    </div>
    <?php if ($role != 'contact') { ?>
    <div class="main_navbar_center">
        <div class="main_navbar_cat js_navbar_cat">
            <i class="fas fa-th cc_navbar_cat_icon"></i>
            <span class="cc_navbar_cat_title"><?php echo get_string("v-base_layout_main-category") ?></span>
            <!-- Categories Dropdown -->
            <div class="cc_navbar_cat_dropdown js_navbar_cat_dropdown">
                <ul class="">
                    <?php if (!empty($list_category)) {
                        foreach ($list_category as $cat) { ?>
                            <li class="">
                                <a class="cate-name"
                                   href="<?php echo site_url("site/home_search/index?category=" . $cat->name . "") ?>">
                                    <span class="cc_navbar_cat_dropdown_arrow float-left">
                                        <i class="fas fa-angle-right"></i>
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
            <div class="input-group">
                <form method="GET" action="<?php echo site_url("site/home_search/index") ?>"
                      class="form-inline">
                    <input type="text" class="input-search" name="course"
                           placeholder="<?php echo get_string("v-base_layout_main-search_course") ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
<!--        <div class="cc_comp_btn_noty d-none d-md-block d-lg-block d-xl-block ml-3 btn-noty-hidden">-->
<!--            <a class="cc_comp_btn_active_cod js_btn_active_cod" href="--><?php //echo site_url("site/home_active"); ?><!--">-->
<!--                <i class="material-icons"-->
<!--                   title="--><?php //echo get_string('v-base_layout_main-active_order'); ?><!--">lock_open</i>--><?php //echo get_string('v-base_layout_main-active_order'); ?>
<!--            </a>-->
<!--        </div>-->
    </div>
    <?php if (isset($warning_maintain)) echo $warning_maintain; ?>
    <div class="header-right">
        <?php if (isset($user_data) && ($user_data->role_alias == "student" || $user_data->role_alias == "teacher")) {
            if (!empty($join_class_running) && !empty($live_class) && !empty($live_class->join) && $time_diff > 0) { ?>
                <div class="join-live-class" data-time-compare="<?php if (isset($time_diff)) echo $time_diff; ?>">
                    <a class="btn-join btn-join-top active running"
                       title="<?php echo empty($class_name) ? "" : $class_name; ?>"
                       href="<?php echo $join_class_running; ?>"
                       target="_blank"><?php echo get_string("v-attend_live_class"); ?></a>
                </div>
            <?php }
        } ?>
        <?php if (isset($user_data) && $user_data->role_alias == "student") { ?>
            <div class="quick-tools-menu">
                <a href="<?php echo site_url('site/list_course_program'); ?>"
                   class="quick-tools-item <?php active_menu('list_course_program.*', $child_current, $child_current_all); ?>"
                   title="<?php echo get_string("c-side_bar_left-my_program"); ?>">
                    <i class="material-icons">import_contacts</i>
                </a>
                <a href="<?php echo site_url('site/manage_schedule_live'); ?>"
                   class="quick-tools-item <?php active_menu('manage_schedule_live.index', $child_current, $child_current_all); ?>"
                   title="<?php echo get_string('c-side_bar_left-student-manage_live_class_available'); ?>">
                    <i class="material-icons">computer</i>
                </a>
                <a href="<?php echo site_url('site/manage_schedule'); ?>"
                   class="quick-tools-item <?php active_menu('manage_schedule.index', $child_current, $child_current_all); ?>"
                   title="<?php echo get_string('c-side_bar_left-student-manage_list_class_available'); ?>">
                    <i class="material-icons">event_available</i>
                </a>
            </div>
        <?php } ?>
        <?php $this->load->view("site/base_layout/minimize_notify"); ?>
        <div class="information-menu has-sub">
            <a href="#">
            <span class="avatar">
                <img src="<?php echo !empty($user_data->avatar) ? base_url($user_data->avatar) : base_url("assets/images/site/default-avatar.png"); ?>">
            </span>
                <p class="user-name"><?php
                    echo empty($user_data->display_name) ? $user_data->username : $user_data->display_name; ?>
                </p>
                <div class="icon drop-down-arrow"></div>
            </a>
            <ul class="sub-menu">
                <?php if (empty($show_menu)) {
                    // show for role view
                    ?>
                    <li class="profile sub-menu-items">
                        <a href="<?php echo site_url('site/profile'); ?>">
                            <div class="cc_navbar_acount_icons" style="background-image: url(<?php echo $avatar ?>)">
                            </div>
                            <span class="cc_navbar_acount_options text-left ml-2">
												<span class="font-weight-bold"><?php echo empty($user_data->display_name) ? $user_data->username : $user_data->display_name; ?></span>
												<span><?php echo isset($user_data->email) ? $user_data->email : ''; ?></span>
											</span>
                        </a>
                    </li>
                    <?php if (!empty($ls_role)) {
                        foreach ($ls_role as $role) { ?>
                            <li class="sub-menu-items">
                                <a href="<?php echo site_url("/site/login/change_role/$role->id") ?>">
                                    <i class="material-icons">cached</i>
                                    <span class="sub-item-text"><?php echo get_string('v-top_bar-change_role', $role->name); ?></span>
                                </a>
                            </li>
                        <?php }
                    } ?>
                    <li class="sub-menu-items <?php active_menu('home_main.*', $child_current, $child_current_all); ?>">
                        <a href="<?php echo site_url("/site/home_main") ?>">
                            <i class="fa fa-home"></i>
                            <span class="sub-item-text"><?php echo get_string('c-side_bar_left-home'); ?></span>
                        </a>
                    </li>
                    <?php
                } ?>
                <?php if (empty($show_menu) && !empty($menu_student)) {
                    // show for role view
                    foreach ($menu_student as $menu) { ?>
                        <li class="sub-menu-items <?php active_menu($menu["obj_active"], $child_current, $child_current_all); ?>">
                            <a href="<?php echo $menu["url"]; ?>">
                                <?php if (strpos($menu["icon"], "<i")) {
                                    echo $menu["icon"];
                                } else { ?>
                                    <i class="material-icons item-icons"><?php echo $menu["icon"] ?></i>
                                <?php } ?>
                                <span class="sub-item-text"><?php echo $menu["text"]; ?></span>
                            </a>
                        </li>
                    <?php }
                } ?>
                <li class="sub-menu-items">
                    <a href="<?php echo site_url("site/login/logout"); ?>">
                        <i class="material-icons">power_settings_new</i>
                        <span class="sub-item-text"><?php echo get_string("v-top_bar-logout"); ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php } ?>
</header>
<script type="text/javascript">
    // $(document).ready(function () {
    //     var time_diff = $(".join-live-class").attr("data-time-compare");
    //     setTimeout(function () {
    //         $(".join-live-class").remove();
    //     }, time_diff * 1000);
    // });
</script>
