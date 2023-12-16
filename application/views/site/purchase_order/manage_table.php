<?php
$record_list_data = empty($record_list_data) ? [] : $record_list_data;
$offset = empty($offset) ? 0 : $offset;
?>
<div class="">
    <table class="table no-margin-bottom">
        <thead class="thead">
        <tr>
            <th class="wid-50">STT</th>
            <th>Mã nhập hàng</th>
            <th>Thời gian</th>
            <th>Cần trả NCC (A = B - C)</th>
            <th>Tổng tiền hàng (B)</th>
            <th>Giảm giá (C)</th>
            <th>Trạng thái</th>
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
                <td class="center"><?php echo $record->code; ?></td>
                <td class="center"><?php echo $record->payment_date; ?></td>
                <td class="align-right"><?php echo number_format($record->total, 0, ",", "."); ?></td>
                <td class="align-right"><?php echo number_format($record->grand_total, 0, ",", "."); ?></td>
                <td class="align-right"><?php echo number_format($record->discount_amount, 0, ",", "."); ?></td>
                <td class="center"><?php echo $record->status == "done" ? "Đã nhập hàng" : "Chưa nhập hàng"; ?></td>
                <td class="center"><span class="btn-action cursor-pointer e_ajax_link" data='{"id":<?php echo $record->id; ?>}' href="<?php echo empty($url_order_detail) ? "" : $url_order_detail; ?>">Chi tiết</span></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="load-more">
    <span class="view-more hidden">Đang tải thêm</span>
</div>