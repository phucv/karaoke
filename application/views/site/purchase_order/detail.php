<?php
$purchase_order = empty($purchase_order) ? (object)[] : $purchase_order;
$purchase_order_detail = empty($purchase_order_detail) ? [] : $purchase_order_detail;
?>
<div class="modal-dialog">
    <div class="modal-content e_modal_content add_purchase">
            <div class="modal-header">
                <span class="header-title">
                    <span>Chi tiết nhập hàng</span>
                </span>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo base_url("assets/images/site/base_manager/icon-remove.png"); ?>">
                </span>
            </div>
            <div class="modal-body">
                <div class="add-form-input">
                    <div class="form-row">
                        <table>
                            <tr class="text-bold">
                                <th>STT</th>
                                <th>Tên hàng</th>
                                <th>Mã hàng</th>
                                <th>Đơn vị</th>
                                <th>Số lượng</th>
                                <th>Giá mua (VNĐ)</th>
                                <th>Giảm giá (VNĐ)</th>
                                <th>Thành tiền (VNĐ)</th>
                            </tr>
                            <?php foreach ($purchase_order_detail as $k => $detail) {
                                $product = json_decode($detail->product_info); ?>
                                <tr>
                                    <td class="align-center"><?php echo $k + 1; ?></td>
                                    <td><?php echo !empty($product->name) ? $product->name : ""; ?></td>
                                    <td><?php echo !empty($product->code) ? $product->code : ""; ?></td>
                                    <td><?php echo !empty($product->unit) ? $product->unit : ""; ?></td>
                                    <td class="align-right"><?php echo number_format($detail->quantity, 0, ',', '.'); ?></td>
                                    <td class="align-right"><?php echo number_format($detail->purchase_price, 0, ',', '.'); ?></td>
                                    <td class="align-right"><?php echo number_format($detail->discount_amount, 0, ',', '.'); ?></td>
                                    <td class="align-right"><?php echo number_format($detail->value_total, 0, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td colspan="6" class="align-right">Tổng tiền hàng:</td>
                                <td class="align-right"><?php echo number_format($purchase_order->grand_total, 0, ',', '.')?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="6" class="align-right">Giảm giá:</td>
                                <td class="align-right"><?php echo number_format($purchase_order->discount_amount, 0, ',', '.')?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="6" class="align-right">Cần trả nhà cung cấp:</td>
                                <td class="align-right"><?php echo number_format($purchase_order->total, 0, ',', '.')?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-row row-btn">
                    <span class="btn btn-cancel" data-dismiss="modal">
                        <i class="material-icons">close</i>
                        <span>Hủy</span>
                    </span>
                </div>
            </div>
    </div>
</div>
<script>
    initSelect2();
</script>
