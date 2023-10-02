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
$first_login = $this->config->item("first_login");
?>
<html lang="<?php echo $lang; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <meta name="robots" content="noindex"/>
    <title><?php echo $title; ?></title>
    <!--    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>-->
    <meta name="description" content="<?php echo $description; ?>"/>
    <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <link rel="canonical" href="<?php echo $canonical; ?>"/>
    <link type="image/x-icon" href="<?php echo $favicon; ?>" rel="shortcut icon"/>
    <?php echo $meta_sharing; ?>
    <?php echo $tag_manager; ?>
    <?php echo $assets_header; ?>
    <?php
    if (isset($lang)) {
        $file_lang = "assets/js/site/validation_messages_$lang.js";
        echo file_exists($file_lang) ? minify_css_js('js', $file_lang, "validation_messages_$lang.js") : '';
    }
    ?>
    <!--    <meta property=”fb:app_id” content=”162116281395499” />-->
    <meta property=”fb:admins” content=”100004315024600”>
    <!--gắn tracking-->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-112165817-1', 'auto');
        ga('send', 'pageview');
    </script>
</head>
<?php $login = $this->session->userdata("first_login"); ?>
<body class="login-layout" data-barrier="<?php echo $json_barrier; ?>"
      data-fist-login="<?php if (isset($first_login)) echo $first_login; ?>"
      data-change-info="<?php echo empty($this->session->userdata("change_info")) ? 0 : 1; ?>"
      data-login="<?php echo $login; ?>" url-change-info="<?php echo site_url("site/home/change_info"); ?>"
      url-popup-notification="<?php echo (!$this->session->has_userdata('show_notification') && $this->session->userdata('id')) ? site_url("site/home/show_popup_notification") : ''; ?>"
      data-url-maintain="<?php if (!empty($maintain) && $this->session->userdata('id')) echo site_url("site/home/show_popup_maintain"); ?>"
      data-time-maintain="<?php if (!empty($maintain)) echo(strtotime($maintain->time_start) - $maintain->time_notify * 60); ?>">

<div id="loading-overlay-modal">
    <div id="loading-overlay-modal-animation"></div>
</div>
<div class="hide" id="i_language" data-weekday="<?php echo get_string("v-lang-text-date"); ?>"></div>
<div class="hide" id="js_i_data" data-url-upload-ckeditor="<?php echo site_url("site/home/upload_ckeditor"); ?>"></div>
<?php echo $top_bar; ?>
<div id="site-container" class="container <?php echo !empty($show_menu) ? '' : 'hide-menu'; ?>">
    <?php echo $side_bar_left; ?>
    <?php echo $content; ?>
</div>
<div class="both"></div>
<?php echo $assets_footer; ?>
</body>
</html>