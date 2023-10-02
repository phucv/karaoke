<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 2/24/2017
 * Time: 10:26 AM
 */
if (!empty($load_more_css)) {
    echo minify_css_js('css', $load_more_css, isset($function) ? $function : NULL);
}
if (!empty($load_more_js)) {
    echo minify_css_js('js', $load_more_js, isset($function) ? $function : NULL);
}
?>
<!-- Modal -->
<div class="modal-dialog modal-delete" role="document">
    <div class="modal-content delete-form-input e_modal_content" callback="callbackCreateAjaxTable">
        <form id="form-delete" action="" data-url="<?php echo isset($url_save_data) ? $url_save_data : ''; ?>"
              method="post" enctype="multipart/form-data" novalidate>
            <div class="modal-header">
                <span class="header-title">
                    <i class="material-icons">warning</i>
                    <span><?php echo get_string("v-delete-title_text"); ?></span>
                </span>
                <span class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo base_url('assets/images/site/base_manager/icon-remove.png') ?>"/>
                </span>
            </div>
            <div class="modal-body">
                <input type="hidden" name="disable_redirect" value="1"/>
                <?php if(!empty($record_list)) {
                    foreach ($record_list as $record){
                        $record_id = $record->id;
                        echo '<input type="hidden" name="id[]" value="'.$record_id.'"/>';
                    }
                }?>
                <?php echo get_string("v-delete_many-content", count($record_list)); ?>
            </div>
            <div class="modal-footer">
                <div class="form-row row-btn">
                    <button class="btn btn-save e-btn-save" type="submit">
                        <img src="<?php echo base_url('assets/images/site/base_manager/btn-save.png'); ?>"/>
                        <span><?php echo get_string("v-delete-btn_save"); ?></span>
                    </button>
                    <span class="btn btn-cancel" data-dismiss="modal">
                        <i class="material-icons">close</i>
                        <span><?php echo get_string("v-delete-btn_cancel"); ?></span>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    do_ajax_submit($("#form-delete"));
    function callbackCreateAjaxTable(form, data) {
        if (data.status != undefined && data.status == 0) {
            notify(data.msg, "alert-danger");
        } else {
            if (data.msg != undefined) {
                notify(data.msg, "alert-success");
            }
        }
        $(".manage-filter").find(".show-selected").addClass("hidden");
        createAjaxTable($(".manage-table"));
    }
</script>