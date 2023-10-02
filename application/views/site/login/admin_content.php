<!-- start container -->
<div class="sub-container col-form">
    <div class="sub-col-form">
        <div id="admin" class="tab-pane fade in active tabcontent">
            <form method="POST" class="e_ajax_submit" action="<?php echo $login_url; ?>"
                  data-ajax--url="<?php echo $login_url; ?>">
                <div class="title-login text-center" style="margin-top: 25px;"><?php echo get_string("v-admin-login"); ?></div>
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
                        <input class="password" name="password" value="" type="password" placeholder="Password">
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
</div>
<!-- end container -->