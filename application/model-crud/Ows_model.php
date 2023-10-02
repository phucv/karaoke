<?php

/**
 * Created by PhpStorm.
 * User: Pham Quynh
 * Date: 2/6/2017
 * Time: 2:45 PM
 */
class Ows_model extends Crud_model {

    public $schema = Array();
    public $_table_tmp = [];
    public $_user_log_table = 'ows_user_logs';
    protected $save_log = TRUE;

    public function __construct() {
        parent::__construct();
        $this->_standard_schema();
        $this->before_create['before_create'] = 'init_before_create';
        $this->before_update['before_update'] = 'init_before_update';
        $this->before_delete['before_delete'] = 'init_before_delete';
        if ($this->save_log) {
            $this->after_create['after_create'] = 'init_after_create';
            $this->after_update['after_update'] = 'init_after_update';
            $this->after_delete['after_delete'] = 'init_after_delete';
        }
        $this->load->library('OWS_Permission_System');
        $this->load->library('OWS_Save_Log_Active');
        $this->load->library("File_cacher");
        $this->_user_log_table = $this->_getTableUserLog();
    }

    protected function cache_set($key, $value) {
        File_cacher::cache_set($this->_table, $key, $value);
    }

    protected function cache_get($key) {
        return File_cacher::cache_get($this->_table, $key);
    }

    protected function cache_remove($key) {
        return File_cacher::cache_remove($this->_table, $key);
    }

    protected function _standard_schema() {
        foreach ($this->schema as &$item) {
            if (!isset($item['db_field'])) {
                $item['db_field'] = $this->_table_alias . "." . $item['field'];
            }
            if (isset($item['table']) && !is_array($item['table'])) {
                $item['table'] = Array();
            }
            if (isset($item['form']) && !is_array($item['form'])) {
                $item['form'] = Array();
            }
            if (isset($item['filter']) && !is_array($item['filter'])) {
                $item['filter'] = Array();
            }
            if (!isset($item['rules'])) {
                $item['rules'] = "";
            }
        }
    }

