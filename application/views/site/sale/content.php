<?php
echo minify_css_js('css', array(
    'assets/css/site/modal.css',
    'assets/css/site/base_manager/manage.css',
    'assets/css/site/base_manager/table.css',
    'assets/css/site/sale/sale.css',
), 'sale.css');
echo minify_css_js('js', 'assets/js/site/purchase_order/purchase_order.js', 'purchase_order.js');
$products = empty($products) ? [] : $products;
?>

<form data-url="<?php echo isset($url_save_data) ? $url_save_data : ''; ?>" class="e_ajax_submit"
      method="post" enctype="multipart/form-data" novalidate>
    <div class="d-flex box-content add_sale">
        <div class="width-70">
            <div class="add-form-input">
                <div class="row-input">
                    <select class="site-select2 e_product" data-placeholder="Chọn sản phẩm">
                        <option></option>
                        <?php foreach ($products as $product) {
                            if ($product->parent_id && !empty($product_parent[$product->parent_id])) {
                                $product->quantity = round($product_parent[$product->parent_id]->quantity / $product->unit_value, 2);
                            }
                            $price = empty($product->price) ? 0 : number_format($product->price, 0, ',', '.');
                            echo "<option value='$product->id' data-name='$product->name' data-code='$product->code' data-unit='$product->unit' data-purchase_price='$product->price'>$product->name - " . ($product->unit ? $product->unit . ": " : "") . "$price (Tồn: $product->quantity)</option>";
                        } ?>
                    </select>
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
                            <th>Giá bán</th>
                            <th>Thành tiền</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="e_item_purchase hidden">
                            <input type="hidden" class="e_product_id" name="product_id[]">
                            <td class="e_stt"></td>
                            <td class="e_product_name"></td>
                            <td class="e_product_code"></td>
                            <td class="e_product_unit"></td>
                            <td class="e_product_quantity"><input type="number" name="quantity[]"
                                                                  class="align-right width-95" min="0"></td>
                            <td class="e_product_purchase_price"><input type="number" name="price[]" class="align-right width-95"
                                                               min="0"></td>
                            <td class="e_value_total"><input type="number" readonly class="align-right width-95 value_total" min="0" value="0"></td>
                            <td><i class="material-icons e_remove_purchase remove_sale hover cursor-pointer">close</i>
                            </td>
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
                        <input type="number" class="e_discount_amount_total align-right" value="0"
                               name="discount_amount_total">
                    </div>
                </div>
                <div class="form-row">
                    <label class="row-label width-55">Khách cần trả:</label>
                    <div class="row-input width-40">
                        <input type="number" class="e_total align-right total" value="0" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <label class="row-label width-55">Khách thanh toán:</label>
                    <div class="row-input width-40">
                        <input type="number" class="e_customer_pay align-right" value="0">
                    </div>
                </div>
                <div class="form-row text-value">
                    <label class="row-label width-55">Tiền thừa trả khách:</label>
                    <div class="row-input width-40">
                        <div class="e_refund_customer align-right">0</div>
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
<script>
    do_ajax_submit($(".e_ajax_submit"));
</script>