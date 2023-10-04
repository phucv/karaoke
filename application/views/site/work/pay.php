<?php
$room = empty($room) ? (object)[] : $room;
$id = isset($room->id) ? $room->id : 0;
$room_name = isset($room->name) ? $room->name : "";
$products = empty($products) ? [] : $products;
?>
<div class="modal-dialog">
    <div class="modal-content e_modal_content pay_content e_pay_content">
        <form data-url="<?php echo isset($url_pay_save) ? $url_pay_save : ''; ?>" class="e_ajax_submit"
              method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" value="<?php echo $id; ?>" name="id">
            <div class="modal-header">
                <span class="header-title">
                    <span>Thanh toán phòng <?php echo $room_name; ?></span>
                </span>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo base_url("assets/images/site/base_manager/icon-remove.png"); ?>">
                </span>
            </div>
            <div class="modal-body">
                <div class="add-form-input">
                    <?php $time_enter = $room->time_enter ? strtotime($room->time_enter) : 0; ?>
                    <div class="form-row">
                        <label class="row-label">Thời gian sử dụng:</label>
                        <div class="row-input">
                            Từ <?php echo date("H:i d/m/Y", strtotime($room->time_enter)); ?> đến <?php echo date("H:i d/m/Y")?>
                        </div>
                    </div>
                    <div class="form-row">
                        <table class="e_bill">
                            <tr class="text-bold">
                                <th>STT</th>
                                <th>Sản phẩm</th>
                                <th>Đơn vị</th>
                                <th>Số lượng</th>
                                <th>Đơn giá (VNĐ)</th>
                                <th>Thành tiền (VNĐ)</th>
                            </tr>
                            <tr class="e_item">
                                <input type="hidden" name="room_id" value="<?php echo $room->id; ?>">
                                <td class="align-center e_stt">1</td>
                                <td>Phòng hát</td>
                                <td>giờ</td>
                                <td><input type="number" name="room_quantity" class="align-right width-100 e_quantity" value="<?php echo round((time() - $time_enter) / 3600, 1)?>"></td>
                                <td><input type="number" name="room_price" class="align-right width-100 e_price" value="<?php echo $room->price; ?>"></td>
                                <td class="e_money align-right"></td>
                            </tr>
                            <tr class="e_total">
                                <td></td>
                                <td colspan="4" class="align-right">Cộng sản phẩm</td>
                                <td class="e_money align-right"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="4" class="align-right">Giảm giá</td>
                                <td><input type="number" name="discount_amount" class="align-right width-100 e_discount"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="4" class="align-right">Tổng</td>
                                <td class="e_total_money align-right"></td>
                            </tr>
                            <tr class="e_item hidden">
                                <input type="hidden" name="product_id[]" class="e_product">
                                <td class="align-center e_stt"></td>
                                <td class="pos-rel"><span class="e_name"></span><i class="material-icons e_remove_product remove_product">close</i></td>
                                <td class="e_unit"></td>
                                <td><input type="number" name="product_quantity[]" class="align-right width-100 e_quantity"></td>
                                <td><input type="number" name="product_price[]" class="align-right width-100 e_price"></td>
                                <td class="e_money align-right"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="add-form-input">
                    <div class="form-row">
                        <label class="row-label">Thêm sản phẩm sử dụng:</label>
                        <div class="row-input">
                            <select class="site-select2 e_chosen_product">
                                <option value=0>Chọn sản phẩm</option>
                                <?php foreach ($products as $product) {
                                    echo "<option value='$product->id' data-name='$product->name' data-unit='$product->unit' data-price='$product->price'>$product->name</option>";
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
    totalService();
</script>
