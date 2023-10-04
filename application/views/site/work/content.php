
<div class="manage-header">
    <div class="header-content">
        <span class="manage-title"><?php echo isset($title) ? $title : ""; ?></span>
    </div>
</div>
<div class="manage-content">
    <?php echo isset($manage_filter) ? $manage_filter : ""; ?>
    <div class="manage-table" data-url="<?php echo isset($url_ajax_data_table) ? $url_ajax_data_table : ''; ?>">
        <div class="ajax-data-table"></div>
    </div>
</div>
