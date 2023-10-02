<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 03/04/2017
 * Time: 10:30 AM
 */
?>
<!--Part sort-->
<span class="filter-sort">
    <i class="material-icons">sort</i>
    <select name="filter-sort">
        <option value="created_on" sort-type="desc"><?php echo get_string("v-filter-created_desc")?></option>
        <option value="created_on" sort-type="asc"><?php echo get_string("v-filter-created_asc");?></option>
    </select>
</span>
