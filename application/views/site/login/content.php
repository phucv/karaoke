<!-- start container -->
<div class="container container-login text-center">
    <div class="sub-container col-form">
        <div class="sub-col-form">
            <div class="student-tab tab-pane fade in tabcontent">
                <form method="POST" class="e_ajax_submit" action="<?php echo $login_url; ?>"
                      data-ajax--url="<?php echo $login_url; ?>">
                    <div class="title-login text-center"
                         style="margin-top: 25px;">Đăng nhập - Quản lý</div>
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
                                 data-show="0" title="click hiển thị mật khẩu">
                        </div>
                        <div class="remember-checkbox-wrapper">
                            <label><input type="checkbox" class="checkbox remember_me"
                                          name="remember">Ghi nhớ</label>
                        </div>
                        <button class="login-btn login cursor-pointer" name="login">
                            <span class="text-center text-btn-login">ĐĂNG NHẬP</span>
                        </button>
                    </div>
                    <div class="text-center forget-pass">
                        <a id="forgot" href="<?php echo base_url('site/login/forgot_password/') ?>"></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end container -->