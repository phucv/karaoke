<?php
$room = empty($room) ? (object)[] : $room;
$rooms = empty($rooms) ? [] : $rooms;
?>
<div class="modal-dialog">
    <div class="modal-content e_modal_content">
        <form data-url="<?php echo isset($url_change_room_save) ? $url_change_room_save : ''; ?>" class="e_ajax_submit"
              method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" value="<?php echo $room->id; ?>" name="id">
            <div class="modal-header">
                <span class="header-title">
                    <span>Thay đổi phòng <?php echo $room->name; ?></span>
                </span>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo base_url("assets/images/site/base_manager/icon-remove.png"); ?>">
                </span>
            </div>
            <div class="modal-body">
                <div class="add-form-input">
                    <div class="form-row">
                        <label class="row-label">Chọn phòng<span class="red">*</span>:</label>
                        <div class="row-input">
                            <select class="site-select2" name="room_change_id" required data-placeholder="Chọn phòng muốn chuyển">
                                <option></option>
                                <?php foreach ($rooms as $r) {
                                    echo "<option value='$r->id'>$r->name</option>";
                                } ?>
                            </select>
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
