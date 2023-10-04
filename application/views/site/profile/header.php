<!--Load CSS-->
<?php
echo minify_css_js('css', array(
    'assets/css/site/profile/profile.css',
    'assets/css/site/profile/m_profile.css',
), 'header_profile.css');
echo minify_css_js('js', 'assets/plugins-bower/jquery-form/jquery.form.js', 'jquery.form.js');
?>