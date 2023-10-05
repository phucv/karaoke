<?php
$bill = empty($bill) ? (object)[] : $bill;
$bill_details = empty($bill_details) ? [] : $bill_details;
$products = empty($products) ? [] : $products;
?>
<div class="modal-dialog">
    <div class="modal-content e_modal_content pay_content">
            <div class="modal-header">
                <span class="header-title">
                    <span>Chi tiết hoá đơn</span>
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
                                <th>Sản phẩm</th>
                                <th>Đơn vị</th>
                                <th>Số lượng</th>
                                <th>Đơn giá (VNĐ)</th>
                                <th>Thành tiền (VNĐ)</th>
                            </tr>
                            <?php foreach ($bill_details as $k => $detail) { ?>
                                <tr>
                                    <td class="align-center"><?php echo $k + 1; ?></td>
                                    <td><?php echo !empty($products[$detail->product_id]->name) ? $products[$detail->product_id]->name : "Phòng hát"; ?></td>
                                    <td><?php echo !empty($products[$detail->product_id]->unit) ? $products[$detail->product_id]->unit : "giờ"; ?></td>
                                    <td class="align-right"><?php echo empty($products[$detail->product_id]->name) ? number_format($detail->quantity, 1, ',', '.') : (int)$detail->quantity; ?></td>
                                    <td class="align-right"><?php echo number_format($detail->price, 0, ',', '.'); ?></td>
                                    <td class="align-right"><?php echo number_format($detail->value_total, 0, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td colspan="4" class="align-right">Cộng sản phẩm</td>
                                <td class="align-right"><?php echo number_format($bill->grand_total, 0, ',', '.')?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="4" class="align-right">Giảm giá</td>
                                <td class="align-right"><?php echo number_format($bill->discount_amount, 0, ',', '.')?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="4" class="align-right">Tổng</td>
                                <td class="align-right"><?php echo number_format($bill->total, 0, ',', '.')?></td>
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
