
<span class="row-filter-select-label filter-clock"><?php echo isset($label) ? $label : "Ngày tạo";?>: </span>
<span class="data-filter-custom" filter-type='filter' filter-field='time_created'>
    <span>Từ ngày</span>
    <span class="data-input">
        <input type="text" autocomplete="off" class="e-filter-range date-picker" name="range-from" value="<?php echo empty($value_from) ? "" : $value_from; ?>" placeholder="Chọn ngày"/>
        <span class="remove-date <?php echo empty($value_from) ? "hidden" : ""; ?>"><i class="material-icons">close</i></span>
        <img src="<?php echo base_url('assets/images/site/base_filter/icon-calendar.png') ?>" alt="">
    </span>
    <span>đến ngày</span>
    <span class="data-input">
        <input type="text" autocomplete="off" class="e-filter-range date-picker" name="range-to" value="<?php echo empty($value_to) ? "" : $value_to; ?>" placeholder="Chọn ngày"/>
        <span class="remove-date <?php echo empty($value_to) ? "hidden" : ""; ?>"><i class="material-icons">close</i></span>
        <img src="<?php echo base_url('assets/images/site/base_filter/icon-calendar.png') ?>" alt="">
    </span>
</span>