    /**
     * Updated a record based on the primary value.
     */
    public function update($primary_value, $data, $skip_validation = FALSE) {
        $data[$this->primary_key] = $primary_value;
        $data = $this->trigger('before_update', $data);
        
        if ($skip_validation === FALSE) {
            $validate = $this->get_validate_for_update($primary_value);
            $data = $this->validate($data, $validate);
        }

        if ($data !== FALSE) {
            $result = $this->_database->where_in($this->_table_alias . "." . $this->primary_key, $primary_value)
                ->set($data)
                ->update($this->_table . " AS " . $this->_table_alias);
            $affected_rows = $this->_database->affected_rows();
            $data["id"] = $primary_value;
            $this->trigger('after_update', array($data, $affected_rows));

            return $affected_rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get validate rule for update
     *
     * @param      $edit_id
     * @param null $validate
     *
     * @return array
     */

    public function get_validate_for_update($edit_id, $validate = NULL) {
        if (!$validate) {
            $validate = $this->get_validate_from_schema();
        }
        $table_key = $this->get_primary_key();
        foreach ($validate as &$item) {
            $rules = $item['rules'];
            $pattern = [];
            $replacement = [];
            //Fix rule for is_unique (is_unique will ignore current row)
            array_push($pattern, '/is_unique\[([^]]+)\]/');
            array_push($replacement, "is_unique[$1,$table_key!=$edit_id]");

            //Fix rule for upload file: remove required because old value file is not same as input field
            array_push($pattern, '/file_required(.*)required/', '/required(.*)file_required/', '/file_required/');
            array_push($replacement, "$1", "$1", "");

            array_push($pattern, '/\|+/', '/^\|/', '/\|$/');
            array_push($replacement, "|", "", "");

            $item['rules'] = preg_replace($pattern, $replacement, $rules);
        }
        return $validate;
    }

    /**
     * Get validate array of model from schema
     *
     * @return array
     */
    public function get_validate_from_schema() {
        $validate = Array();
        foreach ($this->schema as $item) {
            if (!isset($item['form'])) {
                continue;
            }
            $validate[$item['field']] = Array(
                'field'    => $item['field'],
                'db_field' => $item['db_field'],
                'label'    => $item['label'],
                'rules'    => $item['rules'],
                'errors'   => isset($item['errors']) ? $item['errors'] : [],
            );
            //Add validate with select and multiple select
            if (isset($item['form']['type']) &&
                ($item['form']['type'] == 'select' || $item['form']['type'] == 'multiple_select')
            ) {
                $validate[$item['field']] = $this->_get_validate_for_selected($item, $validate[$item['field']]);
            }
            //Add validate with file and multiple file
            if (isset($item['form']['type']) &&
                ($item['form']['type'] == 'file' || $item['form']['type'] == 'multiple_file')
            ) {
                $validate[$item['field']] = $this->_get_validate_for_file($item, $validate[$item['field']]);
            }
        }
        return $validate;
    }

    private function _get_validate_for_selected($schema_item, $validate_item) {
        $list_in = $this->get_data_select($schema_item['form']);
        $list_string = implode(",", array_keys($list_in));
        $this->_add_validate_rule($validate_item['rules'], "in_list[$list_string]");
        return $validate_item;
    }

    /**
     * Get data for select
     *
     * @param $form
     *
     * @return mixed
     * @throws Exception
     */
    public function get_data_select($form) {
        if (!isset($form['target_model']) ||
            !isset($form['target_function'])
        ) {
            throw new Exception("Filter type 'select' must be have 'target_model' and 'target_function'");
        }
        $target_model = $form['target_model'];
        $target_function = $form['target_function'];
        $target_arg = Array();
        if (isset($form['target_arg'])) {
            $target_arg = $form['target_arg'];
        }
        if ($target_model == 'this') {
            $data_select = call_user_func_array(Array($this, $target_function), $target_arg);
        } else {
            $model_name = strtolower('select_' . $target_model);
            if (!isset($this->$model_name)) {
                $this->load->model($target_model, $model_name);
            }
            $data_select = call_user_func_array(Array($this->$model_name, $target_function), $target_arg);
        }
        return $data_select;
    }

    private function _add_validate_rule(&$org_rule, $add_string) {
        if (!strlen($add_string)) {
            return $org_rule;
        }
        if (is_string($org_rule)) {
            if (strlen($org_rule)) {
                $org_rule .= "|$add_string";
            } else {
                $org_rule .= "$add_string";
            }
        } else if (is_array($org_rule)) {
            $org_rule[] = $add_string;
        }
        return $org_rule;
    }

    private function _get_validate_for_file($schema_item, $validate_item) {
        if (!isset($schema_item['form']['upload'])) {
            return $validate_item;
        }
        if (strpos($validate_item['rules'], "required") !== FALSE) {
            $this->_add_validate_rule($validate_item['rules'], "file_required");
        }
        $upload_config = $schema_item['form']['upload'];
        $file_rules = ['upload_path', 'allowed_types', 'min_size', 'max_size',
            'min_width', 'max_width', 'min_height', 'max_height'];
        foreach ($file_rules as $item) {
            if (isset($upload_config[$item])) {
                $value = str_replace("|", ",", $upload_config[$item]);
                $more_rule = "file_{$item}[$value]";
                $this->_add_validate_rule($validate_item['rules'], $more_rule);
            }
        }
        return $validate_item;
    }

    public function get_primary_key() {
        return $this->primary_key;
    }

    /**
     * @param array      $data     Data need to validate
     * @param array|null $validate Rule validate, if NULL, get from schema
     *
     * @return bool|array Validate data with validate return FALSE if invalid, return data if valid
     */
    public function validate($data, $validate = NULL) {
        if (!$validate) {
            $validate = $this->get_validate_from_schema();
        }
        $result = parent::validate($data, $validate);
        if ($result) {
            $result = $this->file_validated_handle($validate, $result);
        }
        return $result;
    }

    protected function file_validated_handle($validate, $form_data) {
        foreach ($validate as $field => $item) {
            $form = $this->schema[$field]['form'];
            if (isset($form['type'])                                                //Has 'type' attribute in form
                && ($form['type'] == "file" || $form['type'] == "multiple_file")    //Type is 'file' or 'multiple_file'
            ) {
                if (isset($_FILES[$field])) { //Has new file upload: update data
                    $this->load->library('upload');
                    if (!isset($form['upload']) || !isset($form['upload']['upload_path'])) {
                        throw new Exception("Missing upload config for input form.");
                    }
                    if (!isset($form['upload']['upload_path'])) {
                        throw new Exception("Missing upload_path in config.");
                    }
                    $upload_config = $form['upload'];
                    $this->upload->initialize($upload_config);
                    if ($this->upload->do_upload($field)) {
                        $file_data = $this->upload->data();
                        $form_data[$field] = $form['upload']['upload_path'] . "/" . $file_data['file_name'];
                    } else {
                        $error = $this->upload->error_msg;
                        $this->form_validation->add_error($field, $error);
                        return FALSE;
                    }
                } else { //no have file upload: keep old value
                    unset($form_data[$field]);
                }
            }
        }
        return $form_data;
    }

    /**
     * Get array to show add/edit form
     *
     * @return array
     */
    public function get_form() {
        $form = Array();
        foreach ($this->schema as $item) {
            if (!isset($item['form'])) {
                continue;
            }
            if (!is_array($item['form'])) {
                $item['form'] = Array();
            }
            $form_item = Array(
                'field'    => $item['field'],
                'db_field' => $item['db_field'],
                'label'    => isset($item['form']['label']) ? $item['form']['label'] : $item['label'],
                'rules'    => $item['rules'],
                'form'     => $item['form'],
            );
            if (isset($form_item['form']['type']) &&
                ($form_item['form']['type'] == 'select' || $form_item['form']['type'] == 'multiple_select')
            ) {
                $form_item['form']['data_select'] = $this->get_data_select($form_item['form']);
            }
            $form[$item['field']] = $form_item;
        }
        return $form;
    }

    /**
     * @return array Field of manager table
     */
    public function get_table_field() {
        $table_field = Array();
        foreach ($this->schema as $item) {
            if (isset($item['table'])) {
                $temp_column = Array(
                    'field'    => $item['field'],
                    'db_field' => $item['db_field'],
                    'label'    => isset($item['table']['label']) ? $item['table']['label'] : $item['label'],
                    'rules'    => $item['rules'],
                    'table'    => $item['table'],
                );
                $table_field[$item['field']] = $temp_column;
            }
        }
        return $table_field;
    }

    /**
     * get filter data from filter form
     *
     * @param $post_filter
     *
     * @return array
     */
    public function standard_filter_data($post_filter) {
        $where_condition = Array();
        $where_in_condition = Array();
        $like_condition = Array();
        $schema_filters = $this->get_filter();
        foreach ($post_filter as $key => $value) {
            if (is_string($value)) {
                $value = trim($value);
            }
            if (isset($schema_filters[$key])) {
                $db_key = $schema_filters[$key]['db_field'];
                $filter = $schema_filters[$key]['filter'];
                if (isset($filter['search_type'])) {
                    switch ($filter['search_type']) {
                        case 'where' :
                            is_string($value) AND strlen($value) AND $where_condition[$db_key] = $value;
                            break;
                        case 'where_in' :
                            is_string($value) AND strlen($value) AND $where_in_condition[$db_key] = $value;
                            is_array($value) AND count($value) AND $where_in_condition[$db_key] = $value;
                            break;
                        case 'like' :
                            is_string($value) AND strlen($value) AND $like_condition[$db_key] = $value;
                            break;
                        default :
                            is_string($value) AND strlen($value) AND $like_condition[$db_key] = $value;
                    }
                } elseif (isset($filter['type'])) {
                    switch ($filter['type']) {
                        case 'select' :
                            is_string($value) AND strlen($value) AND $where_condition[$db_key] = $value;
                            break;
                        case 'multiple_select' :
                            is_string($value) AND strlen($value) AND $where_in_condition[$db_key] = $value;
                            is_array($value) AND count($value) AND $where_in_condition[$db_key] = $value;
                            break;
                        default :
                            is_string($value) AND strlen($value) AND $like_condition[$db_key] = $value;
                    }
                } else {
                    $value AND $like_condition[$db_key] = $value;
                }
            } else {
                continue;
            }
        }
        return [
            "where"    => $where_condition,
            "where_in" => $where_in_condition,
            "like"     => $like_condition,
        ];
    }

    /**
     * Get filter array of model from schema
     */
    public function get_filter() {
        $filter = Array();
        foreach ($this->schema as $item) {
            if (!isset($item['filter'])) {
                continue;
            }
            if (!is_array($item['filter'])) {
                $item['filter'] = Array();
            }
            $temp_filter = Array(
                'field'    => $item['field'],
                'db_field' => $item['db_field'],
                'label'    => isset($item['filter']['label']) ? $item['filter']['label'] : $item['label'],
                'rules'    => $item['rules'],
                'filter'   => $item['filter'],
            );
            if (isset($item['form'])) {
                $temp_filter['filter'] = array_merge($item['form'], $item['filter']);
            }
            if (isset($temp_filter['filter']) && isset($temp_filter['filter']['type']) &&
                ($temp_filter['filter']['type'] == 'select' || $temp_filter['filter']['type'] == 'multiple_select')
            ) {
                $temp_filter['filter']['data_select'] = $this->get_data_select($temp_filter['filter']);
            }
            $filter[$item['field']] = $temp_filter;
        }
        return $filter;
    }

    /**
     * Get list object with special condition
     *
     * @param   array  $whereCondition   Array with key is field need to find, and value is String, which need to find
     *                                   with WHERE
     *
     * @param   array  $whereInCondition Array with key is field need to find, and value is Array of value need to find
     *                                   with WHERE_IN
     * @param   array  $likeCondition    Array with key is field need to find, and value is String of value need to find
     *                                   with LIKE
     * @param   Int    $limit            Limit number of item to get (same as LIMIT in SQL)
     * @param   Int    $post             Item start to get (same as POST in SQL)
     * @param   String $order            Order value, a String as 'title DESC, name ASC'
     *
     * @return array                    A Array of object with attribute base on $schema
     */
    public function get_list_filter($whereCondition, $whereInCondition, $likeCondition, $limit = 0, $post = 0, $order = NULL) {
        $this->_filter_prepare($whereCondition, $whereInCondition, $likeCondition, $limit, $post, $order);
        return $this->get_all();
    }

    protected function _filter_prepare($whereCondition, $whereInCondition, $likeCondition, $limit = 0, $post = 0, $order = NULL) {
        if (isset($whereCondition["where_text"])) {
            if ($whereCondition["where_text"]) {
                $this->db->group_start();
                $this->_database->where($whereCondition["where_text"]);
                $this->db->group_end();
            }
            unset($whereCondition["where_text"]);
        }
        if (is_array($whereCondition) && count($whereCondition) > 0) {
            $this->_database->where($whereCondition);
        } else if (intval($whereCondition) > 0) {
            $this->_database->where("m." . $this->primary_key, $whereCondition);
        }
        if (is_array($whereInCondition) && count($whereInCondition) > 0) {
            $this->db->group_start();
            foreach ($whereInCondition as $key => $value) {
                if ($key == "or") {
                    foreach ($value as $keyOr => $valueOr) {
                        if ((is_array($valueOr) && count($valueOr)) ||
                            (is_string($valueOr) && strlen($valueOr))
                        ) {
                            $this->_database->or_where_in($keyOr, $valueOr);
                        }
                    }
                } else if ((is_array($value) && count($value)) ||
                    (is_string($value) && strlen($value))
                ) {
                    $this->_database->where_in($key, $value);
                }
            }
            $this->db->group_end();
        }
        if (isset($likeCondition['and']) && is_array($likeCondition['and']) && count($likeCondition['and']) > 0) {
            $this->db->group_start();
            foreach ($likeCondition['and'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $value_item) {
                        $this->_database->like($key, $value_item);
                    }
                } else {
                    $this->_database->like($key, $value);
                }
            }
            $this->db->group_end();
        }

        if (isset($likeCondition['or']) && is_array($likeCondition['or']) && count($likeCondition['or']) > 0) {
            $this->db->group_start();
            foreach ($likeCondition['or'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $value_item) {
                        $this->_database->or_like($key, $value_item);
                    }
                } else {
                    $this->_database->or_like($key, $value);
                }
            }
            $this->db->group_end();
        }
        if ($limit) {
            $this->limit($limit, $post);
        }
        if ($order) {
            $this->order_by($order);
        } else {
            $this->order_by($this->_table_alias . "." . $this->primary_key, "DESC");
        }
    }

