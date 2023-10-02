<?php
if (!isset($current_controller)) $current_controller = NULL;
function active_menu($controller, $method = "index") {
    $ci = &get_instance();
    $current_controller = $ci->load->get_var('current_controller');
    $current_method = $ci->load->get_var('current_method');
    if ($controller == $current_controller && $method == $current_method) {
        echo "active";
    }
}

$role = $this->session->userdata("user_data")->role_alias;
if (!empty($live_class)) {
    $duration = $live_class->duration;
    $start = $live_class->time_start;
    $time_diff = strtotime($start) + ($duration + 15) * 60 - time();
}
?>
<header class="main-header">
    <div class="logo logo-manager">
        <a href="<?php echo ($role == "student") ? site_url("site/home") : site_url(); ?>">
            <img class="img img_full js_img_logo"
                 src="<?php echo !empty($url_logo) ? base_url($url_logo) : base_url('assets/images/site/logo.png'); ?>">
            <img class="img_mini" style="width: 50%;"
                 src="<?php echo base_url('assets/images/site/logo_mini.png'); ?>">
        </a>
    </div>
<!--    <div class="title">-->
<!--        <a href="#" class="side-menu-btn"><img src="--><?php //echo base_url('assets/images/site/menu.svg') ?><!--"></a>-->
<!--        <span>-->
<!--             --><?php
//             if (isset($breadcrumb) && (isset($show_menu) && $show_menu === TRUE)) {
//                 // info breadcrumb duplicate -> not show breadcrumb
////                 echo $breadcrumb;
//             }
//             ?>
<!--        </span>-->
<!--    </div>-->
    <?php if (isset($warning_maintain)) echo $warning_maintain; ?>
    <?php if ($role != 'contact') { ?>
    <div class="header-right">
        <?php if (isset($user_data) && ($user_data->role_alias == "student" || $user_data->role_alias == "teacher")) {
            if (!empty($join_class_running) && !empty($live_class) && !empty($live_class->join) && $time_diff > 0) { ?>
                <div class="join-live-class" data-time-compare="<?php if (isset($time_diff)) echo $time_diff; ?>">
                    <span class="sp_date"><?php echo empty($class_name) ? "" : $class_name; ?></span>
                    <a class="btn-join btn-join-top active running" href="<?php echo $join_class_running; ?>"
                       target="_blank"><?php echo get_string("v-attend_live_class"); ?></a>
                </div>
            <?php }
        } ?>
        <?php if (isset($user_data) && $user_data->role_alias == "student") { ?>
            <div class="quick-tools-menu">
                <a href="<?php echo site_url('site/list_course_program'); ?>"
                   class="quick-tools-item <?php active_menu('list_course_program'); ?>"
                   title="<?php echo get_string("c-side_bar_left-my_program"); ?>">
                    <i class="material-icons">import_contacts</i>
                </a>
                <a href="<?php echo site_url('site/manage_schedule_live'); ?>"
                   class="quick-tools-item <?php active_menu('manage_schedule_live', 'index'); ?>"
                   title="<?php echo get_string('c-side_bar_left-student-manage_live_class_available'); ?>">
                    <i class="material-icons">computer</i>
                </a>
                <a href="<?php echo site_url('site/manage_schedule'); ?>"
                   class="quick-tools-item <?php active_menu('manage_schedule', 'index'); ?>"
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
                <span class="user-name"><?php
                    echo empty($user_data->display_name) ? (empty($user_data->email) ? "" : $user_data->email) : $user_data->display_name; ?>
                    <!--                    --><?php //if (isset($user_data) && $user_data->role_alias == "teacher") {
                    //                        if (!empty($user_data->rate_avg)) {
                    //                            ?>
                    <!--                            (--><?php //echo $user_data->rate_avg; ?>
                    <!--                            <i class="material-icons">star</i>)-->
                    <!--                        --><?php //}
                    //                    } ?>
                </span>
                <div class="icon drop-down-arrow"></div>
            </a>
            <ul class="sub-menu">
                <?php if (empty($show_menu)) {
                    // show for role view
                    ?>
                    <li class="sub-menu-items <?php active_menu('home_main'); ?>">
                        <a href="<?php echo site_url("/site/home_main") ?>">
                            <i class="material-icons">home</i>
                            <span class="sub-item-text"><?php echo get_string('c-side_bar_left-home'); ?></span>
                        </a>
                    </li>
                    <?php
                } ?>
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
                <li class="sub-menu-items <?php active_menu('profile'); ?>">
                    <a href="<?php echo site_url("/site/profile") ?>">
                        <i class="material-icons">person</i>
                        <span class="sub-item-text"><?php echo get_string('v-top_bar-profile'); ?></span>
                    </a>
                </li>
                <?php if (empty($show_menu)) {
                    // show for role view
                    ?>
                    <li class="sub-menu-items hidden <?php active_menu('user_progress'); ?>">
                        <a href="<?php echo site_url('site/user_progress'); ?>">
                            <i class="material-icons">show_chart</i>
                            <span class="sub-item-text"><?php echo get_string('v-top_bar-student-user_progress'); ?></span>
                        </a>
                    </li>
                    <li class="sub-menu-items <?php active_menu('list_course_program'); ?>">
                        <a href="<?php echo site_url('site/list_course_program'); ?>">
                            <i class="material-icons">import_contacts</i>
                            <span class="sub-item-text"><?php echo get_string('v-top_bar-student-my_course'); ?></span>
                        </a>
                    </li>
                    <li class="sub-menu-items <?php active_menu('class_study'); ?>">
                        <a href="<?php echo site_url('site/class_study'); ?>">
                            <i class="material-icons">videocam</i>
                            <span class="sub-item-text"><?php echo get_string('c-side_bar_left-live_class'); ?></span>
                        </a>
                    </li>
                    <li class="sub-menu-items hidden <?php active_menu('list_course_program', 'index'); ?>">
                        <a href="<?php echo site_url('site/list_course_program'); ?>">
                            <i class="material-icons">library_books</i>
                            <span class="sub-item-text"><?php echo get_string("c-side_bar_left-list_program"); ?></span>
                        </a>
                    </li>
                    <li class="sub-menu-items <?php active_menu('exam_result'); ?>">
                        <a href="<?php echo site_url('site/exam_result'); ?>">
                            <i class="material-icons">check_circle</i>
                            <span class="sub-item-text"><?php echo get_string('v-top_bar-student-exam_result'); ?></span>
                        </a>
                    </li>
                    <li class="sub-menu-items <?php active_menu('user_certificate'); ?>">
                        <a href="<?php echo site_url('site/user_certificate'); ?>">
                            <i class="material-icons">stars</i>
                            <span class="sub-item-text"><?php echo get_string('v-top_bar-student-user_certificate'); ?></span>
                        </a>
                    </li>
                    <li class="sub-menu-items <?php active_menu('student_absent'); ?>">
                        <a href="<?php echo site_url('site/student_absent'); ?>">
                            <i class="material-icons">videocam_off</i>
                            <span class="sub-item-text"><?php echo get_string('v-top_bar-student-absent'); ?></span>
                        </a>
                    </li>
                    <li class="sub-menu-items <?php active_menu('ranking'); ?>">
                        <a href="<?php echo site_url('site/ranking'); ?>">
                            <i class="material-icons">equalizer</i>
                            <span class="sub-item-text"><?php echo get_string('v-top_bar-student-ranking'); ?></span>
                        </a>
                    </li>
                    <!--                    <li class="sub-menu-items --><?php //active_menu('forum'); ?><!--">-->
                    <!--                        <a href="--><?php //echo site_url('site/forum'); ?><!--">-->
                    <!--                            <i class="material-icons">forum</i>-->
                    <!--                            <span class="sub-item-text">--><?php //echo get_string('c-side_bar_left-admin-forum');?><!--</span>-->
                    <!--                        </a>-->
                    <!--                    </li>-->
<!--                    <li class="sub-menu-items --><?php //active_menu('student_register'); ?><!--">-->
<!--                        <a href="--><?php //echo site_url('site/student_register'); ?><!--">-->
<!--                            <i class="material-icons">playlist_add</i>-->
<!--                            <span class="sub-item-text">--><?php //echo get_string('c-side_bar_left-admin-register'); ?><!--</span>-->
<!--                        </a>-->
<!--                    </li>-->
                    <li class="sub-menu-items <?php active_menu('student_reserve'); ?>">
                        <a href="<?php echo site_url('site/student_reserve'); ?>">
                            <i class="material-icons">replay</i>
                            <span class="sub-item-text"><?php echo get_string('c-side_bar_left-admin-reserve'); ?></span>
                        </a>
                    </li>
                    <?php
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
