<?php
$site_lang = $this->session->userdata('site_lang');
?>
<footer class="cc_block_footer js_block_footer">
    <div class="cc_footer_child_1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="cc_footer_link d-none d-md-flex d-lg-flex d-xl-flex">
                        <ul>
                            <li>
                                <a href="#" class="font-weight-bold">
                                    <?php echo get_string("v-base_layout_main-name_business") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="font-weight-bold">
                                    <?php echo get_string("v-base_layout_main-instructor") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-mobile_app") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-about_us") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-careers") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-blog") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-topics") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-support") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-affiliate") ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="id_footer_lang">
                                <i class="fas fa-globe-americas"></i>
                            </label>
                        </div>
                        <select class="custom-select js_select_language" id="id_footer_lang">
                            <option value="<?php echo site_url('site/lang_switch/switchLanguage/vi'); ?>" <?php if ($site_lang == 'vi') {
                                echo "selected";
                            } ?>>Vietnamese
                            </option>
                            <option value="<?php echo site_url('site/lang_switch/switchLanguage/ja'); ?>" <?php if ($site_lang == 'ja') {
                                echo "selected";
                            } ?>>日本語
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="cc_footer_local d-none d-md-flex d-lg-flex d-xl-flex">

                        <ul>
                            <li>
                                <a href="#" class="font-weight-bold">
                                    <?php echo get_string("v-base_layout_main-home_page") ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('site/lang_switch/switchLanguage/vi'); ?>"
                                   class="<?php if ($site_lang == 'vi') {
                                       echo "active";
                                   } ?>">
                                    Vietnamese
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('site/lang_switch/switchLanguage/ja'); ?>"
                                   class="<?php if ($site_lang == 'ja') {
                                       echo "active";
                                   } ?>">
                                    日本語
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    English
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Español
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Français
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Português
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="cc_footer_child_2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="cc_footer_logo">
                        <a class="navbar-brand" href="#">
                            <img
                                    src="<?php echo (!empty($info_company["logo_company"])) ? base_url($info_company["logo_company"]) : base_url("assets/images/site/home_main/logo_coral.png") ?>"
                                    alt=""
                                    class="img-fluid cc_navbar_logo">
                        </a>
                        <span class="cc_footer_copyright d-none d-md-flex d-lg-flex d-xl-flex"><?php echo empty($home_config->copyright) ? "Copyright © 2018 Ows, Inc." : $home_config->copyright; ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cc_footer_temps">
                        <ul>
                            <li>
                                <a href="<?php echo site_url("site/policy"); ?>">
                                    <?php echo get_string("v-base_layout_main-terms") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-privacy") ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url("site/policy"); ?>">
                                    <?php echo get_string("v-base_layout_main-policy") ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <?php echo get_string("v-base_layout_main-intellectual") ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <span
                            class="cc_footer_copyright d-block d-sm-none"><?php echo empty($home_config->copyright) ? "Copyright © 2018 Ows, Inc." : $home_config->copyright; ?></span>
                </div>
            </div>
        </div>
    </div>
</footer>