    /**
     * Get total item with special condition(use with get_list_filter for paging)
     *
     * @param   array $whereCondition   Array with key is field need to find, and value is String, which need to find
     *                                  with WHERE
     * @param   array $whereInCondition Array with key is field need to find, and value is Array of value need to find
     *                                  with WHERE_IN
     * @param   array $likeCondition    Array with key is field need to find, and value is String of value need to find
     *                                  with LIKE
     *
     * @return  Int     Count of all item match param condition
     */
    public function get_list_filter_count($whereCondition, $whereInCondition, $likeCondition) {
        $this->_filter_prepare($whereCondition, $whereInCondition, $likeCondition, 0, 0, NULL);
        return $this->count_all_results();
    }

    /**
     * ThieuLM: 20/04/2017
     * Init default create
     *
     * @param $row array
     *
     * @return array
     */
    public function init_before_create($row) {
        $table = $this->_table;
        if ($this->db->field_exists("created_on", $table) && empty($row["created_on"])) {
            $row["created_on"] = date("Y-m-d H:i:s");
        }
        if ($this->db->field_exists("created_by", $table) && empty($row["created_by"])) {
            $row["created_by"] = $this->_get_user_id();
        }
        if (in_array($table, $this->_table_tmp)) {
            $row[$this->primary_key] = "tmp" . date("ymdHis") . rand(1000, 9999);
        }
        $permission = new OWS_Permission_System();
        $permission->check_permission_table($table, $row, "insert");
        return $row;
    }

