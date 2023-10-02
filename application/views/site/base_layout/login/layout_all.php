<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <meta name="robots" content="noindex"/>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="description" content="<?php echo $description; ?>"/>
    <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <link rel="canonical" href="<?php echo $canonical; ?>"/>
    <link type="image/x-icon" href="<?php echo $favicon; ?>" rel="shortcut icon"/>
    <?php echo $meta_sharing; ?>
    <?php echo $tag_manager; ?>
    <?php echo $assets_header; ?>
    <?php
    $language = $this->config->item("language");
    $lang_list_define = $this->config->item("lang_list");
    if (!empty($lang_list_define)) {
        $lang = array_search($language, $lang_list_define);
        $file_lang = "assets/js/site/validation_messages_$lang.js";
        echo file_exists($file_lang) ? minify_css_js('js', $file_lang, "validation_messages_$lang.js") : '';
    }
    ?>
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
<body class="login-layout" data-barrier="<?php echo $json_barrier; ?>"
      data-url-maintain="<?php if (!empty($maintain)) echo site_url('site/home_main/show_popup_maintain'); ?>"
      data-time-maintain="<?php if (!empty($maintain)) echo(strtotime($maintain->time_start) - $maintain->time_notify * 60); ?>">
<?php echo $content; ?>
<?php echo $assets_footer; ?>
<script>
    $(document).ready(function (e) {
        let url_maintain = $("body").attr('data-url-maintain');
        if (url_maintain) {
            let current_time = new Date().getTime();
            let notify_time = parseInt($("body").attr('data-time-maintain')) * 1000;
            setTimeout(() => {
                call_ajax_link(url_maintain, '', $('body'), '')
            }, notify_time - current_time);
        }
    });
</script>
</body>
</html>