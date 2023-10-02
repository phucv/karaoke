<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 2/21/2017
 * Time: 10:05 AM
 */
?>
<div class="manage-filter"
     limit-df="<?php echo isset($default_limit) ? $default_limit : 10; ?>"
     offset-df="<?php echo isset($default_offset) ? $default_offset : 0; ?>">
    <?php
    if (isset($filter_view)) {
        foreach ($filter_view as $key_rows => $rows) {
            echo "<div class='filter-row $key_rows'>";
            foreach ($rows as $key_row_parts => $row_parts) {
                echo "<span class='filter-part $key_row_parts'>";
                foreach ($row_parts as $subpart) {
                    $subpart_class = isset($subpart["class"]) ? $subpart["class"] : "";
                    $subpart_attr = isset($subpart["attributes"]) ? $subpart["attributes"] : "";
                    echo "<span class='sub-part $subpart_class' $subpart_attr>";
                    echo $subpart["html"];
                    echo "</span>";
                }
                echo "</span>";
            }
            echo "<span class='both'></span>";
            echo "</div>";
        }
    }
    ?>
    <div id="data-filter-template" class="hidden">
        <span class="value-filter-custom">
            <span class="filter-value-text" title="FILTER_VALUE">FILTER_VALUE</span>
            <span class="btn-remove-value-filter-custom" filter-key="FILTER_KEY_DATA" filter-type="FILTER_TYPE_DATA"
                  filter-field="FILTER_FIELD_DATA">
                <i class="material-icons">close</i>
            </span>
        </span>
    </div>
</div>