    public function init_after_create($id) {
        $username = $this->_get_user_name();
        $object = $this->get($id);
        if (!empty($object->name)) {
            $name_object = "[" . $object->name . "]";
        } else {
            $name_object = "[id: " . $id . "]";
        }
        $log = array(
            "user_id"    => $this->_get_user_id(),
            "object_id"  => $id,
            "table"      => $this->_table,
            "action"     => "insert",
            "content"    => $username . "{insert}{" . $this->_table . "}" . $name_object . "{at}" . date('d-m-Y H:i:s', now()),
            "json_new"   => json_encode($object),
            "created_on" => date("Y-m-d H:i:s"),
            "created_by" => $this->_get_user_id(),
        );
        $this->db->insert($this->_user_log_table, $log);

        $log_active = new OWS_Save_Log_Active();
        $log_active->check_save_log_active($this->_table, $object, "insert");
    }

    public function init_after_update($data = array()) {
        $username = $this->_get_user_name();
        $object_id = empty($data[0]["id"]) ? 0 : $data[0]["id"];
        $object = $this->get($object_id);
        if (!empty($object->name)) {
            $name_object = "[" . $object->name . "]";
        } else {
            $name_object = "[id: " . $object_id . "]";
        }
        $log = array(
            "user_id"    => $this->_get_user_id(),
            "object_id"  => $object_id,
            "table"      => $this->_table,
            "action"     => "update",
            "content"    => $username . "{update}{" . $this->_table . "}" . $name_object . "{at}" . date('d-m-Y H:i:s', now()),
            "json_new"   => json_encode($object ? $object : $data),
            "created_on" => date("Y-m-d H:i:s"),
            "created_by" => $this->_get_user_id(),
        );
        $this->db->insert($this->_user_log_table, $log);
    }

