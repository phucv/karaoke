<?php
$id = isset($record_data->id) ? $record_data->id : "";
$callback = empty($callback) ? "" : $callback;
?>
<div class="modal-dialog">
    <div class="modal-content e_modal_content">
        <form data-url="<?php echo isset($url_save_data) ? $url_save_data : ''; ?>" class="e_ajax_customer_submit"
              method="post" enctype="multipart/form-data" novalidate data-callback="<?php echo $callback; ?>">
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
                        <label class="row-label">Tên nhà cung cấp<span class="red">*</span>:</label>
                        <div class="row-input">
                            <input type="text" name="name" value="<?php echo empty($record_data->name) ? "" : $record_data->name; ?>" required
                                   placeholder="Tên nhà cung cấp">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Mã nhà cung cấp:</label>
                        <div class="row-input">
                            <input type="text" name="code" value="<?php echo empty($record_data->code) ? "" : $record_data->code; ?>"
                                   placeholder="Mã nhà cung cấp">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Số điện thoại:</label>
                        <div class="row-input">
                            <input type="text" name="phone" value="<?php echo empty($record_data->phone) ? "" : $record_data->phone; ?>"
                                   placeholder="Số điện thoại">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Email:</label>
                        <div class="row-input">
                            <input type="text" name="email" value="<?php echo empty($record_data->email) ? "" : $record_data->email; ?>"
                                   placeholder="Email">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Địa chỉ:</label>
                        <div class="row-input">
                            <input type="text" name="address" value="<?php echo empty($record_data->address) ? "" : $record_data->address; ?>"
                                   placeholder="Địa chỉ">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Mã số thuế:</label>
                        <div class="row-input">
                            <input type="text" name="tax_code" value="<?php echo empty($record_data->tax_code) ? "" : $record_data->tax_code; ?>"
                                   placeholder="Mã số thuế">
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
    do_ajax_submit($(".e_ajax_customer_submit"));
</script>
