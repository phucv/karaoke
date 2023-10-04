<meta content="telephone=no" name="format-detection">
<!-- FONT MATERIAL-ICONS -->
<link href="<?php echo base_url("assets/icon/MaterialIcons/material-icons.css"); ?>" rel="stylesheet">

<!-- FONT AWESOME-5 -->
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

<!-- LIBRARY JQUERY -->
<script src="<?php echo base_url("assets/plugins-bower/jquery/dist/jquery.min.js"); ?>"></script>
<script type="text/javascript">
    window.jQuery || document.write("<script src='<?php echo base_url('assets/plugins-bower/jquery/dist/jquery.min.js') ?>'>" + "<" + "/script>");
</script>
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement)
        document.write("<script src='<?php echo base_url('assets/plugins-bower/jquery-mobile/js/jquery.mobile.js'); ?>'>" + "<" + "/script>");
</script>
<script src="<?php echo base_url("assets/plugins-bower/jquery-validation/dist/jquery.validate.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins-bower/jquery-ui/jquery-ui.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins-bower/jqueryui-touch-punch/jquery.ui.touch-punch.min.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url("assets/plugins-bower/jquery-ui/themes/base/jquery-ui.min.css"); ?>"/>
<script src="<?php echo base_url("assets/plugins-bower/jGrowl/jquery.jgrowl.min.js"); ?>"></script>
<link rel=" stylesheet" href="<?php echo base_url("assets/plugins-bower/jGrowl/jquery.jgrowl.min.css"); ?>"/>

<!-- LIBRARY SELECT2 -->
<script src="<?php echo base_url("assets/plugins-bower/select2/dist/js/select2.full.min.js"); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url("assets/plugins-bower/select2/dist/css/select2.min.css"); ?>"/>

<!-- LIBRARY ENSCROLL -->
<script src="<?php echo base_url("assets/js/site/enscroll/enscroll-0.6.2.min.js"); ?>"></script>

<!-- LIBRARY CKEDITOR -->
<script src="<?php echo base_url("assets/plugins-bower/ckeditor/ckeditor.js"); ?>"></script>

<?php
$base_css = array(
    "assets/css/site/modal.css",
    "assets/css/site/base-conf.css",
    "assets/css/site/base.css",
    "assets/css/site/layout.css",
    "assets/css/site/header.css",
    "assets/css/site/side_bar.css",
);
$base_js = array(
    "assets/js/site/modal.js",
    "assets/js/site/validation_base.js",
    "assets/js/site/layout_function.js",
    "assets/js/site/layout.js",
);
echo minify_css_js('css', $base_css, 'base_layout.css');
echo minify_css_js('js', $base_js, 'base_layout.js');