    /**
     * ThieuLM: 20/04/2017
     * Init default update
     *
     * @param $row array
     *
     * @return array
     */
    public function init_before_update($row) {
        $table = $this->_table;
        if ($this->db->field_exists("lastest_update_on", $table) && empty($row["lastest_update_on"])) {
            $row["lastest_update_on"] = date("Y-m-d H:i:s");
        }
        if ($this->db->field_exists("lastest_update_by", $table) && empty($row["lastest_update_by"])) {
            $row["lastest_update_by"] = $this->_get_user_id();
        }
        $permission = new OWS_Permission_System();
        $permission->check_permission_table($this->_table, $row, "update");

        $log_active = new OWS_Save_Log_Active();
        $log_active->check_save_log_active($this->_table, $row, "update");
        return $row;
    }

    /**
     * PhuCV: 04/06/2019
     * Init default delete
     *
     * @param $row array
     *
     * @return array
     */
    public function init_before_delete($row) {
        $table = $this->_table;
        if ($this->db->field_exists("lastest_update_on", $table) && empty($row["lastest_update_on"])) {
            $row["lastest_update_on"] = date("Y-m-d H:i:s");
        }
        if ($this->db->field_exists("lastest_update_by", $table) && empty($row["lastest_update_by"])) {
            $row["lastest_update_by"] = $this->_get_user_id();
        }
        $permission = new OWS_Permission_System();
        $permission->check_permission_table($this->_table, $row, "delete");

        $log_active = new OWS_Save_Log_Active();
        $log_active->check_save_log_active($this->_table, $row, "delete");
        return $row;
    }

