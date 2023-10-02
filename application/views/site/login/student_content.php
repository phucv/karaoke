<!-- start container -->
<div class="sub-container col-form">
    <div class="sub-col-form">
        <ul class="tabs">
            <li class="tab-left">
                <a class="tablinks active cursor-pointer" href="<?php echo site_url('student') ?>"><?php echo get_string("v-login-tab-student"); ?></a>
            </li>
            <li class="tab-right">
                <a class="tablinks cursor-pointer" href="<?php echo site_url('teacher') ?>"><?php echo get_string("v-login-tab-teacher"); ?></a>
            </li>
        </ul>
        <div id="student" class="tab-pane fade in active tabcontent" style="border-top: 1px solid #f1f1f1;">
            <form method="POST" class="e_ajax_submit" action="<?php echo $login_url; ?>"
                  data-ajax--url="<?php echo $login_url; ?>">
                <div class="title-login text-center"><?php echo get_string("v-login2"); ?></div>
                <div class="has-error">
                    <?php if ($this->session->flashdata('message')) {
                        echo $this->session->flashdata('message');
                    } ?>
                </div>
                <div class="text-center">
                    <div class="email-group">
                        <div class="header-input"><img class="icon-input"
                                                       src="<?php echo base_url('assets/images/site/icon-email.svg'); ?>">
                        </div>
                        <input class="username" name="username" value="" type="text" placeholder="Username">
                    </div>
                    <div class="password-group">
                        <div class="header-input-pwd"><img class="icon-input"
                                                           src="<?php echo base_url('assets/images/site/icon-lock.svg') ?>">
                        </div>
                        <input class="password" name="password" type="password" value="" placeholder="Password">
                        <img class="peek-password"
                             src="<?php echo base_url('assets/images/site/icon-eye.svg') ?>"
                             data-show="0" title="<?php echo get_string("v-title-show-pass"); ?>">
                    </div>
                    <div class="remember-checkbox-wrapper">
                        <label><input type="checkbox" class="checkbox remember_me" name="remember"><?php echo get_string("v-remember-me"); ?></label>
                    </div>
                    <button class="login-btn login cursor-pointer" name="login">
                        <span class="text-center text-btn-login"><?php echo get_string("v-login"); ?></span>
                    </button>
                    <div class="tablet-login-alternate">
                        <hr>
                        <p><?php echo get_string("v-login-other"); ?></p>
                        <a class="login-w-gg" href="<?php echo base_url('site/google_api/index/student') ?>"
                           title="">
                            <div>
                                <span>
                                <img class="logo-gg" src="<?php echo base_url('assets/images/site/google.svg') ?>">
                                </span>
                                <span class="text-login-gg"> Google</span>
                            </div>
                        </a>
                    </div>
                    <a class="login-w-gg" id="google"
                       href="<?php echo base_url('site/google_api/index/student') ?>">
                        <div>
                            <span>
                                <img class="logo-gg" src="<?php echo base_url('assets/images/site/google.svg') ?>">
                            </span>
                            <span class="text-login-gg"><?php echo get_string("v-login-with-google"); ?></span>
                        </div>
                    </a>
                </div>
                <div class="text-center forget-pass">
                    <a id="forgot" href="<?php echo base_url('site/login/forgot_password/' . $role) ?>"
                       title="<?php echo get_string("v-title-request-password"); ?>">
                        <p><?php echo get_string("v-request-password"); ?></p>
                    </a>
                </div>
            </form>
        </div>
    </div>
    <a class="login_manager" href="<?php echo site_url("manager") ?>"><?php echo get_string("v-login-is-manager"); ?></a>
</div>
<!-- end container -->
<script type="text/javascript">
    Object.defineProperty(navigator, 'userAgent', {
        get: function () {
            return 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0)';
        }
    });
</script>
