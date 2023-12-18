<?php
$id = isset($record_data->id) ? $record_data->id : "";
$groups = empty($groups) ? [] : $groups;
$child = empty($child) ? [] : $child;
?>
<div class="modal-dialog">
    <div class="modal-content e_modal_content add_product">
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
                        <label class="row-label">Tên <span class="red">*</span>:</label>
                        <div class="row-input">
                            <input type="text" name="name"
                                   value="<?php echo empty($record_data->name) ? "" : $record_data->name; ?>" required
                                   placeholder="Tên sản phẩm">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Nhóm hàng:</label>
                        <div class="row-input">
                            <select class="site-select2" data-placeholder="Chọn nhóm hàng" name="group_id">
                                <option></option>
                                <?php
                                $group_id = empty($record_data->group_id) ? 0 : $record_data->group_id;
                                foreach ($groups as $group) {
                                    $select = $group_id == $group->id ? "selected" : "";
                                    echo "<option value='$group->id' $select>$group->name</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Giá mua:</label>
                        <div class="row-input">
                            <input type="number" name="purchase_price"
                                   value="<?php echo empty($record_data->purchase_price) ? 0 : $record_data->purchase_price; ?>"
                                   placeholder="Giá mua">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Giá bán:</label>
                        <div class="row-input">
                            <input type="number" name="price"
                                   value="<?php echo empty($record_data->price) ? 0 : $record_data->price; ?>"
                                   placeholder="Giá bán">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Tồn kho:</label>
                        <div class="row-input">
                            <input type="number" name="quantity"
                                   value="<?php echo empty($record_data->quantity) ? 0 : $record_data->quantity; ?>"
                                   placeholder="Giá bán">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Mã sản phẩm:</label>
                        <div class="row-input">
                            <input type="text" name="code"
                                   value="<?php echo empty($record_data->code) ? '' : $record_data->code; ?>"
                                   placeholder="Mã sản phẩm">
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="row-label">Mã vạch:</label>
                        <div class="row-input">
                            <input type="text" name="barcode"
                                   value="<?php echo empty($record_data->barcode) ? '' : $record_data->barcode; ?>"
                                   placeholder="Mã vạch">
                        </div>
                    </div>
                    <fieldset>
                        <legend>Đơn vị bán:</legend>
                        <div class="form-row">
                            <label class="row-label">Đơn vị bán thấp nhất:</label>
                            <div class="row-input">
                                <input type="text" name="unit" class="e_unit_base"
                                       value="<?php echo empty($record_data->unit) ? '' : $record_data->unit; ?>"
                                       placeholder="chai, thùng, gói, đĩa, lon....">
                            </div>
                        </div>
                        <div class="form-row">
                            <table class="e_unit unit <?php echo count($child) ? "" : "hidden"; ?>">
                                <tr class="text-bold">
                                    <th>Tên đơn vị</th>
                                    <th>Giá trị quy đổi</th>
                                    <th>Giá bán</th>
                                    <th>Mã hàng</th>
                                    <th>Mã vạch</th>
                                    <th></th>
                                </tr>
                                <?php foreach ($child as $value) { ?>
                                    <tr class="e_item">
                                        <input type="hidden" name="unit_id[]" value="<?php echo $value->id;?>">
                                        <td><input type="text" name="unit_name[]" class="align-right width-95" value="<?php echo $value->unit;?>"></td>
                                        <td><input type="number" name="unit_value[]" class="align-right width-95" min="1" value="<?php echo $value->unit_value;?>"></td>
                                        <td><input type="number" name="unit_price[]" class="align-right width-95" min="1" value="<?php echo $value->price;?>"></td>
                                        <td><input type="text" name="unit_code[]" class="align-right width-95" value="<?php echo $value->code;?>"></td>
                                        <td><input type="text" name="unit_barcode[]" class="align-right width-95" value="<?php echo $value->barcode;?>"></td>
                                        <td><i class="material-icons e_remove_unit remove_unit">close</i></td>
                                    </tr>
                                <?php } ?>
                                <tr class="e_item hidden">
                                    <input type="hidden" name="unit_id[]">
                                    <td><input type="text" name="unit_name[]" class="align-right width-95"></td>
                                    <td><input type="number" name="unit_value[]" class="align-right width-95" min="1" value="1"></td>
                                    <td><input type="number" name="unit_price[]" class="align-right width-95" min="1"></td>
                                    <td><input type="text" name="unit_code[]" class="align-right width-95"></td>
                                    <td><input type="text" name="unit_barcode[]" class="align-right width-95"></td>
                                    <td><i class="material-icons e_remove_unit remove_unit">close</i></td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-row"><span class="e_add_unit cursor-pointer hover add_unit"><i class="material-icons">add</i>Thêm đơn vị bán</span></div>
                    </fieldset>
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