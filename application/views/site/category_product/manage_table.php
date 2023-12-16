<span class="content-table">
<!--  Row       -->
    <?php
    if (!empty($record_list_data)) {
        foreach ($record_list_data as $record_data) {
            $id = $record_data->id;
            ?>
            <span class="content-row">
                <span class="content-col col-info">
                    <span class="info-name" title="<?php echo $record_data->name; ?>">
                        <?php echo $record_data->name; ?>
                        <a class="btn-edit hidden e_ajax_link"
                           href="<?php echo isset($url_edit) ? $url_edit . "/" . $id : ''; ?>"></a>
                    </span>
                </span>
                <span class="content-col col-delete hidden e_ajax_link"
                      href="<?php echo isset($url_delete) ? $url_delete : ''; ?>" data='{"id":"<?php echo $id; ?>"}'>
                    <i class="material-icons">delete_forever</i>
                </span>
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