<span class="content-table">
    <?php if (!empty($record_list_data)) {
        foreach ($record_list_data as $record_data) {
            $id = $record_data->id;
            $status = isset($record_data->status) ? $record_data->status : 0;
            $row_name = $record_data->name;
            ?>
            <span class="content-row">
                <span class="content-col col-info">
                    <span class="info-name <?php echo $status ? "used" : ""; ?>" title="<?php echo $row_name; ?>">
                        <?php echo $row_name;
                        echo $status ? " (Đang sử dụng từ " . date("H:i d/m/Y", strtotime($record_data->time_enter)) . ")" : ""; ?>
                    </span>
                    <span class="info-other">
                        <span class="info-other-left">
                            <span class="info-topic">
                                <i class="material-icons">local_offer</i>
                                <span>
                                    <b>Giá (1h): </b><?php echo empty($record_data->price) ? 0 : number_format($record_data->price, 0, ",", "."); ?> (VNĐ)
                                </span>
                            </span>
                            <span class="info-topic">
                                <i class="material-icons">local_offer</i>
                                <span>
                                    <b>Sức chứa: </b><?php echo empty($record_data->capacity) ? "N/A" : $record_data->capacity; ?>
                                </span>
                            </span>
                            <span class="info-topic">
                                <i class="material-icons">local_offer</i>
                                <span>
                                    <b>Diện tích: </b><?php echo empty($record_data->area) ? "N/A" : $record_data->area; ?> (m2)
                                </span>
                            </span>
                        </span>
                    </span>
                </span>
                <div class="content-col width-20">
                    <?php if ($status) { ?>
                        <span class="btn-action e_ajax_link" data='{"id":"<?php echo $id; ?>"}' href="<?php echo empty($url_pay) ? "" : $url_pay; ?>">Thanh toán</span>
                        <span class="btn-action margin-left-10 e_ajax_link" data='{"id":"<?php echo $id; ?>"}' href="<?php echo empty($url_change_room) ? "" : $url_change_room; ?>">Chuyển phòng</span>
                    <?php } else { ?>
                        <span class="btn-action e_ajax_link" callback="enterRoom" data='{"id":"<?php echo $id; ?>"}' href="<?php echo empty($url_enter_room) ? "" : $url_enter_room; ?>">Sử dụng</span>
                    <?php } ?>
                </div>
            </span>
            <?php
        }
    } else {
        echo "<div class='no-record'>Không có bản ghi thỏa mãn.</div>";
    }
    ?>
    </span>
<div class="load-more">
    <span class="view-more hidden">Đang tải thêm</span>
</div>