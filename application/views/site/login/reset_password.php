<div class="container width-80 reset-password">
    <div class="box-container">
        <div class="box-center">
            <form method="POST">
                <h2><?php echo get_string("v-login-reset-pass"); ?></h2>
                <div id="pass"
                     data-url="<?php echo base_url("site/login/update_password/" . $user_id); ?>"
                     style="display: flex;">
                    <div style="margin: auto;text-align: right;padding: 8px 10px 0px 0px;;width: 30%"><?php echo get_string("v-login-new-pass"); ?></div>
                    <span class="box-input">
                    <input style="width: 86%;padding-right: 30px" class="right-input form-control password" type="password" id="password" name="password"
                           placeholder="<?php echo get_string("v-login-type-new-pass"); ?>" minlength="6" required>
                    <img class="peek-password" src="<?php echo base_url('assets/images/site/icon-eye.svg') ?>"
                         data-show="0" title="<?php echo get_string("v-title-show-pass"); ?>">
                    <label class="error-message"></label>
                </span>
                </div>
                <div id="re-pass"
                     data-url="<?php echo base_url("site/login/update_password/" . $user_id); ?>"
                     style="display: flex;">
                    <div style="margin: auto;text-align: right;padding: 8px 10px 0px 0px;;width: 30%"><?php echo get_string("v-login-retype-pass"); ?></div>
                    <span class="box-input">
                    <input style="width: 86%;padding-right: 30px" class="right-input form-control password" type="password" id="re-password" name="re-password"
                           equalTo="#password"
                           placeholder="<?php echo get_string("v-login-retype-pass"); ?>" minlength="6" required>
                    <img class="peek-password" src="<?php echo base_url('assets/images/site/icon-eye.svg') ?>"
                         data-show="0" title="<?php echo get_string("v-title-show-pass"); ?>">
                    <label class="error-message"></label>
                </span>
                </div>
                <button class="btn-submit" id="request_reset_password"
                        class=""><?php echo get_string("v-login-add-new-pass"); ?></button>
            </form>
        </div>
    </div>
</div>