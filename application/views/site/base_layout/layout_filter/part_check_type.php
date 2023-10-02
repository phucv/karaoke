<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 03/04/2017
 * Time: 10:33 AM
 */
?>
<!--Part check status, public/private-->
<span class="filter-status">
    <span class="title-status"><?php echo get_string("v-filter-status")?>:</span>
    <span class="btn-status status-public active" title="<?php echo get_string("v-filter-status_public")?>" status-key="public" status-value="1" active="0">
        <i class="material-icons">public</i>
    </span>
    <span class="btn-status status-private active" title="<?php echo get_string("v-filter-status_private")?>" status-key="private" status-value="0" active="0">
        <i class="material-icons">lock</i>
    </span>
    <span class="btn-status status-group active" title="<?php echo get_string("v-filter-status_group")?>" status-key="group" status-value="3" active="0">
        <i class="material-icons">group</i>
    </span>
</span>
