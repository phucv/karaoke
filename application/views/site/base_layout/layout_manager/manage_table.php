<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 3/1/2017
 * Time: 6:52 PM
 */
?>
<span class="content-table">
<!--  Row       -->
    <?php
    if (!empty($record_list_data)) {
        foreach ($record_list_data as $record_data) {
            $id = $record_data->id;
            $public = isset($record_data->public) ? $record_data->public : NULL;
            $fullname = "Luong Minh Thieu";
            $file_type = empty($record_data->file_type) ? "No file" : $record_data->file_type;
            $file_link = "Link";
            $row_name = "Name";
            ?>
            <span class="content-row">
                <span class="content-col col-info">
                    <span class="info-name" title="<?php echo $row_name; ?>">
                        <a href="<?php echo isset($url_detail) ? $url_detail . "/" . $id : ''; ?>"><?php echo $row_name; ?></a>
                        <a class="btn-edit hidden"
                           href="<?php echo isset($url_edit) ? $url_edit . "/" . $id : ''; ?>"></a>
                    </span>
                    <span class="info-desc">
                        <?php if(!empty($record_data->description))
                                    echo $record_data->description; ?>
                    </span>
                    <span class="info-other">
                        <span class="info-other-left">
                            <span class="info-topic" title="Loại học liệu">
                                <i class="material-icons">local_offer</i>
                                <span>
                                    <?php echo $file_type; ?>
                                </span>
                            </span>
                            <span class="info-created">
                                <i class="material-icons">adb</i>
                                <span><?php
                                    echo $fullname;
                                    ?></span>
                                <span> - </span>
                                <i class="material-icons">alarm</i>
                                <span><?php echo date("H:i:s d/m/Y", strtotime($record_data->created_on)); ?></span>
                            </span>
                        </span>
                    </span>
                </span>
                 <span class="content-col header-select col-select hidden">
                    <input type="checkbox" name="id_check" value="<?php echo $id; ?>"/>
                </span>
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
                        <span class="row-status e_ajax_link <?php if($public == 1) echo 'active';?>"
                              href="<?php echo isset($url_change_status) ? $url_change_status : ''; ?>"
                              callback="callbackChangeStatus" data='{"id":"<?php echo $id; ?>","public" :"1"}'
                              class-data="public"
                        >
                            <i class="material-icons">public</i>
                            <span class="public">Public</span>
                        </span>
                        <span class="row-status e_ajax_link <?php if($public == 0) echo 'active';?>"
                              href="<?php echo isset($url_change_status) ? $url_change_status : ''; ?>"
                              callback="callbackChangeStatus" data='{"id":"<?php echo $id; ?>","public" :"0"}'
                              class-data="private"
                        >
                            <i class="material-icons">lock</i>
                            <span class="private">Private</span>
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
        echo "<div class='no-record'>" . get_string("v-manage_table-no_record") . "</div>";
    }
    ?>
    </span>
<div class="load-more">
    <span class="view-more hidden"><?php echo get_string("v-manage_table-load_more")?></span>
</div>