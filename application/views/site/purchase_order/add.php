<?php
$products = empty($products) ? [] : $products;
$product_parent = empty($product_parent) ? [] : $product_parent;
?>
<div class="modal-dialog width-80">
    <div class="modal-content e_modal_content add_purchase">
        <form data-url="<?php echo isset($url_save_data) ? $url_save_data : ''; ?>" class="e_ajax_submit"
              method="post" enctype="multipart/form-data" novalidate>
            <div class="modal-header">
                <span class="header-title">
                    <span><?php echo empty($title) ? "" : $title; ?></span>
                </span>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo base_url("assets/images/site/base_manager/icon-remove.png"); ?>">
                </span>
            </div>
            <div class="modal-body d-flex">
                <div class="width-70">
                    <div class="add-form-input">
                        <div class="form-row">
                            <div class="row-input">
                                <select class="site-select2 e_product" data-placeholder="Chọn sản phẩm">
                                    <option></option>
                                    <?php foreach ($products as $product) {
                                        if ($product->parent_id && !empty($product_parent[$product->parent_id])) {
                                            $product->purchase_price = $product->unit_value * $product_parent[$product->parent_id]->purchase_price;
                                            $product->quantity = round($product_parent[$product->parent_id]->quantity / $product->unit_value, 2);
                                        }
                                        $purchase_price = empty($product->purchase_price) ? 0 : number_format($product->purchase_price, 0, ',', '.');
                                        echo "<option value='$product->id' data-name='$product->name' data-code='$product->code' data-unit='$product->unit' data-purchase_price='$product->purchase_price'>$product->name - " . ($product->unit ? $product->unit . ": " : "") . "$purchase_price (Tồn: $product->quantity)</option>";
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <table class="e_list_product product width-100">
                                <thead>
                                <tr class="text-bold">
                                    <th>STT</th>
                                    <th>Tên hàng</th>
                                    <th>Mã hàng</th>
                                    <th>Đơn vị</th>
                                    <th>Số lượng</th>
                                    <th>Giá mua</th>
                                    <th>Giảm giá</th>
                                    <th>Thành tiền</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody >
                                <tr class="e_item_purchase hidden">
                                    <input type="hidden" class="e_product_id" name="product_id[]">
                                    <td class="e_stt"></td>
                                    <td class="e_product_name"></td>
                                    <td class="e_product_code"></td>
                                    <td class="e_product_unit"></td>
                                    <td class="e_product_quantity"><input type="number" name="quantity[]" class="align-right width-95" min="0"></td>
                                    <td class="e_product_purchase_price"><input type="number" name="purchase_price[]" class="align-right width-95" min="0"></td>
                                    <td class="e_discount_amount"><input type="number" name="discount_amount[]" class="align-right width-95" min="0" value="0"></td>
                                    <td class="e_value_total"><input type="number" name="value_total[]" class="align-right width-95" min="0" value="0"></td>
                                    <td><i class="material-icons e_remove_purchase hover remove_purchase cursor-pointer">close</i></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="width-30 col-right">
                    <div class="add-form-input">
                        <div class="form-row text-value">
                            <label class="row-label width-55">Tổng tiền hàng:</label>
                            <div class="row-input width-40">
                                <div class="e_grand_total align-right">0</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="row-label width-55">Giảm giá:</label>
                            <div class="row-input width-40">
                                <input type="number" class="e_discount_amount_total align-right" value="0" name="discount_amount_total">
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="row-label width-55">Cần trả nhà cung cấp:</label>
                            <div class="row-input width-40">
                                <input type="text" class="e_total align-right total" value="0">
                            </div>
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
