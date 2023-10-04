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
$url_logo = empty($url_logo) ? base_url("assets/images/site/logo.png") : $url_logo;
?>
<header class="main-header">
    <div class="logo logo-manager">
        <a href="<?php echo site_url(); ?>">
            <img class="img img_full js_img_logo"
                 src="<?php echo $url_logo; ?>">
            <img class="img_mini" style="width: 50%;"
                 src="<?php echo $url_logo; ?>">
        </a>
    </div>
    <div class="header-right">
        <div class="information-menu has-sub">
            <a href="#">
            <span class="avatar">
                <img src="<?php echo !empty($user_data->avatar) ? base_url($user_data->avatar) : base_url("assets/images/default-avatar.png"); ?>">
            </span>
                <span class="user-name"><?php echo empty($user_data->display_name) ? "" : $user_data->display_name; ?></span>
                <div class="icon drop-down-arrow"></div>
            </a>
            <ul class="sub-menu">
                <li class="sub-menu-items <?php active_menu('profile'); ?>">
                    <a href="<?php echo site_url("profile") ?>">
                        <i class="material-icons">person</i>
                        <span class="sub-item-text">Tài khoản của tôi</span>
                    </a>
                </li>
                <li class="sub-menu-items">
                    <a href="<?php echo site_url("login/logout"); ?>">
                        <i class="material-icons">power_settings_new</i>
                        <span class="sub-item-text">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
