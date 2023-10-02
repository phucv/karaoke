<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 03/04/2017
 * Time: 10:15 AM
 */
?>
<!--Part row filter input-->
<span class="row-filter-select-label filter-clock"><?php echo isset($label) ? $label : get_string("v-filter-input_create_time");?>: </span>
<span class="data-filter-custom" filter-type='filter' filter-field='time_created'>
    <span><?php echo get_string("v-filter-input_time_from");?></span>
    <span class="data-input">
        <input type="text" autocomplete="off" class="e-filter-range date-picker" name="range-from" value="<?php echo empty($value_from) ? "" : $value_from; ?>" placeholder="<?php echo get_string("v-filter-input_chosen");?>"/>
        <span class="remove-date <?php echo empty($value_from) ? "hidden" : ""; ?>"><i class="material-icons">close</i></span>
        <img src="<?php echo base_url('assets/images/site/base_filter/icon-calendar.png') ?>" alt="">
    </span>
    <span><?php echo get_string("v-filter-input_time_to");?></span>
    <span class="data-input">
        <input type="text" autocomplete="off" class="e-filter-range date-picker" name="range-to" value="<?php echo empty($value_to) ? "" : $value_to; ?>" placeholder="<?php echo get_string("v-filter-input_chosen");?>"/>
        <span class="remove-date <?php echo empty($value_to) ? "hidden" : ""; ?>"><i class="material-icons">close</i></span>
        <img src="<?php echo base_url('assets/images/site/base_filter/icon-calendar.png') ?>" alt="">
    </span>
</span>