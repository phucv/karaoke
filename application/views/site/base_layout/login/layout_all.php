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
    $lang = "vi";
    $file_lang = "assets/js/site/validation_messages_$lang.js";
    echo file_exists($file_lang) ? minify_css_js('js', $file_lang, "validation_messages_$lang.js") : '';
    ?>
</head>
<body class="login-layout" data-barrier="<?php echo $json_barrier; ?>">
<?php echo $content; ?>
<?php echo $assets_footer; ?>
</body>
</html>