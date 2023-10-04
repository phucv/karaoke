<div class="profile">
    <!-- start container -->
    <div id="myModal" class="modal modal-add-avatar">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <span class="sp_username"><?php if (!empty($user_info->display_name)) {
                        echo $user_info->display_name;
                    } else {
                        echo "Cập nhật ảnh đại diện";
                    } ?></span>
            </div>
            <div class="modal-body">
                <div class="image-wrapper">
                    <div class="file-preview"></div>
                </div>
                <form action="<?php echo $update_profile_url ?>" enctype="multipart/form-data"
                      class="update-avatar form_btn_preview"">
                    <input type="file" id="my-file" name="userfile" accept=".jpg, .png, .jpeg">
                    <div class="button-wrapper">
                        <input type="submit" class="update-profile" value="Lưu">
                        <button type="button"
                                class="cancel btn-action">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="left-menu-wrapper">
        <div class="left-menu">
            <div class="profile-area">
                <img class="profile-img"
                     src="<?php echo !empty($user_info->avatar) ? base_url($user_info->avatar) : base_url("assets/images/site/default-avatar.png"); ?>"
                     alt="">
                <button class="profile-update-btn">Cập nhật ảnh</button>
            </div>
            <a class="form-switch active"
               data-form="1">Thông tin chung</a>
            <a class="form-switch"
               data-form="2">Đổi mật khẩu</a>
        </div>
    </div>
    <div class="right-content-wrapper">
        <div class="right-content">
            <div id="general-info" class="profile-tab fade in active">
                <form method="POST" id="form1" action="<?php echo $update_general_info_url ?>">
                    <div class="form-wrapper">
                        <div class="row row_title">Thông tin chung</div>
                        <div class="row">
                            <label class="left-label"
                                   for="display-name">Tên hiển thị</label>
                            <input class="right-input" type="text" name="display_name" id="display-name"
                                   placeholder="Tên hiển thị"
                                   value="<?php echo $user_info->display_name; ?>">
                        </div>
                        <div class="row">
                            <label class="left-label" for="username">Tên đăng nhập<span class="text-red">*</span></label>
                            <div class="inline-block text_disable"><?php echo $user_info->username; ?></div>
                        </div>
                        <div class="row row_sex">
                            <label class="left-label">Giới tính</label>
                            <?php
                            if (isset($sex_list)) {
                                foreach ($sex_list as $s_key => $sex) {
                                    $checked = "";
                                    if ($s_key == $user_info->sex) {
                                        $checked = "checked";
                                    }
                                    echo '<label class="lb_sex"><input type="radio" name="sex" value="' . $s_key . '" ' . $checked . '> ' . $sex . '&nbsp&nbsp</label>';
                                }
                            }
                            ?>
                        </div>
                        <div class="row">
                            <label class="left-label"
                                   for="phone">Số điện thoại</label>
                            <input class="right-input" isPhoneNumber="true" name="phone" id="phone"
                                   placeholder="Số điện thoại"
                                   value="<?php echo $user_info->phone; ?>">
                            <div class="error-message-wrapper" style="display: none;">
                                <p class="error-message"></p>
                            </div>
                        </div>
                    </div>
                    <button class="save-button" data-form="1">Lưu</button>
                </form>
            </div>
            <div id="change-password" class="profile-tab fade in active">
                <form method="POST" id="form2" style="display:none;" action="<?php echo $update_password_url ?>">
                    <div class="form-wrapper">
                        <div class="row row_title">Đổi mật khẩu</div>
                        <div class="row">
                            <label for="username2"
                                   class="left-label">Tài khoản</label>
                            <div class="inline-block text_disable"><?php echo $user_info->username; ?></div>
                        </div>
                        <div class="row">
                            <label class="left-label"
                                   for="old-pass">Mật khẩu cũ</label>
                            <input required class="right-input align-top" type="password" name="old-pass"
                                   placeholder="Mật khẩu cũ" id="old-pass"
                                   minlength="6">
                            <div class="error-message-wrapper" style="display: none;">
                                <p class="error-message"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="left-label"
                                   for="new-pass">Mật khẩu mới</label>
                            <input required class="right-input align-top" type="password" name="new-pass"
                                   placeholder="Mật khẩu mới" id="new-pass"
                                   minlength="6">
                            <div class="error-message-wrapper" style="display: none;">
                                <p class="error-message"></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="left-label"
                                   for="new-pass-repeat">Nhập lại mật khẩu mới</label>
                            <input required class="right-input align-top" type="password" name="new-pass-repeat"
                                   placeholder="Nhập lại mật khẩu mới"
                                   id="new-pass-repeat" equalTo="#new-pass"
                                   minlength="6">
                            <div class="error-message-wrapper" style="display: none;">
                                <p class="error-message"></p>
                            </div>
                        </div>
                    </div>
                    <button class="save-button" data-form="2">Lưu</button>
                </form>
            </div>
            <p class="last-update">
                <?php echo "Chỉnh sửa lần cuối được thực hiện vào" . date('d/m/Y h:i:s a', strtotime($user_info->lastest_update_on));?>
            </p>
        </div>
    </div>
</div>