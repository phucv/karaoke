<?php
$id = isset($record_data->id) ? $record_data->id : "";
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
                        <label class="row-label">Tên danh mục<span class="red">*</span>:</label>
                        <div class="row-input">
                            <input type="text" name="name" value="<?php echo empty($record_data->name) ? "" : $record_data->name; ?>" required
                                   placeholder="Tên danh mục">
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
</script>
