<div class="container width-80">
    <div class="box-container">
        <form class="form-forgot-pwd" method="post" action="">
            <div class="box-center">
                <h2><?php echo get_string("v-login-update-password-title"); ?></h2>
                <div id="email" data-role="<?php echo $role; ?>"
                     data-url="<?php echo base_url("site/login/send_request_forgot_password"); ?>">
                    <p><?php echo get_string("v-login-update-password-guide"); ?></p>
                    <input class="form-control email_user" required type="email" id="input" name="email" placeholder="<?php get_string("v-login-type-email"); ?>">
                </div>
                <div style="display: none;" id="message"></div>
                <div class="both"></div>
                <button class="btn-submit" id="request_forgot_password"><?php echo get_string("v-login-send"); ?></button>
            </div>
        </form>
    </div>
</div>
<span class="hidden alert-no-email"><?php echo get_string("v-login-no-email"); ?></span>