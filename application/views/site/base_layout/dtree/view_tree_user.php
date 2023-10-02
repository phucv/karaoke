<?php
$level = isset($level) ? $level : 0;
$level_show = isset($level_show) ? $level_show : 3;
$row_id = isset($row_id) ? $row_id : 0;
$checked = empty($checked) ? FALSE : TRUE;
$count_select = isset($count_select) ? $count_select : 0;
$display_name = isset($display_name) ? $display_name : "";
$display_icon = isset($display_icon) ? $display_icon : "";
$display_checkbox = isset($display_checkbox) ? $display_checkbox : FALSE;
$department_position = isset($department_position) ? $department_position : [];
$department_mapping = [];
foreach ($department_position as $value) {
    if (is_array($value)) {
        foreach ($value as $key => $val) {
            if ($key == "position_department") {
                $department_mapping = array_merge($department_mapping, $val);
            }
        }
    }
}
?>
<li class="dtree-node js-node" row-id="<?php echo $row_id; ?>" row-type="<?php echo $type; ?>"
    row-level="<?php echo $level; ?>">
    <div class="node-data js-node-data">
        <span class="dtree-content-col dtree-content-left join-col-left js-dtree-col">
            <span class='label-name'>
                <?php
                echo str_repeat('<span class="dtree-line-space"></span>', $level);
                if ($type == "department") {
                    $show = ($level < $level_show) ? "show" : "";
                    echo "<span class='icon-navigator icon-expand js-ajax-expand $show'></span>";
                }
                ?>
                <i class="name-icon material-icons"><?php echo $display_icon; ?></i>
                <span class="name-text"><?php echo $display_name; ?></span>
            </span>
            <span class="checkbox-allow js-checkbox" data-col-id="0">
            <?php if ($type != "department" && (!$display_checkbox || in_array($row_id, $department_mapping))) { ?>
                <input type="checkbox" <?php echo $display_checkbox ? "disabled" : "";?>/>
            <?php } ?>
            </span>
        </span>
    </div>