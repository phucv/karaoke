<?php
$level = isset($level) ? $level : 0;
$row_id = isset($row_id) ? $row_id : 0;
$checked = empty($checked) ? FALSE : TRUE;
$count_select = isset($count_select) ? $count_select : 0;
$display_name = isset($display_name) ? $display_name : "";
$display_icon = isset($display_icon) ? $display_icon : "";
$list_user = isset($list_user) ? $list_user : "";
?>
<li class="dtree-node js-node" row-id="<?php echo $row_id; ?>" row-type="<?php echo $type; ?>"
    row-level="<?php echo $level; ?>">
    <div class="node-data js-node-data" data-user="<?php echo $list_user; ?>"
         data-gender="<?php if (isset($user_gender)) echo $user_gender; ?>">
        <span class="dtree-content-col dtree-content-left join-col-left js-dtree-col">
            <span class='label-name'>
                <?php
                echo str_repeat('<span class="dtree-line-space"></span>', $level);
                if (isset($count_children)) {
                    $show = ($type == "department" && $level < $level_show) ? "show" : "";
                    echo "<span class='icon-navigator icon-expand js-ajax-expand $show'></span>";
                }
                ?>
                <i class="name-icon material-icons"><?php echo $display_icon; ?></i>
                <span class="name-text"><?php echo $display_name; ?></span>
            </span>
            <span class="checkbox-allow js-checkbox" data-col-id="0">
                <input type="checkbox"/>
            </span>
        </span><span class="dtree-content-col dtree-content-right join-col-right js-dtree-col">
            <span class="checkbox-required js-checkbox-required">
                <?php
                if (isset($programs_selected)) {
                    foreach ($programs_selected as $program_mapping) {
                        ?>
                        <span class="checkbox-required-item js-checkbox"
                              style="width: <?php echo isset($width_col_required) ? $width_col_required : 'auto'; ?>%"
                              data-col-id="<?php echo $program_mapping->id; ?>">
                            <input type='checkbox'/>
                        </span>
                        <?php
                    }
                }
                ?>
            </span>
        </span>
    </div>