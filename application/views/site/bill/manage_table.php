<?php
$record_list_data = empty($record_list_data) ? [] : $record_list_data;
$offset = empty($offset) ? 0 : $offset;
?>
<div class="">
    <table class="table no-margin-bottom">
        <thead class="thead">
        <tr>
            <th class="wid-50">STT</th>
            <th>Thời gian thanh toán</th>
            <th>Tổng tiền (A = B - C)</th>
            <th>Tổng sản phẩm (B)</th>
            <th>Giảm giá (C)</th>
            <th>Trạng thái</th>
            <th>Hình thức thanh toán</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>
</div>
<div class="content-table e_manager_report">
    <table class="table no-margin-top">
        <tbody class="tbody">
        <?php foreach ($record_list_data as $k => $record) { ?>
            <tr class="e_row_report">
                <td class="center wid-50"><?php echo $offset + $k + 1; ?></td>
                <td class="center"><?php echo $record->payment_date; ?></td>
                <td class="align-right"><?php echo number_format($record->total, 0, ",", "."); ?></td>
                <td class="align-right"><?php echo number_format($record->grand_total, 0, ",", "."); ?></td>
                <td class="align-right"><?php echo number_format($record->discount_amount, 0, ",", "."); ?></td>
                <td class="center"><?php echo $record->status == "done" ? "Thành công" : "Chưa thanh toán"; ?></td>
                <td><?php echo $record->payment_method == "cash" ? "Tiền mặt" : "Chuyển khoản"; ?></td>
                <td class="center"><span class="btn-action e_ajax_link" data='{"id":<?php echo $record->id; ?>}' href="<?php echo empty($url_bill_detail) ? "" : $url_bill_detail; ?>">Chi tiết</span></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="load-more">
    <span class="view-more hidden">Đang tải thêm</span>
</div>