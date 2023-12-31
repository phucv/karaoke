<span class="content-table">
<!--  Row       -->
    <?php
    if (!empty($record_list_data)) {
        foreach ($record_list_data as $record_data) {
            $id = $record_data->id;
            $public = isset($record_data->public) ? $record_data->public : NULL;
            $row_name = $record_data->display_name;
            ?>
            <span class="content-row">
                <span class="content-col col-info">
                    <span class="info-name" title="<?php echo $row_name; ?>">
                        <?php echo $row_name; ?>
                        <a class="btn-edit hidden e_ajax_link"
                           href="<?php echo isset($url_edit) ? $url_edit . "/" . $id : ''; ?>"></a>
                    </span>
                    <span class="info-other">
                        <span class="info-other-left">
                            <span class="info-topic">
                                <i class="material-icons">local_offer</i>
                                <span>
                                    <b>Tên đăng nhập: </b><?php echo empty($record_data->username) ? "" : $record_data->username; ?>
                                </span>
                            </span>
                            <span class="info-topic">
                                <i class="material-icons">local_offer</i>
                                <span>
                                    <b>Số điện thoại: </b><?php echo empty($record_data->phone) ? "N/A" : $record_data->phone; ?>
                                </span>
                            </span>
                        </span>
                    </span>
                </span>
                <div class="content-col width-20">
                    <div class="info-other font-size-13">Quyền: <?php echo empty($record_data->role_name) ? "N/A" : $record_data->role_name; ?></div>
                </div>
                <span class="content-col col-status">
                    <span class="status-icon">
                        <?php
                        if ($public == 1) {
                            echo '<i class="material-icons public">public</i>';
                        } else if ($public == 0) {
                            echo '<i class="material-icons private">lock</i>';
                        }
                        ?>
                    </span>
                    <span class="status-bg"></span>
                    <span class="tooltip-box-status">
                        <span class="row-status e_ajax_link <?php if ($public == 1) echo 'active'; ?>"
                              href="<?php echo isset($url_change_status) ? $url_change_status : ''; ?>"
                              callback="callbackChangeStatus" data='{"id":"<?php echo $id; ?>","public" :"1"}'
                              class-data="public"
                        >
                            <i class="material-icons">public</i>
                            <span class="public">Active</span>
                        </span>
                        <span class="row-status e_ajax_link <?php if ($public == 0) echo 'active'; ?>"
                              href="<?php echo isset($url_change_status) ? $url_change_status : ''; ?>"
                              callback="callbackChangeStatus" data='{"id":"<?php echo $id; ?>","public" :"0"}'
                              class-data="private"
                        >
                            <i class="material-icons">lock</i>
                            <span class="private">Khoá</span>
                        </span>
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