    /**
     * Delete a row from the table by the primary value
     */
    public function delete($id) {
        $data[$this->primary_key] = $id;
        $data = $this->trigger('before_delete', $data);
        if ($this->soft_delete) {
            $this->_database->where($this->_table_alias . "." . $this->primary_key, $id);
            $this->_database->update($this->_table . " AS " . $this->_table_alias,
                array_merge($data, array($this->_table_alias . "." . $this->soft_delete_key => TRUE)));
        } else {
            $this->_database->where($this->primary_key, $id);
            $this->_database->delete($this->_table);
        }
        $affected_rows = $this->_database->affected_rows();
        $row = [
            "list_id"       => [$id],
            "affected_rows" => $affected_rows,
        ];
        $this->trigger('after_delete', $row);

        return $affected_rows;
    }

    public function init_after_delete($row) {
        $username = $this->_get_user_name();
        $affected_rows = isset($row["affected_rows"]) ? $row["affected_rows"] : 0;
        if ($this->soft_delete) {
            $content = $username . "{delete}" . $affected_rows . "{" . $this->_table . "}{at}" . date('d-m-Y H:i:s', now());
        } else {
            $content = $username . "{delete_db}" . $affected_rows . "{" . $this->_table . "}{at}" . date('d-m-Y H:i:s', now());
        }
        $log = array(
            "user_id"    => $this->_get_user_id(),
            "object_id"  => "",
            "table"      => $this->_table,
            "action"     => "delete",
            "content"    => $content,
            "json_new"   => json_encode($row),
            "created_on" => date("Y-m-d H:i:s"),
            "created_by" => $this->_get_user_id(),
        );
        $this->db->insert($this->_user_log_table, $log);
    }

