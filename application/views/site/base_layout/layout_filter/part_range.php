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
<span class="data-filter-custom data-range" filter-type="filter" filter-field="<?php echo isset($filter_field) ? $filter_field : ""; ?>">
    <span class="range-slider" data-value="<?php echo $range_data;?>"></span>
</span>