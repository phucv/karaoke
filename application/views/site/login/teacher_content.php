<!-- start container -->
<div class="sub-container col-form">
    <div class="sub-col-form">
        <ul class="tabs">
            <li class="tab-left">
                <a class="tablinks cursor-pointer" href="<?php echo site_url('student') ?>"><?php echo get_string("v-login-tab-student"); ?></a>
            </li>
            <li class="tab-right">
                <a class="tablinks active cursor-pointer" href="<?php echo site_url('teacher') ?>"><?php echo get_string("v-login-tab-teacher"); ?></a>
            </li>
        </ul>
        <div id="teacher" class="student-tab tab-pane fade in tabcontent" style="border-top: 1px solid #f1f1f1;">
            <form method="POST" class="e_ajax_submit" action="<?php echo $login_url; ?>"
                  data-ajax--url="<?php echo $login_url; ?>">
                <div class="title-login text-center"><?php echo get_string("v-login2"); ?></div>
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
                        <img class="peek-password" src="<?php echo base_url('assets/images/site/icon-eye.svg') ?>"
                             data-show="0" title="<?php echo get_string("v-title-show-pass"); ?>">
                    </div>
                    <div class="remember-checkbox-wrapper">
                        <label><input type="checkbox" class="checkbox remember_me" name="remember"><?php echo get_string("v-remember-me"); ?></label>
                    </div>
                    <button class="login-btn login cursor-pointer" name="login">
                        <span class="text-center text-btn-login"><?php echo get_string("v-login"); ?></span>
                    </button>
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
    <a class="login_manager" href="<?php echo site_url("manager")?>"><?php echo get_string("v-login-is-manager"); ?></a>
</div>
<!-- end container -->