<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 11/04/2017
 * Time: 10:35 AM
 */
?>
<span class="show-selected hidden">
    <a class="btn-delete-many e_ajax_link"
       href="<?php echo isset($url_delete_many) ? $url_delete_many : ''; ?>"
    ><?php echo get_string("v-filter-delete");?></a>
    <?php echo get_string("v-filter-or");?>
    <a class="btn-unselect"><?php echo get_string("v-filter-unchecked");?></a>
    <?php echo get_string("v-filter-or");?>
    <a class="header-select header-select-all"><?php echo get_string("v-filter-select_all");?></a>
</span>