    // check field exist
    public function check_field_exist($field = "") {
        if ($this->db->field_exists($field, $this->_table)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Lay thong tin cua row tu dieu kien
     * $where: Mảng điều kiện
     */
    function get_info_rule($where = array()) {
        $this->db->where($where);
        $query = $this->db->get($this->table);
        if ($query->num_rows()) {
            return $query->row();
        }
        return FALSE;
    }

    /**
     * Cap nhat row tu dieu kien
     * $where: điều kiện
     */
    function update_rule($where, $data) {
        if (!$where || !is_array($where)) {
            return FALSE;
        }
        //thêm điều kiện
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $this->db->where_in($key, $value);
            } else {
                $this->db->where($key, $value);
            }
        }
        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            if ($this->db->field_exists($this->soft_delete_key, $this->_table)) {
                $this->db->where($this->_table_alias . "." . $this->soft_delete_key, 0);
            }
        }
        $data = $this->trigger('before_update', $data);
        $this->db->set($data)->update($this->_table . " AS " . $this->_table_alias);
        $affected_rows = $this->db->affected_rows();
        $this->trigger('after_update', array($data, $affected_rows, 'by_where' => $where));
        return $affected_rows;
    }

    /**
     * Get username by session
     **/
    protected function _get_user_name() {
        $user = !empty($this->session->userdata()) ? $this->session->userdata("user_data") : NULL;
        if (empty($user)) {
            $username = "";
        } else {
            $username = empty($user->display_name) ? $user->username : $user->display_name;
            $username = $username . " (" . $user->email . ")";
        }
        return $username;
    }

    /**
     * Get user_id by session
     **/
    protected function _get_user_id() {
        $user_id = empty($this->session->userdata()) ? 0 : $this->session->userdata("id");
        if (!$user_id) $user_id = 0;
        return $user_id;
    }

    /**
     * get full name of user login
     * @return string
     */
    public function _get_user_full_name() {
        $user = !empty($this->session->userdata()) ? $this->session->userdata("user_data") : NULL;
        if (empty($user)) {
            $username = "";
        } else {
            $username = empty($user->firstname) ? $user->username : $user->firstname . " " . $user->lastname;
            $username = $username . " (" . $user->email . ")";
        }
        return $username;
    }

    /**
     * custom data to filter
     * @param array $data_object
     * @return array
     */
    public function custom_data_filter($data_object = array()) {
        foreach ($data_object as $object) {
            $object->id = $object->name;
        }
        return $data_object;
    }

    /**
     * Transaction manual: begin
     */
    public function trans_begin() {
        $this->db->trans_begin();
    }

    /**
     * Transaction manual: status
     */
    public function trans_status() {
        return $this->db->trans_status();
    }

    /**
     * Transaction manual: rollback
     */
    public function trans_rollback() {
        $this->db->trans_rollback();
    }

    /**
     * Transaction manual: commit
     */
    public function trans_commit() {
        $this->db->trans_commit();
    }

    public function _with_deleted() {
        $this->_temporary_with_deleted = TRUE;
    }

    public function deleted_db_by($where) {
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $this->_database->where_in($key, $value);
            } else {
                $this->_database->where($key, $value);
            }

        }
        $this->_database->delete($this->_table);

        $affected_rows = $this->_database->affected_rows();
        $this->trigger('after_delete', $affected_rows);

        return $affected_rows;
    }

    protected function _getTableUserLog() {
        $pre = "ows_user_logs";
        $key = date("Ym");
        $table = File_cacher::cache_get($pre, $key);
        if (!$table) {
            $table = $pre . "_" . $key;
            if (!$this->db->table_exists($table)) {
                $sql = "CREATE TABLE $table (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `table` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `json_new` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` timestamp NULL DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
                $this->db->query($sql);
            }
            File_cacher::cache_set($pre, $key, $table);
        }
        return $table;
    }
}