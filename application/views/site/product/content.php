<div class="manage-header">
    <div class="header-content">
        <span class="manage-title"><?php echo isset($title) ? $title : ""; ?></span>
        <span class="import-file hover" data-url="<?php echo site_url('product/import_file') ?>">
            <i class="material-icons">file_upload</i>
            <span class="text">Import sản phẩm</span>
            <input type="file" class="hidden" id="import" accept=".xls,.xlsx"/>
        </span>
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

<div id="importModal" class="modal">
    <div class="modal-content">
        <span class="close" title="Click để đóng">&times;</span>
        <div class="modal-header">
            <i class="material-icons">backup</i>
            <span>Import sản phẩm từ file</span>
        </div>
        <div class="modal-body">
            <p class="modal-text">Ấn nút lấy tệp mẫu để tải về cấu trúc của một file có thể import được dữ liệu sản phẩm</p>
        </div>
        <div class="form-group">
            <button class="btn btn-warning hover">
                <i class="material-icons">get_app</i>
                <a href="<?php echo base_url("assets/document/template_import_product.xlsx") ?>">Lấy tệp mẫu</a>
            </button>
            <button type="submit"
                    class="btn btn-success import-btn">
                <i class="material-icons">file_upload</i>&nbsp;&nbsp;Chọn File
            </button>
        </div>
    </div>
</div>
