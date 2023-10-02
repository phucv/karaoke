<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 03/04/2017
 * Time: 10:15 AM
 */
?>
<!--Row-select, action-type = 1 (submit now), 0 (submit after)-->
<span class="row-filter-select-label <?php echo isset($label_class) ? $label_class : ''; ?>">
    <?php echo isset($label_icon) ? "<i class='material-icons'>" . $label_icon . "</i>" : ""; ?>
    <?php echo isset($label) ? "<span>" . $label . ": </span> " : ""; ?>
</span>
<span class="data-filter-custom" filter-field="<?php echo isset($filter_field) ? $filter_field : ""; ?>">
    <span class="data-filter-default"><?php echo isset($data_default) ? $data_default : get_string("v-filter-not_chosen"); ?></span>
    <span class="data-filter-append"></span>
</span>
<span class="filter-quick-add">
    <span class="filter-quick-add-input hidden">
        <select class="value-add select2 e-filter-add-value"
                <?php echo isset($url_ajax) && isset($list_data) && !count($list_data) ? "url-ajax='$url_ajax'" : "" ?>
                name="<?php echo isset($filter_field) ? $filter_field : ""; ?>" data-placeholder="<?php echo get_string("v-filter-chosen"); ?>"
                filter-type="filter" filter-field="<?php echo isset($filter_field) ? $filter_field : ""; ?>"
                <?php echo isset($data_search) ? "data-search='$data_search'" : "" ?>>
                <option value=""><?php echo get_string("v-filter-chosen"); ?></option>
            <?php if (isset($list_data)) {
                $list_option = array();
                // process duplicate data
                foreach ($list_data as $list) {
                    $id = isset($list->id) ? $list->id : 0;
                    $name = isset($list->name) ? $list->name : "";
                    $list_option[$id] = $name;
                }
                // display option
                foreach ($list_option as $value_option => $text_option) {
                    if ($value_option) {
                        echo "<option value='" . $value_option . "'>" . $text_option . "</option>";
                    }
                }
            } ?>
        </select>
    </span>
    <span class="filter-quick-add-btn">
        <img src="<?php echo base_url('assets/images/site/base_filter/plus.png'); ?>"/>
    </span>
</span>