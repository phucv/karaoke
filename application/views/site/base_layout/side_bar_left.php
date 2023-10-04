<?php
if (empty($show_menu)) return FALSE;
?>
<nav class="side-menu <?php echo is_string($show_menu) ? $show_menu : NULL; ?>">
    <!--  Xu ly menu  -->
    <div class="box-search">
        <input type="text" class="ip_search_menu js_search_menu" placeholder="search...">
        <i class="material-icons ic_remove js_remove">clear</i>
        <i class="material-icons ic_search">search</i>
    </div>
    <ul class="nav nav-list site-enscroll">
        <?php function renderCategory($categoryList, $is_child = FALSE, $current_controller = NULL, $current_method = NULL) { ?>
            <?php foreach ($categoryList as $item) { ?>
                <li class="<?php
                echo $is_child ? "sub-menu-items" : "menu-items";
                echo empty($item["child"]) ? "" : " has-sub";
                if (empty($item["child"])) {
                    $obj_active = isset($item['obj_active']) ? explode(";", $item['obj_active']) : array();
                    $obj_current = $current_controller . "." . $current_method;
                    $obj_current_all = $current_controller . ".*";
                    if (in_array($obj_current, $obj_active) || in_array($obj_current_all, $obj_active)) {
                        echo ' active';
                    }
                } else {
                    foreach ($item['child'] as $child) {
                        $child_active = isset($child['obj_active']) ? explode(";", $child['obj_active']) : array();
                        $child_current = $current_controller . "." . $current_method;
                        $child_current_all = $current_controller . ".*";
                        if (in_array($child_current, $child_active) || in_array($child_current_all, $child_active)) {
                            echo $is_child ? ' active' : ' active show';
                        }
                    }
                }
                ?>">
                    <a class="<?php echo (isset($item["class"]) ? $item["class"] : '') . ($is_child ? "" : " text_parent"); ?>"
                       href="<?php echo empty($item["child"]) ? $item["url"] : "#"; ?>"
                       title="<?php echo $item["text"] ?>">
                        <span class="<?php echo $is_child ? "hide" : ""; ?>">
                        <?php if (strpos($item["icon"], "<i")) {
                            echo $item["icon"];
                        } else { ?>
                            <i class="material-icons item-icons"><?php echo $item["icon"] ?></i>
                        <?php } ?>
                        </span>
                        <span class="item-text"><?php echo $item["text"] ?></span>
                        <?php echo empty($item["child"]) ? "" : '<div class="arrow drop-arrow"></div>'; ?>
                    </a>
                    <?php if (!empty($item["child"])) {
                        echo '<ul class="submenu">';
                        renderCategory($item["child"], TRUE, $current_controller, $current_method);
                        echo '</ul>';
                    } ?>
                </li>
            <?php }
        } ?>
        <?php
        if (isset($current_controller) && isset($current_method)) {
            renderCategory($menu_data, FALSE, $current_controller, $current_method);
        }
        ?>
    </ul>
    <ul class="nav nav-list menu_fixed">
        <li class="menu-items <?php echo ($show_menu === 'close') ? 'show-menu' : 'hide-menu'; ?>" id="menu">
            <a href="#" title="Đóng/mở menu" class="text_parent">
                <i class="material-icons item-icons"><?php echo ($show_menu === 'close') ? 'fast_forward' : 'fast_rewind'; ?></i>
                <span class="item-text">Thu gọn menu</span>
            </a>
        </li>
    </ul>
</nav>