<!-- FONT MATERIAL-ICONS -->
<link href="<?php echo base_url("assets/icon/MaterialIcons/material-icons.css"); ?>" rel="stylesheet">

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
<script src="<?php echo base_url("assets/plugins-bower/jGrowl/jquery.jgrowl.min.js"); ?>"></script>

<link rel="stylesheet" href="<?php echo base_url("assets/plugins-bower/jGrowl/jquery.jgrowl.min.css"); ?>"/>

<?php
$base_login_css = array(
    "assets/css/site/modal.css",
    "assets/css/site/base-conf.css",
    "assets/css/site/base.css",
);
$base_login_js = array(
    "assets/js/site/modal.js",
    "assets/js/site/layout_function.js",
    "assets/js/site/validation_base.js",
);
echo minify_css_js('css', $base_login_css, 'base_login.css');
echo minify_css_js('js', $base_login_js, 'base_login.js');
?>
