<!DOCTYPE html>
<?php
$this->config->load("my_constant_config");
$lang_list_define = $this->config->item("lang_list");
$site_lang = $this->session->userdata('site_lang');
if (!empty($site_lang)) {
    $lang = $site_lang;
} else {
    $lang = 'vi';
}
$base_layout_main_css = array(
    "assets/css/site/base_layout_main/main.css",
    "assets/css/site/base_layout_main/navbar.css",
    "assets/css/site/base_layout_main/navbar_mobile.css",
    "assets/css/site/base_layout_main/added_course_new.css",
    "assets/css/site/base_layout_main/list_course.css",
    "assets/css/site/base_layout_main/footer.css",
    "assets/css/site/base_layout_main/login_regis.css",
    "assets/css/site/base_layout_main/components.css",
    "assets/css/site/base_layout_main/responsive.css",
);
$base_layout_main_js = array(
    "assets/js/site/validation_base.js",
    "assets/js/site/base_layout_main/cart.js",
    "assets/js/site/layout_function.js",
    "assets/js/site/layout.js",
    "assets/js/site/base_layout_main/main.js",
    "assets/js/site/login/home_main_login.js",
    "assets/js/site/exam_for_guest/exam_for_guest.js",
);
?>
<html lang="<?php echo $lang; ?>" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>
        Freetalk English
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=no">
    <?php echo $meta_sharing; ?>
    <link type="image/x-icon" href="<?php echo $favicon; ?>" rel="shortcut icon"/>
    <link rel='stylesheet' href='<?php echo base_url("assets/plugins-bower/bootstrap/dist/css/bootstrap.min.css") ?>'>
    <link rel="stylesheet"
          href="<?php echo base_url('assets/plugins-bower/font-awesome-5/web-fonts-with-css/css/solid.min.css') ?>"
          integrity="" crossorigin="anonymous">
    <link rel="stylesheet"
          href="<?php echo base_url('assets/plugins-bower/font-awesome-5/web-fonts-with-css/css/regular.min.css') ?>"
          integrity="" crossorigin="anonymous">
    <link rel="stylesheet"
          href="<?php echo base_url('assets/plugins-bower/font-awesome-5/web-fonts-with-css/css/brands.min.css') ?>"
          integrity="" crossorigin="anonymous">
    <link rel="stylesheet"
          href="<?php echo base_url('assets/plugins-bower/font-awesome-5/web-fonts-with-css/css/fontawesome.min.css') ?>"
          integrity="" crossorigin="anonymous">
    <!--Font material-icons-->
    <link href="<?php echo base_url("assets/icon/MaterialIcons/material-icons.css"); ?>" rel="stylesheet">
    <link rel=" stylesheet" href="<?php echo base_url("assets/plugins-bower/jGrowl/jquery.jgrowl.min.css"); ?>"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i"
          rel="stylesheet">
    <?php echo $tag_manager; ?>
    <?php echo minify_css_js('css', $base_layout_main_css, 'base_layout_main.css'); ?>
    <script src="<?php echo base_url("assets/plugins-bower/jquery/dist/jquery.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/plugins-bower/jquery-ui/jquery-ui.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/plugins-bower/jGrowl/jquery.jgrowl.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/plugins-bower/jquery-validation/dist/jquery.validate.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/plugins-bower/bootstrap/dist/js/bootstrap.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/plugins-bower/select2/dist/js/select2.full.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/site/enscroll/enscroll-0.6.2.min.js"); ?>"></script>
    <?php echo minify_css_js('js', $base_layout_main_js, 'base_layout_main.js'); ?>
    <?php echo $assets_header; ?>
    <script src="<?php echo base_url("assets/plugins-bower/bootstrap/dist/js/bootstrap.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/site/validation_base.js"); ?>"></script>
    <?php
    if (isset($lang)) {
        $file_lang = "assets/js/site/validation_messages_$lang.js";
        echo file_exists($file_lang) ? minify_css_js('js', $file_lang, "validation_messages_$lang.js") : '';
    }
    ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<?php $login = $this->session->userdata("first_login"); ?>
<body style="position: relative"
      data-change-info="<?php echo empty($this->session->userdata("change_info")) ? 0 : 1; ?>"
      data-login="<?php echo $login; ?>" url-change-info="<?php echo site_url("site/home/change_info"); ?>"
      url-popup-notification="<?php echo (!$this->session->has_userdata('show_notification') && $this->session->userdata('id')) ? site_url("site/home/show_popup_notification") : ''; ?>"
      data-url-maintain="<?php if (!empty($maintain)) echo(($this->session->userdata('id')) ? site_url('site/home/show_popup_maintain') : site_url('site/home_main/show_popup_maintain')); ?>"
      data-time-maintain="<?php if (!empty($maintain)) echo(strtotime($maintain->time_start) - $maintain->time_notify * 60); ?>">
<?php echo $content; ?>
<?php echo $assets_footer; ?>
<div class='modal e_modal_content'>
</div>
</body>
</html>
