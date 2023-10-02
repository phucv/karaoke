<?php
/**
 * Created by IntelliJ IDEA.
 * User: thieulm
 * Date: 6/2/18
 * Time: 3:00 PM
 */
?>
<div class="row list-join">
    <div class="list-join-title">
                <span class="join-title-text">
                    <?php echo get_string("v-dtree-join"); ?> (
                    <span class="join-number-user"
                          data-url="<?php echo isset($url_get_num_user) ? $url_get_num_user : ''; ?>">
                        <?php echo isset($num_user_department_join) ? $num_user_department_join : 0; ?>
                    </span>
                    )
                </span>
    </div>
    <div class="list-join-header">
                <span class="join-header-left join-col-left">
                    <span class="list-join-search">
                        <input type="text" class="join-search-input js-search-department"
                               placeholder="<?php echo get_string("v-dtree-search_placeholder"); ?>"/>
                        <span class="btn-close-search-de hidden" ajax="0"><i class="material-icons">close</i></span>
                        <span class="btn-search-de"><i class="material-icons">search</i></span>
                    </span>
                    <span class="join-header-allow">
                        <?php echo get_string("v-dtree-join"); ?>
                    </span>
                </span><span class="join-header-right join-col-right">
                    <span class="join-header-required">
                        <?php echo get_string("v-dtree-program_required"); ?>
                    </span>
                    <span class="header-required-program">
                        <?php
                        if (isset($programs_selected)) {
                            foreach ($programs_selected as $program_mapping) {
                                ?>
                                <span style="width: <?php echo isset($width_col_required) ? $width_col_required : 'auto'; ?>%"
                                      class="join-required-col col-required-name"
                                      data-col-id="<?php echo $program_mapping->id; ?>">
                                    <?php echo $program_mapping->name; ?>
                                </span>
                                <?php
                            }
                        }
                        ?>
                    </span>
                </span>
    </div>
    <div class="list-join-content js-dtree"
         url-get-user="<?php echo isset($url_get_user) ? $url_get_user : ''; ?>"
         data-tree='<?php echo isset($object_permission) ? json_encode($object_permission) : ""; ?>'>
        <?php echo isset($department_tree) ? $department_tree : ""; ?>
    </div>
</div>
