<?php
$id = isset($record_data->id) ? $record_data->id : "";
$roles = empty($roles) ? [] : $roles;
$role_id = empty($record_data->role_id) ? 2 : $record_data->role_id;
?>
<div class="modal-dialog">
    <div class="modal-content e_modal_content">
        <form data-url="<?php echo isset($url_save_data) ? $url_save_data : ''; ?>" class="e_ajax_submit"
              method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" value="<?php echo $id; ?>" name="id">
            <div class="modal-header">
                <span class="header-title">
                    <span><?php echo empty($title) ? "" : $title; ?></span>
                </span>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo base_url("assets/images/site/base_manager/icon-remove.png"); ?>">
                </span>
            </div>
            <div class="modal-body">
                <div class="add-form-input">
                    <div class="form-row">
                        <label class="row-label">Tên đăng nhập<span class="red">*</span>:</label>
                        <div class="row-input">
                            <input type="text" name="username" value="<?php echo empty($record_data->username) ? "" : $record_data->username; ?>" required
                                   placeholder="Tên đăng nhập">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Quyền<span class="red">*</span>:</label>
                        <div class="row-input">
                            <select class="site-select2" name="role_id" required>
                                <?php foreach ($roles as $role) {
                                    $selected = $role_id == $role->id ? "selected" : "";
                                    echo "<option $selected value='$role->id'>$role->name</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Tên hiển thị:</label>
                        <div class="row-input">
                            <input type="text" name="display_name" value="<?php echo empty($record_data->display_name) ? '' : $record_data->display_name; ?>"
                                   placeholder="Tên hiển thị">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Mật khẩu:</label>
                        <div class="row-input">
                            <input type="password" name="password" placeholder="Mật khẩu">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Số điện thoại:</label>
                        <div class="row-input">
                            <input type="text" name="phone" value="<?php echo empty($record_data->phone) ? '' : $record_data->phone; ?>"
                                   placeholder="Số điện thoại">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-row row-btn">
                    <button class="btn btn-save e-btn-save" type="submit">
                        <img src="<?php echo base_url("assets/images/site/base_manager/btn-save.png"); ?>">
                        <span>Lưu</span>
                    </button>
                    <span class="btn btn-cancel" data-dismiss="modal">
                        <i class="material-icons">close</i>
                        <span>Hủy</span>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    do_ajax_submit($(".e_ajax_submit"));
    initSelect2();
</script>
<style>
    .select2-container--default .select2-selection--single {
        width: 170px;
    }
</style>
