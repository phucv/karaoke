<div class="manage-header">
    <div class="header-content">
        <span class="manage-title"><?php echo isset($title) ? $title : ""; ?></span>
        <a class="manage-add-new e_ajax_link" href="<?php echo isset($url_add) ? $url_add : ''; ?>">
            <?php echo empty($text_add) ? (isset($object_class) ? "Thêm mới" : "") : $text_add; ?>
        </a>
    </div>
</div>
<div class="manage-content">
    <?php echo isset($manage_filter) ? $manage_filter : ""; ?>
    <div class="manage-table" data-url="<?php echo isset($url_ajax_data_table) ? $url_ajax_data_table : ''; ?>">
        <div class="ajax-data-table"></div>
    </div>
</div>
